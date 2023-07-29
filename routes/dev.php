<?php

use Illuminate\Support\Facades\Route;
use App\Models\Translation;
use App\Models\Page;
use App\Models\Post;
use App\Models\Mailer;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailerPostFound;
use App\Mail\PasswordReset;
use App\Mail\TmpMail;

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
    $d = [];

    dd($d);
});

Route::get('emails/mailer', function () {
    $posts = Post::inRandomOrder()->limit(4)->get();
    $mailer = Mailer::first();

    return new MailerPostFound($mailer, $posts);
});

Route::get('emails/email-verify', function () {
    return new TmpMail('https://rigmanager.com.ua/');
});

Route::get('emails/pasword-reset', function () {
    return new PasswordReset('https://rigmanager.com.ua/');
});

Route::view('front-elements', 'dev.front-elements');
Route::view('invoice-pdf', 'dev.invoice-pdf');

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
