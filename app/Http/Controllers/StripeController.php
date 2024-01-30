<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StripeService;
use App\Services\SubscriptionService;

class StripeController extends Controller
{
    public function setupIntent(Request $request, StripeService $service)
    {
        $user = auth()->user();
        $service->createCustomer($user);
        $intent = $service->setupIntent($user->stripe_id);

        return $this->jsonSuccess('', [
            'intent_id' => $intent->id,
            'client_secret' => $intent->client_secret
        ]);
    }

    public function webhook(Request $request)
    {
        dlog("StripeController@webhook", $request->all()); //! LOG
        try {
            $stripeService = new StripeService();
            $event = $stripeService->getEvent($request->id);
            $type = $event['type'];
            $object = $event['data']['object'];

            if ($type == 'customer.subscription.updated') {
                SubscriptionService::subscriptionUpdatedHook($object);
            }
        } catch (\Throwable $th) {
            // prevent stripe reactions for failed webhooks
            \Log::error('ERROR in Stripe webhook: ' . exceptionAsString($th), $request->all());
        }
    }
}
