<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\SubscriptionCycle;
use App\Http\Controllers\Controller;

class SubscriptionCycleController extends Controller
{
    public function edit(Request $request, SubscriptionCycle $subscriptionCycle)
    {
        return view('admin.subscription-cycles.edit', compact('subscriptionCycle'));
    }

    public function update(Request $request, SubscriptionCycle $subscriptionCycle)
    {
        $data = $request->validate([
            'is_active' => ['required', 'boolean'],
            'price' => ['nullable', 'numeric'],
            'invoice' => ['nullable', 'json'],
            'expire_at' => ['required', 'date'],
        ]);

        $data['invoice'] = json_decode($data['invoice'], true);

        $subscriptionCycle->update($data);

        $this->jsonSuccess('Subscription Cycle updated successfully', [
            'redirect' => route('admin.subscriptions.edit', $subscriptionCycle->subscription_id)
        ]);
    }
}
