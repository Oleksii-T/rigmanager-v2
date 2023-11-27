<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogUserLogout
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
     * @param  Logout $event
     * @return void
     * @throws \Exception
     */
    public function handle(Logout $event)
    {
        activity('users')
            ->event('logout')
            ->withProperties(infoForActivityLog())
            ->log('');
    }

}
