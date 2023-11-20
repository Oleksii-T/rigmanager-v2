<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubscriptionRequest;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\SubscriptionCycle;
use App\Models\Setting;
use Yajra\DataTables\DataTables;
use App\Services\StripeService;

class SubscriptionController extends Controller
{
    private $stripeService;

    public function __construct()
    {
        try {
            $this->stripeService = new StripeService();
        } catch (\Exception $e) {
            redirect()->route('admin.settings.index')->with('error', $e->getMessage())->send();
        }
    }

    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('admin.subscriptions.index');
        }

        return Subscription::dataTable(Subscription::query());
    }

    public function show(Request $request, Subscription $subscription)
    {
        if (!$request->ajax()) {
            return view('admin.subscriptions.show', compact('subscription'));
        }

        return SubscriptionCycle::dataTable($subscription->cycles());
    }

    public function destroy(Subscription $subscription)
    {
        if ($subscription->stripe_id && $subscription->status == 'active') {
            $this->stripeService->cancelSubscription($subscription->stripe_id);
        }

        $subscription->delete();

        return $this->jsonSuccess("The Subscription  #$subscription->id successfully deleted!");
    }
}
