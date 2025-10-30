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
     * Get all subscriptions that should ship on the given schedule date
     */
    private function getSubscriptionsForShipment(ShipmentSchedule $schedule): \Illuminate\Support\Collection
    {
        // Get all active or paused subscriptions
        $allSubscriptions = Subscription::with(['user', 'plan'])
            ->whereIn('status', ['active', 'paused'])
            ->get();

        // Filter by shipment date
        return $allSubscriptions->filter(function ($subscription) use ($schedule) {
            return $subscription->shouldShipOn($schedule->shipment_date);
        });
    }

    /**
     * Check if a new subscription configuration is available
     * 
     * @param array $configuration
     * @param ShipmentSchedule $schedule
     * @return array ['available' => bool, 'out_of_stock' => array of product names]
     */
    public function checkAvailability(array $configuration, ShipmentSchedule $schedule): array
    {
        try {
            // Calculate what coffees this subscription would need
            $neededCoffees = $this->allocationService->allocateCoffeesForSubscription(
                $configuration,
                $schedule
            );

            $outOfStock = [];

            foreach ($neededCoffees as $productId => $neededQuantity) {
                // Get current reservations
                $reservation = StockReservation::where('product_id', $productId)
                    ->where('shipment_schedule_id', $schedule->id)
                    ->first();

                $currentlyReserved = $reservation ? $reservation->actual_quantity : 0;

                // Get product stock
                $product = \App\Models\Product::find($productId);
                if (!$product) {
                    continue;
                }

                $availableStock = $product->stock - $currentlyReserved;

                if ($availableStock < $neededQuantity) {
                    $outOfStock[] = $product->name;
                }
            }

            return [
                'available' => empty($outOfStock),
                'out_of_stock' => $outOfStock,
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
            ];
        }
    }

    /**
     * Check which subscription types are available for the next shipment
     * 
     * @param ShipmentSchedule $schedule
     * @return array ['espresso' => bool, 'filter' => bool, 'decaf' => bool]
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

        // Check espresso availability (need E1, E2)
        $espressoAvailable = $this->checkSlotAvailability([$slots['e1'], $slots['e2']], $schedule);

        // Check filter availability (need F1, F2)
        $filterAvailable = $this->checkSlotAvailability([$slots['f1'], $slots['f2']], $schedule);

        // Check decaf availability (need D)
        $decafAvailable = !empty($slots['d']) && $this->checkSlotAvailability([$slots['d']], $schedule);

        // Mix is available if both espresso and filter are available
        $mixAvailable = $espressoAvailable && $filterAvailable;

        return [
            'espresso' => $espressoAvailable,
            'filter' => $filterAvailable,
            'decaf' => $decafAvailable,
            'mix' => $mixAvailable,
        ];
    }

    /**
     * Check if specific coffee slots have available stock
     * Returns false if ANY slot has 0 or negative stock
     * 
     * Note: product.stock now already has reservations deducted,
     * so we just check if stock > 0
     */
    private function checkSlotAvailability(array $productIds, ShipmentSchedule $schedule): bool
    {
        foreach ($productIds as $productId) {
            if (empty($productId)) {
                return false; // Slot not configured
            }

            $product = \App\Models\Product::find($productId);
            if (!$product) {
                return false;
            }

            // Stock already has reservations deducted, so just check if > 0
            if ($product->stock <= 0) {
                \Log::info('Slot unavailable due to insufficient stock', [
                    'product_id' => $productId,
                    'product_name' => $product->name,
                    'stock' => $product->stock,
                ]);
                return false;
            }
        }

        return true;
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
     * Update reservations for all upcoming shipments
     * This should be called via cron job on the 16th of each month
     */
    public function updateAllUpcomingReservations(): void
    {
        $today = now();
        
        // Get current month's schedule if billing hasn't passed yet
        $currentSchedule = ShipmentSchedule::getForMonth($today->year, $today->month);
        
        if ($currentSchedule && $today->lessThan($currentSchedule->billing_date)) {
            $this->updateReservationsForSchedule($currentSchedule);
        }

        // Get next month's schedule
        $nextMonth = $today->copy()->addMonth();
        $nextSchedule = ShipmentSchedule::getForMonth($nextMonth->year, $nextMonth->month);
        
        if ($nextSchedule) {
            $this->updateReservationsForSchedule($nextSchedule);
        }

        Log::info('Updated all upcoming reservations via cron job');
    }
}

