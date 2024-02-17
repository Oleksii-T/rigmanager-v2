<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Stevebauman\Location\Facades\Location;
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
                'country' => strtolower(Location::get()->countryCode),
                'slug' => makeSlug($socialUser->name, User::pluck('slug')->toArray()),
                'name' => $socialUser->name,
                'email' => $socialUser->email,
                'last_active_at' => now(),
            ]);

            $user->info()->create([]);

            event(new \Illuminate\Auth\Events\Verified($user));
        }

        if (!$user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
        }

        $social = [
            'provider' => $provider,
            'token' => $socialUser->token
        ];

        $user->socials()->updateOrCreate($social, $social);

        Auth::login($user);

        flash(trans($user->wasRecentlyCreated ? 'messages.registerSuccess' : 'messages.loginSuccess'));

        return redirect()->route('index');
    }
}
