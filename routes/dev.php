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
    \DB::enableQueryLog();


    try {

        // \App\Models\PostCost::query()->update([
        //     'type' => 'eq'
        // ]);
        // dd('done');

        $costFrom = 3000;
        $costFrom = 1500;
        $costTo = 2000;
        // $costTo = null;
        // $costFrom = null;
        $currency = 'usd';


        $posts = Post::query()
            ;

        if ($costFrom || $costTo) {
            $posts->whereHas('costs');
        }

        if ($costFrom) {
            $posts->where(function ($q) use($currency, $costFrom) {
                $q->whereHas('costs', fn ($q1) =>
                    $q1
                        ->where('currency', $currency)
                        ->where('type', 'el')
                        ->where('cost', '>=', $costFrom
                ))
                ->orWhereHas('costs', fn ($q1) =>
                    $q1
                        ->where('currency', $currency)
                        ->where('type', 'to')
                        ->where('cost', '>=', $costFrom
                ));
            });
        }

        if ($costTo) {
            $posts->where(function ($q) use($currency, $costTo) {
                $q->whereHas('costs', fn ($q1) =>
                    $q1
                        ->where('currency', $currency)
                        ->where('type', 'el')
                        ->where('cost', '<=', $costTo
                ))
                ->orWhereHas('costs', fn ($q1) =>
                    $q1
                        ->where('currency', $currency)
                        ->where('type', 'from')
                        ->where('cost', '<=', $costTo
                ));
            });
        }

        $posts->where('posts.id', 695);

        $d[] = $posts->toRawSql();
        $d[] = $posts->get()->first();

        /*
            select *
            from `posts`
            inner join `post_costs` on `posts`.`id` = `post_costs`.`post_id`
            where `posts`.`is_double_cost` = 1
            and `post_costs`.`currency` = 'usd'
            and `post_costs`.`type` = 'to'
            and `post_costs`.`cost` >= 1500
            and `posts`.`is_double_cost` = 1
            and `post_costs`.`currency` = 'usd'
            and `post_costs`.`type` = 'from'
            and `post_costs`.`cost` <= 2000
            and `posts`.`id` = 695
            and `posts`.`deleted_at`
            is null




            select *
            from `posts`
            inner join `post_costs` on `posts`.`id` = `post_costs`.`post_id`
            where (
                (
                    `posts`.`is_double_cost` = 0 and
                    `post_costs`.`currency` = 'usd' and
                    `post_costs`.`type` = 'eq' and
                    `post_costs`.`cost` >= 1500
                )
                or (
                    `posts`.`is_double_cost` = 1 and
                    `post_costs`.`currency` = 'usd' and
                    `post_costs`.`type` = 'to' and
                    `post_costs`.`cost` >= 1500
                )
            )
            and (
                (
                    `posts`.`is_double_cost` = 0 and
                    `post_costs`.`currency` = 'usd' and
                    `post_costs`.`type` = 'eq' and
                    `post_costs`.`cost` <= 2000
                )
                or (
                    `posts`.`is_double_cost` = 1 and
                    `post_costs`.`currency` = 'usd' and
                    `post_costs`.`type` = 'from' and
                    `post_costs`.`cost` <= 2000
                )
            )
            and `posts`.`id` = 695
            and `posts`.`deleted_at`
            is null


        */

        // $posts->where(function ($q) use($costFrom){
        //     $q->where('post_costs.cost_from', '>=', $costFrom)
        //         ->orWhere('post_costs.cost_to', '<=', $costFrom);
        // });

    } catch (\Throwable $th) {
        dump('ERROR', $th);
    }

    dump('QUERY LOG', \DB::getQueryLog());
    dd('RESULT', $d);
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
