<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\SubscriptionShipmentService;
use Illuminate\Console\Command;

class ResumeExpiredPausedSubscriptions extends Command
{
    protected $signature = 'subscriptions:resume-paused';

    protected $description = 'Resume subscriptions whose pause period has ended';

    public function handle(SubscriptionShipmentService $shipmentService): int
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
                $result = $shipmentService->resumeSubscription($subscription);
                
                if (!$result['success']) {
                    $this->warn("  ⏭ Skipped: " . $result['message']);
                    $failedCount++;
                    continue;
                }
                
                // Get the calculated next shipment info
                $shipmentInfo = $shipmentService->getShipmentInfo($subscription);
                $nextShipment = $shipmentInfo->nextShipmentDate();
                $nextBilling = $subscription->fresh()->next_billing_date;
                
                $this->info("  ✓ Resumed → Next billing: {$nextBilling?->format('d.m.Y')}, Next shipment: {$nextShipment?->format('d.m.Y')}");
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


