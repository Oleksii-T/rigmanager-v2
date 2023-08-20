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
 */

$ip = request()->ip();
$devs = [
    '127.0.0.1',
];

if (!in_array($ip, $devs)) {
    // return;
}

Route::get('test', function () {
    // some testing code
    $d = [];

    $a = [
        0 => 'zero',
        2 => 'two'
    ];
    $b = [
        1 => 'one'
    ];

    $d = $a + $b;

    ksort($d);

    dd($d);
});

Route::post('post', function () {
    // some testing code
    $d = request()->all();

    dd($d);
});

Route::prefix('emails')->group(function () {
    Route::get('verify', function () {
        $url = url('');
        $mail = new \App\Mail\TmpMail($url);

        if (request()->email) {
            Mail::to(request()->email)->send($mail);
        }

        return $mail;
    });

    Route::get('mailer', function () {
        $posts = Post::inRandomOrder()->limit(4)->get();
        $mailer = Mailer::first();
        $mail = new MailerPostFound($mailer, $posts);

        return $mail;
    });

    Route::get('pasword-reset', function () {
        return new PasswordReset('https://rigmanagers.com/');
    });
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
