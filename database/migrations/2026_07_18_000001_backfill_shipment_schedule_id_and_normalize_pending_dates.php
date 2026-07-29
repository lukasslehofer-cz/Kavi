<?php

use App\Models\ShipmentSchedule;
use App\Models\SubscriptionShipment;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * KROK 2 revize evidence předplatných.
 *
 * 1) Doplní shipment_schedule_id všem řádkům ledgeru, které ho nemají – měsíc se
 *    určí z shipment_date a schedule se dohledá/vytvoří přes getOrCreateForMonth.
 *    Tím se z rozvrhu (ShipmentSchedule) stává spolehlivý klíč měsíce ještě před
 *    zavedením unique indexu (KROK 4).
 *
 * 2) Znormalizuje shipment_date pouze BUDOUCÍCH pending řádků na schedule.shipment_date,
 *    aby identita seděla na rozvrh. Odeslaná historie a minulá data se nechávají být
 *    (zákazník už je viděl / reprezentují skutečné datum odeslání).
 *
 * Migrace je dopředná (idempotentní při opakování). down() je záměrně no-op:
 * doplněné schedule_id je korektní a neškodné, původní NULL stav byl chyba.
 */
return new class extends Migration
{
    public function up(): void
    {
        $today = Carbon::today();
        $backfilled = 0;
        $normalized = 0;
        $skipped = 0;

        DB::transaction(function () use ($today, &$backfilled, &$normalized, &$skipped) {
            SubscriptionShipment::query()
                ->orderBy('id')
                ->chunkById(200, function ($shipments) use ($today, &$backfilled, &$normalized, &$skipped) {
                    foreach ($shipments as $shipment) {
                        if (! $shipment->shipment_date) {
                            $skipped++;
                            \Log::warning('Backfill: shipment bez shipment_date, přeskočeno', [
                                'shipment_id' => $shipment->id,
                            ]);

                            continue;
                        }

                        $date = Carbon::parse($shipment->shipment_date);
                        $schedule = ShipmentSchedule::getOrCreateForMonth($date->year, $date->month);

                        // 1) Doplnit chybějící schedule_id.
                        if ($shipment->shipment_schedule_id === null) {
                            $shipment->shipment_schedule_id = $schedule->id;
                            $backfilled++;
                            \Log::info('Backfill: doplněn shipment_schedule_id', [
                                'shipment_id' => $shipment->id,
                                'shipment_date' => $date->toDateString(),
                                'shipment_schedule_id' => $schedule->id,
                            ]);
                        }

                        // 2) Normalizovat datum jen budoucích pending boxů.
                        if ($shipment->status === 'pending'
                            && $date->gte($today)
                            && $schedule->shipment_date
                            && ! $date->isSameDay($schedule->shipment_date)) {
                            $old = $date->toDateString();
                            $shipment->shipment_date = $schedule->shipment_date->copy()->startOfDay();
                            $normalized++;
                            \Log::info('Backfill: normalizováno shipment_date pending boxu', [
                                'shipment_id' => $shipment->id,
                                'from' => $old,
                                'to' => $schedule->shipment_date->toDateString(),
                            ]);
                        }

                        if ($shipment->isDirty()) {
                            $shipment->saveQuietly();
                        }
                    }
                });
        });

        \Log::info('Backfill migrace dokončena', [
            'schedule_backfilled' => $backfilled,
            'dates_normalized' => $normalized,
            'skipped' => $skipped,
        ]);
    }

    public function down(): void
    {
        // Záměrně no-op – doplněná schedule_id jsou korektní; NULL stav byl chyba,
        // kterou nechceme obnovovat. Reverzibilní je až unique index v KROKU 4.
        \Log::info('Backfill migrace down(): no-op (dopředná datová migrace)');
    }
};
