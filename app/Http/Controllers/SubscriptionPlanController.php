<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $plans = SubscriptionPlan::all();
        $plans = [
            1 => [
                'month' => $plans->where('level', 1)->where('interval', 'month')->first(),
                'year' => $plans->where('level', 1)->where('interval', 'year')->first(),
            ],
            2 => [
                'month' => $plans->where('level', 2)->where('interval', 'month')->first(),
                'year' => $plans->where('level', 2)->where('interval', 'year')->first(),
            ]
        ];

        return view('subscription-plans.index', compact('plans'));
    }

    public function show(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        return view('subscription-plans.show', compact('subscriptionPlan'));
    }
}
