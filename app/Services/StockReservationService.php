<?php

namespace App\Services;

use App\Helpers\SubscriptionHelper;
use App\Models\ShipmentSchedule;
use App\Models\StockReservation;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockReservationService
{
    public function __construct(
        private CoffeeAllocationService $allocationService
    ) {}

    /**
     * Update reservations for a specific shipment schedule
     * This recalculates all reservations based on active subscriptions
     * 
     * @param ShipmentSchedule $schedule
     */
    public function updateReservationsForSchedule(ShipmentSchedule $schedule): void
    {
        if ($schedule->isPast()) {
            $hasSentShipments = \App\Models\SubscriptionShipment::where('shipment_schedule_id', $schedule->id)
                ->where('status', 'sent')
                ->exists();

            if ($hasSentShipments) {
                Log::warning('Attempted to recalculate reservations for already-shipped schedule, finalizing instead', [
                    'schedule_id' => $schedule->id,
                    'month' => $schedule->month . '/' . $schedule->year,
                ]);
                $this->finalizeReservationsForSchedule($schedule);
                return;
            }
        }

        if (!$schedule->hasCoffeeSlotsConfigured()) {
            Log::info('Skipping reservation update - coffee slots not configured', [
                'schedule_id' => $schedule->id,
                'month' => $schedule->month . '/' . $schedule->year,
            ]);
            return;
        }

        DB::transaction(function () use ($schedule) {
            // Get all subscriptions that should ship on this date
            $subscriptions = $this->getSubscriptionsForShipment($schedule);

            Log::info('Updating stock reservations', [
                'schedule_id' => $schedule->id,
                'year' => $schedule->year,
                'month' => $schedule->month,
                'subscriptions_count' => $subscriptions->count(),
            ]);

            // STEP 1: Restore stock from old reservations before deleting them
            $oldReservations = StockReservation::where('shipment_schedule_id', $schedule->id)->get();
            
            foreach ($oldReservations as $oldReservation) {
                $product = \App\Models\Product::find($oldReservation->product_id);
                if ($product) {
                    // Return reserved quantity back to stock
                    $product->stock += $oldReservation->actual_quantity;
                    $product->save();
                    
                    Log::info('Released stock from old reservation', [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'released_quantity' => $oldReservation->actual_quantity,
                        'new_stock' => $product->stock,
                    ]);
                }
            }

            // Delete old reservations for this schedule
            StockReservation::where('shipment_schedule_id', $schedule->id)->delete();

            // Aggregate coffee needs
            $reservations = [];

            foreach ($subscriptions as $subscription) {
                try {
                    $coffees = $this->allocationService->allocateCoffeesForSubscription(
                        $subscription->configuration,
                        $schedule
                    );

                    foreach ($coffees as $productId => $quantity) {
                        if (!isset($reservations[$productId])) {
                            $reservations[$productId] = 0;
                        }
                        $reservations[$productId] += $quantity;
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to allocate coffees for subscription', [
                        'subscription_id' => $subscription->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // STEP 2: Create new reservations and deduct from stock
            foreach ($reservations as $productId => $quantity) {
                StockReservation::create([
                    'product_id' => $productId,
                    'shipment_schedule_id' => $schedule->id,
                    'reserved_quantity' => $quantity,
                    'actual_quantity' => $quantity,
                ]);
                
                // Deduct reserved quantity from stock
                $product = \App\Models\Product::find($productId);
                if ($product) {
                    $product->stock -= $quantity;
                    $product->save();
                    
                    Log::info('Reserved stock for new reservation', [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'reserved_quantity' => $quantity,
                        'new_stock' => $product->stock,
                    ]);
                }
            }

            Log::info('Stock reservations updated', [
                'schedule_id' => $schedule->id,
                'products_count' => count($reservations),
                'total_packages' => array_sum($reservations),
            ]);
        });
    }

    /**
     * Get all subscriptions that should ship on the given schedule date.
     * Uses two-source approach (mirroring calculateShipmentStats in admin):
     * 1. Subscriptions with existing SubscriptionShipment records (already scheduled)
     * 2. Additional subscriptions matching cadence via shouldShipOn
     */
    private function getSubscriptionsForShipment(ShipmentSchedule $schedule): \Illuminate\Support\Collection
    {
        $billingDate = ShipmentSchedule::getBillingDateForMonth($schedule->year, $schedule->month);

        // Source 1: Subscriptions with existing pending/sent shipment records
        $shipmentSubscriptionIds = \App\Models\SubscriptionShipment::whereDate('shipment_date', $schedule->shipment_date->toDateString())
            ->whereIn('status', ['pending', 'sent', 'delivered'])
            ->where(function($q) use ($billingDate) {
                $q->whereNotNull('subscription_payment_id')
                  ->orWhereHas('subscription', function($q2) use ($billingDate) {
                      $q2->where(function($q3) use ($billingDate) {
                          $q3->where('status', '!=', 'paused')
                             ->orWhereNull('paused_until_date')
                             ->orWhere('paused_until_date', '<=', $billingDate);
                      });
                  });
            })
            ->pluck('subscription_id')
            ->unique();

        // Source 2: Additional subscriptions matching cadence (not yet having a shipment record)
        $additionalSubscriptions = Subscription::with(['user', 'plan'])
            ->whereIn('status', ['active', 'paused', 'pending', 'cancelled', 'complimentary'])
            ->whereNotIn('id', $shipmentSubscriptionIds)
            ->where(function($q) use ($billingDate) {
                $q->where('status', '!=', 'paused')
                  ->orWhereNull('paused_until_date')
                  ->orWhere('paused_until_date', '<=', $billingDate);
            })
            ->get()
            ->filter(function ($subscription) use ($schedule) {
                return $subscription->shouldShipOn($schedule->shipment_date);
            });

        // Combine both sources
        $allIds = $shipmentSubscriptionIds->merge($additionalSubscriptions->pluck('id'))->unique();

        return Subscription::with(['user', 'plan'])->whereIn('id', $allIds)->get();
    }

    /**
     * Check if a specific subscription configuration can be fulfilled
     *
     * Unlike checkTypeAvailability() this is quantity aware - it knows that an XL
     * filter box needs F1 twice, and that L/XL boxes reach into E3/F3 at all.
     *
     * @param array $configuration
     * @param ShipmentSchedule $schedule
     * @return array ['available' => bool, 'out_of_stock' => array of product names, 'missing_slots' => array of slot names]
     */
    public function checkAvailability(array $configuration, ShipmentSchedule $schedule): array
    {
        try {
            $slots = $schedule->getCoffeeSlotsArray();
            $needs = $this->resolveProductNeeds($configuration, $slots);

            // A slot this configuration reaches into is not filled for this month,
            // or the rules cannot produce the promised number of bags. Either way
            // selling it would silently ship the customer short.
            if (!empty($needs['missing_slots']) || !$this->allocationIsComplete($configuration, $needs)) {
                return [
                    'available' => false,
                    'out_of_stock' => [],
                    'missing_slots' => $needs['missing_slots'],
                ];
            }

            $products = \App\Models\Product::whereIn('id', array_keys($needs['products']))->get()->keyBy('id');

            $outOfStock = [];

            foreach ($needs['products'] as $productId => $neededQuantity) {
                $product = $products->get($productId);

                if (!$product) {
                    $outOfStock[] = '#'.$productId;
                    continue;
                }

                // Stock already has reservations deducted, so it is the available quantity
                if ($product->stock < $neededQuantity) {
                    $outOfStock[] = $product->name;
                }
            }

            return [
                'available' => empty($outOfStock),
                'out_of_stock' => $outOfStock,
                'missing_slots' => [],
            ];
        } catch (\Exception $e) {
            Log::error('Failed to check availability', [
                'configuration' => $configuration,
                'schedule_id' => $schedule->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'available' => false,
                'out_of_stock' => ['Chyba při kontrole dostupnosti'],
                'missing_slots' => [],
            ];
        }
    }

    /**
     * Build the full availability matrix for a shipment
     *
     * Every purchasable combination is evaluated so the configurator can disable
     * exactly the box size / split that cannot be fulfilled, instead of only
     * knowing whether a coffee type is sold out as a whole.
     *
     * Shape:
     *   'espresso' => [2 => ['plain' => bool, 'decaf' => bool], 3 => [...], 4 => [...]]
     *   'filter'   => same
     *   'mix'      => [2 => ['plain' => [filterCount => bool], 'decaf' => [...]], ...]
     *
     * @param ShipmentSchedule $schedule
     * @return array
     */
    public function getAvailabilityMatrix(ShipmentSchedule $schedule): array
    {
        $slots = $schedule->getCoffeeSlotsArray();

        return $this->buildAvailabilityMatrix($slots, $this->getStockBySlotProduct($slots));
    }

    /**
     * Check which subscription types are available for the next shipment
     *
     * A type counts as available when at least one of its box sizes can be
     * fulfilled - the configurator disables the individual sizes from the matrix.
     *
     * @param ShipmentSchedule $schedule
     * @return array ['espresso' => bool, 'filter' => bool, 'decaf' => bool, 'mix' => bool]
     */
    public function checkTypeAvailability(ShipmentSchedule $schedule): array
    {
        // Check if coffee slots are configured
        if (!$schedule->hasCoffeeSlotsConfigured()) {
            return [
                'espresso' => false,
                'filter' => false,
                'decaf' => false,
                'mix' => false,
            ];
        }

        $slots = $schedule->getCoffeeSlotsArray();
        $stock = $this->getStockBySlotProduct($slots);
        $matrix = $this->buildAvailabilityMatrix($slots, $stock);

        // Decaf never adds a bag, it replaces one - a single D bag is enough
        $decafAvailable = !empty($slots['d']) && ($stock[$slots['d']] ?? 0) > 0;

        return [
            'espresso' => $this->hasAvailableCombination($matrix['espresso']),
            'filter' => $this->hasAvailableCombination($matrix['filter']),
            'decaf' => $decafAvailable,
            'mix' => $this->hasAvailableCombination($matrix['mix']),
        ];
    }

    /**
     * Translate a configuration into per-product quantities for a given set of slots
     *
     * Two slots can hold the same coffee, so quantities are summed per product.
     *
     * @return array ['products' => [product_id => quantity], 'missing_slots' => array of slot names, 'bags' => int]
     */
    private function resolveProductNeeds(array $configuration, array $slots): array
    {
        $products = [];
        $missingSlots = [];
        $bags = 0;

        foreach ($this->allocationService->getRequiredSlots($configuration) as $slotName => $quantity) {
            $bags += $quantity;

            $productId = $slots[$slotName] ?? null;

            if (empty($productId)) {
                $missingSlots[] = $slotName;
                continue;
            }

            if (!isset($products[$productId])) {
                $products[$productId] = 0;
            }
            $products[$productId] += $quantity;
        }

        return [
            'products' => $products,
            'missing_slots' => $missingSlots,
            'bags' => $bags,
        ];
    }

    /**
     * Does the allocation actually produce the number of bags the box promises?
     *
     * Guards against configurations the rules cannot express - an all-filter mix
     * only reaches F1-F3, so a 4 bag mix without espresso would ship one bag short.
     */
    private function allocationIsComplete(array $configuration, array $needs): bool
    {
        return $needs['bags'] === (int) ($configuration['amount'] ?? 0);
    }

    /**
     * Load the stock of every product used by the shipment slots in a single query
     *
     * @return array [product_id => stock]
     */
    private function getStockBySlotProduct(array $slots): array
    {
        $productIds = array_values(array_unique(array_filter($slots)));

        if (empty($productIds)) {
            return [];
        }

        return \App\Models\Product::whereIn('id', $productIds)
            ->pluck('stock', 'id')
            ->map(fn ($stock) => (int) $stock)
            ->all();
    }

    /**
     * Evaluate every purchasable combination in memory
     */
    private function buildAvailabilityMatrix(array $slots, array $stock): array
    {
        $matrix = [
            'espresso' => [],
            'filter' => [],
            'mix' => [],
        ];

        foreach ([2, 3, 4] as $amount) {
            foreach (['espresso', 'filter'] as $type) {
                $matrix[$type][$amount] = [
                    'plain' => $this->configurationFits(
                        ['amount' => $amount, 'type' => $type, 'isDecaf' => false],
                        $slots,
                        $stock
                    ),
                    'decaf' => $this->configurationFits(
                        ['amount' => $amount, 'type' => $type, 'isDecaf' => true],
                        $slots,
                        $stock
                    ),
                ];
            }

            $matrix['mix'][$amount] = ['plain' => [], 'decaf' => []];

            // Keyed by the number of filter bags in the split; espresso takes the rest
            for ($filterCount = 0; $filterCount <= $amount; $filterCount++) {
                $mix = ['filter' => $filterCount, 'espresso' => $amount - $filterCount];

                foreach (['plain' => false, 'decaf' => true] as $key => $isDecaf) {
                    $matrix['mix'][$amount][$key][$filterCount] = $this->configurationFits(
                        ['amount' => $amount, 'type' => 'mix', 'isDecaf' => $isDecaf, 'mix' => $mix],
                        $slots,
                        $stock
                    );
                }
            }
        }

        return $matrix;
    }

    /**
     * Can this exact configuration be fulfilled from the given slots and stock?
     */
    private function configurationFits(array $configuration, array $slots, array $stock): bool
    {
        $needs = $this->resolveProductNeeds($configuration, $slots);

        if (!empty($needs['missing_slots']) || !$this->allocationIsComplete($configuration, $needs)) {
            return false;
        }

        foreach ($needs['products'] as $productId => $neededQuantity) {
            if (($stock[$productId] ?? 0) < $neededQuantity) {
                return false;
            }
        }

        return true;
    }

    /**
     * Is at least one combination in this branch of the matrix available?
     */
    private function hasAvailableCombination(array $branch): bool
    {
        foreach ($branch as $value) {
            if (is_array($value)) {
                if ($this->hasAvailableCombination($value)) {
                    return true;
                }
            } elseif ($value) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get coffee usage statistics for admin dashboard
     * 
     * @param ShipmentSchedule $schedule
     * @return array
     */
    public function getCoffeeUsageStats(ShipmentSchedule $schedule): array
    {
        $reservations = StockReservation::with(['product.roastery', 'shipmentSchedule'])
            ->where('shipment_schedule_id', $schedule->id)
            ->get();

        $stats = [];
        $slots = $schedule->getCoffeeSlotsArray();

        // Create reverse mapping (product_id => slots)
        $productSlots = [];
        foreach ($slots as $slotName => $productId) {
            if ($productId) {
                if (!isset($productSlots[$productId])) {
                    $productSlots[$productId] = [];
                }
                $productSlots[$productId][] = strtoupper($slotName);
            }
        }

        foreach ($reservations as $reservation) {
            $product = $reservation->product;
            if (!$product) {
                continue;
            }

            // Note: product.stock now already has reservations deducted
            // So stock represents actual available quantity
            $stats[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'roastery_name' => $product->roastery ? $product->roastery->name : 'N/A',
                'slots' => $productSlots[$product->id] ?? [],
                'reserved' => $reservation->actual_quantity,
                'stock' => $product->stock, // This is already the remaining stock after reservations
                // Status is insufficient if stock is 0 or less
                'status' => $product->stock > 0 ? 'ok' : 'insufficient',
            ];
        }

        // Sort by stock (lowest first)
        usort($stats, function ($a, $b) {
            return $a['stock'] <=> $b['stock'];
        });

        return $stats;
    }

    /**
     * Finalize (consume) reservations for a past schedule whose shipments have been sent.
     * Deletes StockReservation records WITHOUT releasing stock back to the products,
     * permanently locking in the stock deduction after physical shipping.
     */
    public function finalizeReservationsForSchedule(ShipmentSchedule $schedule): void
    {
        $deleted = StockReservation::where('shipment_schedule_id', $schedule->id)->delete();

        if ($deleted > 0) {
            Log::info('Finalized (consumed) reservations for shipped schedule', [
                'schedule_id' => $schedule->id,
                'month' => $schedule->month . '/' . $schedule->year,
                'deleted_records' => $deleted,
            ]);
        }
    }

    /**
     * Update reservations for all upcoming shipments
     * This should be called via cron job on the 16th of each month
     */
    public function updateAllUpcomingReservations(): void
    {
        $today = now();

        // Finalize past schedules that still have lingering reservations
        $pastScheduleIds = StockReservation::select('shipment_schedule_id')
            ->distinct()
            ->pluck('shipment_schedule_id');

        foreach ($pastScheduleIds as $scheduleId) {
            $schedule = ShipmentSchedule::find($scheduleId);
            if ($schedule && $schedule->isPast()) {
                $this->finalizeReservationsForSchedule($schedule);
            }
        }

        // Get current month's schedule if billing hasn't passed yet
        $currentSchedule = ShipmentSchedule::getForMonth($today->year, $today->month);
        
        if ($currentSchedule && $today->lessThan($currentSchedule->billing_date)) {
            $this->updateReservationsForSchedule($currentSchedule);
        }

        // Get next month's schedule
        $nextMonth = $today->copy()->addMonthNoOverflow();
        $nextSchedule = ShipmentSchedule::getForMonth($nextMonth->year, $nextMonth->month);
        
        if ($nextSchedule) {
            $this->updateReservationsForSchedule($nextSchedule);
        }

        Log::info('Updated all upcoming reservations via cron job');
    }
}

