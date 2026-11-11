<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $timezone = config('app.timezone', 'Africa/Lagos');

        // Daily Business & Performance Report at 10:00 PM (22:00)
        $schedule->command('report:daily')
            ->dailyAt('22:00')
            ->timezone($timezone);

        // Daily Morning Inventory Level Scan at 8:00 AM
        $schedule->command('inventory:check')
            ->dailyAt('08:00')
            ->timezone($timezone);
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
