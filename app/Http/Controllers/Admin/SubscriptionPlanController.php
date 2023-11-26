<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubscriptionPlanRequest;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Services\StripeService;
use App\Models\Setting;

class SubscriptionPlanController extends Controller
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
            return view('admin.subscription-plans.index');
        }

        return SubscriptionPlan::dataTable(SubscriptionPlan::query());
    }

    public function create()
    {
        return view('admin.subscription-plans.create');
    }

    public function store(SubscriptionPlanRequest $request)
    {
        try {
            $input = $request->validated();
            $stripePlan = $this->stripeService->createPlan(
                $input['title']['en'],
                $input['price'],
                $input['interval'],
                1,
                $input['trial']
            );
            $input['stripe_id'] = $stripePlan['id'];
            $subscriptionPlan = SubscriptionPlan::create($input);
            $subscriptionPlan->saveTranslations($input);
        } catch (\Throwable $th) {
            if (isset($subscriptionPlan)) {
                $subscriptionPlan->delete();
            }

            throw $th;
        }

        return $this->jsonSuccess('Subscription plan created successfully', [
            'redirect' => route('admin.subscription-plans.index')
        ]);
    }

    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        return view('admin.subscription-plans.edit', compact('subscriptionPlan'));
    }

    public function update(SubscriptionPlanRequest $request, SubscriptionPlan $subscriptionPlan)
    {
        $input = $request->validated();
        $stripePlan = $this->stripeService->updatePlan(
            $subscriptionPlan->stripe_id,
            $input['title']['en'],
            $input['trial']
        );
        $subscriptionPlan->update($input);
        $subscriptionPlan->saveTranslations($input);

        return $this->jsonSuccess('Subscription plan updated successfully.');
    }

    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        if ($subscriptionPlan->stripe_id) {
            $this->stripeService->deletePlan($subscriptionPlan->stripe_id);
        }

        $subscriptionPlan->delete();

        return $this->jsonSuccess(null, "The Subscription plan #$subscriptionPlan->id successfully deleted!");
    }
}
