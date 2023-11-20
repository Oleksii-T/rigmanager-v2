<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StripeService;

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
}
