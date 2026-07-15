<?php

namespace App\Console\Commands;

use App\Models\ShipmentSchedule;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Services\SubscriptionShipmentService;
use Illuminate\Console\Command;

class ReconcileSubscriptionBilling extends Command
{
    /**
     * Backfill durable failure state for subscriptions whose payments failed under
     * the old logic (which pushed them to 'paused' and marked the month 'skipped',
     * leaving no per-period record and hiding the unpaid shipment from admin).
     *
     * Pass an id to reconcile a single subscription, otherwise every subscription
     * with an unresolved payment problem is processed.
     */
    protected $signature = 'subscriptions:reconcile-billing {id? : Subscription id to reconcile} {--dry-run : Report changes without writing}';

    protected $description = 'Backfill failed payment records and unpaid shipments for subscriptions with hidden payment problems';

    public function handle(SubscriptionShipmentService $shipmentService): int
    {
        $isDryRun = (bool) $this->option('dry-run');
        $id = $this->argument('id');

        $query = Subscription::query();
        if ($id) {
            $query->where('id', $id);
        } else {
            // Subscriptions that still owe money: explicit unpaid, an outstanding
            // pending invoice, a running reminder count, or a payment-failure pause.
            $query->where(function ($q) {
                $q->where('status', 'unpaid')
                    ->orWhereNotNull('pending_invoice_id')
                    ->orWhere('payment_failure_count', '>', 0)
                    ->orWhere('consecutive_unpaid_shipments', '>', 0)
                    ->orWhere(function ($qq) {
                        $qq->where('status', 'paused')
                            ->where('pause_reason', 'payment_failure_skip');
                    });
            });
        }

        $subscriptions = $query->get();

        if ($subscriptions->isEmpty()) {
            $this->info('No subscriptions to reconcile.');

            return self::SUCCESS;
        }

        $this->info(($isDryRun ? '[DRY RUN] ' : '')."Reconciling {$subscriptions->count()} subscription(s)...");

        $changed = 0;

        foreach ($subscriptions as $subscription) {
            $frequencyMonths = max(1, (int) ($subscription->frequency_months ?? 1));

            // Diagnostics — print what we actually see, so the state is auditable.
            $this->line('');
            $this->line("── {$subscription->subscription_number} (id {$subscription->id})");
            $this->line("   status={$subscription->status}".
                ' next_billing='.($subscription->next_billing_date?->toDateString() ?? 'null').
                " failure_count={$subscription->payment_failure_count}".
                " consecutive_unpaid={$subscription->consecutive_unpaid_shipments}");
            $this->line('   last_failure_at='.($subscription->last_payment_failure_at?->toDateString() ?? 'null').
                ' pending_invoice='.($subscription->pending_invoice_id ?? 'null'));
            foreach ($subscription->shipments()->orderBy('shipment_date')->get() as $sh) {
                $this->line("     · {$sh->shipment_date->toDateString()}  {$sh->status}".
                    ($sh->subscription_payment_id ? "  (payment #{$sh->subscription_payment_id})" : ''));
            }

            // Pick the period to reconcile:
            //  1. If next_billing_date is overdue, THAT is the period currently
            //     failing — reconcile it (the active problem).
            //  2. Otherwise the subscription has already advanced to a future
            //     period, so the failure is historical — anchor on when it
            //     actually failed (last_payment_failure_at), never the future
            //     next_billing_date.
            $nbd = $subscription->next_billing_date?->copy();
            if ($nbd && $nbd->lte(today())) {
                $anchor = $nbd;
                $anchorIsHistorical = false;
            } else {
                $anchor = $subscription->last_payment_failure_at?->copy();
                $anchorIsHistorical = true;
            }

            if (! $anchor) {
                $this->line('   → no overdue or recorded failure to reconcile (skipping)');
                continue;
            }

            // period_end = billing date of the anchor month.
            $schedule = ShipmentSchedule::getForMonth($anchor->year, $anchor->month);
            $periodEnd = $schedule?->billing_date?->copy() ?? $anchor->copy()->day(15);

            if ($periodEnd->gt(today())) {
                $this->line("   → computed period {$periodEnd->toDateString()} is in the future — subscription already moved past the failure (skipping)");
                continue;
            }

            $periodStart = $periodEnd->copy()->subMonths($frequencyMonths);

            // Already covered by a paid or failed record for this period? Nothing to do.
            $existing = SubscriptionPayment::where('subscription_id', $subscription->id)
                ->whereDate('period_end', $periodEnd->toDateString())
                ->whereIn('status', ['paid', 'failed'])
                ->first();

            if ($existing && $existing->status === 'paid') {
                $this->line("   → period {$periodEnd->toDateString()} already paid (skipping)");
                continue;
            }

            $reason = $subscription->last_payment_failure_reason ?? 'Reconciled unpaid period';

            // The reconciled period is itself an unpaid shipment, so the
            // consecutive-unpaid counter must be at least 1 (this backfills the
            // already-abandoned period the old logic hid). Live charge failures
            // then increment it further for subsequent unpaid periods.
            $newConsecutive = max((int) $subscription->consecutive_unpaid_shipments, 1);

            // For a historical failure the subscription already advanced to a
            // future period, so the leftover per-shipment reminder count is stale
            // (it was cumulative under the old logic). Reset it so the next period
            // gets a clean 3-reminder cycle. For an overdue period the count is
            // still meaningful, so keep it.
            $newFailureCount = $anchorIsHistorical ? 0 : (int) $subscription->payment_failure_count;

            $this->line("   → period {$periodEnd->toDateString()}: ".
                ($existing ? 'failed record exists, ensuring shipment visible' : 'creating failed record + unpaid shipment').
                "; consecutive_unpaid {$subscription->consecutive_unpaid_shipments} → {$newConsecutive}".
                ($anchorIsHistorical ? "; failure_count {$subscription->payment_failure_count} → {$newFailureCount} (stale reset)" : ''));

            if ($isDryRun) {
                $changed++;
                continue;
            }

            $payment = SubscriptionPayment::firstOrNew([
                'subscription_id' => $subscription->id,
                'status' => 'failed',
                'period_end' => $periodEnd->toDateString(),
            ]);
            $payment->fill([
                'amount' => $payment->amount ?? ($subscription->configured_price ?? $subscription->plan?->price ?? 0),
                'currency' => strtolower($subscription->currency ?? 'CZK'),
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
                'failure_reason' => $reason,
                'attempts' => max(1, (int) ($payment->attempts ?? 0)),
                'last_attempt_at' => $subscription->last_payment_failure_at ?? now(),
            ]);
            $payment->save();

            // Surface the shipment for that period as unpaid (converts an existing
            // skipped/pending row or creates one), linked to the failed record.
            $shipmentService->markShipmentUnpaid($payment->refresh(), $subscription);

            // Count this already-abandoned period toward the consecutive-unpaid
            // total so it is visible as a payment problem, and clear any stale
            // per-shipment reminder count.
            $subscription->update([
                'consecutive_unpaid_shipments' => $newConsecutive,
                'payment_failure_count' => $newFailureCount,
            ]);

            $changed++;
        }

        $this->line('');
        $this->info(($isDryRun ? '[DRY RUN] ' : '')."Done. {$changed} subscription(s) ".($isDryRun ? 'would be ' : '')."reconciled.");

        return self::SUCCESS;
    }
}
