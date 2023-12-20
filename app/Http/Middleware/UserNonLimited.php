<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\UserStatus;
use Illuminate\Http\Request;
use App\Traits\JsonResponsable;
use Symfony\Component\HttpFoundation\Response;

class UserNonLimited
{
    use JsonResponsable;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->status == UserStatus::LIMITED) {
            if (!request()->ajax()) {
                return redirect()->route('limited');
            }

            return $this->jsonError('This action was prohibited for you', null, 403);
        }

        return $next($request);
    }
}
