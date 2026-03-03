<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\CleanupUnverifiedUsers::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('queue:work --stop-when-empty')->everyMinute()->withoutOverlapping();

        // Cleanup of unverified users every 2 days at 4 AM
        $schedule->command('users:cleanup-unverified 2')->cron('0 4 * * */2');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
