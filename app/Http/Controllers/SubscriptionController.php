<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StripeService;
use App\Models\SubscriptionCycle;
use App\Http\Controllers\Controller;
use App\Services\SubscriptionService;

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

        SubscriptionService::create($request->plan_id);

        return $this->jsonSuccess('Subscribed successfully', [
            'redirect' => route('profile.subscription')
        ]);
    }

    public function cancel(Request $request)
    {
        $error = SubscriptionService::cancel();

        if ($error) {
            return $this->jsonError($error);
        }

        flash('Subscription canceled successfully');

        return $this->jsonSuccess('', [
            'reload' => true
        ]);
    }

    public function invoiceUrl(Request $request, SubscriptionCycle $subscriptionCycle)
    {
        $url = SubscriptionService::getInvoiceUrl($subscriptionCycle);

        if (!$url) {
            // should never occure
            \Log::error("User asked for missing invoice for subscriptionCycle #$subscriptionCycle->id");
            return $this->jsonError('Can not find invoice. Please contact us to solve the issue.');
        }

        return $this->jsonSuccess('', [
            'open' => $url
        ]);
    }
}
