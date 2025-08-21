<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('stock:check-alerts')->dailyAt('08:00');
        $schedule->command('appointments:send-reminders')->dailyAt('08:00');
        $schedule->command('reminders:send-treatment-plan-appointment')->dailyAt('08:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}
