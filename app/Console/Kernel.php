<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ClearLogs;  // Add this import

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ClearLogs::class,  // Register the ClearLogs command
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Example: schedule the clear logs command at midnight
        $schedule->command('logs:clear')->daily()->at('00:00');

        // Other scheduled commands (e.g., backup)
        $schedule->command('backup:clean')->daily()->at('01:00');
        $schedule->command('backup:run')->daily()->at('01:30');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
