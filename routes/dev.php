<?php

use Illuminate\Support\Facades\Route;
use App\Models\Translation;
use App\Models\Page;
use App\Models\Post;
use App\Models\Mailer;
use App\Models\Category;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailerPostFound;

/*
 *
 * Temporary Dev Routes
 *
 * Notes:
 * \{\{ ?loc.*\)\) ?\}\}
 *
 */

if (config('app.env') == 'production') {
    return;
}

Route::get('test', function () {
    // some testing code

    $a = [12,23];
    $b = [13];

    dd($a + $b);

    $c = array_merge($a, $b);

    dd($c);

    $mailer = Mailer::first();
    $posts = Post::latest()->limit(5)->get();

    return new MailerPostFound($mailer, $posts);

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
