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
        $schedule->command('rates:update')->daily();
        $schedule->command('mailers:send')->daily();
        $schedule->command('location:update')->weekly();
        $schedule->command('sitemap:generate')->everySixHours();

        $schedule->command('posts:delete-trashed')->daily();
        $schedule->command('posts:truncate-deleted')->daily();

        $schedule->command('notifications:daily-check')->daily();
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
