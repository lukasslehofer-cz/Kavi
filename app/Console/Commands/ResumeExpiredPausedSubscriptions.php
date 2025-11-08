<?php

namespace App\Console\Commands;

use App\Helpers\SubscriptionHelper;
use App\Models\ShipmentSchedule;
use App\Models\Subscription;
use Illuminate\Console\Command;

class ResumeExpiredPausedSubscriptions extends Command
{
    protected $signature = 'subscriptions:resume-paused';

    protected $description = 'Resume subscriptions whose pause period has ended';

    public function handle(): int
    {
        $this->info('🔍 Looking for subscriptions with expired pause period...');
        
        $paused = Subscription::where('status', 'paused')
            ->whereNotNull('paused_until_date')
            ->whereDate('paused_until_date', '<=', now()->toDateString())
            ->with('user')
            ->get();

        if ($paused->isEmpty()) {
            $this->info('✓ No subscriptions to resume.');
            return Command::SUCCESS;
        }

        $this->info("📦 Found {$paused->count()} subscription(s) to resume.");
        $this->newLine();

        $resumedCount = 0;
        $failedCount = 0;

        foreach ($paused as $subscription) {
            $subscriptionNumber = $subscription->subscription_number ?? '#' . $subscription->id;
            
            $this->line("Processing: {$subscriptionNumber}");
            
            try {
                // Calculate first shipment after pause
                $firstShipmentAfterPause = SubscriptionHelper::getNextShipmentAfterDate(
                    $subscription,
                    $subscription->paused_until_date
                );
                
                // Get billing date for this shipment month
                $schedule = ShipmentSchedule::getForMonth(
                    $firstShipmentAfterPause->year,
                    $firstShipmentAfterPause->month
                );
                
                $nextBillingDate = $schedule 
                    ? $schedule->billing_date->copy()->startOfDay()
                    : $firstShipmentAfterPause->copy()->day(15)->startOfDay();
                
                // Resume subscription (clears pause data, sets status to active)
                $subscription->resume();
                
                // Update next_billing_date
                $subscription->update([
                    'next_billing_date' => $nextBillingDate,
                ]);
                
                $this->info("  ✓ Resumed → Next billing: {$nextBillingDate->format('d.m.Y')}, Next shipment: {$firstShipmentAfterPause->format('d.m.Y')}");
                $resumedCount++;
                
            } catch (\Exception $e) {
                $this->error("  ✗ Failed: " . $e->getMessage());
                
                \Log::error('Failed to resume subscription in cron', [
                    'subscription_id' => $subscription->id,
                    'subscription_number' => $subscriptionNumber,
                    'paused_until_date' => $subscription->paused_until_date?->toDateString(),
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                
                $failedCount++;
            }
        }

        $this->newLine();
        $this->info('📊 Summary:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Resumed', $resumedCount],
                ['Failed', $failedCount],
                ['Total', $paused->count()],
            ]
        );

        return $failedCount > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}


