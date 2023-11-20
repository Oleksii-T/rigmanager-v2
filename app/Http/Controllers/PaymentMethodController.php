<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Services\StripeService;

class PaymentMethodController extends Controller
{
    public function store(Request $request)
    {
        $stripeService = new StripeService();
        $user = auth()->user();
        $intent_id = $request->intent_id;
        $stripeService->createCustomer($user);

        $defaultMethodExist = $user->getDefaultPaymentMethod();
        if (!$defaultMethodExist || $request->use_as_default) {
            $stripeService->updateDefaultPaymentMethod($user->stripe_id, $request->paymentMethod['id']);
            $user->paymentMethods()->update([
                'is_default' => false
            ]);
        }
        $pm = $user->paymentMethods()->create([
            'is_default' => !$defaultMethodExist ? 1 : $request->use_as_default ?? 0,
            'token' => $request->paymentMethod['id'],
            'data' => $request->paymentMethod,
        ]);

        $stripeService->attachMethodToCustomer($user->stripe_id, $request->paymentMethod['id']);

        return $this->jsonSuccess('Payments method added successfully', [
            'paymentMethod' => $pm,
            'redirect' => route('profile.index')
        ]);
    }

    public function setDefault(Request $request, PaymentMethod $paymentMethod)
    {
        $stripeService = new StripeService();
        $user = auth()->user();

        $stripeService->updateDefaultPaymentMethod($user->stripe_id, $paymentMethod->token);
        $paymentMethod->update([
            'is_default' => true
        ]);
        $user->paymentMethods()->where('id', '!=', $paymentMethod->id)->update([
            'is_default' => false
        ]);

        return $this->jsonSuccess('Default payment method updated successfully', [
            'redirect' => route('profile.index')
        ]);
    }

    public function destroy(Request $request, PaymentMethod $paymentMethod)
    {
        $stripeService = new StripeService();
        $user = auth()->user();
        $method = $stripeService->deletePaymentMethod($paymentMethod->token);
        if ($paymentMethod->is_default) {
            $otherPm = $user->paymentMethods()->first();
            if ($otherPm) {
                $otherPm->update([
                    'is_default' => true
                ]);
                $method = $stripeService->updateDefaultPaymentMethod($user->stripe_id, $otherPm->token);
            }
        }

        $paymentMethod->delete();

        return $this->jsonSuccess('Payment method deleted successfully', [
            'redirect' => route('profile.index')
        ]);
    }
}
