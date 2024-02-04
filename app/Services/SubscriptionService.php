<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Notification;
use App\Models\Subscription;
use App\Services\StripeService;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionCycle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Enums\NotificationGroup as NG;
use App\Facades\DumperServiceFacade as Dumper;

/**
 * Subscriptions explanaiton:
 * - Designed mainly for Stripe.
 * - Each sub have cycles.
 * - Sub considered active if it have active (payed) cycle.
 * - Some time of payment pending is allowed. It's called 'incomplete' status.
 * - Webhooks "customer.subscription.updated" and "invoice.created" are used to
 *   handle Stripe events such as sub extention and different payment errors.
 * - If webhooks fails there is fallback scheduled check.
 * - More detailed comments can be found inside the methods.
 *
 */
class SubscriptionService
{
    public static function create($planId, $paymentMethodId=null)
    {
        $stripeService = new StripeService();
        $user = auth()->user();
        $activeSub = $user->activeSubscription();
        $subscriptionPlan = SubscriptionPlan::find($planId);

        if ($paymentMethodId) {
            $paymentMethodId = $user->paymentMethods()->where('id', $paymentMethodId)->value('token');
            if (!$paymentMethodId) {
                return 'Payment method not found';
            }
        }

        $subscription = $stripeService->createSubscription($user->stripe_id, $subscriptionPlan->stripe_id, $paymentMethodId);
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
            'invoice' => SubscriptionCycle::extractInvoiceData($invoice),
            'price' => $invoice['amount_paid']/100,
            'expire_at' => Carbon::createFromTimestamp($subscription['current_period_end'])
        ]);

        self::makeNotif($user, NG::SUB_CREATED, $subscriptionPlan, $cycle);

        if ($subscription->status != 'active') {
            self::makeNotif($user, NG::SUB_CAN_NOT_AUTO_COLLECT, $subscriptionPlan, $sub);
        }

        return null;
    }

    public static function extendManual()
    {
        $subs = Subscription::query()
            ->where('provider', 'manual')
            ->whereIn('status', 'active')
            ->get();

        foreach ($subs as $subscription) {
            DB::beginTransaction();

            try {
                dlog("SubscriptionService@extendManual. $subscription->id"); //! LOG

                $activeCycle = $subscription->cycle;

                if ($activeCycle->expire_at > now()) {
                    continue;
                }

                $cycles = $subscription->cycles;
                $plan = $subscription->plan;
                $user = $subscription->user;

                if ($subscription->max_cycles && $cycles->count() >= $subscription->max_cycles) {
                    $subscription->cancel();
                    $activeCycle->deactivate();
                    self::makeNotif($user, NG::SUB_EXTENTION_FAILED, $plan, $subscription);
                    continue;
                }

                $activeCycle->deactivate(false, false);
                $cycle = $subscription->cycles()->create([
                    'is_active' => true,
                    'invoice' => null,
                    'price' => 0,
                    'expire_at' => $plan->getNextExpireAt()
                ]);

                self::makeNotif($user, NG::SUB_EXTENDED, $plan, $cycle);
            } catch (\Throwable $th) {
                dlog("ERROR"); //! LOG
                \Log::error("Error when extending #$subscription->id manual subscription: " . exceptionAsString($th));
                DB::rollBack();
                continue;
            }

            DB::commit();
        }
    }

    // Daily sync with stripe.
    // All checks done in this method are covered by webhooks.
    // This is a fallback logic in case webhooks will fail.
    public static function extendStripe()
    {
        $validStatuses = ['trialing', 'active'];
        $subs = Subscription::query()
            ->where('provider', 'stripe')
            ->whereIn('status', $validStatuses)
            ->get();

        foreach ($subs as $subscription) {
            DB::beginTransaction();

            // dlog("start tranlsaction for #$subscription->id"); //! LOG

            try {
                self::extendHelperForStripe($subscription, $validStatuses);
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

    // Webhook.
    // Sync the Stripe subscriptions with app db.
    // See detailed comments for each case inside.
    public static function subscriptionUpdatedHook($object, $prevAttrs)
    {
        dlog(" SubscriptionService@subscriptionUpdatedHook"); //! LOG

        $stripeService = new StripeService();
        $sId = $object['id'];
        $object = $stripeService->getSubscription($sId)->toArray();
        $invoice = $object['latest_invoice'];
        $subscription = Subscription::query()
            ->where('external_id', $sId)
            ->whereHas('cycle')
            ->with(['cycle', 'plan', 'user'])
            ->first();

        if (!$subscription) {
            return;
        }

        $plan = $subscription->plan;
        $cycle = $subscription->cycle;
        $user = $subscription->user;

        if ($subscription->status == 'incomplete' && $object['status'] == 'active') {
            // mark subscription as active after been incomplete - user made a payment

            dlog("  make sub active - user make a payment"); //! LOG

            $user = $subscription->user;
            $cycle = $subscription->cycle;

            $subscription->update([
                'status' => 'active'
            ]);

            $cycle->update([
                'invoice' => SubscriptionCycle::extractInvoiceData($invoice),
                'price' => $invoice['amount_paid']/100,
            ]);

            self::makeNotif($user, NG::SUB_INCOMPLETED_PAID, $plan, $cycle);

            return;
        }
        
        if ($subscription->status == 'incomplete' && $object['status'] == 'incomplete_expired') {
            // Canceling just created subscription which was not paid manually.
            // Manual payment is required when automatic collection can not be executed.
            // It can occure if customer`s payment method have security checks enabled (3DS).
            // Stripe will send a webhook when payment window runs out.
            // This payment window is static and equals to 23 hours (due to JAN 2024).

            dlog("  cancel sub - user fail to make first payment"); //! LOG

            $subscription->cancel();
            $cycle->deactivate(true);
            self::makeNotif($user, NG::SUB_INCOMPLETED_EXPIRED, $plan, $subscription);

            return;
        }
        
        if ($subscription->status == 'active' && $object['status'] == 'past_due') {
            // Mark the subscription which can not be paid automaticaly as 'incomplete'.

            dlog("  sub is past_due"); //! LOG

            $subscription->update([
                'status' => 'incomplete'
            ]);

            self::makeNotif($user, NG::SUB_CAN_NOT_AUTO_COLLECT, $plan, $subscription);

            return;
        }
        
        if ($subscription->status != 'canceled' && $object['status'] == 'canceled') {
            // Cancel the subscription which was manualy cancelated via Stripe UI.

            dlog("  cancel sub - manual cancelation via stripe"); //! LOG

            $subscription->cancel();
            $cycle->deactivate(true);
            self::makeNotif($user, NG::SUB_CANCELED, $plan, $subscription);

            return;
        }
        
        if (isset($prevAttrs['latest_invoice']) && $invoice['id'] != $cycle->invoice['id']) {
            // Subscription got new latest_invoice it means, it been extended.
            // At this moment, invoice is in 'draft' phase - no payment done yet.
            // Due to Stripe rules, the actual payment will be attempted in 1 hour.
            // If payment OK, no logic will be triggered.
            // If payment fails, the webhook with sub status 'past_due' will be received.

            dlog("  sub extended!"); //! LOG

            $stripeExpDate = Carbon::createFromTimestamp($object['current_period_end']);

            $cycle->deactivate(false, false);
            $newCycle = $subscription->cycles()->create([
                'is_active' => true,
                'invoice' => SubscriptionCycle::extractInvoiceData($invoice),
                'price' => 0,
                'expire_at' => $stripeExpDate
            ]);

            self::makeNotif($user, NG::SUB_EXTENDED, $plan, $newCycle);

            return;
        }

        dlog("  no logic found", $invoice); //! LOG
    }

    // Webhook.
    public static function subscriptionDeletedHook($object, $prevAttrs)
    {
        // Cancel the subscription which was not paid manually.
        // Manual payment is required when automatic collection can not be executed.
        // It can occure if customer`s payment method have security checks enabled (3DS).
        // Stripe will send a webhook when payment window runs out.
        // The payment window and new status should be set in 
        // Stripe > Settings > Subscriptions and emails > Manage payments that require confirmation.

        dlog("  cancel sub - payment not done"); //! LOG

        $subscription = Subscription::query()
            ->where('external_id', $object['id'])
            ->whereHas('cycle')
            ->with(['cycle', 'plan', 'user'])
            ->first();

        if (!$subscription) {
            return;
        }

        $plan = $subscription->plan;
        $cycle = $subscription->cycle;
        $user = $subscription->user;

        $subscription->cancel();
        $cycle->deactivate(true);
        self::makeNotif($user, NG::SUB_CANCELED, $plan, $subscription);
    }

    // Webhook.
    public static function invoiceUpdatedHook($object, $prevAttrs)
    {
        // update info about subscription cycle invoice.
        // this info can be empty when new cycle created, 
        // because Stripe has 1hr gap between sub extend and invoice creation.

        dlog(" SubscriptionService@invoiceUpdatedHook"); //! LOG
        
        $stripeService = new StripeService();

        if ($object['status'] == 'uncollectible' && $prevAttrs['status'] == 'open') {
            // User did not payed invoice. To preven out of logic late payments - 'void' the invoice.
            // Must be set in Stripe > Settings > Subscriptions and emails > Manage payments that require confirmation.
            // Do not update the subscription because there is separate webhook to handle attached sub

            dlog("  void invoice"); //! LOG

            $stripeService->voidInvoice($object['id']);

            return;
        }

        $cycle = SubscriptionCycle::query()
            ->where('invoice->id', $object['id'])
            ->where('price', 0)
            ->first();

        if ($cycle) {
            // sync stripe invoice data

            dlog("  update cycle #$cycle->id"); //! LOG

            $object = $stripeService->getInvoice($object['id'])->toArray();

            $cycle->update([
                'invoice' => SubscriptionCycle::extractInvoiceData($object),
                'price' => $object['amount_paid']/100
            ]);

            return;
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
            $paymentIntent = $stripeService->paymentIntent($invoice['payment_intent']);
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

        if (!$user->info->is_registered) {
            return;
        }

        if ($group == NG::SUB_CREATED) {
            Mail::to($user)->send(new \App\Mail\Subscriptions\Created($resource));
        }

        if ($group == NG::SUB_CAN_NOT_AUTO_COLLECT) {
            Mail::to($user)->send(new \App\Mail\Subscriptions\CantAutoCollect($resource));
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

    private static function extendHelperForStripe($subscription, $validStatuses)
    {
        // dlog("SubscriptionService@extendHelperForStripe"); //! LOG

        $stripeService = new StripeService();
        $user = $subscription->user;
        $stripeSub = $stripeService->getSubscription($subscription->external_id);
        $stripeSubStatus = $stripeSub->status;
        $stripeExpDate = Carbon::createFromTimestamp($stripeSub->current_period_end);
        $activeCycle = $subscription->cycle;
        $invoice = $stripeSub['latest_invoice']??[];

        // dlog(" got sub: ", $stripeSub->toArray()); //! LOG

        if ($activeCycle->expire_at == $stripeExpDate) {
            // we are within paid subscription period

            // dlog(" within payed period"); //! LOG

            if (!in_array($stripeSubStatus, $validStatuses)) {
                // Subscription was canceled in stripe within a active cycle (rare case).
                // So deactivate it on out side as well.
                // Keep the cycle active - it will deactivate when time comes automaticaly

                $subscription->cancel();
            }

            // dlog(' the $activeCycle->expire_at is equal to $stripeSub->current_period_end', [
            //     'active_cycle_expire_at' => $activeCycle->expire_at, 
            //     'stripe_sub_current_period_end' => $stripeExpDate
            // ]); //! LOG

            return;
        }

        dlog(" SubscriptionService@extendHelperForStripe need to renew"); //! LOG

        // current cycle should be renewed

        // stripe possible statuses: incomplete, incomplete_expired, trialing, active, past_due, canceled, unpaid
        // only 'active' and 'incomplete' are treated as success.
        // trialing and incomplete can not occure here.
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
            'invoice' => SubscriptionCycle::extractInvoiceData($invoice),
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
}
