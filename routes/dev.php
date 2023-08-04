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

// if (config('app.env') == 'production') {
//     return;
// }

Route::get('test', function () {
    // some testing code
    $d = [];

    $val = '$20000';
    $currencies = array_values(currencies());
    $currency = $val[0];
    $val = substr($val, 1);

    dd($currency, $currencies, $val, floatval($val), $val == floatval($val) ? 'same' : 'not same');

    if (!in_array($currency, $currencies) || $val != floatval($val)) {
        abort(422, trans('messages.import.errors.cost'));
    }


    $ats = \App\Models\Attachment::where('attachmentable_type', Post::class)->get();
    foreach ($ats as $a) {
        try {
            unlink($a->path);
            $a->delete();
        } catch (\Throwable $th) {
            $a->delete();
        }
    }

    dd('done');


    \Illuminate\Support\Facades\DB::transaction(function () {
        sleep(5);
    });

    dd('done');


    $post = Post::find(209);
    $disk = \Illuminate\Support\Facades\Storage::disk('aimages');
    $a = "https://static.westarcloud.com/5fb5ca47b2c1a7004b498371/images/20210330/db327f4b9326d48faf70b5ad7b2649d5.jpg 
    https://static.westarcloud.com/5fb5ca47b2c1a7004b498371/images/20210330/dc4ca57ea5612c2308a3599214a01cb0.jpg
https://static.westarcloud.com/5fb5ca47b2c1a7004b498371/images/20210330/19a9af464a0bc73ecf3416aeecec2f24.jpg 
    https://static.westarcloud.com/5fb5ca47b2c1a7004b498371/images/20221010/c01fa4fe3a4673de9c0dfa860860d102.jpg   https://static.westarcloud.com/5fb5ca47b2c1a7004b498371/images/20221010/e70791068f1af2fef02bdd3521b049bb.jpg 
    https://static.westarcloud.com/5fb5ca47b2c1a7004b498371/images/20221010/e70791068f1af2fef02bdd3521b049bb.jpg 
    ";

    $imagesTmp = explode(' ', $a);
    $images = [];
    //? use preg_split instead
    foreach ($imagesTmp as $i) {
        $res = explode("\n", $i);
        count($res) == 1 ? $images[] = $i : $images = array_merge($images, $res);
    }
    dd($images);
    foreach ($images as $url) {
        dump($url);
        if (!$url) {
            dump(' empty 1');
            continue;
        }
        $pattern = '/\s*/m';
        $replace = '';
        $url = preg_replace($pattern, $replace, $url);
        $url = trim($url);
        
        if (!$url) {
            dump(' empty 2');
            continue;
        }
        
        dump(' do');

        try {
            $contents = file_get_contents($url);
            $name = substr($url, strrpos($url, '/') + 1);
            $ext = substr($name, strrpos($name, '.'));
            $random_name = \Illuminate\Support\Str::random(40) . $ext;
            $random_name = 'test-image-save-manualy' . $ext;

            $disk->put($random_name, $contents);

            $size = $disk->size($random_name);
            $mime = $disk->mimeType($random_name);

            if (!in_array($mime, ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'])) {
                $disk->delete($random_name);
                dump(' delete ' . $mime);
                continue;
            }
        } catch (\Throwable $th) {
            dump(' ERROR ' . $th->getMessage());
        }

        dump(' SAVE=====');
    }
    
    $d[] = explode(' ', $a);
    // ttps://static.westarcloud.com/5fb5ca47b2c1a7004b498371/images/20210330/dc4ca57ea5612c2308a3599214a01cb0.jpg

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
