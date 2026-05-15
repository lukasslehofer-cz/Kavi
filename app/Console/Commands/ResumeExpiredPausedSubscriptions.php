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
        $failureDetails = [];

        foreach ($paused as $subscription) {
            $subscriptionNumber = $subscription->subscription_number ?? '#'.$subscription->id;

            $this->line("Processing: {$subscriptionNumber}");

            try {
                $result = $shipmentService->resumeSubscription($subscription);

                if (! $result['success']) {
                    $this->warn('  ⏭ Skipped: '.$result['message']);
                    $failureDetails[] = [
                        'subscription_number' => $subscriptionNumber,
                        'paused_until_date' => $subscription->paused_until_date?->toDateString(),
                        'reason' => $result['message'],
                    ];
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
                $this->error('  ✗ Failed: '.$e->getMessage());

                \Log::error('Failed to resume subscription in cron', [
                    'subscription_id' => $subscription->id,
                    'subscription_number' => $subscriptionNumber,
                    'paused_until_date' => $subscription->paused_until_date?->toDateString(),
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                $failureDetails[] = [
                    'subscription_number' => $subscriptionNumber,
                    'paused_until_date' => $subscription->paused_until_date?->toDateString(),
                    'reason' => 'EXCEPTION: '.$e->getMessage(),
                ];
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

        if ($failedCount > 0) {
            $this->sendFailureAlert($failureDetails, $resumedCount, $failedCount, $paused->count());
        }

        return $failedCount > 0 ? Command::FAILURE : Command::SUCCESS;
    }

    /**
     * Send alert email to admin about resume failures.
     * Resume failures are usually fixable (e.g. coffee unavailable for the resumed
     * month) but they accumulate silently otherwise — the same sub may sit paused
     * for weeks until someone notices it never got billed.
     */
    private function sendFailureAlert(array $failureDetails, int $resumed, int $failed, int $total): void
    {
        try {
            $adminEmail = config('mail.from.address');

            if (! $adminEmail) {
                return;
            }

            $body = "Subscription Resume Alert\n\n"
                ."Failed: {$failed} / Total processed: {$total} (Resumed: {$resumed})\n"
                .'Date: '.now()->toDateTimeString()."\n\n"
                ."Failed subscriptions:\n"
                .collect($failureDetails)
                    ->map(fn ($d) => "- {$d['subscription_number']} (paused_until={$d['paused_until_date']}): {$d['reason']}")
                    ->join("\n")
                ."\n\nThese subscriptions remain in status='paused'. They will not be billed until they are successfully resumed or self-healed by the billing cron.";

            \Mail::raw($body, function ($message) use ($adminEmail) {
                $message->to($adminEmail)
                    ->subject('⚠️ Subscription Resume Failures - '.now()->toDateString());
            });

            $this->warn("📧 Alert email sent to {$adminEmail}");
        } catch (\Exception $e) {
            $this->error('Failed to send alert email: '.$e->getMessage());
        }
    }
}
