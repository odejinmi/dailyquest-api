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
        // Reset daily challenges at midnight
        $schedule->command('dailyquest:reset-challenges')->dailyAt('00:00');

        // Check and update streaks
        $schedule->command('dailyquest:update-streaks')->dailyAt('00:05');

        // Clean up expired rewards
        $schedule->command('dailyquest:cleanup-rewards')->daily();
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
