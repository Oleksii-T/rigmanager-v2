<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use App\Traits\JsonResponsable;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;

class FortifyServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // login config
        Fortify::loginView(function () {
            return view('auth.login');
        });
        $this->app->instance(LoginResponse::class, new class implements LoginResponse {
            use JsonResponsable;

            public function toResponse($request)
            {
                if (!$request->ajax()) {
                    return redirect()->route('index');
                }
                return $this->jsonSuccess('Logged in successfully', [
                    'redirect' => route('index')
                ]);
            }
        });
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(50)->by($email.$request->ip());
        });

        // register config
        Fortify::registerView(function () {
            return view('auth.register');
        });
        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse {
            use JsonResponsable;

            public function toResponse($request)
            {
                return $this->jsonSuccess('Registered successfully', [
                    'redirect' => route('index')
                ]);
            }
        });

        // reset pass config
        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.password-email');
        });
        Fortify::resetPasswordView(function ($request) {
            $token = $request->token;
            $email = $request->email;
            return view('auth.password-reset', compact('token', 'email'));
        });

        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });
    }
}
