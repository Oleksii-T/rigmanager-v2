<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\User;

class StripeService
{
    /**
     * @var \Stripe\StripeClient
     */
    private $stripe;

    /**
     * StripeService constructor.
     * @throws \Exception
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function __construct()
    {
        if (!Setting::get('stripe_secret_key')) {
            throw new \Exception('You need to configure the stripe');
        }

        $this->stripe = new \Stripe\StripeClient(Setting::get('stripe_secret_key'));

        if (!Setting::get('stripe_product')) {
            Setting::set('stripe_product', $this->createProduct()->id);
        }
    }

    /**
     * @throws \Stripe\Exception\ApiErrorException
     */
    private function createProduct()
    {
        return $this->stripe->products->create([
            'name' => env('APP_NAME')
        ]);
    }

    /**
     * @param float $amount
     * @param $interval
     * @param int $interval_count
     * @return \Stripe\Plan
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createPlan($nickname, float $amount, $interval, $interval_count = 1, $trialsDays=0)
    {
        return $this->stripe->plans->create([
            'amount' => $amount * 100,
            'currency' => Setting::get('currency'),
            'nickname' => $nickname,
            'interval' => $interval,
            'product' => Setting::get('stripe_product'),
            'interval_count' => $interval_count,
            'trial_period_days' => $trialsDays
        ]);
    }

    /**
     * @param $planId
     * @return \Stripe\Plan
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function deletePlan($planId)
    {
        return $this->stripe->plans->delete(
            $planId,
            []
        );
    }

    public function updatePlan($planId, $nickname, $trialsDays)
    {
        return $this->stripe->plans->update(
            $planId,
            [
                'nickname' => $nickname,
                'trial_period_days' => $trialsDays,
            ]
        );
    }

    /**
     * @param User $user
     * @return mixed
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createCustomer(User $user)
    {
        if (!$user->stripe_id) {
            $customer = $this->stripe->customers->create([
                'name' => $user->name,
                'description' => 'User #' . $user->id,
                'email' => $user->email
            ]);

            $user->stripe_id = $customer->id;
            $user->save();
        }

        return $user;
    }

    public function updateCustomer(User $user)
    {
        if (!$user->stripe_id) {
            return;
        }

        $this->stripe->customers->update($user->stripe_id, [
            'name' => $user->name,
            'description' => 'User #' . $user->id,
            'email' => $user->email
        ]);
    }

    /**
     * @param User $user
     * @param $paymentMethodId
     * @param $save
     * @return bool
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function updateDefaultPaymentMethod($customerId, $paymentMethodId)
    {
        $this->stripe->customers->update($customerId, [
            'invoice_settings' => [
                'default_payment_method' => $paymentMethodId
            ]
        ]);

        return true;
    }

    /**
     * @param $paymentMethodId
     * @param bool $customerId
     * @return \Stripe\PaymentMethod
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function getPaymentMethod($paymentMethodId, $customerId = false)
    {
        $payment_method = $this->stripe->paymentMethods->retrieve($paymentMethodId);
        if ($customerId) {
            $payment_method->attach([
                'customer' => $customerId
            ]);
        }

        return $payment_method;
    }

    /**
     * @param $customerId
     * @param $subscriptionId
     * @return \Stripe\Subscription
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createSubscription($customerId, $subscriptionId)
    {
        $subscription = [
            'customer' => $customerId,
            'items' => [
                ['price' => $subscriptionId],
            ],
            'expand' => ['latest_invoice.payment_intent'],
            'trial_from_plan' => true
        ];

        return $this->stripe->subscriptions->create($subscription);
    }

    /**
     * @param $subscriptionId
     * @return \Stripe\Subscription
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function getSubscription($subscriptionId)
    {
        return $this->stripe->subscriptions->retrieve(
            $subscriptionId,
            []
        );
    }

    /**
     * @param $subscriptionId
     * @return \Stripe\Subscription
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function cancelSubscription($subscriptionId)
    {
        return $this->stripe->subscriptions->cancel(
            $subscriptionId,
            []
        );
    }

    /**
     * @param $customerId
     * @return mixed
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function getPaymentsMethods($customerId)
    {
        $methods = $this->stripe->customers->allPaymentMethods(
            $customerId,
            ['type' => 'card']
        );

        return $methods['data'];
    }

    /**
     * @param $methodId
     * @return \Stripe\PaymentMethod
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function deletePaymentMethod($methodId)
    {
        return $this->stripe->paymentMethods->detach(
            $methodId,
            []
        );
    }

    public function updateSubscription($sId, $data)
    {
        $this->stripe->subscriptions->update($sId, $data);
    }

    public function getInvoice($id)
    {
        return $this->stripe->invoices->retrieve($id, []);
    }

    public function setupIntent($customerId)
    {
        return $this->stripe->setupIntents->create([
            'payment_method_types' => ['card'],
            'usage' => 'off_session',
            'customer' => $customerId
        ]);
    }

	public function attachMethodToCustomer($customerId, $payment_method){
		return $this->stripe->paymentMethods->attach(
            $payment_method,
            ['customer' => $customerId]
        );
	}
}
