<?php

namespace App\Helpers;

use App\Models\ShipmentSchedule;
use Carbon\Carbon;

/**
 * Jeden měsíční cyklus předplatného: uzávěrka objednávek a rozesílka.
 *
 * Uzávěrka platí do 23:59:59 dne billing_date (časová zóna aplikace, Europe/Prague).
 * Bez rozvrhu v adminu se použije záložní 15. / 20. den (isFallback).
 */
class ShipmentCycle
{
    public function __construct(
        public readonly int $year,
        public readonly int $month,
        public readonly Carbon $cutoffAt,
        public readonly Carbon $shipmentDate,
        public readonly ?ShipmentSchedule $schedule,
        public readonly bool $isFallback,
    ) {}

    /**
     * Klíč měsíce ve formátu Y-m (stejný jako Product.coffee_of_month_date)
     */
    public function key(): string
    {
        return sprintf('%04d-%02d', $this->year, $this->month);
    }

    /**
     * První den měsíce cyklu
     */
    public function month(): Carbon
    {
        return Carbon::create($this->year, $this->month, 1)->startOfDay();
    }

    /**
     * Krátké datum uzávěrky, např. "15. 11."
     */
    public function cutoffShort(): string
    {
        return DateFormat::dayMonth($this->cutoffAt);
    }

    /**
     * Krátké datum rozesílky, např. "20. 11."
     */
    public function shipmentShort(): string
    {
        return DateFormat::dayMonth($this->shipmentDate);
    }
}
