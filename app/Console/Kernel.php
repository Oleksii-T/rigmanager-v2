<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // update exchange rates
        $schedule->command('rates:update')->daily();

        // send emails about posts found by mailers
        $schedule->command('mailers:send')->daily();

        // update ip-to-country database
        $schedule->command('location:update')->weekly();

        // generate sitemap
        $schedule->command('sitemap:generate')->everySixHours();

        // delete posts with status 'trashed'
        $schedule->command('posts:delete-trashed')->daily();

        // permanently delete deleted posts
        $schedule->command('posts:truncate-deleted')->daily();

        // create notifications on daily basis
        $schedule->command('notifications:daily-check')->daily();

        // create notifications on weekly basis
        $schedule->command('notifications:weekly-check')->weekly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
