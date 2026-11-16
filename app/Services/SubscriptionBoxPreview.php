<?php

namespace App\Services;

use App\Helpers\ShipmentCycle;
use App\Helpers\ShipmentCycles;
use App\Models\Product;
use App\Models\Roastery;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Co přijde v dalším nezaplaceném boxu předplatného: měsíc, termíny a kávy podle pražíren.
 *
 * Jen čte. Používají ho e-maily posílané z cronu, takže nesmí zakládat řádky ledgeru
 * ani rozvrh rozesílek - getNextShipment(), getShipmentInfo() a
 * ShipmentSchedule::getOrCreateForMonth() zapisují, proto se tu nevolají.
 */
class SubscriptionBoxPreview
{
    /** Kávy přesně podle slotů rozesílky a konfigurace předplatného */
    public const SOURCE_SLOTS = 'slots';

    /** Jen pražírny měsíce (sloty ještě nejsou vyplněné), bez konkrétních káv */
    public const SOURCE_LINEUP = 'lineup';

    public function __construct(
        private SubscriptionShipmentService $shipmentService,
        private CoffeeAllocationService $allocationService,
        private CoffeeOfMonthService $coffeeOfMonth,
    ) {}

    /**
     * @return array{
     *     month: Carbon,
     *     shipment_date: Carbon,
     *     billing_date: Carbon,
     *     source: ?string,
     *     roasteries: array<int, array{name: string, place: string, slug: ?string, coffees: array<int, array{name: string, quantity: int}>}>
     * }|null Null = další box nejde určit (předplatné nemá nezaplacený řádek ani datum platby)
     */
    public function for(Subscription $subscription, string $locale): ?array
    {
        $cycle = $this->nextCycle($subscription);

        if (! $cycle) {
            return null;
        }

        // Názvy pražíren, káv a zemí se překládají podle app()->getLocale(), e-mail ale
        // z cronu odchází v jazyce příjemce
        return $this->withLocale($locale, function () use ($subscription, $cycle) {
            $roasteries = $this->fromSlots($subscription, $cycle);
            $source = $roasteries !== null ? self::SOURCE_SLOTS : null;

            if ($roasteries === null) {
                $roasteries = $this->fromLineup($cycle);
                $source = $roasteries ? self::SOURCE_LINEUP : null;
            }

            return [
                'month' => $cycle->month(),
                'shipment_date' => $cycle->shipmentDate->copy(),
                'billing_date' => $cycle->cutoffAt->copy()->startOfDay(),
                'source' => $source,
                'roasteries' => $roasteries,
            ];
        });
    }

    /**
     * Cyklus dalšího nezaplaceného boxu. U pozastaveného předplatného je to první
     * pending řádek po pauze - next_billing_date pauza neaktualizuje, proto se z něj
     * měsíc bere jen u aktivního předplatného, kterému řádek chybí.
     */
    private function nextCycle(Subscription $subscription): ?ShipmentCycle
    {
        $nextUnpaid = $this->shipmentService->nextUnpaidShipment($subscription);

        if ($nextUnpaid) {
            return ShipmentCycles::forMonth($nextUnpaid->shipment_date->year, $nextUnpaid->shipment_date->month);
        }

        // Platba strhávaná 15. platí box rozesílaný 20. téhož měsíce
        if ($subscription->isActive() && $subscription->next_billing_date) {
            return ShipmentCycles::forMonth($subscription->next_billing_date->year, $subscription->next_billing_date->month);
        }

        return null;
    }

    /**
     * Kávy, které zákazník podle své konfigurace opravdu dostane. Null, když se box
     * nedá složit celý (nevyplněný slot, káva bez pražírny) - poloviční výčet by
     * sliboval něco jiného, než co přijde.
     */
    private function fromSlots(Subscription $subscription, ShipmentCycle $cycle): ?array
    {
        if (! $cycle->schedule) {
            return null;
        }

        try {
            $required = $this->allocationService->getRequiredSlots($this->configurationFor($subscription));
        } catch (\Throwable $e) {
            return null;
        }

        if (! $required) {
            return null;
        }

        $slots = $cycle->schedule->getCoffeeSlotsArray();
        $quantities = [];

        foreach ($required as $slot => $quantity) {
            $productId = $slots[$slot] ?? null;

            if (empty($productId)) {
                return null;
            }

            // Dva sloty můžou nést stejnou kávu
            $quantities[$productId] = ($quantities[$productId] ?? 0) + $quantity;
        }

        $products = Product::with('roastery')->whereIn('id', array_keys($quantities))->get()->keyBy('id');
        $roasteries = [];

        foreach ($quantities as $productId => $quantity) {
            $product = $products->get($productId);

            if (! $product || ! $product->roastery) {
                return null;
            }

            $roasteries[$product->roastery->id] ??= $this->roasteryRow($product->roastery);
            $roasteries[$product->roastery->id]['coffees'][] = [
                'name' => $product->getName(),
                'quantity' => $quantity,
            ];
        }

        return array_values($roasteries);
    }

    /**
     * Pražírny měsíce z "Kávy měsíce" - zadávají se dřív než sloty rozesílky
     */
    private function fromLineup(ShipmentCycle $cycle): array
    {
        /** @var Collection<int, Roastery> $roasteries */
        $roasteries = $this->coffeeOfMonth->lineup($cycle->month())->displayRoasteries();

        return $roasteries->map(fn (Roastery $roastery) => $this->roasteryRow($roastery))->values()->all();
    }

    private function roasteryRow(Roastery $roastery): array
    {
        return [
            'name' => $roastery->getName(),
            'place' => collect([$roastery->getCity(), $roastery->getCountry()])->filter()->implode(', '),
            // Neaktivní pražírna nemá veřejnou stránku
            'slug' => $roastery->is_active ? $roastery->slug : null,
            'coffees' => [],
        ];
    }

    /**
     * Živá konfigurace předplatného - stejná, ze které e-mail počítá částku platby.
     * Čekající změna se sem nepromítá: použije se den po odeslání zaplaceného boxu,
     * takže v době připomínek už bývá zapsaná, a obsah by jinak neseděl s cenou.
     */
    private function configurationFor(Subscription $subscription): array
    {
        $configuration = $subscription->configuration;

        if (is_string($configuration)) {
            $configuration = json_decode($configuration, true);
        }

        return is_array($configuration) ? $configuration : [];
    }

    private function withLocale(string $locale, callable $callback): mixed
    {
        $original = app()->getLocale();
        app()->setLocale($locale);

        try {
            return $callback();
        } finally {
            app()->setLocale($original);
        }
    }
}
