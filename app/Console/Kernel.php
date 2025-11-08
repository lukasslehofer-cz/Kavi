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
        $cronLog = storage_path('logs/cron.log');

        // ============================================
        // SUBSCRIPTION BILLING (Custom Billing System)
        // ============================================
        
        // Charge subscription payments (primary run at midnight)
        $schedule->command('subscriptions:charge-payments')
            ->dailyAt('00:00')
            ->timezone('Europe/Prague')
            ->withoutOverlapping(10) // Prevent concurrent runs
            ->appendOutputTo($cronLog);

        // Backup billing run (in case midnight run failed)
        $schedule->command('subscriptions:charge-payments')
            ->dailyAt('06:00')
            ->timezone('Europe/Prague')
            ->withoutOverlapping(10)
            ->when(function () {
                // Only run if midnight run didn't complete successfully
                $lastRun = \Cache::get('subscription_billing_cron_last_run');
                return !$lastRun || $lastRun->isYesterday();
            })
            ->appendOutputTo($cronLog);

        // Monitor billing system health (hourly during business hours)
        $schedule->command('subscriptions:monitor-billing')
            ->hourly()
            ->between('08:00', '20:00')
            ->timezone('Europe/Prague')
            ->appendOutputTo($cronLog);

        // Send payment reminders 3 days before billing date (daily at 9:00 AM)
        $schedule->command('subscriptions:send-payment-reminders')
            ->dailyAt('09:00')
            ->timezone('Europe/Prague')
            ->appendOutputTo($cronLog);

        // ============================================
        // OTHER SCHEDULED TASKS
        // ============================================

        // Send Trustpilot review requests (daily at 10:00 AM)
        $schedule->command('reviews:send')
            ->dailyAt('10:00')
            ->timezone('Europe/Prague')
            ->appendOutputTo($cronLog);

        // Clean up expired login tokens (daily at 3:00 AM)
        $schedule->command('auth:cleanup-login-tokens')
            ->dailyAt('03:00')
            ->timezone('Europe/Prague')
            ->appendOutputTo($cronLog);

        // Resume subscriptions whose pause ended (daily at 4:00 AM)
        $schedule->command('subscriptions:resume-paused')
            ->dailyAt('04:00')
            ->timezone('Europe/Prague')
            ->appendOutputTo($cronLog);

        // Update stock reservations on 16th of each month (at midnight)
        $schedule->command('stock:update-reservations')
            ->monthlyOn(16, '00:00')
            ->timezone('Europe/Prague')
            ->appendOutputTo($cronLog);
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




