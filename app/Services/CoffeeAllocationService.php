<?php

namespace App\Services;

use App\Models\ShipmentSchedule;
use App\Models\Subscription;
use App\Models\SubscriptionCoffeeAllocation;
use Illuminate\Support\Facades\DB;

class CoffeeAllocationService
{
    /**
     * Allocate coffees for a subscription based on configuration
     *
     * @param array $configuration Subscription configuration (amount, type, isDecaf, mix)
     * @param ShipmentSchedule $schedule The shipment schedule with coffee slots
     * @return array Array of [product_id => quantity]
     */
    public function allocateCoffeesForSubscription(array $configuration, ShipmentSchedule $schedule): array
    {
        $type = $configuration['type'] ?? '';
        $isDecaf = (bool) ($configuration['isDecaf'] ?? false);

        // Get coffee slots from schedule
        $slots = $schedule->getCoffeeSlotsArray();

        // Validate that required slots are filled
        if (!$this->validateSlots($slots, $type, $isDecaf)) {
            throw new \Exception('Coffee slots not properly configured for this shipment schedule');
        }

        // Map slot names to the products configured for this shipment.
        // Two slots can hold the same coffee, so quantities are summed per product.
        $aggregated = [];

        foreach ($this->getRequiredSlots($configuration) as $slotName => $quantity) {
            $productId = $slots[$slotName] ?? null;

            if (empty($productId)) {
                continue; // Slot not configured for this month
            }

            if (!isset($aggregated[$productId])) {
                $aggregated[$productId] = 0;
            }
            $aggregated[$productId] += $quantity;
        }

        return $aggregated;
    }

    /**
     * Get the coffee slots a configuration needs, independent of any shipment schedule
     *
     * This is the single source of truth for the allocation rules. It answers
     * "which slots and how many bags of each", e.g. an XL filter box returns
     * ['f1' => 2, 'f2' => 1, 'f3' => 1] because F1 is used twice.
     *
     * @param array $configuration Subscription configuration (amount, type, isDecaf, mix)
     * @return array Array of [slot_name => quantity]
     */
    public function getRequiredSlots(array $configuration): array
    {
        // Cast to integers - JSON decode may return strings
        $amount = (int) ($configuration['amount'] ?? 0); // 2, 3, 4
        $type = $configuration['type'] ?? ''; // espresso, filter, mix
        $isDecaf = (bool) ($configuration['isDecaf'] ?? false);
        $mix = $configuration['mix'] ?? null;

        if ($type === 'espresso') {
            $slots = $isDecaf
                ? $this->allocateEspressoWithDecaf($amount)
                : $this->allocateEspresso($amount);
        } elseif ($type === 'filter') {
            $slots = $isDecaf
                ? $this->allocateFilterWithDecaf($amount)
                : $this->allocateFilter($amount);
        } elseif ($type === 'mix') {
            $slots = $isDecaf
                ? $this->allocateMixWithDecaf($mix)
                : $this->allocateMix($mix);
        } else {
            throw new \Exception('Invalid subscription type: ' . $type);
        }

        return $this->countOccurrences($slots);
    }

    /**
     * Allocate Espresso packages
     * - 2 packages: E1, E2
     * - 3 packages: E1, E2, E3
     * - 4 packages: E1, E2, E3, E1
     */
    private function allocateEspresso(int $amount): array
    {
        if ($amount === 2) {
            return ['e1', 'e2'];
        } elseif ($amount === 3) {
            return ['e1', 'e2', 'e3'];
        } elseif ($amount === 4) {
            return ['e1', 'e2', 'e3', 'e1']; // E1 repeats
        }

        return [];
    }

    /**
     * Allocate Espresso with Decaf
     * - 2 packages: E1, D
     * - 3 packages: E1, E2, D
     * - 4 packages: E1, E2, E3, D
     */
    private function allocateEspressoWithDecaf(int $amount): array
    {
        if ($amount === 2) {
            return ['e1', 'd'];
        } elseif ($amount === 3) {
            return ['e1', 'e2', 'd'];
        } elseif ($amount === 4) {
            return ['e1', 'e2', 'e3', 'd'];
        }

        return [];
    }

    /**
     * Allocate Filter packages
     * - 2 packages: F1, F2
     * - 3 packages: F1, F2, F3
     * - 4 packages: F1, F2, F3, F1
     */
    private function allocateFilter(int $amount): array
    {
        if ($amount === 2) {
            return ['f1', 'f2'];
        } elseif ($amount === 3) {
            return ['f1', 'f2', 'f3'];
        } elseif ($amount === 4) {
            return ['f1', 'f2', 'f3', 'f1']; // F1 repeats
        }

        return [];
    }

    /**
     * Allocate Filter with Decaf
     * - 2 packages: F1, D
     * - 3 packages: F1, F2, D
     * - 4 packages: F1, F2, F3, D
     */
    private function allocateFilterWithDecaf(int $amount): array
    {
        if ($amount === 2) {
            return ['f1', 'd'];
        } elseif ($amount === 3) {
            return ['f1', 'f2', 'd'];
        } elseif ($amount === 4) {
            return ['f1', 'f2', 'f3', 'd'];
        }

        return [];
    }

    /**
     * Allocate Mix packages
     * Start from E1/F1 and go up
     * Example: 3 packages, 2 filter + 1 espresso = F1, F2, E1
     * Example: 4 packages, 1 filter + 3 espresso = F1, E1, E2, E3
     */
    private function allocateMix(?array $mix): array
    {
        $espressoCount = (int) ($mix['espresso'] ?? 0);
        $filterCount = (int) ($mix['filter'] ?? 0);

        $coffees = [];

        // Add filter coffees first
        if ($filterCount >= 1) $coffees[] = 'f1';
        if ($filterCount >= 2) $coffees[] = 'f2';
        if ($filterCount >= 3) $coffees[] = 'f3';

        // Add espresso coffees
        if ($espressoCount >= 1) $coffees[] = 'e1';
        if ($espressoCount >= 2) $coffees[] = 'e2';
        if ($espressoCount >= 3) $coffees[] = 'e3';

        return $coffees;
    }

    /**
     * Allocate Mix with Decaf
     * Start from E1/F1, go up, and replace the last coffee from the larger group with D
     * Example: 3 packages, 2 filter + 1 espresso = F1, D, E1
     * Example: 4 packages, 1 filter + 3 espresso = F1, E1, E2, D
     * If equal counts, replace one filter
     */
    private function allocateMixWithDecaf(?array $mix): array
    {
        $espressoCount = (int) ($mix['espresso'] ?? 0);
        $filterCount = (int) ($mix['filter'] ?? 0);

        $coffees = [];

        // Determine which group to replace last item with decaf
        $replaceFilter = $filterCount >= $espressoCount;

        // Add filter coffees
        $filterToAdd = $replaceFilter ? $filterCount - 1 : $filterCount;
        if ($filterToAdd >= 1) $coffees[] = 'f1';
        if ($filterToAdd >= 2) $coffees[] = 'f2';
        if ($filterToAdd >= 3) $coffees[] = 'f3';

        // Add decaf if we're replacing filter
        if ($replaceFilter && $filterCount > 0) {
            $coffees[] = 'd';
        }

        // Add espresso coffees
        $espressoToAdd = !$replaceFilter ? $espressoCount - 1 : $espressoCount;
        if ($espressoToAdd >= 1) $coffees[] = 'e1';
        if ($espressoToAdd >= 2) $coffees[] = 'e2';
        if ($espressoToAdd >= 3) $coffees[] = 'e3';

        // Add decaf if we're replacing espresso
        if (!$replaceFilter && $espressoCount > 0) {
            $coffees[] = 'd';
        }

        return $coffees;
    }

    /**
     * Count how many times each value appears
     *
     * @param array $values Array of slot names
     * @return array Array of [slot_name => quantity]
     */
    private function countOccurrences(array $values): array
    {
        $counted = [];

        foreach ($values as $value) {
            if (empty($value)) {
                continue;
            }

            if (!isset($counted[$value])) {
                $counted[$value] = 0;
            }
            $counted[$value]++;
        }

        return $counted;
    }

    /**
     * Validate that required slots are filled
     */
    private function validateSlots(array $slots, string $type, bool $isDecaf): bool
    {
        // Always need E1, E2, F1, F2
        if (empty($slots['e1']) || empty($slots['e2']) || 
            empty($slots['f1']) || empty($slots['f2'])) {
            return false;
        }

        // If decaf is required, check decaf slot
        if ($isDecaf && empty($slots['d'])) {
            return false;
        }

        return true;
    }

    /**
     * Store allocations for a subscription
     * 
     * @param Subscription $subscription
     * @param ShipmentSchedule $schedule
     * @param array $allocations [product_id => quantity]
     */
    public function storeAllocations(Subscription $subscription, ShipmentSchedule $schedule, array $allocations): void
    {
        DB::transaction(function () use ($subscription, $schedule, $allocations) {
            foreach ($allocations as $productId => $quantity) {
                SubscriptionCoffeeAllocation::create([
                    'subscription_id' => $subscription->id,
                    'shipment_schedule_id' => $schedule->id,
                    'coffee_product_id' => $productId,
                    'quantity' => $quantity,
                    'status' => 'allocated',
                    'allocated_at' => now(),
                ]);
            }
        });
    }

    /**
     * Cancel allocations for a subscription
     */
    public function cancelAllocations(Subscription $subscription, ShipmentSchedule $schedule): void
    {
        SubscriptionCoffeeAllocation::where('subscription_id', $subscription->id)
            ->where('shipment_schedule_id', $schedule->id)
            ->where('status', 'allocated')
            ->update(['status' => 'cancelled']);
    }

    /**
     * Mark allocations as shipped
     */
    public function markAllocationsAsShipped(Subscription $subscription, ShipmentSchedule $schedule): void
    {
        SubscriptionCoffeeAllocation::where('subscription_id', $subscription->id)
            ->where('shipment_schedule_id', $schedule->id)
            ->where('status', 'allocated')
            ->update(['status' => 'shipped']);
    }
}

