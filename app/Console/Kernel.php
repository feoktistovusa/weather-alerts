<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // Schedule the weather alert command to run hourly
        $schedule->command('weather:send-alerts')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        // Load commands from the app/Console/Commands directory
        $this->load(__DIR__.'/Commands');

        // Include additional console routes
        require base_path('routes/console.php');
    }
}
