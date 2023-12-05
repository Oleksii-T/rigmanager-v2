<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogFailLogin
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
     * @param  Failed $event
     * @return void
     * @throws \Exception
     */
    public function handle(Failed $event)
    {
        activity('users')
            ->event('fail-login')
            ->withProperties(infoForActivityLog())
            ->log('');
    }

}
