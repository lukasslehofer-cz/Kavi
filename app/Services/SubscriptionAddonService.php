<?php

namespace App\Services;

use App\Helpers\SubscriptionHelper;
use App\Models\Order;
use App\Models\ShipmentSchedule;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;

class SubscriptionAddonService
{
    const MAX_ADDON_SLOTS = 3;

    /**
     * Zjistit dostupné sloty pro konkrétní předplatné uživatele
     *
     * @param User $user
     * @param Subscription|null $subscription Konkrétní předplatné, nebo null pro první aktivní
     * @return array
     */
    public function getAvailableSlots(User $user, ?Subscription $subscription = null): array
    {
        // Pokud není specifikováno předplatné, použij první aktivní
        if (!$subscription) {
            $subscription = $user->activeSubscription;
        }

        if (!$subscription || !$subscription->isActive()) {
            return [
                'available' => 0,
                'used' => 0,
                'max' => 0,
                'subscription' => null,
                'next_shipment' => null,
            ];
        }

        $nextShipment = $this->getNextShipmentSchedule($subscription);
        if (!$nextShipment) {
            return [
                'available' => 0,
                'used' => 0,
                'max' => 0,
                'subscription' => $subscription,
                'next_shipment' => null,
            ];
        }

        // Sečíst využité sloty ze všech objednávek pro tuto rozesílku a toto předplatné
        $usedSlots = Order::where('subscription_id', $subscription->id)
            ->where('shipment_schedule_id', $nextShipment->id)
            ->where('shipped_with_subscription', true)
            ->sum('subscription_addon_slots_used');

        return [
            'available' => max(0, self::MAX_ADDON_SLOTS - $usedSlots),
            'used' => $usedSlots,
            'max' => self::MAX_ADDON_SLOTS,
            'subscription' => $subscription,
            'next_shipment' => $nextShipment,
        ];
    }

    /**
     * Získat dostupné sloty pro všechna aktivní předplatná uživatele
     *
     * @param User $user
     * @return array Array of slots info for each subscription
     */
    public function getAllAvailableSlots(User $user): array
    {
        $activeSubscriptions = $user->activeSubscriptions()->get();
        
        if ($activeSubscriptions->isEmpty()) {
            return [];
        }

        $result = [];
        foreach ($activeSubscriptions as $subscription) {
            $slots = $this->getAvailableSlots($user, $subscription);
            if ($slots['next_shipment']) {
                $result[] = $slots;
            }
        }

        return $result;
    }

    /**
     * Zkontrolovat, zda uživatel může přidat N kusů k předplatnému
     *
     * @param User $user
     * @param int $quantity
     * @param Subscription|null $subscription
     * @return bool
     */
    public function canAddItems(User $user, int $quantity, ?Subscription $subscription = null): bool
    {
        $slots = $this->getAvailableSlots($user, $subscription);
        return $slots['available'] >= $quantity;
    }

    /**
     * Získat příští rozesílku pro předplatné
     * 
     * Po uzávěrce (billing_date) se zobrazí následující rozesílka podle frekvence předplatného.
     *
     * @param Subscription $subscription
     * @return ShipmentSchedule|null
     */
    public function getNextShipmentSchedule(Subscription $subscription): ?ShipmentSchedule
    {
        $nextShipmentDate = SubscriptionHelper::calculateNextShipmentDate($subscription);
        
        if (!$nextShipmentDate) {
            return null;
        }

        $schedule = ShipmentSchedule::where('shipment_date', $nextShipmentDate->format('Y-m-d'))->first();
        
        if (!$schedule) {
            return null;
        }
        
        // Check if we're past the billing cutoff for this shipment
        $today = Carbon::now()->startOfDay();
        if ($today->gt($schedule->billing_date)) {
            // Past billing cutoff - show the NEXT shipment based on subscription frequency
            $frequencyMonths = max(1, (int)($subscription->frequency_months ?? 1));
            $followingMonth = $nextShipmentDate->copy()->addMonths($frequencyMonths);
            
            return ShipmentSchedule::getForMonth($followingMonth->year, $followingMonth->month);
        }

        return $schedule;
    }

    /**
     * Zkontrolovat, zda má uživatel alespoň jedno aktivní předplatné
     *
     * @param User $user
     * @return bool
     */
    public function hasActiveSubscription(User $user): bool
    {
        return $user->activeSubscriptions()->exists();
    }

    /**
     * Získat informace o doplňkovém zboží pro objednávku (pro zobrazení)
     *
     * @param Order $order
     * @return array|null
     */
    public function getAddonInfoForOrder(Order $order): ?array
    {
        if (!$order->shipped_with_subscription) {
            return null;
        }

        return [
            'is_addon' => true,
            'subscription' => $order->subscription,
            'shipment_schedule' => $order->shipmentSchedule,
            'slots_used' => $order->subscription_addon_slots_used,
            'shipment_date' => $order->shipmentSchedule?->shipment_date,
        ];
    }
}

