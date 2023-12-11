<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Setting;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Services\StripeService;
use App\Models\SubscriptionPlan;
use Yajra\DataTables\DataTables;
use App\Models\SubscriptionCycle;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubscriptionRequest;

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

    public function create(Request $request)
    {
        $plans = SubscriptionPlan::all();
        $users = User::latest()->get();

        return view('admin.subscriptions.create', compact('plans', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subscription_plan_id' => ['required', 'exists:subscription_plans,id'],
            'user_id' => ['required', 'exists:users,id'],
            'stripe_id' => ['nullable', 'string'],
            'status' => ['required', 'string'],
            'is_active' => ['required', 'boolean'],
            'invoice' => ['nullable', 'json'],
            'price' => ['nullable', 'json'],
            'expire_at' => ['required', 'date'],
        ]);

        $user = User::findOrFail($data['user_id']);
        $activeSub = $user->activeSubscription();

        if ($activeSub) {
            return $this->jsonError("User already have active subscription");
        }

        $data['price'] = $data['price'] ?: 0;
        $data['invoice'] = $data['invoice'] ? json_decode($data['invoice'], true) : null;

        $subscription = Subscription::create($data);
        $subscription->cycle()->create($data);

        return $this->jsonSuccess("Subscription successfully created!", [
            'redirect' => route('admin.subscriptions.index')
        ]);
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
