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

    public function store(Request $request, StripeService $stripeService)
    {
        $data = $request->validate([
            'subscription_plan_id' => ['required', 'exists:subscription_plans,id'],
            'user_id' => ['required', 'exists:users,id'],
            'status' => ['required', 'string'],
            'provider' => ['required', 'in:stripe,manual'],
            'external_id' => ['nullable', 'required_if:provider,stripe', 'string', ], //'unique:subscriptions'
            'max_cycles' => ['nullable', 'numeric'],
        ]);

        $user = User::findOrFail($data['user_id']);
        $activeSub = $user->activeSubscription();

        if ($activeSub) {
            return $this->jsonError("User already have active subscription");
        }

        // create subscription
        $subscription = $user->subscriptions()->create($data);
        
        // create cycle
        $plan = $subscription->plan;
        if ($data['provider'] == 'stripe') {
            try {
                $stripeSub = $stripeService->getSubscription($data['external_id']);
            } catch (\Throwable $th) {
                return $this->jsonError('Stripe Subscription "' . $data['external_id'] . '" not found');
            }
            if ($stripeSub->status != 'active') {
                return $this->jsonError('Stripe Subscription should be active');
            }
            $invoice = $stripeSub['latest_invoice'];
            $data['active'] = true;
            $data['invoice'] = [
                'id' => $invoice['id'],
                'number' => $invoice['number'],
                'payment_intent_id' => $invoice['payment_intent']['id']
            ];
            $data['price'] = $plan->price;
        } else {
            $data['price'] = 0;
        }
        $data['expire_at'] = $plan->getNextExpireAt();
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
        if ($subscription->external_id && $subscription->status == 'active') {
            $this->stripeService->cancelSubscription($subscription->external_id);
        }

        $subscription->delete();

        return $this->jsonSuccess("The Subscription  #$subscription->id successfully deleted!");
    }
}
