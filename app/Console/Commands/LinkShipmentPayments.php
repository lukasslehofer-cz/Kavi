<?php

namespace App\Console\Commands;

use App\Models\ShipmentSchedule;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionShipment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Backfill osiřelých zaplacených plateb – BEZPEČNÁ link-only varianta.
 *
 * Naváže zaplacenou platbu (subscription_shipments.subscription_payment_id) na JIŽ EXISTUJÍCÍ
 * řádek zásilky jejího měsíce, a to POUZE když je stav pending/sent/delivered. Záměrně:
 *   - NEZAKLÁDÁ nové řádky (na rozdíl od live linkPaymentToShipment / getOrCreateForSchedule),
 *   - NEMĚNÍ stav zásilky – tedy NEOŽIVUJE skipped/cancelled měsíce.
 * Vše ostatní (skipped/cancelled box, box už patřící jiné platbě, chybějící box, platba bez data)
 * se přeskočí a vypíše k RUČNÍ revizi.
 *
 * Cílový měsíc platby = měsíc period_end (jinak fallback na paid_at, stejně jako
 * SubscriptionPayment::expected_shipment_date). Bezpečné default = dry-run; zápis až s --apply.
 */
class LinkShipmentPayments extends Command
{
    protected $signature = 'subscriptions:link-shipment-payments {--apply : Skutečně zapsat vazby (bez tohoto přepínače jen náhled)}';

    protected $description = 'Bezpečně naváže osiřelé zaplacené platby na existující zásilku (bez oživení, bez nových řádků)';

    public function handle(): int
    {
        $apply = (bool) $this->option('apply');

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

        $this->info(($apply ? '⚙️  APPLY – ' : '🧪 DRY-RUN – ')."Nalezeno {$orphans->count()} osiřelých plateb.");
        $this->newLine();

        $toLink = [];      // [payment_id => shipment]
        $manual = [];      // řádky k ruční revizi

        foreach ($orphans as $payment) {
            $subNumber = $payment->subscription?->subscription_number ?? '#'.$payment->subscription_id;

            if (! $payment->subscription) {
                $manual[] = "Platba #{$payment->id} ({$subNumber}): chybí předplatné";

                continue;
            }

            $month = $this->targetMonth($payment);
            if (! $month) {
                $manual[] = "Platba #{$payment->id} ({$subNumber}): nelze určit měsíc (period_end/paid_at)";

                continue;
            }

            // Read-only: pokud schedule pro měsíc neexistuje, NEZAKLÁDÁME ho – jde k ruční revizi.
            $schedule = ShipmentSchedule::getForMonth($month['year'], $month['month']);
            $shipment = $schedule
                ? SubscriptionShipment::where('subscription_id', $payment->subscription_id)
                    ->where('shipment_schedule_id', $schedule->id)
                    ->first()
                : null;

            $ym = sprintf('%04d-%02d', $month['year'], $month['month']);

            if (! $shipment) {
                $manual[] = "Platba #{$payment->id} ({$subNumber}): žádný box pro {$ym} (nezakládám)";

                continue;
            }
            if ($shipment->subscription_payment_id) {
                $manual[] = "Platba #{$payment->id} ({$subNumber}): box #{$shipment->id} už patří platbě #{$shipment->subscription_payment_id}";

                continue;
            }
            if (in_array($shipment->status, ['skipped', 'cancelled'], true)) {
                $manual[] = "Platba #{$payment->id} ({$subNumber}): box #{$shipment->id} ({$ym}) je {$shipment->status} – NEOŽIVUJI";

                continue;
            }

            // pending / sent / delivered bez platby → bezpečné navázání beze změny stavu.
            $toLink[$payment->id] = $shipment;
            $this->line("  ✓ Platba #{$payment->id} ({$subNumber}) → box #{$shipment->id} ({$shipment->shipment_date->format('Y-m-d')}, {$shipment->status})");
        }

        if ($apply && $toLink) {
            DB::transaction(function () use ($toLink) {
                foreach ($toLink as $payId => $shipment) {
                    // Jen FK, žádná změna stavu; saveQuietly = bez model-eventů (žádné e-maily).
                    $shipment->subscription_payment_id = $payId;
                    $shipment->saveQuietly();

                    \Log::info('Backfill: linked orphan payment to existing shipment', [
                        'payment_id' => $payId,
                        'shipment_id' => $shipment->id,
                        'status' => $shipment->status,
                    ]);
                }
            });
        }

        $this->newLine();
        if ($manual) {
            $this->warn('K RUČNÍ REVIZI ('.count($manual).'):');
            foreach ($manual as $mline) {
                $this->line('  - '.$mline);
            }
            $this->newLine();
        }

        $verb = $apply ? 'Navázáno' : 'Navázalo by se';
        $this->info("{$verb}: ".count($toLink)." | k ruční revizi: ".count($manual).' | celkem osiřelých: '.$orphans->count());

        if (! $apply) {
            $this->line('   (dry-run – nic nezapsáno; pro zápis spusť s --apply)');
        }

        return 0;
    }

    /**
     * Cílový měsíc platby: period_end (jinak paid_at s posunem po 15.) – zrcadlí
     * SubscriptionPayment::expected_shipment_date, ale bez vytváření schedule.
     *
     * @return array{year:int,month:int}|null
     */
    private function targetMonth(SubscriptionPayment $payment): ?array
    {
        if ($payment->period_end) {
            return ['year' => $payment->period_end->year, 'month' => $payment->period_end->month];
        }

        if ($payment->paid_at) {
            $d = $payment->paid_at->day > 15
                ? $payment->paid_at->copy()->addMonthNoOverflow()
                : $payment->paid_at->copy();

            return ['year' => $d->year, 'month' => $d->month];
        }

        return null;
    }
}
