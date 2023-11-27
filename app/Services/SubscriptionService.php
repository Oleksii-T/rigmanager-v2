<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Notification;
use App\Models\Subscription;
use App\Services\StripeService;
use App\Enums\NotificationGroup as NG;
use App\Models\SubscriptionPlan;

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

            $activeSub->cycle->deactivate(true);
        }

        $cycle = $sub->cycles()->create([
            'is_active' => true,
            'invoice' => [
                'id' => $invoice['id'],
                'number' => $invoice['number'],
                'payment_intent_id' => $invoice['payment_intent']['id']
            ],
            'price' => $subscriptionPlan->price,
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
        $stripeService = new StripeService();
        $validStatuses = ['trialing', 'active'];

        foreach (Subscription::whereIn('status', $validStatuses)->get() as $subscription) {
            if (!$subscription->stripe_id) {
                continue;
            }

            $user = $subscription->user;
            $stripeSub = $stripeService->getSubscription($subscription->stripe_id);
            $stripeSubStatus = $stripeSub->status;
            $nextDate = Carbon::createFromTimestamp($stripeSub->current_period_start);
            $activeCycle = $subscription->cycle;

            if ($activeCycle->expire_at >= $nextDate) {
                // we are within paid subscription period

                if (!in_array($stripeSubStatus, $validStatuses)) {
                    // Subscription was canceled in stripe within a active cycle.
                    // So deactivate it on out side as well.

                    $subscription->cancel();

                    //TODO: notification
                }

                continue;
            }

            // current cycle should be renewed

            // stripe possible statuses: incomplete, incomplete_expired, trialing, active, past_due, canceled, unpaid
            // only 'active' and 'incomplete' are treated as success.
            //? trialing can not occure here.
            if ($stripeSubStatus != 'active' && $stripeSubStatus != 'incomplete') {
                // stripe can not renew subscription automaticaly
                $subscription->cancel();
                $activeCycle->deactivate();

                self::makeNotif($user, NG::SUB_TERMINATED_CAUSE_STRIPE, $subscription->plan, $subscription);

                continue;
            }

            // update status. For example: trialing->active or active->incomplete
            $subscription->update([
                'status' => $stripeSubStatus
            ]);

            $activeCycle->deactivate(false, false);
            $cycle = $subscription->cycles()->create([
                'is_active' => true,
                'invoice' => [
                    'id' => $stripeSub->invoice['id'],
                    'number' => $stripeSub->invoice['number'],
                    'payment_intent_id' => $stripeSub->invoice['payment_intent']
                ],
                'expire_at' => $nextDate
            ]);

            self::makeNotif(
                $user,
                $stripeSubStatus == 'active' ? NG::SUB_EXTENDED : NG::SUB_EXTENDED_INCOMPLETE,
                $subscription->plan,
                $cycle
            );
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

            self::makeNotif($user, NG::SUB_TERMINATED_INCOMPLETE, $subscription->plan, $subscription);
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
            ]
        ]);

        self::makeNotif($user, NG::SUB_INCOMPLETE_PAID, $subscription->plan, $cycle);
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

    private static function makeNotif($user, $group, $plan, $resource)
    {
        Notification::make($user->id, $group, [
            'vars' => [
                'title' => $plan->title
            ]
        ], $resource);
    }
}
