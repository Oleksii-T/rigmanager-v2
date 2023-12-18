<?php

namespace App\Providers;

use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        \Illuminate\Auth\Events\Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \Illuminate\Auth\Events\Verified::class => [
            \App\Listeners\SendWelcomeMail::class,
        ],
        \Illuminate\Mail\Events\MessageSent::class => [
            \App\Listeners\LogSentMessage::class,
        ],
        \Illuminate\Auth\Events\Login::class => [
            \App\Listeners\LogUserLogin::class
        ],
        \Illuminate\Auth\Events\Logout::class => [
            \App\Listeners\LogUserLogout::class
        ],
        \Illuminate\Auth\Events\Failed::class => [
            \App\Listeners\LogFailLogin::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
