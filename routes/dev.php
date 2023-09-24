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

Route::get('ip', function () {
    return 'Your ip is: ' . request()->ip();
});

Route::get('public', function () {
    $d = [];

    dd('RESULT DUMP', $d);
});

if (!isdev()) {
    // return;
}

Route::get('test', function () {
    // some testing code
    $d = [];

    try {
        $d['cepai'] = \App\Services\PostScraperService::make('http://en.cepai.com.cn/product/instrument/mperatu/index.html')
            ->pagination('#main_product_center #main1_box #main1_box2 a')
            ->post('#main_product_center #main1_box #main1_box1_1')
            ->postLink('.main1_box1_12 a')
            ->value('title', '#main_product #main2_btleft span')
            ->value('description', '#main_product #main2_cpmsbj p')
            ->value('image', '#main_product #main2_cpxqbj img', 'src')
            ->value('body', '#main_product #main2_lrxq #main2_box4', 'html')
            ->value('breadcrumbs', '#main_product #main1_wei a', null, true)
            ->abortOnEmpty(true)
            ->limit(1)
            ->sleep(0)
            // ->scrape(false);
            ->count();

        $d['cnsanmon'] = \App\Services\PostScraperService::make('http://www.cnsanmon.com/sjal')
            ->pagination('.ny_pages a')
            ->post('.nproduct li')
            ->postLink('a')
            ->value('title', '.nmain .news_title')
            ->value('description', '.nmain .newsbody', 'html')
            ->value('image', 'img', 'src', false, true)
            ->value('category', '.nbt')
            ->abortOnEmpty(true)
            ->limit(1)
            ->sleep(0)
            // ->debug(true)
            // ->scrape(false);
            ->count(false);

    } catch (\Throwable $th) {
        dd('ERROR', $th);
    }

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
