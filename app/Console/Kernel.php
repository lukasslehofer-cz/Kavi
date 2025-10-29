<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Send payment reminders 3 days before billing date (daily at 9:00 AM)
        $schedule->command('subscriptions:send-payment-reminders')
            ->dailyAt('09:00')
            ->timezone('Europe/Prague');

        // Clean up expired login tokens (daily at 3:00 AM)
        $schedule->command('auth:cleanup-login-tokens')
            ->dailyAt('03:00')
            ->timezone('Europe/Prague');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}




