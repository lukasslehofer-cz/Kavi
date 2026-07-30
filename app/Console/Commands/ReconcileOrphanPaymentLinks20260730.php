<?php

namespace App\Console\Commands;

use App\Models\SubscriptionShipment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * JEDNORÁZOVÁ cílená náprava 2 zbylých orphanů, které bezpečný link-only backfill
 * (subscriptions:link-shipment-payments) nechal k ruční revizi. Spouštět AŽ PO něm.
 *
 *  - KVS-2025-029: pay#19 → box#7 (červenec, delivered). period_end platby je legacy
 *    Stripe-styl [16.7.–16.8.], takže "měsíc = srpen" mířil na skipped srpen; reálně
 *    kryje červenec.
 *  - KVS-2025-021: raný řetězec plateb je posunutý o měsíc proti konvenci systému
 *    (period_end měsíc → box téhož měsíce), takže první platba (pay#3) vypadla.
 *    Přeskládá pay#3→#47 … pay#7→#43 a odstraní duplicitní vazbu pay#33 (box#41).
 *
 * GUARDOVANÉ + all-or-nothing: každý box se před změnou ověří proti očekávanému stavu
 * (subscription, status, aktuální payment). Když cokoli nesedí (např. prod od zálohy
 * 2026-07-30 odjel), NIC se nezapíše. Mění se pouze FK subscription_payment_id – žádná
 * změna stavu (tj. žádné oživení skipped/cancelled), faktury na platbách nedotčeny.
 *
 * Bezpečné default = dry-run; zápis až s --apply.
 */
class ReconcileOrphanPaymentLinks20260730 extends Command
{
    protected $signature = 'subscriptions:reconcile-orphan-links-20260730 {--apply : Skutečně zapsat (bez tohoto jen náhled)}';

    protected $description = 'Jednorázová náprava 2 zbylých orphanů (pay#19 a řetězec KVS-2025-021) – guardované, bez oživení';

    /**
     * [box_id, očekávaný sub_number, očekávaný status, očekávaný současný pay (null=žádný), nový pay (null=odpojit)]
     */
    protected array $ops = [
        [7,  'KVS-2025-029', 'delivered', null, 19],  // pay#19 kryje červenec
        [47, 'KVS-2025-021', 'sent',      4,    3],    // květen → pay#3 (orphan)
        [46, 'KVS-2025-021', 'sent',      5,    4],    // červen
        [45, 'KVS-2025-021', 'sent',      6,    5],    // červenec
        [44, 'KVS-2025-021', 'sent',      7,    6],    // srpen
        [43, 'KVS-2025-021', 'sent',      null, 7],    // září
        [41, 'KVS-2025-021', 'sent',      33,   null], // listopad: odpojit (dup pay#33)
        // box#27 (prosinec) si pay#33 ponechává – neměníme.
    ];

    public function handle(): int
    {
        $apply = (bool) $this->option('apply');
        $this->info($apply ? '⚙️  APPLY' : '🧪 DRY-RUN');
        $this->newLine();

        $planned = [];
        $errors = [];

        foreach ($this->ops as [$boxId, $subNo, $status, $curPay, $newPay]) {
            $box = SubscriptionShipment::with('subscription')->find($boxId);

            if (! $box) {
                $errors[] = "box#{$boxId}: neexistuje";

                continue;
            }

            $actualSub = optional($box->subscription)->subscription_number;
            $actualPay = $box->subscription_payment_id;

            $mismatch = [];
            if ($actualSub !== $subNo) {
                $mismatch[] = "sub {$actualSub}≠{$subNo}";
            }
            if ($box->status !== $status) {
                $mismatch[] = "status {$box->status}≠{$status}";
            }
            if ((int) $actualPay !== (int) $curPay) {
                $mismatch[] = 'pay '.($actualPay ?? 'NULL').'≠'.($curPay ?? 'NULL');
            }

            if ($mismatch) {
                $errors[] = "box#{$boxId}: NESEDÍ (".implode(', ', $mismatch).')';

                continue;
            }

            $planned[] = ['box' => $box, 'from' => $actualPay, 'to' => $newPay];
            $this->line(sprintf('  box#%-4d (%s, %s)  pay %s → %s', $boxId, $subNo, $status, $actualPay ?? 'NULL', $newPay ?? 'NULL'));
        }

        $this->newLine();

        if ($errors) {
            $this->error('❌ GUARD SELHAL – nic se nezapíše:');
            foreach ($errors as $e) {
                $this->line('   - '.$e);
            }

            return 1;
        }

        $this->info('Guard OK – '.count($planned).' operací připraveno.');

        if (! $apply) {
            $this->line('   (dry-run – nic nezapsáno; pro zápis spusť s --apply)');

            return 0;
        }

        DB::transaction(function () use ($planned) {
            foreach ($planned as $p) {
                $p['box']->subscription_payment_id = $p['to'];
                $p['box']->saveQuietly(); // jen FK, žádné model-eventy, stav se nemění

                \Log::info('Reconcile orphan payment link (20260730)', [
                    'shipment_id' => $p['box']->id,
                    'from_payment' => $p['from'],
                    'to_payment' => $p['to'],
                ]);
            }
        });

        $this->info('✅ ZAPSÁNO ('.count($planned).' operací).');

        return 0;
    }
}
