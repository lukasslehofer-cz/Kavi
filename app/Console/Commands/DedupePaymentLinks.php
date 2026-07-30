<?php

namespace App\Console\Commands;

use App\Models\SubscriptionPayment;
use App\Models\SubscriptionShipment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Náprava plateb navázaných na VÍCE boxů (1 platba = 1 zásilka v měsíčním modelu).
 *
 * Pro každou platbu odkazovanou >1 zásilkou ponechá vazbu na box jejího `period_end`
 * měsíce (jinak fallback na `paid_at`, jako expected_shipment_date) a ostatní odpojí
 * (subscription_payment_id = NULL). Uvolněné boxy zůstanou beze změny stavu – jde o
 * historickou mezeru v neevidovaných platbách, kterou tímto nezhoršujeme.
 *
 * Bezpečné: pokud ŽÁDNÝ z navázaných boxů neodpovídá měsíci platby, případ se NEŘEŠÍ
 * a jde k ruční revizi. Default = dry-run; zápis až s --apply (v transakci, bez eventů).
 */
class DedupePaymentLinks extends Command
{
    protected $signature = 'subscriptions:dedupe-payment-links {--apply : Skutečně odpojit spurious vazby (bez tohoto jen náhled)}';

    protected $description = 'Odpojí platby navázané na více boxů – ponechá box period_end měsíce, ostatní odpojí';

    public function handle(): int
    {
        $apply = (bool) $this->option('apply');

        $multi = SubscriptionShipment::whereNotNull('subscription_payment_id')
            ->select('subscription_payment_id')
            ->groupBy('subscription_payment_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('subscription_payment_id');

        if ($multi->isEmpty()) {
            $this->info('✓ Žádná platba navázaná na více boxů.');

            return 0;
        }

        $this->info(($apply ? '⚙️  APPLY – ' : '🧪 DRY-RUN – ')."Nalezeno {$multi->count()} plateb na více boxech.");
        $this->newLine();

        $unlink = [];   // zásilky k odpojení (subscription_payment_id → null)
        $manual = [];

        foreach ($multi as $paymentId) {
            $payment = SubscriptionPayment::with('subscription')->find($paymentId);
            $subNo = $payment?->subscription?->subscription_number ?? '#'.optional($payment)->subscription_id;

            $shipments = SubscriptionShipment::where('subscription_payment_id', $paymentId)
                ->orderBy('shipment_date')
                ->get();

            $month = $this->targetMonth($payment);
            if (! $month) {
                $manual[] = "pay#{$paymentId} ({$subNo}): nelze určit měsíc (period_end/paid_at)";

                continue;
            }

            $keep = $shipments->first(fn ($s) => $s->shipment_date
                && (int) $s->shipment_date->year === $month['year']
                && (int) $s->shipment_date->month === $month['month']);

            if (! $keep) {
                $manual[] = "pay#{$paymentId} ({$subNo}): žádný box neodpovídá měsíci "
                    .sprintf('%04d-%02d', $month['year'], $month['month'])." (ponechávám beze změny)";

                continue;
            }

            $others = $shipments->reject(fn ($s) => $s->id === $keep->id);
            $this->line("  pay#{$paymentId} ({$subNo}): ponechat box#{$keep->id} ({$keep->shipment_date->format('Y-m')}), odpojit "
                .$others->map(fn ($s) => 'box#'.$s->id.' ('.optional($s->shipment_date)->format('Y-m').')')->implode(', '));

            foreach ($others as $s) {
                $unlink[] = $s;
            }
        }

        if ($apply && $unlink) {
            DB::transaction(function () use ($unlink) {
                foreach ($unlink as $s) {
                    $from = $s->subscription_payment_id;
                    $s->subscription_payment_id = null;
                    $s->saveQuietly(); // jen FK, stav se nemění, žádné model-eventy
                    \Log::info('Dedupe payment link: unlinked spurious shipment', [
                        'shipment_id' => $s->id,
                        'from_payment' => $from,
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

        $verb = $apply ? 'Odpojeno spurious vazeb' : 'Odpojilo by se spurious vazeb';
        $this->info("{$verb}: ".count($unlink).' | k ruční revizi: '.count($manual).' | plateb na více boxech: '.$multi->count());

        if (! $apply) {
            $this->line('   (dry-run – nic nezapsáno; pro zápis spusť s --apply)');
        }

        return 0;
    }

    /**
     * Cílový měsíc platby: period_end (jinak paid_at s posunem po 15.).
     *
     * @return array{year:int,month:int}|null
     */
    private function targetMonth(?SubscriptionPayment $payment): ?array
    {
        if (! $payment) {
            return null;
        }

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
