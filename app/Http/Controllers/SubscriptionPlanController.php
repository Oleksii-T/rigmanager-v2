<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $activeSub = $user?->activeSubscription();
        $plans = SubscriptionPlan::all();
        $plans = [
            'premium' => [
                'monthly' => $plans->where('id', 3)->first(),
                'yearly' => $plans->where('id', 4)->first(),
            ],
            'commercial' => [
                'monthly' => $plans->where('id', 1)->first(),
                'yearly' => $plans->where('id', 2)->first(),
            ]
        ];

        return view('subscription-plans.index', compact('plans', 'activeSub'));
    }

    public function show(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        return view('subscription-plans.show', compact('subscriptionPlan'));
    }
}
