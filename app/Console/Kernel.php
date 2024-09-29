<?php

namespace App\Console;

use Illuminate\Support\Facades\Schedule;

class Kernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('weather:send-alerts')->hourly();
    }
}
