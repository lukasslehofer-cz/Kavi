<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Roastery;
use App\Models\ShipmentSchedule;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Pražírny a kávy jednoho měsíčního boxu (výsledek CoffeeOfMonthService::lineup()).
 */
class MonthlyLineup
{
    public function __construct(
        public readonly Carbon $month,
        public readonly ?ShipmentSchedule $schedule,
        public readonly Collection $roasteries,
        public readonly Collection $coffees,
    ) {}

    public function key(): string
    {
        return $this->month->format('Y-m');
    }

    public function isEmpty(): bool
    {
        return $this->roasteries->isEmpty() && $this->coffees->isEmpty();
    }

    /**
     * Pražírny k zobrazení: nastavené na měsíc, a když chybí, odvozené z káv
     */
    public function displayRoasteries(int $limit = 3): Collection
    {
        $roasteries = $this->roasteries->isNotEmpty()
            ? $this->roasteries
            : $this->coffees->pluck('roastery')->filter()->unique('id')->values();

        return $roasteries->take($limit);
    }

    /**
     * @return Collection<int, Product>
     */
    public function coffeesFor(Roastery $roastery): Collection
    {
        return $this->coffees->where('roastery_id', $roastery->id)->values();
    }

    /**
     * Promo fotka měsíce (ShipmentSchedule.promo_image), bez fallbacku
     */
    public function promoImage(): ?string
    {
        return $this->schedule?->promo_image;
    }

    /**
     * Název měsíce v požadovaném tvaru, viz lang/{cs,en}/months.php
     * (nominative "říjen", adjective "říjnový", adjective_locative "říjnovém")
     */
    public function monthName(string $form = 'nominative', ?string $locale = null): string
    {
        return __('months.' . $form . '.' . $this->month->month, [], $locale);
    }

    /**
     * "říjen 2026" / "October 2026"
     */
    public function monthWithYear(?string $locale = null): string
    {
        return $this->monthName('nominative', $locale) . ' ' . $this->month->year;
    }

    /**
     * Názvy pražíren oddělené čárkou (eyebrow v hero)
     */
    public function roasteryNames(int $limit = 3): string
    {
        return $this->displayRoasteries($limit)->map(fn (Roastery $r) => $r->getName())->implode(', ');
    }
}
