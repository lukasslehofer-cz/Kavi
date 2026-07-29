<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\ShipmentSchedule;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionShipment;
use App\Models\User;
use App\Services\SubscriptionShipmentService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

/**
 * Naplní DB testovacími předplatnými v různých fázích pro manuální QA před nasazením
 * revize evidence předplatných. Ledger se staví přes SubscriptionShipmentService, takže
 * `subscriptions:check-consistency` zůstává u "čistých" fází zelený.
 *
 * Bezpečné: odmítá běžet v produkci. Data jsou označena prefixem KVS-TEST- a e-maily
 * @test.kavi.local (--fresh je před novým během smaže). Model eventy jsou během seedu
 * vypnuté (žádné e-maily / přepočty skladu).
 */
class SeedTestSubscriptions extends Command
{
    protected $signature = 'subscriptions:seed-test-data {--fresh : Smazat dříve vytvořená test data před seedem}';

    protected $description = 'Naplní DB ~18 testovacími předplatnými v různých fázích (jen NE-produkce)';

    protected const SUB_PREFIX = 'KVS-TEST-';

    protected const EMAIL_DOMAIN = '@test.kavi.local';

    protected SubscriptionShipmentService $service;

    protected int $counter = 0;

    protected array $created = [];

    public function handle(SubscriptionShipmentService $service): int
    {
        if (app()->environment('production')) {
            $this->error('❌ Odmítám běžet v produkčním prostředí.');

            return 1;
        }

        $this->service = $service;

        if ($this->option('fresh')) {
            $this->cleanup();
        }

        $this->info('🌱 Vytvářím testovací předplatná...');

        // Model eventy vypnuté: žádné e-maily (delivered/shipped) ani přepočty skladu.
        Model::withoutEvents(function () {
            $this->buildAll();
        });

        $this->newLine();
        $this->info('✅ Hotovo. Vytvořeno '.count($this->created).' testovacích předplatných:');
        $this->table(['Číslo', 'Fáze', 'Freq', 'Měna'], $this->created);

        $this->newLine();
        $this->line('Doporučené kontroly:');
        $this->line('  php artisan subscriptions:check-consistency --details');
        $this->line('  php artisan subscriptions:charge-payments --dry-run');
        $this->line('  (skladové rezervace: php artisan stock:update-reservations)');
        $this->line('Úklid: php artisan subscriptions:seed-test-data --fresh');

        return 0;
    }

    /* ------------------------------------------------------------------ */
    /* Scénáře                                                             */
    /* ------------------------------------------------------------------ */

    protected function buildAll(): void
    {
        // 1) Aktivní, freq 1, čerstvé – jen 1 pending krytý aktivační platbou.
        $s = $this->baseSub(['frequency_months' => 1, 'starts_at' => now()->subDays(5)]);
        $pay = $this->paidPayment($s, $this->sched(1));
        $this->shipment($s, $this->sched(1), 'pending', ['subscription_payment_id' => $pay->id]);
        $this->setNextBilling($s, 1);
        $this->done($s, 'aktivní • čerstvé (1 pending, zaplaceno)');

        // 2) Aktivní, freq 1, 3 měsíce historie + pending.
        $s = $this->baseSub(['frequency_months' => 1]);
        $this->addHistory($s, [-3, -2, -1]);
        $this->shipment($s, $this->sched(1), 'pending');
        $this->setNextBilling($s, 1);
        $this->done($s, 'aktivní • historie 3 boxy + pending');

        // 3) Aktivní, freq 2, EUR, historie + pending.
        $s = $this->baseSub(['frequency_months' => 2, 'currency' => 'EUR', 'price' => 29]);
        $this->addHistory($s, [-4, -2]);
        $this->shipment($s, $this->sched(2), 'pending');
        $this->setNextBilling($s, 2);
        $this->done($s, 'aktivní • freq2 EUR + historie');

        // 4) Aktivní, freq 3, čerstvé.
        $s = $this->baseSub(['frequency_months' => 3, 'price' => 1490]);
        $pay = $this->paidPayment($s, $this->sched(1));
        $this->shipment($s, $this->sched(1), 'pending', ['subscription_payment_id' => $pay->id]);
        $this->setNextBilling($s, 1);
        $this->done($s, 'aktivní • freq3 čerstvé');

        // 5) Aktivní + addon objednávka navázaná na pending box.
        $s = $this->baseSub(['frequency_months' => 1]);
        $this->addHistory($s, [-2, -1]);
        $pending = $this->shipment($s, $this->sched(1), 'pending');
        $this->addonOrder($s, $pending, attached: true);
        $this->setNextBilling($s, 1);
        $this->done($s, 'aktivní • s navázaným addonem');

        // 6) Aktivní + ODPOJENÝ addon (hold-for-manual) – záměrně "osiřelý".
        $s = $this->baseSub(['frequency_months' => 1]);
        $this->addHistory($s, [-1]);
        $this->shipment($s, $this->sched(1), 'pending');
        $this->addonOrder($s, null, attached: false);
        $this->setNextBilling($s, 1);
        $this->done($s, 'aktivní • ODPOJENÝ addon (k ruční revizi)');

        // 7) Pozastavené, freq 1 – 2 skipped + pending po pauze.
        $s = $this->baseSub(['frequency_months' => 1]);
        $this->addHistory($s, [-2, -1]);
        $this->shipment($s, $this->sched(1), 'skipped', ['notes' => 'Paused by user: test']);
        $this->shipment($s, $this->sched(2), 'skipped', ['notes' => 'Paused by user: test']);
        $this->shipment($s, $this->sched(3), 'pending');
        $s->update([
            'status' => 'paused',
            'paused_iterations' => 2,
            'paused_until_date' => $this->sched(2)->shipment_date->copy()->addDay()->endOfDay(),
            'pause_reason' => 'user_request',
        ]);
        $this->setNextBilling($s, 3);
        $this->done($s, 'pozastavené • 2 skipped + pending');

        // 8) Pozastavené, freq 2, EUR.
        $s = $this->baseSub(['frequency_months' => 2, 'currency' => 'EUR', 'price' => 29]);
        $this->addHistory($s, [-2]);
        $this->shipment($s, $this->sched(2), 'skipped', ['notes' => 'Paused by user: test']);
        $this->shipment($s, $this->sched(4), 'pending');
        $s->update([
            'status' => 'paused',
            'paused_iterations' => 1,
            'paused_until_date' => $this->sched(2)->shipment_date->copy()->addDay()->endOfDay(),
            'pause_reason' => 'user_request',
        ]);
        $this->setNextBilling($s, 4);
        $this->done($s, 'pozastavené • freq2 EUR');

        // 9) Nezaplacené, freq 1, 1. selhání.
        $s = $this->baseSub(['frequency_months' => 1, 'status' => 'unpaid']);
        $this->addHistory($s, [-2, -1]);
        $this->shipment($s, $this->sched(1), 'pending'); // bez platby = neuhrazený box
        $s->update([
            'payment_failure_count' => 1,
            'last_payment_failure_at' => now()->subDay(),
            'last_payment_failure_reason' => 'card_declined',
            'next_billing_date' => now()->subDay(),
        ]);
        $this->done($s, 'nezaplacené • failure_count=1');

        // 10) Nezaplacené, freq 1, 2. selhání.
        $s = $this->baseSub(['frequency_months' => 1, 'status' => 'unpaid']);
        $this->addHistory($s, [-3, -2, -1]);
        $this->shipment($s, $this->sched(1), 'pending');
        $s->update([
            'payment_failure_count' => 2,
            'consecutive_unpaid_shipments' => 0,
            'last_payment_failure_at' => now()->subDays(2),
            'last_payment_failure_reason' => 'insufficient_funds',
            'next_billing_date' => now()->subDays(2),
        ]);
        $this->done($s, 'nezaplacené • failure_count=2');

        // 11) Zrušené okamžitě (bez zbytkového pending).
        $s = $this->baseSub(['frequency_months' => 1]);
        $this->addHistory($s, [-3, -2]);
        $s->update(['status' => 'cancelled', 'ends_at' => now()->subDays(3), 'cancellation_reason' => 'user_request']);
        $this->done($s, 'zrušené • okamžitě');

        // 12) Zrušené ke konci období – drží 1 ZAPLACENÝ pending box.
        $s = $this->baseSub(['frequency_months' => 1]);
        $this->addHistory($s, [-2, -1]);
        $prepaid = $this->paidPayment($s, $this->sched(1));
        $this->shipment($s, $this->sched(1), 'pending', ['subscription_payment_id' => $prepaid->id]);
        $s->update(['status' => 'cancelled', 'ends_at' => $this->sched(1)->shipment_date->copy()->endOfDay()]);
        $this->done($s, 'zrušené • ke konci období (placený box)');

        // 13) Complimentary (zdarma), bez billingu.
        $s = $this->baseSub(['frequency_months' => 1, 'status' => 'complimentary', 'price' => 0]);
        $this->shipment($s, $this->sched(1), 'pending');
        $s->update(['next_billing_date' => null]);
        $this->done($s, 'complimentary • bez billingu');

        // 14) Jednorázový box (freq 0), pending.
        $s = $this->baseSub(['frequency_months' => 0, 'status' => 'active']);
        $pay = $this->paidPayment($s, $this->sched(1));
        $this->shipment($s, $this->sched(1), 'pending', ['subscription_payment_id' => $pay->id]);
        $s->update(['next_billing_date' => null]);
        $this->done($s, 'jednorázový box • pending');

        // 15) Jednorázový box (freq 0), odeslaný/dokončený.
        $s = $this->baseSub(['frequency_months' => 0, 'status' => 'completed']);
        $pay = $this->paidPayment($s, $this->sched(-1));
        $this->shipment($s, $this->sched(-1), 'delivered', [
            'subscription_payment_id' => $pay->id,
            'sent_at' => $this->sched(-1)->shipment_date,
            'delivered_at' => $this->sched(-1)->shipment_date->copy()->addDays(2),
            'packeta_packet_id' => 'Z'.rand(100000000, 999999999),
        ]);
        $s->update(['next_billing_date' => null]);
        $this->done($s, 'jednorázový box • dokončený');

        // 16) Aktivní, poslední box DORUČENÝ (test delivered stavu/trackingu).
        $s = $this->baseSub(['frequency_months' => 1]);
        $this->addHistory($s, [-3, -2]);
        $payD = $this->paidPayment($s, $this->sched(-1));
        $this->shipment($s, $this->sched(-1), 'delivered', [
            'subscription_payment_id' => $payD->id,
            'sent_at' => $this->sched(-1)->shipment_date,
            'delivered_at' => $this->sched(-1)->shipment_date->copy()->addDays(2),
            'packeta_packet_id' => 'Z'.rand(100000000, 999999999),
            'packeta_tracking_url' => 'https://tracking.packeta.com/cs/?id=Z'.rand(100000000, 999999999),
        ]);
        $this->shipment($s, $this->sched(1), 'pending');
        $this->setNextBilling($s, 1);
        $this->done($s, 'aktivní • poslední box doručený');

        // 17) Aktivní EUR s výdejním místem + tracking (test štítku).
        $s = $this->baseSub([
            'frequency_months' => 1, 'currency' => 'EUR', 'price' => 29,
            'packeta_point_id' => '9876', 'packeta_point_name' => 'Z-BOX Bratislava',
        ]);
        $this->addHistory($s, [-1], withPacketa: true);
        $this->shipment($s, $this->sched(1), 'pending');
        $this->setNextBilling($s, 1);
        $this->done($s, 'aktivní • EUR pickup + tracking');

        // 18) Aktivní, PŘEDPLACENÉ 2 budoucí boxy (test paid coverage / pauzy).
        $s = $this->baseSub(['frequency_months' => 1]);
        $this->addHistory($s, [-1]);
        $p1 = $this->paidPayment($s, $this->sched(1));
        $this->shipment($s, $this->sched(1), 'pending', ['subscription_payment_id' => $p1->id]);
        $p2 = $this->paidPayment($s, $this->sched(2));
        $this->shipment($s, $this->sched(2), 'pending', ['subscription_payment_id' => $p2->id]);
        $this->setNextBilling($s, 3);
        $this->done($s, 'aktivní • předplacené 2 boxy dopředu');

        // 19) Aktivní s dopravcem (carrier místo Packeta point).
        $s = $this->baseSub(['frequency_months' => 1, 'carrier_id' => '106', 'carrier_pickup_point' => 'BOX-123']);
        $this->addHistory($s, [-2, -1]);
        $this->shipment($s, $this->sched(1), 'pending');
        $this->setNextBilling($s, 1);
        $this->done($s, 'aktivní • carrier (ne Packeta point)');

        // 20) Expirované (dojeté).
        $s = $this->baseSub(['frequency_months' => 1]);
        $this->addHistory($s, [-4, -3, -2]);
        $s->update(['status' => 'expired', 'ends_at' => now()->subDays(10)]);
        $this->done($s, 'expirované');
    }

    /* ------------------------------------------------------------------ */
    /* Stavebnice                                                          */
    /* ------------------------------------------------------------------ */

    protected function baseSub(array $o = []): Subscription
    {
        $this->counter++;
        $currency = $o['currency'] ?? 'CZK';
        $country = $currency === 'EUR' ? 'SK' : 'CZ';
        $freq = $o['frequency_months'] ?? 1;

        $user = User::create([
            'name' => 'Test Zákazník '.$this->counter,
            'email' => 'test'.$this->counter.self::EMAIL_DOMAIN,
            'password' => Hash::make('password'),
            'phone' => '+420777'.str_pad((string) $this->counter, 6, '0', STR_PAD_LEFT),
            'country' => $country,
            'city' => $currency === 'EUR' ? 'Bratislava' : 'Praha',
            'packeta_point_id' => $o['packeta_point_id'] ?? '12345',
            'carrier_id' => $o['carrier_id'] ?? null,
            'carrier_pickup_point' => $o['carrier_pickup_point'] ?? null,
        ]);

        $sub = Subscription::create([
            'subscription_number' => self::SUB_PREFIX.str_pad((string) $this->counter, 3, '0', STR_PAD_LEFT),
            'user_id' => $user->id,
            'status' => $o['status'] ?? 'active',
            'starts_at' => $o['starts_at'] ?? now()->subMonths(4),
            'configured_price' => $o['price'] ?? ($currency === 'EUR' ? 29 : 590),
            'vat_rate' => 12,
            'currency' => $currency,
            'frequency_months' => $freq,
            'configuration' => $o['configuration'] ?? [
                'type' => 'espresso', 'amount' => '2', 'isDecaf' => false, 'frequency' => (string) $freq,
            ],
            'shipping_address' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'country' => $country,
                'billing_city' => $user->city,
                'billing_address' => 'Testovací 1',
                'billing_postal_code' => $currency === 'EUR' ? '81101' : '11000',
                'packeta_point_id' => $o['packeta_point_id'] ?? '12345',
            ],
            'packeta_point_id' => $o['packeta_point_id'] ?? '12345',
            'packeta_point_name' => $o['packeta_point_name'] ?? 'Z-BOX Testovací',
            'carrier_id' => $o['carrier_id'] ?? null,
            'carrier_pickup_point' => $o['carrier_pickup_point'] ?? null,
            'payment_method' => 'card',
        ]);

        return $sub->fresh();
    }

    /**
     * Odeslaná historie: pro každý offset (v měsících dozadu) vytvoří sent box +
     * navázanou zaplacenou platbu.
     */
    protected function addHistory(Subscription $sub, array $offsets, bool $withPacketa = false): void
    {
        foreach ($offsets as $offset) {
            $schedule = $this->sched($offset);
            $payment = $this->paidPayment($sub, $schedule);
            $opts = [
                'subscription_payment_id' => $payment->id,
                'sent_at' => $schedule->shipment_date,
            ];
            if ($withPacketa) {
                $opts['packeta_packet_id'] = 'Z'.rand(100000000, 999999999);
                $opts['packeta_tracking_url'] = 'https://tracking.packeta.com/cs/?id=Z'.rand(100000000, 999999999);
            }
            $this->shipment($sub, $schedule, 'sent', $opts);
        }
    }

    protected function paidPayment(Subscription $sub, ShipmentSchedule $schedule): SubscriptionPayment
    {
        $billing = $schedule->billing_date->copy();
        $freq = max(1, (int) $sub->frequency_months);

        return SubscriptionPayment::create([
            'subscription_id' => $sub->id,
            'stripe_payment_intent_id' => 'pi_test_'.$sub->id.'_'.$schedule->id,
            'amount' => $sub->configured_price,
            'currency' => $sub->currency,
            'status' => 'paid',
            'paid_at' => $billing,
            'period_start' => $billing->copy()->subMonths($freq),
            'period_end' => $billing,
        ]);
    }

    protected function shipment(Subscription $sub, ShipmentSchedule $schedule, string $status, array $opts = []): SubscriptionShipment
    {
        return $this->service->getOrCreateForSchedule($sub, $schedule, array_merge(['status' => $status], $opts));
    }

    protected function addonOrder(Subscription $sub, ?SubscriptionShipment $shipment, bool $attached): Order
    {
        return Order::create([
            'order_number' => 'KV-TEST-'.str_pad((string) $this->counter, 4, '0', STR_PAD_LEFT),
            'user_id' => $sub->user_id,
            'subscription_id' => $sub->id,
            'shipment_schedule_id' => $shipment?->shipment_schedule_id ?? $this->sched(1)->id,
            'subscription_shipment_id' => $attached ? $shipment?->id : null,
            'shipped_with_subscription' => true,
            'subscription_addon_slots_used' => 1,
            'status' => 'processing',
            'payment_status' => 'paid',
            'subtotal' => 200,
            'shipping' => 0,
            'tax' => 0,
            'total' => 200,
            'currency' => $sub->currency,
            'shipping_address' => $sub->shipping_address,
            'billing_address' => $sub->shipping_address,
            'admin_notes' => $attached ? null : '['.now()->toDateString().'] Addon odpojen od zásilky (test) – k ruční revizi.',
        ]);
    }

    protected function setNextBilling(Subscription $sub, int $offset): void
    {
        $sub->update(['next_billing_date' => $this->sched($offset)->billing_date]);
    }

    protected function sched(int $monthOffset): ShipmentSchedule
    {
        $d = now()->startOfMonth()->addMonths($monthOffset);

        return ShipmentSchedule::getOrCreateForMonth($d->year, $d->month);
    }

    protected function done(Subscription $sub, string $phase): void
    {
        $this->created[] = [$sub->subscription_number, $phase, $sub->frequency_months, $sub->currency];
    }

    /* ------------------------------------------------------------------ */
    /* Úklid                                                               */
    /* ------------------------------------------------------------------ */

    protected function cleanup(): void
    {
        $this->warn('🧹 Mažu dříve vytvořená testovací data...');

        $subIds = Subscription::where('subscription_number', 'like', self::SUB_PREFIX.'%')->pluck('id');

        Order::whereIn('subscription_id', $subIds)->orWhere('order_number', 'like', 'KV-TEST-%')->delete();
        SubscriptionShipment::whereIn('subscription_id', $subIds)->delete();
        SubscriptionPayment::whereIn('subscription_id', $subIds)->delete();
        $userIds = Subscription::whereIn('id', $subIds)->pluck('user_id');
        Subscription::whereIn('id', $subIds)->delete();
        User::whereIn('id', $userIds)->orWhere('email', 'like', '%'.self::EMAIL_DOMAIN)->delete();

        $this->line("  smazáno {$subIds->count()} předplatných + související data.");
    }
}
