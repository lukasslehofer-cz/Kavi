<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\ShipmentSchedule;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionShipment;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Read-only konzistenční kontrola evidence předplatných a rozesílek.
 *
 * Slouží jako "gate" pro postupnou konsolidaci datového modelu (viz plán revize).
 * NIC nezapisuje – jen detekuje třídy rozjezdu mezi třemi historickými zdroji
 * pravdy (rodič `subscriptions`, ledger `subscription_shipments`, dopočet z plateb).
 *
 * Návratový kód:
 *   0 = žádný nález (nebo jen INFO)
 *   1 = nalezena aspoň jedna nekonzistence (WARN/BLOCKER) – vhodné pro CI/deploy gate
 */
class CheckSubscriptionConsistency extends Command
{
    protected $signature = 'subscriptions:check-consistency
                            {--details : Vypsat ukázkové řádky ke každému nálezu}
                            {--limit=20 : Maximální počet ukázkových řádků na jeden nález}
                            {--json : Strojově čitelný JSON výstup}';

    protected $description = 'Read-only kontrola konzistence předplatných a rozesílek (nezapisuje)';

    /** @var array<int, array{key:string,label:string,severity:string,rows:array}> */
    protected array $findings = [];

    public function handle(): int
    {
        $limit = max(1, (int) $this->option('limit'));

        // Načteme jednou vše potřebné pro per-subscription kontroly.
        $subscriptions = Subscription::with(['shipments' => function ($q) {
            $q->orderBy('shipment_date', 'asc');
        }, 'shipments.payment', 'payments'])->get();

        $schedules = ShipmentSchedule::all()->keyBy('id');

        // --- Kontroly ---
        $this->checkNullScheduleRows($subscriptions);
        $this->checkDuplicateSchedule($subscriptions);
        $this->checkShipmentDateMismatch($subscriptions, $schedules);
        $this->checkCancelledWithLeftoverPending($subscriptions);
        $this->checkPaidPaymentsWithoutShipment();
        $this->checkPaymentOnMultipleShipments();
        $this->checkOrphanedAddonOrders();
        $this->checkLegacyStripeSubscriptions($subscriptions);

        return $this->report($limit);
    }

    /* ------------------------------------------------------------------ */
    /* Kontroly                                                            */
    /* ------------------------------------------------------------------ */

    /**
     * A) Řádky ledgeru bez shipment_schedule_id – blokují budoucí unique index
     * a znemožňují spolehlivé klíčování na měsíc.
     */
    protected function checkNullScheduleRows($subscriptions): void
    {
        $rows = [];
        foreach ($subscriptions as $sub) {
            foreach ($sub->shipments as $s) {
                if ($s->shipment_schedule_id === null && $s->status !== 'cancelled') {
                    $rows[] = [
                        'subscription' => $sub->subscription_number ?? '#'.$sub->id,
                        'shipment_id' => $s->id,
                        'shipment_date' => optional($s->shipment_date)->toDateString(),
                        'status' => $s->status,
                    ];
                }
            }
        }

        $this->record('null_schedule', 'Zásilky bez shipment_schedule_id', 'blocker', $rows);
    }

    /**
     * B) Více než jeden ne-zrušený řádek na (subscription_id, shipment_schedule_id).
     * Přímo brání zavedení unique(subscription_id, shipment_schedule_id).
     */
    protected function checkDuplicateSchedule($subscriptions): void
    {
        $rows = [];
        foreach ($subscriptions as $sub) {
            // Přes VŠECHNY statusy – budoucí unique(subscription_id, shipment_schedule_id)
            // se vztahuje i na cancelled/skipped řádky (MySQL neumí filtrovaný unique index).
            $groups = $sub->shipments
                ->whereNotNull('shipment_schedule_id')
                ->groupBy('shipment_schedule_id');

            foreach ($groups as $scheduleId => $group) {
                if ($group->count() > 1) {
                    $rows[] = [
                        'subscription' => $sub->subscription_number ?? '#'.$sub->id,
                        'shipment_schedule_id' => $scheduleId,
                        'count' => $group->count(),
                        'shipment_ids' => $group->pluck('id')->implode(','),
                        'statuses' => $group->pluck('status')->implode(','),
                    ];
                }
            }
        }

        $this->record('dup_schedule', 'Duplicitní zásilky na (subscription, schedule)', 'blocker', $rows);
    }

    /**
     * 2) shipment_date se neshoduje s datem svého ScheduleMonth.
     * Identita musí být schedule, ne spočtené datum.
     */
    protected function checkShipmentDateMismatch($subscriptions, $schedules): void
    {
        $rows = [];
        foreach ($subscriptions as $sub) {
            foreach ($sub->shipments as $s) {
                // Jen budoucí/plánované boxy musí mít datum == schedule (kvůli adresovatelnosti).
                // Odeslaná historie nese skutečné datum odeslání a smí se lišit.
                if ($s->shipment_schedule_id === null || $s->status !== 'pending') {
                    continue;
                }
                $schedule = $schedules->get($s->shipment_schedule_id);
                if (! $schedule) {
                    continue;
                }
                $rowDate = optional($s->shipment_date)->toDateString();
                $schedDate = optional($schedule->shipment_date)->toDateString();
                if ($rowDate !== $schedDate) {
                    $rows[] = [
                        'subscription' => $sub->subscription_number ?? '#'.$sub->id,
                        'shipment_id' => $s->id,
                        'row_date' => $rowDate,
                        'schedule_date' => $schedDate,
                        'status' => $s->status,
                    ];
                }
            }
        }

        $this->record('date_mismatch', 'pending shipment_date != schedule.shipment_date', 'warn', $rows);
    }

    /**
     * 6) Zrušené předplatné se zbytkovými pending zásilkami bez zaplacené platby.
     * (Placené pending jsou legitimní: cancel-at-period-end drží doplacený box.)
     */
    protected function checkCancelledWithLeftoverPending($subscriptions): void
    {
        $rows = [];
        foreach ($subscriptions as $sub) {
            if ($sub->status !== 'cancelled') {
                continue;
            }
            foreach ($sub->shipments as $s) {
                if ($s->status === 'pending' && ! $this->shipmentIsPaid($s)) {
                    $rows[] = [
                        'subscription' => $sub->subscription_number ?? '#'.$sub->id,
                        'shipment_id' => $s->id,
                        'shipment_date' => optional($s->shipment_date)->toDateString(),
                        'ends_at' => optional($sub->ends_at)->toDateString() ?? '—',
                    ];
                }
            }
        }

        $this->record('cancelled_leftover', 'Zrušené předplatné s neplaceným pending boxem', 'warn', $rows);
    }

    /**
     * 8) Zaplacené platby bez navázané zásilky (subscription_shipments.subscription_payment_id).
     * FK má být jediným zdrojem "kterou zásilku platba pokrývá".
     */
    protected function checkPaidPaymentsWithoutShipment(): void
    {
        $paidPaymentIds = SubscriptionPayment::where('status', 'paid')->pluck('id');
        $linkedPaymentIds = SubscriptionShipment::whereNotNull('subscription_payment_id')
            ->pluck('subscription_payment_id')
            ->unique();

        $orphanIds = $paidPaymentIds->diff($linkedPaymentIds);

        $rows = [];
        if ($orphanIds->isNotEmpty()) {
            $payments = SubscriptionPayment::with('subscription')
                ->whereIn('id', $orphanIds)
                ->get();
            foreach ($payments as $p) {
                $rows[] = [
                    'payment_id' => $p->id,
                    'subscription' => $p->subscription?->subscription_number ?? '#'.$p->subscription_id,
                    'amount' => $p->amount,
                    'paid_at' => optional($p->paid_at)->toDateString(),
                    'period_end' => optional($p->period_end)->toDateString() ?? '—',
                ];
            }
        }

        $this->record('paid_without_shipment', 'Zaplacené platby bez navázané zásilky', 'high', $rows);
    }

    /**
     * 8b) Jedna platba navázaná na VÍCE boxů. V měsíčním modelu platí 1 platba = 1 zásilka;
     * víc odkazů = historický mislink (starý dvojí linkovací průchod). Náprava:
     * subscriptions:dedupe-payment-links.
     */
    protected function checkPaymentOnMultipleShipments(): void
    {
        $multi = SubscriptionShipment::whereNotNull('subscription_payment_id')
            ->select('subscription_payment_id')
            ->groupBy('subscription_payment_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('subscription_payment_id');

        $rows = [];
        foreach ($multi as $paymentId) {
            $payment = SubscriptionPayment::with('subscription')->find($paymentId);
            $shipments = SubscriptionShipment::where('subscription_payment_id', $paymentId)
                ->orderBy('shipment_date')
                ->get();

            $rows[] = [
                'payment_id' => $paymentId,
                'subscription' => $payment?->subscription?->subscription_number ?? '#'.optional($payment)->subscription_id,
                'boxes' => $shipments->count(),
                'shipment_ids' => $shipments->pluck('id')->implode(','),
                'period_end' => optional($payment?->period_end)->toDateString() ?? '—',
            ];
        }

        $this->record('payment_multi_shipment', 'Platba navázaná na více boxů', 'high', $rows);
    }

    /**
     * 7) Osiřelé/odpojené addon objednávky (KROK 10): shipped_with_subscription, ještě
     * neodeslané, a jejich subscription_shipment_id je NULL nebo míří na přeskočený/zrušený
     * box. Tyto jsou k RUČNÍ revizi (hold-for-manual).
     */
    protected function checkOrphanedAddonOrders(): void
    {
        $addonOrders = Order::where('shipped_with_subscription', true)
            ->whereNotIn('status', ['shipped', 'delivered', 'cancelled'])
            ->with('subscriptionShipment')
            ->get();

        $rows = [];
        foreach ($addonOrders as $order) {
            $ship = $order->subscriptionShipment;
            $orphaned = ! $ship || in_array($ship->status, ['skipped', 'cancelled'], true);

            if ($orphaned) {
                $rows[] = [
                    'order' => $order->order_number ?? '#'.$order->id,
                    'subscription_id' => $order->subscription_id,
                    'subscription_shipment_id' => $order->subscription_shipment_id ?? '—',
                    'shipment_status' => $ship?->status ?? 'null',
                    'status' => $order->status,
                ];
            }
        }

        $this->record('orphan_addons', 'Odpojené addon objednávky (k ruční revizi)', 'high', $rows);
    }

    /**
     * 9) Předplatná se zbylým stripe_subscription_id (legacy Stripe-managed billing).
     * Očekáváme 0 – jinak nelze bezpečně smazat staré webhook větve.
     */
    protected function checkLegacyStripeSubscriptions($subscriptions): void
    {
        $rows = [];
        foreach ($subscriptions as $sub) {
            if (! empty($sub->stripe_subscription_id)) {
                $rows[] = [
                    'subscription' => $sub->subscription_number ?? '#'.$sub->id,
                    'status' => $sub->status,
                    'stripe_subscription_id' => $sub->stripe_subscription_id,
                ];
            }
        }

        $this->record('legacy_stripe', 'Předplatná se stripe_subscription_id (legacy)', 'high', $rows);
    }

    /* ------------------------------------------------------------------ */
    /* Pomocné                                                             */
    /* ------------------------------------------------------------------ */

    protected function shipmentIsPaid(SubscriptionShipment $shipment): bool
    {
        return $shipment->payment && $shipment->payment->status === 'paid';
    }

    protected function record(string $key, string $label, string $severity, array $rows): void
    {
        $this->findings[] = [
            'key' => $key,
            'label' => $label,
            'severity' => $severity,
            'rows' => $rows,
        ];
    }

    protected function report(int $limit): int
    {
        if ($this->option('json')) {
            $this->line(json_encode([
                'generated_at' => now()->toDateTimeString(),
                'findings' => $this->findings,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

            return $this->hasIssues() ? 1 : 0;
        }

        $this->newLine();
        $this->info('🔍 Kontrola konzistence předplatných a rozesílek (read-only)');
        $this->newLine();

        $summary = [];
        foreach ($this->findings as $f) {
            $count = count($f['rows']);
            $icon = $count === 0 ? '✅' : $this->severityIcon($f['severity']);
            $summary[] = [$icon, $f['label'], $count, strtoupper($f['severity'])];
        }

        $this->table(['', 'Kontrola', 'Nálezů', 'Závažnost'], $summary);

        $totalIssues = $this->totalIssues();

        if ($this->option('details')) {
            foreach ($this->findings as $f) {
                if (empty($f['rows'])) {
                    continue;
                }
                $this->newLine();
                $this->warn("▶ {$f['label']} ({$f['severity']}) – ".count($f['rows']).' nález(ů)');
                $rows = array_slice($f['rows'], 0, $limit);
                $headers = array_keys($rows[0]);
                $this->table($headers, array_map(fn ($r) => array_values($r), $rows));
                if (count($f['rows']) > $limit) {
                    $this->line('  … a dalších '.(count($f['rows']) - $limit).' (zvyš --limit)');
                }
            }
        }

        $this->newLine();
        if ($totalIssues === 0) {
            $this->info('✅ Vše konzistentní – žádný nález.');

            return 0;
        }

        $this->error("❌ Nalezeno {$totalIssues} nekonzistentních položek napříč ".$this->issueClassCount().' třídami.');
        if (! $this->option('details')) {
            $this->line('   Spusť s --details pro ukázkové řádky.');
        }

        return 1;
    }

    protected function severityIcon(string $severity): string
    {
        return match ($severity) {
            'blocker' => '⛔',
            'high' => '🔴',
            'warn' => '🟡',
            default => 'ℹ️',
        };
    }

    protected function totalIssues(): int
    {
        return array_sum(array_map(fn ($f) => count($f['rows']), $this->findings));
    }

    protected function issueClassCount(): int
    {
        return count(array_filter($this->findings, fn ($f) => count($f['rows']) > 0));
    }

    protected function hasIssues(): bool
    {
        return $this->totalIssues() > 0;
    }
}
