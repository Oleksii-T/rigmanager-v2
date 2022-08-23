<?php

use Illuminate\Support\Facades\Route;

/*
 *
 * Temporary Dev Routes
 *
 */

if (config('app.env') == 'production') {
    return;
}

Route::get('test', function () {

    // some testing code

    dd('done');
});

Route::get('login/{user?}', function () {

    $user = request()->user;

    if (!$user) {
        $user = \App\Models\User::where('email', 'admin@mail2.com')->first();
        if (!$user) {
            $user = \App\Models\User::whereHas('roles', function ($q) {
                $q->where('name', 'admin');
            })->first();
        }
    } else {
        $user = \App\Models\User::find($user);
    }

    auth()->login($user);

    return redirect('/');
});

Route::get('phpinfo', function () {
    phpinfo();
});
