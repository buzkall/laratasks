<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        //$schedule->command('inspire')->everyMinute();
        $schedule->command('laratasks:notify-about-old-tasks')->daily();

        //$schedule->command('model:prune')->daily();
        $schedule->command('queue:work --stop-when-empty')->everyMinute();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
