<?php

declare(strict_types=1);

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

final class Karnel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('update:currency-rates')->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
