<?php

namespace App\Helpers;

use App\Models\ShipmentSchedule;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Nadcházející cykly předplatného (uzávěrka + rozesílka) z ShipmentSchedule.
 *
 * Jediný zdroj dat pro odpočet v konfigurátoru, "Kdy začít" u dárků a data
 * na landing page. Začíná měsícem z SubscriptionHelper::getOrderingTargetMonth(),
 * takže se vždy shoduje s tím, kam spadne nová objednávka.
 * Rozvrh jen čte (getForMonth), nikdy ho při zobrazení stránky nezakládá.
 */
class ShipmentCycles
{
    /**
     * Cyklus, do kterého spadne objednávka vytvořená teď
     */
    public static function current(?Carbon $now = null): ShipmentCycle
    {
        return static::upcoming(1, $now)->first();
    }

    /**
     * @return Collection<int, ShipmentCycle>
     */
    public static function upcoming(int $count = 3, ?Carbon $now = null): Collection
    {
        $now = $now ? $now->copy() : Carbon::now();
        $month = SubscriptionHelper::getOrderingTargetMonth($now)->copy()->startOfMonth();

        $cycles = collect();
        $guard = 0;

        while ($cycles->count() < $count && $guard++ < $count + 12) {
            $cycle = static::forMonth($month->year, $month->month);

            // Pojistka proti špatně zadanému rozvrhu: cyklus s uzávěrkou v minulosti
            // by v odpočtu znamenal nekonečné obnovování stránky.
            if ($cycle->cutoffAt->greaterThan($now)) {
                $cycles->push($cycle);
            }

            $month->addMonthNoOverflow();
        }

        return $cycles;
    }

    public static function forMonth(int $year, int $month): ShipmentCycle
    {
        $schedule = ShipmentSchedule::getForMonth($year, $month);

        $billing = $schedule?->billing_date?->copy() ?? Carbon::create($year, $month, 15);
        $shipment = $schedule?->shipment_date?->copy() ?? Carbon::create($year, $month, 20);

        return new ShipmentCycle(
            $year,
            $month,
            $billing->endOfDay(),
            $shipment->startOfDay(),
            $schedule,
            $schedule === null,
        );
    }
}
