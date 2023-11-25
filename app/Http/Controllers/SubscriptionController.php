<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Services\StripeService;
use App\Enums\NotificationGroup;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionCycle;
use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $stripeService = new StripeService();
        $user = auth()->user();
        $activeSub = $user->activeSubscription();

        if (!$user->paymentMethods()->count()) {
            return $this->jsonError('No payment method found');
        }

        if ($activeSub) {
            // deactivate previous sub completely

            if ($activeSub->status == 'canceled') {
                // sub already was canceled by user
            } else {
                // cancel sub manualy
                $stripeService->cancelSubscription($activeSub->stripe_id);
                $activeSub->cancel();
            }

            $activeSub->cycle->deactivate(true);

            Notification::make($user->id, NotificationGroup::SUB_CANCELED_TERMINATED, [
                'vars' => [
                    'title' => $activeSub->plan->title,
                ]
            ], $activeSub);
        }

        $subscriptionPlan = SubscriptionPlan::find($request->plan_id);
        $subscription = $stripeService->createSubscription($user->stripe_id, $subscriptionPlan->stripe_id);
        $invoice = $subscription['latest_invoice']??[];

        $sub = $user->subscriptions()->create([
            'subscription_plan_id' => $subscriptionPlan->id,
            'stripe_id' => $subscription['id'],
            'status' => $subscription['status'],
        ]);

        $cycle = [
            'is_active' => true,
            'invoice_number' => $invoice['number']??null,
            'price' => $subscriptionPlan->price,
            'expire_at' => Carbon::createFromTimestamp($subscription['current_period_end'])
        ];

        if ($invoice) {
            $cycle['invoice'] = [
                'id' => $invoice['id'],
                'number' => $invoice['number'],
                'payment_intent_id' => $invoice['payment_intent']['id']
            ];
        } else {
            // should never occure
            \Log::error("Cannot retrieve invoice for subscription #$sub->id");
        }

        $cycle = $sub->cycles()->create($cycle);

        Notification::make($user->id, NotificationGroup::SUB_CREATED, [
            'vars' => [
                'title' => $subscriptionPlan->title,
            ]
        ], $cycle);

        return $this->jsonSuccess('Subscribed successfully', [
            'redirect' => route('profile.subscription')
        ]);
    }

    public function cancel(Request $request)
    {
        $stripeService = new StripeService();
        $user = auth()->user();
        $activeSub = $user->activeSubscription();

        if (!$activeSub) {
            return $this->jsonError('No active subscrption found');
        }

        $stripeService->cancelSubscription($activeSub->stripe_id);
        $activeSub->cancel();

        Notification::make($user->id, NotificationGroup::SUB_CANCELED, [
            'vars' => [
                'title' => $activeSub->plan->title,
            ]
        ], $activeSub);

        flash('Subscription canceled successfully');

        return $this->jsonSuccess('', [
            'reload' => true
        ]);
    }

    public function invoiceUrl(Request $request, SubscriptionCycle $subscriptionCycle)
    {
        $stripeService = new StripeService();
        $user = auth()->user();
        $invoice = $subscriptionCycle->invoice;

        if (!$invoice) {
            // should never occure
            \Log::error("User asked for missing invoice for subscriptionCycle #$subscriptionCycle->id");
            return $this->jsonError('Can not find invoice. Please contact us to solve the issue.');
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
            ->tap(function(\Spatie\Activitylog\Contracts\Activity $activity) {
                $activity->properties = infoForActivityLog();
            })
            ->log('');

        return $this->jsonSuccess('', [
            'open' => $url
        ]);
    }
}
