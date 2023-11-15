<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Activitylog\Contracts\Activity;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogUserLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login $event
     * @return void
     * @throws \Exception
     */
    public function handle(Login $event)
    {
        activity('users')
            ->event('login')
            ->tap(function(Activity $activity) {
                $activity->properties = [
                    'ip' => request()->ip(),
                    'agent' => request()->header('User-Agent')
                ];
            })
            ->log('');
    }

}
