<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\JsonResponsable;
use App\Models\SubscriptionPlan;

class Subscribed
{
    use JsonResponsable;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next, $level=null)
    {
        $user = auth()->user();

        if (!$user->isSub($level)) {
            return $this->subscriptionErrorResponse($level);
        }

        return $next($request);
    }
}
