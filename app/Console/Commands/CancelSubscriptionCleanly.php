<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\SubscriptionShipmentService;
use Illuminate\Console\Command;

class CancelSubscriptionCleanly extends Command
{
    protected $signature = 'subscriptions:cancel-cleanly
                            {id : Subscription ID to cancel}
                            {--dry-run : Show what would be done without making changes}';

    protected $description = 'Cleanly cancel a subscription and remove it from the shipping run (finalizes a botched manual cancel: sets ends_at, cancels unpaid pending shipments, clears leftover unpaid/invoice fields)';

    public function handle(SubscriptionShipmentService $shipmentService): int
    {
        $isDryRun = (bool) $this->option('dry-run');

        if ($isDryRun) {
            $this->warn('DRY RUN MODE - no changes will be made');
        }

        $subscription = Subscription::find($this->argument('id'));

        if (! $subscription) {
            $this->error("Subscription #{$this->argument('id')} not found.");

            return self::FAILURE;
        }

        $number = $subscription->subscription_number ?? ('#'.$subscription->id);
        $customer = $subscription->shipping_address['name'] ?? $subscription->user?->email ?? 'unknown';

        // Diagnostics — full state, so it is auditable before writing.
        $this->info("── {$number} (id {$subscription->id}) — {$customer}");
        $this->line("   status={$subscription->status}".
            ' ends_at='.($subscription->ends_at?->toDateString() ?? 'null').
            ' next_billing='.($subscription->next_billing_date?->toDateString() ?? 'null').
            ' last_shipment='.($subscription->last_shipment_date?->toDateString() ?? 'null'));
        $this->line("   consecutive_unpaid={$subscription->consecutive_unpaid_shipments}".
            " failure_count={$subscription->payment_failure_count}".
            ' pending_invoice='.($subscription->pending_invoice_id ?? 'null'));
        foreach ($subscription->shipments()->orderBy('shipment_date')->get() as $sh) {
            $this->line("     · shipment #{$sh->id}  {$sh->shipment_date->toDateString()}  {$sh->status}".
                ($sh->subscription_payment_id ? "  payment #{$sh->subscription_payment_id}" : '  (no payment)'));
        }

        // Which pending (unpaid) shipments would be cancelled by cancelSubscription().
        $unpaidPending = $subscription->shipments()
            ->where('status', 'pending')
            ->whereDoesntHave('payment', fn ($q) => $q->where('status', 'paid'))
            ->get();

        $this->comment('   → '.($isDryRun ? '[would] ' : '').'cancel subscription (status=cancelled, set ends_at)'.
            ($unpaidPending->isNotEmpty() ? '; cancel '.$unpaidPending->count().' unpaid pending shipment(s)' : ''));
        $this->comment('   → '.($isDryRun ? '[would] ' : '').'clear leftover fields: pending_invoice_id/amount=null, consecutive_unpaid_shipments=0, payment_failure_count=0');

        if ($isDryRun) {
            $this->info('   (dry-run — no changes made)');

            return self::SUCCESS;
        }

        // Canonical cancellation: cancels unpaid pending shipments and sets ends_at
        // to the last paid shipment date (or now if none). Removes it from the run.
        $shipmentService->cancelSubscription($subscription);

        // Finalize the botched manual cancel: clear the unpaid/dunning leftovers so
        // the subscription no longer looks like it owes money.
        $subscription->update([
            'pending_invoice_id' => null,
            'pending_invoice_amount' => null,
            'consecutive_unpaid_shipments' => 0,
            'payment_failure_count' => 0,
            'cancellation_reason' => $subscription->cancellation_reason ?? 'admin_manual',
        ]);

        $subscription->refresh();
        $this->info("   → done. status={$subscription->status}, ends_at=".
            ($subscription->ends_at?->toDateString() ?? 'null').
            ", pending_invoice=".($subscription->pending_invoice_id ?? 'null').
            ", consecutive_unpaid={$subscription->consecutive_unpaid_shipments}");

        return self::SUCCESS;
    }
}
