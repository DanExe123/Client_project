<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\GenerateDailyCashFlow;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Run daily at midnight or any time you prefer
        $schedule->command('app:generate-daily-cash-flow')->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}
