<?php

namespace App\Console\Commands;

use App\Models\SubscriptionPayment;
use App\Models\SubscriptionShipment;
use App\Services\SubscriptionShipmentService;
use Illuminate\Console\Command;

/**
 * Backfill: naváže zaplacené platby, které nemají odpovídající řádek zásilky
 * (subscription_shipments.subscription_payment_id), na jejich zásilku.
 *
 * KROK 6/9 revize: používá centrální SubscriptionShipmentService::linkPaymentToShipment
 * (schedule-keyed, idempotentní). Obsolete migrace starých Packeta dat z `subscriptions`
 * byla odstraněna – tracking žije na ledgeru a duplicitní sloupce na subscription zanikly.
 */
class LinkShipmentPayments extends Command
{
    protected $signature = 'subscriptions:link-shipment-payments {--dry-run : Jen náhled, nic nezapisuje}';

    protected $description = 'Naváže osiřelé zaplacené platby na jejich zásilku (ledger)';

    public function handle(SubscriptionShipmentService $shipmentService): int
    {
        $dryRun = (bool) $this->option('dry-run');

        // Paid platby, na které neukazuje žádná zásilka.
        $referenced = SubscriptionShipment::whereNotNull('subscription_payment_id')
            ->pluck('subscription_payment_id')
            ->unique();

        $orphans = SubscriptionPayment::with('subscription')
            ->where('status', 'paid')
            ->whereNotIn('id', $referenced)
            ->get();

        if ($orphans->isEmpty()) {
            $this->info('✓ Žádné osiřelé zaplacené platby.');

            return 0;
        }

        $this->info(($dryRun ? '🧪 DRY-RUN – ' : '')."Nalezeno {$orphans->count()} osiřelých plateb.");

        $linked = 0;
        $skipped = 0;

        foreach ($orphans as $payment) {
            $subNumber = $payment->subscription?->subscription_number ?? '#'.$payment->subscription_id;

            if (! $payment->subscription) {
                $this->warn("  ✗ Platba #{$payment->id}: chybí předplatné, přeskočeno");
                $skipped++;

                continue;
            }

            if ($dryRun) {
                $expected = $payment->expected_shipment_date?->toDateString() ?? '—';
                $this->line("  [would] Platba #{$payment->id} ({$subNumber}) → zásilka pro {$expected}");
                $linked++;

                continue;
            }

            $shipment = $shipmentService->linkPaymentToShipment($payment, $payment->subscription);

            if ($shipment && $shipment->subscription_payment_id === $payment->id) {
                $this->line("  ✓ Platba #{$payment->id} ({$subNumber}) → zásilka #{$shipment->id} ({$shipment->shipment_date->format('Y-m-d')})");
                $linked++;
            } elseif ($shipment) {
                // Měsíc už obsazen jinou platbou – historická anomálie k ruční revizi.
                $this->warn("  ✗ Platba #{$payment->id} ({$subNumber}): zásilka #{$shipment->id} už patří platbě #{$shipment->subscription_payment_id} – ponechávám k ruční revizi");
                $skipped++;
            } else {
                $this->warn("  ✗ Platba #{$payment->id} ({$subNumber}): nelze určit zásilku");
                $skipped++;
            }
        }

        $this->newLine();
        $this->info(($dryRun ? 'Navázalo by se' : 'Navázáno')." plateb: {$linked}, přeskočeno: {$skipped}.");

        return 0;
    }
}
