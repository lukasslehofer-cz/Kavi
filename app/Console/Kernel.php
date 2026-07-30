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
        
        // Charge subscription payments (primary run at 1:00 AM Prague time)
        // Note: Using 01:00 instead of 00:00 to avoid timezone issues with UTC
        // (00:00 Prague = 23:00 UTC previous day, which could cause idempotency check issues)
        $schedule->command('subscriptions:charge-payments')
            ->dailyAt('01:00')
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

        // Update Packeta delivery status (every 4 hours)
        $schedule->command('packeta:update-delivery-status')
            ->everyFourHours()
            ->timezone('Europe/Prague')
            ->withoutOverlapping(10)
            ->appendOutputTo($cronLog);

        // Send review requests - each domain has its own Google profile. Daily 10:00.
        // Gated by REVIEWS_ENABLED so a deploy never blasts existing customers;
        // run `reviews:send --dry-run` first, then flip the flag.
        $schedule->command('reviews:send')
            ->dailyAt('10:00')
            ->timezone('Europe/Prague')
            ->when(fn () => config('reviews.enabled'))
            ->appendOutputTo($cronLog);

        // Refresh Google reviews cache (daily at 5:00 AM).
        // Google policy allows keeping the content for at most 30 calendar days,
        // so this also keeps the homepage section from going dark.
        $schedule->command('reviews:refresh-google')
            ->dailyAt('05:00')
            ->timezone('Europe/Prague')
            ->when(fn () => count(app(\App\Services\GoogleReviewsService::class)->configuredLocales()) > 0)
            ->appendOutputTo($cronLog);

        // Clean up expired login tokens (daily at 3:00 AM)
        $schedule->command('auth:cleanup-login-tokens')
            ->dailyAt('03:00')
            ->timezone('Europe/Prague')
            ->appendOutputTo($cronLog);

        // Send pause ending reminders 3 days before pause ends (daily at 9:00 AM)
        $schedule->command('subscriptions:send-pause-ending-reminders')
            ->dailyAt('09:00')
            ->timezone('Europe/Prague')
            ->appendOutputTo($cronLog);

        // Resume subscriptions whose pause ended (daily at 4:00 AM)
        $schedule->command('subscriptions:resume-paused')
            ->dailyAt('04:00')
            ->timezone('Europe/Prague')
            ->appendOutputTo($cronLog);

        // Send order payment reminders for pending orders (every hour)
        $schedule->command('orders:send-payment-reminders')
            ->hourly()
            ->timezone('Europe/Prague')
            ->appendOutputTo($cronLog);

        // Cancel unpaid orders older than 24 hours and restore stock (every hour)
        $schedule->command('orders:cancel-expired')
            ->hourly()
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




