<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class SocialAuthController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $socialUser = Socialite::driver($provider)->user();

        $user = User::where('email', $socialUser->email)->first();
        if (!$user) {
            $user = User::create([
                'name' => $socialUser->name,
                'email' => $socialUser->email,
                'email_verified_at' => now()
            ]);
        }

        $social = [
            'provider' => $provider,
            'token' => $socialUser->token
        ];

        $user->socials()->updateOrCreate($social, $social);

        Auth::login($user);

        return redirect()->route('index');
    }
}
