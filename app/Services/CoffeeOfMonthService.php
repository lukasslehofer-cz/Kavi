<?php

namespace App\Services;

use App\Helpers\SubscriptionHelper;
use App\Models\Product;
use App\Models\Roastery;
use App\Models\ShipmentSchedule;
use Carbon\Carbon;

/**
 * Jediné místo, které určuje "Kávu měsíce" (pražírny + kávy) pro web.
 *
 * Měsíc bere z SubscriptionHelper::getOrderingTargetMonth(), tedy ze stejné uzávěrky,
 * podle které se řídí objednávky. Dřív si to homepage počítala vlastním pravidlem
 * "den >= 16" a štítek měsíce se mohl lišit od zobrazených káv.
 */
class CoffeeOfMonthService
{
    /** @var array<string, MonthlyLineup> */
    private array $memo = [];

    public function targetMonth(): Carbon
    {
        return SubscriptionHelper::getOrderingTargetMonth()->copy()->startOfMonth();
    }

    public function lineup(?Carbon $month = null): MonthlyLineup
    {
        $month = ($month ?? $this->targetMonth())->copy()->startOfMonth();
        $key = $month->format('Y-m');

        return $this->memo[$key] ??= new MonthlyLineup(
            $month,
            ShipmentSchedule::getForMonth($month->year, $month->month),
            $this->roasteriesFor($key),
            $this->coffeesFor($key),
        );
    }

    public function roasteriesFor(string $monthKey)
    {
        return Roastery::where('featured_month', $monthKey)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function coffeesFor(string $monthKey)
    {
        return Product::with('roastery')
            ->where('is_coffee_of_month', true)
            ->where('coffee_of_month_date', $monthKey)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }
}
