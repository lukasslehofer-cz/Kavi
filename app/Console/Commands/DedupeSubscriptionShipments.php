<?php

namespace App\Console\Commands;

use App\Models\SubscriptionShipment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * KROK 3 revize evidence předplatných.
 *
 * Sloučí duplicitní řádky ledgeru na (subscription_id, shipment_schedule_id) do
 * jednoho "survivora" – nutná příprava před zavedením unique indexu (KROK 4).
 *
 * Survivor se vybírá podle: status (delivered>sent>pending>skipped) → má navázanou
 * platbu → nejnižší id (nejstarší). Z poražených řádků se do survivora doplní
 * chybějící údaje (platba, packeta, carrier, rozměry, sent_at/delivered_at), poražené
 * se smažou. Zrušené (cancelled) řádky se do dedupu nezahrnují.
 *
 * Bezpečně default = DRY-RUN. Skutečné sloučení až s --apply.
 */
class DedupeSubscriptionShipments extends Command
{
    protected $signature = 'subscriptions:dedupe-shipments
                            {--apply : Skutečně sloučit (bez tohoto přepínače jen náhled)}
                            {--subscription= : Omezit na jedno předplatné (ID)}';

    protected $description = 'Sloučí duplicitní zásilky na (subscription, schedule) do jednoho řádku';

    protected array $statusRank = [
        'delivered' => 4,
        'sent' => 3,
        'pending' => 2,
        'skipped' => 1,
        'cancelled' => 0,
    ];

    /** Sloupce, které se z poražených řádků doplní do survivora, pokud tam chybí. */
    protected array $fillableFromLoser = [
        'subscription_payment_id',
        'packeta_packet_id',
        'packeta_tracking_url',
        'carrier_id',
        'carrier_pickup_point',
        'package_weight',
        'package_length',
        'package_width',
        'package_height',
        'sent_at',
        'delivered_at',
    ];

    public function handle(): int
    {
        $apply = (bool) $this->option('apply');

        if ($apply) {
            $this->warn('⚙️  APPLY režim – duplicity budou skutečně sloučeny.');
        } else {
            $this->info('🧪 DRY-RUN – jen náhled, nic se nezapisuje. Pro sloučení spusť s --apply.');
        }
        $this->newLine();

        // Najdi skupiny s >1 řádkem na (subscription, schedule) – přes VŠECHNY statusy,
        // protože unique index (KROK 4) se vztahuje i na cancelled/skipped.
        $query = SubscriptionShipment::query()
            ->select('subscription_id', 'shipment_schedule_id', DB::raw('COUNT(*) as cnt'))
            ->whereNotNull('shipment_schedule_id')
            ->groupBy('subscription_id', 'shipment_schedule_id')
            ->havingRaw('COUNT(*) > 1');

        if ($subId = $this->option('subscription')) {
            $query->where('subscription_id', $subId);
        }

        $groups = $query->get();

        if ($groups->isEmpty()) {
            $this->info('✅ Žádné duplicity k sloučení.');

            return 0;
        }

        $this->warn("Nalezeno {$groups->count()} duplicitních skupin.");
        $merged = 0;
        $deleted = 0;

        $runner = function () use ($groups, $apply, &$merged, &$deleted) {
            foreach ($groups as $group) {
                $rows = SubscriptionShipment::where('subscription_id', $group->subscription_id)
                    ->where('shipment_schedule_id', $group->shipment_schedule_id)
                    ->where('status', '!=', 'cancelled')
                    ->get();

                if ($rows->count() < 2) {
                    continue;
                }

                $survivor = $this->pickSurvivor($rows);
                $losers = $rows->reject(fn ($r) => $r->id === $survivor->id);

                $this->line("• sub {$group->subscription_id} / schedule {$group->shipment_schedule_id}: "
                    ."survivor #{$survivor->id} ({$survivor->status}), "
                    .'poražení: '.$losers->pluck('id')->implode(','));

                $changes = [];
                $noteAdd = [];
                foreach ($losers as $loser) {
                    // Data zrušeného boxu nesmí "prosáknout" do živého survivora
                    // (např. sent_at/packeta z cancelled řádku do pending). Řádek jen smažeme.
                    if ($loser->status !== 'cancelled') {
                        foreach ($this->fillableFromLoser as $col) {
                            if (empty($survivor->{$col}) && ! empty($loser->{$col})) {
                                $changes[$col] = $loser->{$col};
                                $survivor->{$col} = $loser->{$col};
                            }
                        }
                    }
                    if (! empty($loser->notes)) {
                        $noteAdd[] = "[merged #{$loser->id}] {$loser->notes}";
                    }
                }

                if ($apply) {
                    if (! empty($noteAdd)) {
                        $survivor->notes = trim(($survivor->notes ? $survivor->notes."\n" : '').implode("\n", $noteAdd));
                    }
                    if ($survivor->isDirty()) {
                        $survivor->saveQuietly();
                    }
                    foreach ($losers as $loser) {
                        $loser->delete();
                        $deleted++;
                    }
                    \Log::info('Dedupe: sloučeny duplicitní zásilky', [
                        'subscription_id' => $group->subscription_id,
                        'shipment_schedule_id' => $group->shipment_schedule_id,
                        'survivor_id' => $survivor->id,
                        'deleted_ids' => $losers->pluck('id')->all(),
                        'filled' => array_keys($changes),
                    ]);
                } else {
                    if (! empty($changes)) {
                        $this->line('    → doplnilo by se do survivora: '.implode(', ', array_keys($changes)));
                    }
                    $deleted += $losers->count();
                }

                $merged++;
            }
        };

        if ($apply) {
            DB::transaction($runner);
        } else {
            $runner();
        }

        $this->newLine();
        $this->info(($apply ? '✅ Sloučeno' : '🧪 Sloučilo by se')." skupin: {$merged}, smazaných řádků: {$deleted}.");

        if (! $apply) {
            $this->line('   Spusť znovu s --apply pro provedení.');
        }

        return 0;
    }

    protected function pickSurvivor($rows): SubscriptionShipment
    {
        return $rows->sort(function ($a, $b) {
            // 1) vyšší status rank
            $ra = $this->statusRank[$a->status] ?? 0;
            $rb = $this->statusRank[$b->status] ?? 0;
            if ($ra !== $rb) {
                return $rb <=> $ra;
            }
            // 2) má navázanou platbu
            $pa = $a->subscription_payment_id ? 1 : 0;
            $pb = $b->subscription_payment_id ? 1 : 0;
            if ($pa !== $pb) {
                return $pb <=> $pa;
            }
            // 3) nejnižší id (nejstarší)
            return $a->id <=> $b->id;
        })->first();
    }
}
