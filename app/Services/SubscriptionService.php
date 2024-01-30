<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Notification;
use App\Models\Subscription;
use App\Services\StripeService;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Enums\NotificationGroup as NG;
use App\Facades\DumperServiceFacade as Dumper;

/**
 * Subscriptions explanaiton:
 * - designed for Stripe.
 * - each sub have cycles.
 * - sub considered active if it have active (payed) cycle.
 * - sub are extended automaticaly via schedule command with Stripe checks.
 * - some time of payment pending is allowed. It's called 'incomplete' status.
 * - webhook is used to handle Stripe events for late invoice payments.
 *
 */
class SubscriptionService
{
    public static function create($planId)
    {
        $stripeService = new StripeService();
        $user = auth()->user();
        $activeSub = $user->activeSubscription();
        $subscriptionPlan = SubscriptionPlan::find($planId);
        $subscription = $stripeService->createSubscription($user->stripe_id, $subscriptionPlan->stripe_id);
        $invoice = $subscription['latest_invoice'];

        $sub = $user->subscriptions()->create([
            'subscription_plan_id' => $subscriptionPlan->id,
            'external_id' => $subscription['id'],
            'provider' => 'stripe',
            'status' => $subscription['status'],
        ]);

        if ($activeSub) {
            // deactivate previous sub completely

            if ($activeSub->status == 'canceled') {
                // sub already was canceled by user
                self::makeNotif($user, NG::SUB_CANCELED_TERMINATED_CAUSE_NEW, $activeSub->plan, $activeSub);
            } else {
                // cancel sub manualy
                $stripeService->cancelSubscription($activeSub->external_id);
                $activeSub->cancel();

                self::makeNotif($user, NG::SUB_TERMINATED_CAUSE_NEW, $activeSub->plan, $activeSub);
            }

            $activeSub->cycle->deactivate(true, false);
        }

        $cycle = $sub->cycles()->create([
            'is_active' => true,
            'invoice' => [
                'id' => $invoice['id'],
                'number' => $invoice['number'],
                'payment_intent_id' => $invoice['payment_intent']['id']
            ],
            'price' => $invoice['amount_paid']/100,
            'expire_at' => Carbon::createFromTimestamp($subscription['current_period_end'])
        ]);

        self::makeNotif(
            $user,
            $subscription->status == 'active' ? NG::SUB_CREATED : NG::SUB_CREATED_INCOMPLETE,
            $subscriptionPlan,
            $cycle
        );

        return null;
    }

    public static function extend()
    {
        $validStatuses = ['trialing', 'active'];

        foreach (Subscription::whereIn('status', $validStatuses)->get() as $subscription) {
            DB::beginTransaction();

            // dlog("start tranlsaction for #$subscription->id"); //! LOG

            try {
                if ($subscription->provider == 'stripe') {
                    self::extendHelperForStripe($subscription, $validStatuses);
                } else if ($subscription->provider == 'manual')  {
                    self::extendHelperForManual($subscription);
                }
            } catch (\Throwable $th) {
                dlog("ERROR"); //! LOG
                \Log::error("Error when extending #$subscription->id subscription: " . exceptionAsString($th));
                DB::rollBack();
                continue;
            }

            DB::commit();
        }
    }

    public static function deactivateCanceled()
    {
        $now = Carbon::now();

        // Parse manualy canceled subs.
        // The subscription already canceled in stripe.
        // We need just to deactivate active paid period on our side.
        foreach (Subscription::where('status', 'canceled')->get() as $subscription) {
            $activeCycle = $subscription->cycle;
            if ($activeCycle && $activeCycle->expire_at < $now) {
                $activeCycle->deactivate();

                self::makeNotif($subscription->user, NG::SUB_CANCELED_EXPIRED, $subscription->plan, $activeCycle);
            }
        }
    }

    public static function disablePaidFuntionalities($user)
    {
        $posts = $user->posts()->active()->get();
        $mailers = $user->mailers()->active()->get();

        foreach ($posts as $post) {
            $post->is_active = false;
            $post->save();
        }

        foreach ($mailers as $mailer) {
            $mailer->is_active = false;
            $mailer->save();
        }
    }

    public static function subscriptionUpdatedHook($object)
    {
        $stripeService = new StripeService();
        $sId = $object['id'];
        $plan = $subscription->plan;
        $subscription = Subscription::query()
            ->where('external_id', $sId)
            ->whereHas('cycle')
            ->with(['cycle', 'plan', 'user'])
            ->first();

        if (!$subscription) {
            return;
        }

        if ($subscription->status == 'incomplete' && $object['status'] == 'active') {
            // mark subscription as active after been incomplete - user made a payment

            $user = $subscription->user;
            $cycle = $subscription->cycle;
            $invoice = $stripeService->getInvoice($object['latest_invoice']);

            $subscription->update([
                'status' => 'active'
            ]);

            $cycle->update([
                'invoice' => [
                    'id' => $invoice['id'],
                    'number' => $invoice['number'],
                    'payment_intent_id' => $invoice['payment_intent']['id']??null
                ],
                'price' => $invoice['amount_paid']/100,
            ]);

            self::makeNotif($user, NG::SUB_INCOMPLETED_PAID, $plan, $cycle);
        } else if ($subscription->status == 'incomplete' && $object['status'] == 'incomplete_expired') {
            // Canceling the subscription which was not paid manually and was just created.
            // Manual payment is required when automatic collection can not be executed.
            // It can occure if customer`s payment method have security checks enabled (3DS).
            // Stripe will send a webhook when payment window runs out.
            // This payment window is static and equals to 23 hours (due to JAN 2024).

            $subscription->cancel();
            $cycle->deactivate(true);
            self::makeNotif($user, NG::SUB_CANCELED, $plan, $subscription);
        } else if ($subscription->status == 'active' && $object['status'] == 'past_due') {
            // Canceling the subscription which was not paid manually.
            // Manual payment is required when automatic collection can not be executed.
            // It can occure if customer`s payment method have security checks enabled (3DS).
            // Stripe will send a webhook when payment window runs out.
            // This payment window should be set in 
            // Stripe > Settings > Subscriptions and emails > Manage payments that require confirmation.

            $subscription->cancel();
            $cycle->deactivate(true);
            $stripeService->cancelSubscription($sId); // cancel sub in stripe as well
            self::makeNotif($user, NG::SUB_CANCELED, $plan, $subscription);
        }
    }

    public static function cancel()
    {
        $stripeService = new StripeService();
        $user = auth()->user();
        $activeSub = $user->activeSubscription();

        if (!$activeSub) {
            return 'No active subscrption found';
        }

        $stripeService->cancelSubscription($activeSub->external_id);
        $activeSub->cancel();

        self::makeNotif($user, NG::SUB_CANCELED, $activeSub->plan, $activeSub);

        return null;
    }

    public static function getInvoiceUrl($subscriptionCycle)
    {
        $stripeService = new StripeService();
        $invoice = $subscriptionCycle->invoice;

        if (!$invoice) {
            // should never occure
            return null;
        }

        $invoice = $stripeService->getInvoice($invoice['id']);

        if ($invoice->paid) {
            $paymentIntent = $stripeService->paymentIntent($invoice['payment_intent_id']);
            $charge = $stripeService->charge($paymentIntent->latest_charge);
            $url = $charge->receipt_url;
        } else {
            $url = $invoice->invoice_pdf;
        }

        activity('users')
            ->event('get-invoice')
            ->on($subscriptionCycle)
            ->withProperties(infoForActivityLog())
            ->log('');

        return $url;
    }

    public static function sendPreliminaryNotifs()
    {
        $now = now();
        $subscriptions = Subscription::query()
            ->where('status', 'active')
            ->where('provider', '!=', 'manual')
            ->whereHas('cycle')
            ->with(['user', 'cycle', 'plan'])
            ->get();

        foreach ($subscriptions as $subscription) {
            $cycle = $subscription->cycle;
            $exp = $cycle->expire_at;
            $isCanceled = $subscription->isCanceled();

            if ($exp->addDays(7)->isSameDay($now)) {
                $group = $isCanceled ? NG::SUB_END_NEXT_WEEK : NG::SUB_RENEW_NEXT_WEEK;
                self::makeNotif($subscription->user, $group, $subscription->plan, $cycle);
            }

            if ($exp->addDay()->isSameDay($now)) {
                $group = $isCanceled ? NG::SUB_END_TOMORROW : NG::SUB_RENEW_TOMORROW;
                self::makeNotif($subscription->user, $group, $subscription->plan, $cycle);
            }
        }
    }

    private static function makeNotif($user, $group, $plan, $resource)
    {
        Notification::make($user->id, $group, [
            'vars' => [
                'title' => $plan->title
            ]
        ], $resource);

        if ($group == NG::SUB_CREATED_INCOMPLETE || $group == NG::SUB_CREATED) {
            Mail::to($user)->send(new \App\Mail\Subscriptions\Created($resource, $group));
        }

        if ($group == NG::SUB_CANCELED_TERMINATED_CAUSE_NEW || $group == NG::SUB_TERMINATED_CAUSE_NEW) {
            Mail::to($user)->send(new \App\Mail\Subscriptions\CanceledCauseNew($resource, $group));
        }

        if ($group == NG::SUB_EXTENDED_INCOMPLETE || $group == NG::SUB_EXTENDED) {
            Mail::to($user)->send(new \App\Mail\Subscriptions\Extended($resource, $group));
        }

        if ($group == NG::SUB_EXTENTION_FAILED) {
            Mail::to($user)->send(new \App\Mail\Subscriptions\ExtentionFailed($resource));
        }

        if ($group == NG::SUB_CANCELED_EXPIRED) {
            Mail::to($user)->send(new \App\Mail\Subscriptions\CanceledExpired($resource));
        }

        if ($group == NG::SUB_INCOMPLETED_EXPIRED) {
            Mail::to($user)->send(new \App\Mail\Subscriptions\IncompletedExpired($resource));
        }

        if ($group == NG::SUB_INCOMPLETED_PAID) {
            Mail::to($user)->send(new \App\Mail\Subscriptions\IncompletedPaid($resource));
        }

        if ($group == NG::SUB_CANCELED) {
            Mail::to($user)->send(new \App\Mail\Subscriptions\Canceled($resource));
        }

        if ($group == NG::SUB_END_NEXT_WEEK || $group == NG::SUB_RENEW_NEXT_WEEK) {
            Mail::to($user)->send(new \App\Mail\Subscriptions\EndNextWeek($resource, $group));
        }

        if ($group == NG::SUB_END_TOMORROW || $group == NG::SUB_RENEW_TOMORROW) {
            Mail::to($user)->send(new \App\Mail\Subscriptions\EndTomorrow($resource, $group));
        }
    }

    /*

        before 2024-01-26 16:00:07 i had a cycle 
            expire_at: 2024-02-26 15:25:42
            created_at: 2024-01-26 16:00:03
            {
                "id": "in_1OcrIvDqMxQknFXPomaz9ZDE", 
                "number": null,
                "payment_intent_id": null
            }
        at 2024-01-26 16:00:07 i got sub:
            {
                "id":"sub_1OGkXeDqMxQknFXPSdG5LfR5",
                "billing_cycle_anchor":1701013602,
                "created":1701013602,
                "current_period_end":1708962402,  (26-02-2024 15:46:42)
                "current_period_start":1706284002, (26 January 2024 15:46:42)
                "customer":"cus_P4uMHmL5rViT6F"
            }
        compare 
            cycle expire at -        2024-02-26 15:25:42 VS 
            sub current_period_end - 2024-02-26 15:46:42
    */
    private static function extendHelperForStripe($subscription, $validStatuses)
    {
        dlog("SubscriptionService@extendHelperForStripe"); //! LOG

        $stripeService = new StripeService();
        $user = $subscription->user;
        $stripeSub = $stripeService->getSubscription($subscription->external_id);
        $stripeSubStatus = $stripeSub->status;
        $stripeExpDate = Carbon::createFromTimestamp($stripeSub->current_period_end);
        $activeCycle = $subscription->cycle;
        $invoice = $stripeSub['latest_invoice']??[];

        dlog(" got sub: ", $stripeSub->toArray()); //! LOG

        if ($activeCycle->expire_at == $stripeExpDate) {
            // we are within paid subscription period

            // dlog(" within payed period"); //! LOG

            if (!in_array($stripeSubStatus, $validStatuses)) {
                // Subscription was canceled in stripe within a active cycle (rare case).
                // So deactivate it on out side as well.
                // Keep the cycle active - it will deactivate when time comes automaticaly

                $subscription->cancel();

                //TODO: notification
            }

            dlog(' the $activeCycle->expire_at is equal to $stripeSub->current_period_end', [
                'active_cycle_expire_at' => $activeCycle->expire_at, 
                'stripe_sub_current_period_end' => $stripeExpDate
            ]); //! LOG

            return;
        }

        dlog(" need to renew"); //! LOG

        // current cycle should be renewed

        // stripe possible statuses: incomplete, incomplete_expired, trialing, active, past_due, canceled, unpaid
        // only 'active' and 'incomplete' are treated as success.
        //? trialing and incomplete can not occure here.
        if ($stripeSubStatus != 'active' && $stripeSubStatus != 'past_due') {
            // stripe can not renew subscription automaticaly

            dlog(" cancel"); //! LOG

            $subscription->cancel();
            $activeCycle->deactivate();

            self::makeNotif($user, NG::SUB_EXTENTION_FAILED, $subscription->plan, $subscription);

            // dlog(" cancel done"); //! LOG

            return;
        }

        if ($stripeSubStatus == 'past_due') {
            $stripeSubStatus = 'incomplete';
        }

        dlog(" renew. invoice: ", $invoice->toArray()); //! LOG

        // update status. For example: trialing->active or active->incomplete
        $subscription->update([
            'status' => $stripeSubStatus
        ]);

        $activeCycle->deactivate(false, false);
        $cycle = $subscription->cycles()->create([
            'is_active' => true,
            'invoice' => [
                'id' => $invoice['id'],
                'number' => $invoice['number'],
                'payment_intent_id' => $invoice['payment_intent']['id'] ?? ''
            ],
            'price' => $invoice['amount_paid']/100,
            'expire_at' => $stripeExpDate
        ]);

        self::makeNotif(
            $user,
            $stripeSubStatus == 'active' ? NG::SUB_EXTENDED : NG::SUB_EXTENDED_INCOMPLETE,
            $subscription->plan,
            $cycle
        );

        // dlog(" renew done"); //! LOG
    }

    private static function extendHelperForManual($subscription)
    {
        dlog("SubscriptionService@extendHelperForManual"); //! LOG
        $activeCycle = $subscription->cycle;

        if ($activeCycle->expire_at > now()) {
            return;
        }

        $cycles = $subscription->cycles;
        $plan = $subscription->plan;
        $user = $subscription->user;

        if ($subscription->max_cycles && $cycles->count() >= $subscription->max_cycles) {
            $subscription->cancel();
            $activeCycle->deactivate();
            self::makeNotif($user, NG::SUB_EXTENTION_FAILED, $plan, $subscription);
            return;
        }

        $activeCycle->deactivate(false, false);
        $cycle = $subscription->cycles()->create([
            'is_active' => true,
            'invoice' => null,
            'price' => 0,
            'expire_at' => $plan->getNextExpireAt()
        ]);

        self::makeNotif($user, NG::SUB_EXTENDED, $plan, $cycle);
    }
}
