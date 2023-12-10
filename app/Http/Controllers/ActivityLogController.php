<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function store(Request $request)
    {
        $subject = null;
        $logName = '';
        $event = '';
        $descripton = $request->type;

        if ($descripton == 'post-price-show-by-unsub') {
            $logName = 'users';
            $event = 'not-subscribed';
            $subject = Post::find($request->subject);
        }

        if ($descripton == 'post-price-show-by-guest') {
            $logName = 'users';
            $event = 'unauthenticated';
            $subject = Post::find($request->subject);
        }

        if ($descripton == 'post-author-show-by-unsub') {
            $logName = 'users';
            $event = 'not-subscribed';
            $subject = User::find($request->subject) ?? User::where('slug', $request->subject)->first();
        }

        if ($descripton == 'post-author-show-by-guest') {
            $logName = 'users';
            $event = 'unauthenticated';
            $subject = User::find($request->subject) ?? User::where('slug', $request->subject)->first();
        }

        if (!$logName) {
            return;
        }

        $a = activity($logName)
            ->event($event)
            ->withProperties(infoForActivityLog());

        if ($subject) {
            $a->on($subject);
        }

        $a->log($descripton);
    }
}
