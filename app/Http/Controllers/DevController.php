<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Post;

/**
 * Controller for developers use only
 * To track execution time use $this-t()
 * To dump values use $this->d
 * Set up _authorize() to you needs
 * See example() as an example of usage
 *
 */
class DevController extends Controller
{
    private $d = [];
    private $timings = [];
    private $queryLog = false;
    private $user;
    private $fullStart;
    private $start;

    public function __construct()
    {
        $this->user = auth()->user();
        $this->fullStart = $this->start = microtime(true);
    }

    // base method to navigate and manage testing logic
    public function action($slug)
    {
        if (!method_exists($this, $slug)) {
            dd('ERROR: action not found'); //? 404 instead
        }

        if ($slug != 'public') {
            $this->_authorize();
        }

        $result = $this->{$slug}();

        // dump query log if is set
        if ($this->queryLog) {
            dump('QUERY LOG', \DB::getQueryLog());
        }

        // dump $timings only if we set some
        if ($this->timings) {
            $this->timings['timings-finish'] = microtime(true) - $this->fullStart;
            dump('TIMINGS', $this->timings);
        }

        // dump $d only if we set some
        if ($this->d) {
            dump('RESULT DUMP', $this->d);
        }

        return $result;
    }

    private function test()
    {
        $url = 'https://dummyimage.com/600x400/ff8d12/fff';
        // $url = 'http://localhost/storage/attachments/images/compressed/OaVv8zKWCnSjAv6uKuUQeGD5M5feKbqvsjopAh9o-300-300.webp';
        $url = 'https://www.gstatic.com/webp/gallery/4.sm.webp';
        $url = 'https://dev.w3.org/SVG/tools/svgweb/samples/svg-files/AJ_Digital_Camera.svg';
        try {
            $mime = mime_content_type($url);
        } catch (\Throwable $th) {
            $mime = $th->getMessage();
        }
        $this->d($mime, 'mime php');

        $headers = get_headers($url, 1);
        $this->d($headers, 'headers');

        //.jpg, .jpeg, .png, .bmp, .gif, .svg, .webp
        $mime = exif_imagetype($url);
        $possibleMimes = [];
        $mimes  = [
            IMAGETYPE_GIF => "image/gif",
            IMAGETYPE_JPEG => "image/jpg",
            IMAGETYPE_PNG => "image/png",
            IMAGETYPE_WEBP => "image/webp",
            IMAGETYPE_BMP => "image/bmp",
            // IMAGETYPE_SWF => "image/swf",
            // IMAGETYPE_PSD => "image/psd",
            // IMAGETYPE_JPC => "image/jpc",
            // IMAGETYPE_JP2 => "image/jp2",
            // IMAGETYPE_JPX => "image/jpx",
            // IMAGETYPE_JB2 => "image/jb2",
            // IMAGETYPE_SWC => "image/swc",
            // IMAGETYPE_IFF => "image/iff",
            // IMAGETYPE_WBMP => "image/wbmp",
            // IMAGETYPE_XBM => "image/xbm",
            // IMAGETYPE_ICO => "image/ico",
        ];
        $this->d($mimes, 'mimes');
        $this->d($mime, 'exif_imagetype');

        // $fileContents = file_get_contents($url);
        // if ($fileContents !== false) {
        //     $finfo = new finfo(FILEINFO_MIME);
        //     $mimeType = $finfo->buffer($fileContents);
        //     $this->d($mimeType, 'mime via finfo');
        // } else {
        //     $this->d('EMPTY', 'mime via finfo');
        // }

    }

    // dummy method
    private function example()
    {
        $this->enableQueryLog();

        $this->d('creating 1000 els array and collection...');

        $array = range(-500, 500);
        shuffle($array);

        $colleciton = collect($array);

        $this->d('starting sorting...');
        $this->setFullStart();

        sort($array);

        $this->t('array_sort');

        $colleciton->sort();

        $this->t('collection_sort');
        $this->d('sorting done.');

        return $array;
    }

    // dummy public method.
    // can be used to showcase some functionality to external user.
    private function public()
    {
        return "Hello from devs!";
    }

    // test emails
    private function emails()
    {
        $t = request()->type;
        $email = request()->email;

        if ($t == 'password-reset') {
            $url = url('');
            $mail = new \App\Mail\PasswordReset($url);
        }
        if ($t == 'verify') {
            $url = url('');
            $mail = new \App\Mail\TmpMail($url);
        };
        if ($t == 'mailer') {
            $posts = Post::inRandomOrder()->limit(4)->get();
            $mailer = \App\Models\Mailer::first();
            $mail = new \App\Mail\MailerPostFound($mailer, $posts);
        };
        if ($t == 'tba-non-reg') {
            $user = User::find(12);
            $post = Post::find(371);
            $mail = new \App\Mail\PostTbaForNonReg($post, $user);
        };

        // other emails test here...

        if (!$mail) {
            dd('ERROR: mail not found');
        }

        if ($email) {
            Mail::to($email)->send($mail);
        }

        return $mail;
    }

    // login to user by ID (login to admin by default)
    private function login()
    {
        $user = request()->user;

        if (!$user) {
            $user = User::whereIn('email', ['admin@mail.com', 'admin@admin.com'])->first();
            if (!$user) {
                // todo add belongsTo relation check
                $user = User::whereHas('roles', function ($q) {
                    $q->where('name', 'admin');
                })->first();
            }
            if (!$user) {
                dump('Admin user not found. Please provide user_id manualy');
                dd(User::all());
            }
        } else {
            $user = User::find($user);
        }

        auth()->login($user);

        return redirect('/');
    }

    // get phpinfo
    private function phpinfo()
    {
        phpinfo();
    }

    // get client IP
    private function ip()
    {
        $ip = request()->ip();

        return "Your ip is: $ip";
    }

    // helper to store execution time
    private function t($key=null)
    {
        $t = microtime(true) - $this->start;

        if ($key) {
            $this->timings[$key] = $t;
        } else {
            $this->timings[] = $t;
        }

        $this->start = microtime(true);
    }

    private function d($value, $key=null)
    {
        if ($key) {
            $this->d[$key] = $value;
        } else {
            $this->d[] = $value;
        }
    }

    // reset start time
    private function setFullStart($key=null)
    {
        $this->fullStart = $this->start = microtime(true);
    }

    // authorize access to methods
    private function _authorize()
    {
        $ok = true || isdev() || $this->user?->isAdmin();

        abort_if(!$ok, 403);
    }

    // enable query log
    private function enableQueryLog()
    {
        $this->queryLog = true;
        \DB::connection()->enableQueryLog();
    }
}
