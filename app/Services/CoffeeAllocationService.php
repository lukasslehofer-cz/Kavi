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
        $amount = $configuration['amount']; // 2, 3, 4
        $type = $configuration['type']; // espresso, filter, mix
        $isDecaf = $configuration['isDecaf'] ?? false;
        $mix = $configuration['mix'] ?? null;

        // Get coffee slots from schedule
        $slots = $schedule->getCoffeeSlotsArray();

        // Validate that required slots are filled
        if (!$this->validateSlots($slots, $type, $isDecaf)) {
            throw new \Exception('Coffee slots not properly configured for this shipment schedule');
        }

        // Allocate based on type
        if ($type === 'espresso') {
            $coffees = $isDecaf 
                ? $this->allocateEspressoWithDecaf($amount, $slots)
                : $this->allocateEspresso($amount, $slots);
        } elseif ($type === 'filter') {
            $coffees = $isDecaf 
                ? $this->allocateFilterWithDecaf($amount, $slots)
                : $this->allocateFilter($amount, $slots);
        } elseif ($type === 'mix') {
            $coffees = $isDecaf 
                ? $this->allocateMixWithDecaf($amount, $mix, $slots)
                : $this->allocateMix($amount, $mix, $slots);
        } else {
            throw new \Exception('Invalid subscription type: ' . $type);
        }

        // Aggregate coffees (count occurrences)
        return $this->aggregateCoffees($coffees);
    }

    /**
     * Allocate Espresso packages
     * - 2 packages: E1, E2
     * - 3 packages: E1, E2, E3
     * - 4 packages: E1, E2, E3, E1
     */
    private function allocateEspresso(int $amount, array $slots): array
    {
        $coffees = [];

        if ($amount === 2) {
            $coffees = [$slots['e1'], $slots['e2']];
        } elseif ($amount === 3) {
            $coffees = [$slots['e1'], $slots['e2'], $slots['e3']];
        } elseif ($amount === 4) {
            $coffees = [$slots['e1'], $slots['e2'], $slots['e3'], $slots['e1']];
        }

        return array_filter($coffees); // Remove nulls
    }

    /**
     * Allocate Espresso with Decaf
     * - 2 packages: E1, D
     * - 3 packages: E1, E2, D
     * - 4 packages: E1, E2, E3, D
     */
    private function allocateEspressoWithDecaf(int $amount, array $slots): array
    {
        $coffees = [];

        if ($amount === 2) {
            $coffees = [$slots['e1'], $slots['d']];
        } elseif ($amount === 3) {
            $coffees = [$slots['e1'], $slots['e2'], $slots['d']];
        } elseif ($amount === 4) {
            $coffees = [$slots['e1'], $slots['e2'], $slots['e3'], $slots['d']];
        }

        return array_filter($coffees);
    }

    /**
     * Allocate Filter packages
     * - 2 packages: F1, F2
     * - 3 packages: F1, F2, F3
     * - 4 packages: F1, F2, F3, F1
     */
    private function allocateFilter(int $amount, array $slots): array
    {
        $coffees = [];

        if ($amount === 2) {
            $coffees = [$slots['f1'], $slots['f2']];
        } elseif ($amount === 3) {
            $coffees = [$slots['f1'], $slots['f2'], $slots['f3']];
        } elseif ($amount === 4) {
            $coffees = [$slots['f1'], $slots['f2'], $slots['f3'], $slots['f1']];
        }

        return array_filter($coffees);
    }

    /**
     * Allocate Filter with Decaf
     * - 2 packages: F1, D
     * - 3 packages: F1, F2, D
     * - 4 packages: F1, F2, F3, D
     */
    private function allocateFilterWithDecaf(int $amount, array $slots): array
    {
        $coffees = [];

        if ($amount === 2) {
            $coffees = [$slots['f1'], $slots['d']];
        } elseif ($amount === 3) {
            $coffees = [$slots['f1'], $slots['f2'], $slots['d']];
        } elseif ($amount === 4) {
            $coffees = [$slots['f1'], $slots['f2'], $slots['f3'], $slots['d']];
        }

        return array_filter($coffees);
    }

    /**
     * Allocate Mix packages
     * Start from E1/F1 and go up
     * Example: 3 packages, 2 filter + 1 espresso = F1, F2, E1
     * Example: 4 packages, 1 filter + 3 espresso = F1, E1, E2, E3
     */
    private function allocateMix(int $amount, ?array $mix, array $slots): array
    {
        $espressoCount = $mix['espresso'] ?? 0;
        $filterCount = $mix['filter'] ?? 0;

        $coffees = [];

        // Add filter coffees first
        if ($filterCount >= 1) $coffees[] = $slots['f1'];
        if ($filterCount >= 2) $coffees[] = $slots['f2'];
        if ($filterCount >= 3) $coffees[] = $slots['f3'];

        // Add espresso coffees
        if ($espressoCount >= 1) $coffees[] = $slots['e1'];
        if ($espressoCount >= 2) $coffees[] = $slots['e2'];
        if ($espressoCount >= 3) $coffees[] = $slots['e3'];

        return array_filter($coffees);
    }

    /**
     * Allocate Mix with Decaf
     * Start from E1/F1, go up, and replace the last coffee from the larger group with D
     * Example: 3 packages, 2 filter + 1 espresso = F1, D, E1
     * Example: 4 packages, 1 filter + 3 espresso = F1, E1, E2, D
     * If equal counts, replace one filter
     */
    private function allocateMixWithDecaf(int $amount, ?array $mix, array $slots): array
    {
        $espressoCount = $mix['espresso'] ?? 0;
        $filterCount = $mix['filter'] ?? 0;

        $coffees = [];

        // Determine which group to replace last item with decaf
        $replaceFilter = $filterCount >= $espressoCount;

        // Add filter coffees
        $filterToAdd = $replaceFilter ? $filterCount - 1 : $filterCount;
        if ($filterToAdd >= 1) $coffees[] = $slots['f1'];
        if ($filterToAdd >= 2) $coffees[] = $slots['f2'];
        if ($filterToAdd >= 3) $coffees[] = $slots['f3'];

        // Add decaf if we're replacing filter
        if ($replaceFilter && $filterCount > 0) {
            $coffees[] = $slots['d'];
        }

        // Add espresso coffees
        $espressoToAdd = !$replaceFilter ? $espressoCount - 1 : $espressoCount;
        if ($espressoToAdd >= 1) $coffees[] = $slots['e1'];
        if ($espressoToAdd >= 2) $coffees[] = $slots['e2'];
        if ($espressoToAdd >= 3) $coffees[] = $slots['e3'];

        // Add decaf if we're replacing espresso
        if (!$replaceFilter && $espressoCount > 0) {
            $coffees[] = $slots['d'];
        }

        return array_filter($coffees);
    }

    /**
     * Aggregate coffees - count how many times each product appears
     * 
     * @param array $coffees Array of product IDs
     * @return array Array of [product_id => quantity]
     */
    private function aggregateCoffees(array $coffees): array
    {
        $aggregated = [];

        foreach ($coffees as $productId) {
            if (empty($productId)) {
                continue;
            }

            if (!isset($aggregated[$productId])) {
                $aggregated[$productId] = 0;
            }
            $aggregated[$productId]++;
        }

        return $aggregated;
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

