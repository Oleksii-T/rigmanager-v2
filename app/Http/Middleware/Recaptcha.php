<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Recaptcha
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // return $next($request); //! dev

        $ok = $this->checkRecaptcha($request->get('g-recaptcha-response'));

        if (!$ok) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'reason' => 'recaptcha',
                    'message' => trans('messages.invalid-recaptcha')
                ]);
            }

            abort(403, trans('messages.invalid-recaptcha'));
        }

        return $next($request);
    }

    private function checkRecaptcha($token)
    {
        // dlog("Recaptcha@checkRecaptcha"); //! LOG
        $secret = config('services.recaptcha.private_key');
        $threshold = config('services.recaptcha.threshold');

        if (!$secret || !$threshold) {
            // if keys are empty, assume that recaptcha is disabled by admin
            // dlog(" no keys"); //! LOG
            return true;
        }

        if (!$token) {
            // dlog(" not token"); //! LOG
            return false;
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $token,
            ])->json();

            if (!$response['success'] && in_array('invalid-input-response', $response['error-codes'])) {
                // user has invalid recatcha token
                // dlog(" wrong token"); //! LOG
                return false;
            }

            if ($response['score'] < $threshold) {
                // dlog(" recaptcha score: " . $response['score']); //! LOG
                // dlog(" bad threshold"); //! LOG
                return false;
            }
            // dlog(" ok threshold " . $threshold); //! LOG
        } catch (\Throwable $th) {
            // if recapcha service fails, treat as success
            \Log::error('Repatcha error: ' . $th->getMessage() . '. Response: ' . json_encode($response??''));
        }

        return true;
    }
}
