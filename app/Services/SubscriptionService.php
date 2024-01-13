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
            'stripe_id' => $subscription['id'],
            'status' => $subscription['status'],
        ]);

        if ($activeSub) {
            // deactivate previous sub completely

            if ($activeSub->status == 'canceled') {
                // sub already was canceled by user
                self::makeNotif($user, NG::SUB_CANCELED_TERMINATED_CAUSE_NEW, $activeSub->plan, $activeSub);
            } else {
                // cancel sub manualy
                $stripeService->cancelSubscription($activeSub->stripe_id);
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
                self::extendHelper($subscription, $validStatuses);
            } catch (\Throwable $th) {
                dlog("ERROR"); //! LOG
                \Log::error("Error when extending #$subscription->id subscription: " . $th->getMessage() . '. Trace: ' . $th->getTraceAsString());
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

    public static function deactivateIncomplete()
    {
        $stripeService = new StripeService();
        $now = now();

        foreach (Subscription::where('status', 'incomplete')->get() as $subscription) {

            if (!$subscription->stripe_id) {
                continue;
            }

            if ($subscription->created_at->addDay() >= $now) {
                continue;
            }

            $cycle = $subscription->cycle;
            $user = $subscription->user;

            if (!$cycle) {
                continue;
            }

            if ($subscription->stripe_id) {
                $stripeService->cancelSubscription($subscription->stripe_id);
            }

            $subscription->cancel();
            $cycle->deactivate(true);

            self::makeNotif($user, NG::SUB_INCOMPLETED_EXPIRED, $subscription->plan, $subscription);
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

    public static function activateIncomplete($object)
    {
        $stripeService = new StripeService();
        $sId = $object['id'];
        $subscription = Subscription::query()
            ->where('stripe_id', $sId)
            ->where('status', 'incomplete')
            ->whereHas('cycle')
            ->with(['cycle', 'plan', 'user'])
            ->first();

        if (!$subscription) {
            return;
        }

        if ($object['status'] != 'active') {
            return;
        }

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
                'payment_intent_id' => $invoice['payment_intent']
            ],
            'price' => $invoice['amount_paid']/100,
        ]);

        self::makeNotif($user, NG::SUB_INCOMPLETED_PAID, $subscription->plan, $cycle);
    }

    public static function cancel()
    {
        $stripeService = new StripeService();
        $user = auth()->user();
        $activeSub = $user->activeSubscription();

        if (!$activeSub) {
            return 'No active subscrption found';
        }

        $stripeService->cancelSubscription($activeSub->stripe_id);
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

    private static function extendHelper($subscription, $validStatuses)
    {
        dlog("SubscriptionService@extendHelper"); //! LOG

        $stripeService = new StripeService();

        if (!$subscription->stripe_id) {
            return;
        }

        $user = $subscription->user;
        $stripeSub = $stripeService->getSubscription($subscription->stripe_id);
        $stripeSubStatus = $stripeSub->status;
        $stripeExpDate = Carbon::createFromTimestamp($stripeSub->current_period_end);
        $activeCycle = $subscription->cycle;
        $invoice = $stripeSub['latest_invoice']??[];

        dlog(" got sub: " . json_encode($stripeSub)); //! LOG

        if ($activeCycle->expire_at == $stripeExpDate) {
            // we are within paid subscription period

            // dlog(" within payed period"); //! LOG

            if (!in_array($stripeSubStatus, $validStatuses)) {
                // Subscription was canceled in stripe within a active cycle.
                // So deactivate it on out side as well.

                $subscription->cancel();

                //TODO: notification
            }

            return;
        }

        dlog(" need to renew"); //! LOG

        // current cycle should be renewed

        // stripe possible statuses: incomplete, incomplete_expired, trialing, active, past_due, canceled, unpaid
        // only 'active' and 'incomplete' are treated as success.
        //? trialing can not occure here.
        if ($stripeSubStatus != 'active' && $stripeSubStatus != 'incomplete') {
            // stripe can not renew subscription automaticaly

            // dlog(" cancel"); //! LOG

            $subscription->cancel();
            $activeCycle->deactivate();

            self::makeNotif($user, NG::SUB_EXTENTION_FAILED, $subscription->plan, $subscription);

            // dlog(" cancel done"); //! LOG

            return;
        }

        dlog(" renew. invoice: ", $invoice); //! LOG

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
                'payment_intent_id' => $invoice['payment_intent']
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
}
