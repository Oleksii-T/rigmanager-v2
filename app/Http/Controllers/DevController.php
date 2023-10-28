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

    }

    private function showScrapedCashedFiles()
    {
        $files = [
            'lakepetro' => storage_path('scraper_jsons/lakepetro.json')
        ];
        $empty = [];
        $all = [];
        $standart = [];
        $nonStandart = [];
        $noDesc = [];
        $withDesc = [];
        $parcesDesc = [];

        foreach ($files as $author => $file) {
            $json = file_get_contents($file);
            $scrapedPosts = json_decode($json, true);
            foreach ($scrapedPosts as $url => $p) {
                // if ($url == 'https://www.lakepetro.com/Drill%20Collar') {
                //     dump('Tect sped strip_tags');
                //     $startTable = strpos($p['tab-1-html'], '<table');
                //     $endTable = strpos($p['tab-1-html'], '</table>');
                //     $p['tab-1-html'] = substr($p['tab-1-html'], 0, $startTable) . substr($p['tab-1-html'], $endTable+8);
                //     dump(strip_tags($p['tab-1-html']));
                //     dd($p);
                // }
                $tabs = $p['tabs'];
                if (!$tabs) {
                    $empty[$url] = $p;
                    continue;
                }

                $text = (count($tabs)) . ': ' . implode(' | ', $tabs) . ' - - - - - ' . $url;
                // $text = $url;
                // $text = (count($tabs)) . ':' . ($tabs[0]??'') . ' - - - - - ' . $url;
                // $text = $p;
                $text = $p['category'] . ' - - - - - ' . $url;
                $all[] = $text;

                if (!in_array('Description', $tabs) && !in_array('More Details', $tabs) && !in_array('Technical Specification', $tabs) && !in_array('Techanical Specification', $tabs)) {
                    $noDesc[] = $text;
                } else {
                    $withDesc[] = $text;
                }

                $tabs = $p['tabs'];
                if (in_array('Description', $tabs)) {
                    // we have standart description
                    $field = match (array_search('Description', $tabs)) {
                        0 => 'tab-1-html',
                        1 => 'tab-2-html',
                        2 => 'tab-3-html',
                    };
                    $description = $p[$field];
                    $parcesDesc[$url] = [
                        'add-data' => $p,
                        'parces-desciption' => $description,
                        'from' => $field
                    ];
                } else if (in_array('More Details', $tabs)) {
                    // we have standart description
                    $field = match (array_search('More Details', $tabs)) {
                        0 => 'tab-1-html',
                        1 => 'tab-2-html',
                        2 => 'tab-3-html',
                    };
                    $description = $p[$field];
                    $parcesDesc[$url] = [
                        'add-data' => $p,
                        'parces-desciption' => $description,
                        'from' => $field
                    ];
                }
            }

            $this->d($all, 'all');
            $this->d($standart, 'standart');
            $this->d($nonStandart, 'nonStandart');
            $this->d($empty, 'empty');
            $this->d($noDesc, 'NoDescription');
            $this->d($withDesc, 'withDescription');
            $this->d($parcesDesc, 'parcesDesc');
        }
    }

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
