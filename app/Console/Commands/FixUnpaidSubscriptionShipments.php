<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionShipment;
use App\Services\SubscriptionShipmentService;
use Illuminate\Console\Command;

class FixUnpaidSubscriptionShipments extends Command
{
    protected $signature = 'subscriptions:fix-unpaid-shipments
                            {--dry-run : Show what would be done without making changes}
                            {--subscription= : Process only a specific subscription ID}
                            {--purge-failed : Also delete orphaned failed payment records (kept by default for audit)}';

    protected $description = 'One-time repair for subscriptions left inconsistent by the reverted dunning system: removes orphaned pending shipments and moves stuck unpaid subscriptions cleanly into the pause cycle';

    /**
     * Marker note written by the now-removed markShipmentUnpaid() dunning code
     * (commits 6a0eb30/5ef0150, reverted in 4c4df5c). Any pending shipment with
     * this note and no linked payment is an orphan the current model cannot use.
     */
    private const ORPHAN_NOTE = 'Neuhrazená platba předplatného';

    public function handle(SubscriptionShipmentService $shipmentService): int
    {
        $isDryRun = (bool) $this->option('dry-run');
        $specificId = $this->option('subscription');
        $purgeFailed = (bool) $this->option('purge-failed');

        if ($isDryRun) {
            $this->warn('DRY RUN MODE - no changes will be made');
        }

        // Affected: explicitly unpaid, or carrying an orphaned dunning shipment.
        $query = Subscription::query();
        if ($specificId) {
            $query->where('id', $specificId);
        } else {
            $query->where(function ($q) {
                $q->where('status', 'unpaid')
                    ->orWhereHas('shipments', function ($s) {
                        $s->where('notes', self::ORPHAN_NOTE)
                            ->whereNull('subscription_payment_id');
                    });
            });
        }

        $subscriptions = $query->with('user')->get();

        if ($subscriptions->isEmpty()) {
            $this->info('No affected subscriptions found.');

            return self::SUCCESS;
        }

        $this->info(($isDryRun ? '[DRY RUN] ' : '')."Found {$subscriptions->count()} subscription(s) to process.");

        $results = [];

        foreach ($subscriptions as $subscription) {
            $number = $subscription->subscription_number ?? ('#'.$subscription->id);
            $this->line('');
            $this->info("── {$number} (id {$subscription->id})");

            // 1. Diagnostics — print the full state so it is auditable before writing.
            $this->line("   status={$subscription->status}".
                ' next_billing='.($subscription->next_billing_date?->toDateString() ?? 'null').
                " failure_count={$subscription->payment_failure_count}".
                " consecutive_unpaid={$subscription->consecutive_unpaid_shipments}");
            $this->line('   last_failure_at='.($subscription->last_payment_failure_at?->toDateString() ?? 'null').
                ' pending_invoice='.($subscription->pending_invoice_id ?? 'null'));
            foreach ($subscription->shipments()->orderBy('shipment_date')->get() as $sh) {
                $this->line("     · shipment #{$sh->id}  {$sh->shipment_date->toDateString()}  {$sh->status}".
                    ($sh->subscription_payment_id ? "  payment #{$sh->subscription_payment_id}" : '  (no payment)').
                    ($sh->notes ? "  \"{$sh->notes}\"" : ''));
            }
            foreach ($subscription->payments()->orderBy('id')->get() as $p) {
                $this->line("     \$ payment #{$p->id}  {$p->status}  ".
                    number_format((float) $p->amount, 2).' '.strtoupper($p->currency ?? ''));
            }

            // Orphaned future shipments left by the reverted dunning code.
            $orphanShipments = $subscription->shipments()
                ->where('notes', self::ORPHAN_NOTE)
                ->whereNull('subscription_payment_id')
                ->whereNotIn('status', ['sent', 'delivered'])
                ->whereDate('shipment_date', '>=', today())
                ->get();

            if ($subscription->status !== 'unpaid' && $orphanShipments->isEmpty()) {
                $this->comment('   → nothing to fix (not unpaid, no orphaned shipment)');
                $results[] = [$number, 'skipped', 'nothing to fix'];

                continue;
            }

            // 2. Remove orphaned shipments — an unpaid cycle has no shipment in the
            //    current model, so these phantom pending rows keep the subscription
            //    wrongly visible in the shipping run.
            if ($orphanShipments->isNotEmpty()) {
                foreach ($orphanShipments as $sh) {
                    $this->comment('   → '.($isDryRun ? '[would] ' : '').
                        "delete orphaned shipment #{$sh->id} ({$sh->shipment_date->toDateString()}, {$sh->status})");
                }
                if (! $isDryRun) {
                    SubscriptionShipment::whereIn('id', $orphanShipments->pluck('id'))->delete();
                }
            }

            // 2b. Optional: purge dead 'failed' payment rows (unused by current code).
            if ($purgeFailed) {
                $failed = $subscription->payments()->where('status', 'failed')->get();
                foreach ($failed as $p) {
                    $this->comment('   → '.($isDryRun ? '[would] ' : '')."delete failed payment #{$p->id}");
                }
                if (! $isDryRun && $failed->isNotEmpty()) {
                    SubscriptionPayment::whereIn('id', $failed->pluck('id'))->delete();
                }
            }

            // 3. Move a stuck unpaid subscription cleanly into the pause cycle,
            //    reproducing the payment_failure_count >= 3 branch of
            //    StripeService::handleSubscriptionPaymentFailure().
            if ($subscription->status === 'unpaid') {
                $newConsecutive = max((int) $subscription->consecutive_unpaid_shipments, 1);
                $this->comment('   → '.($isDryRun ? '[would] ' : '').
                    "pause 1 cycle (payment_failure_skip); consecutive_unpaid {$subscription->consecutive_unpaid_shipments} → {$newConsecutive}, failure_count → 0");

                if (! $isDryRun) {
                    $subscription->update([
                        'consecutive_unpaid_shipments' => $newConsecutive,
                        'payment_failure_count' => 0,
                    ]);
                    $shipmentService->pauseSubscription($subscription, 1, 'payment_failure_skip');
                    $subscription->refresh();
                    $this->info("   → paused, status={$subscription->status}, paused_until=".
                        ($subscription->paused_until_date?->toDateString() ?? 'null'));
                }

                $results[] = [$number, $isDryRun ? 'dry-run' : 'paused', "consecutive_unpaid={$newConsecutive}"];
            } else {
                $results[] = [$number, $isDryRun ? 'dry-run' : 'cleaned', 'orphan shipment removed'];
            }
        }

        $this->line('');
        $this->info('=== Results ===');
        $this->table(['Subscription', 'Action', 'Detail'], $results);

        return self::SUCCESS;
    }
}
