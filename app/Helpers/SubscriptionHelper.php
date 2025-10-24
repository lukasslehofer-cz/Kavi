<?php

namespace App\Helpers;

use Carbon\Carbon;

class SubscriptionHelper
{
    /**
     * Get the next shipping date (always 20th of the month)
     * If today is 16th or later, it's 20th of next month
     * If today is 15th or earlier, it's 20th of this month
     */
    public static function getNextShippingDate(): Carbon
    {
        $today = Carbon::now();
        $dayOfMonth = $today->day;
        
        if ($dayOfMonth >= 16) {
            // Order is for next period
            return Carbon::now()->addMonth()->day(20)->startOfDay();
        } else {
            // Order is for current period
            return Carbon::now()->day(20)->startOfDay();
        }
    }

    /**
     * Get the next billing cycle end date (always 15th of the month at midnight)
     */
    public static function getNextBillingCycleEnd(): Carbon
    {
        $today = Carbon::now();
        $dayOfMonth = $today->day;
        
        if ($dayOfMonth >= 16) {
            // Next cycle ends on 15th of next month
            return Carbon::now()->addMonth()->day(15)->endOfDay();
        } else {
            // Current cycle ends on 15th of this month
            return Carbon::now()->day(15)->endOfDay();
        }
    }

    /**
     * Calculate when a subscription should have its next shipment
     * based on frequency (1, 2, or 3 months)
     */
    public static function calculateNextShipmentDate($subscription): ?Carbon
    {
        $frequencyMonths = $subscription->frequency_months ?? 1;
        $lastShipmentDate = $subscription->last_shipment_date;
        
        if (!$lastShipmentDate) {
            // First shipment - use creation logic
            $createdAt = $subscription->created_at;
            
            if ($createdAt->day >= 16) {
                // Created after 15th, first shipment is next month
                return Carbon::parse($createdAt)->addMonth()->day(20)->startOfDay();
            } else {
                // Created before 16th, first shipment is this month
                return Carbon::parse($createdAt)->day(20)->startOfDay();
            }
        }
        
        // Calculate next shipment based on last one + frequency
        return Carbon::parse($lastShipmentDate)->addMonths($frequencyMonths)->day(20)->startOfDay();
    }

    /**
     * Check if subscription should be included in the next shipment (20th)
     */
    public static function shouldShipInNextBatch($subscription, Carbon $targetShipDate): bool
    {
        if ($subscription->status !== 'active') {
            return false;
        }

        $nextShipment = self::calculateNextShipmentDate($subscription);
        
        if (!$nextShipment) {
            return false;
        }

        // Check if the calculated next shipment matches the target date
        return $nextShipment->format('Y-m-d') === $targetShipDate->format('Y-m-d');
    }

    /**
     * Get formatted shipping date info for display
     */
    public static function getShippingDateInfo(): array
    {
        $nextShipping = self::getNextShippingDate();
        $cycleEnd = self::getNextBillingCycleEnd();
        $today = Carbon::now();
        
        $isAfterCutoff = $today->day >= 16;
        
        return [
            'next_shipping_date' => $nextShipping,
            'next_shipping_formatted' => $nextShipping->format('d.m.Y'),
            'cycle_end' => $cycleEnd,
            'is_after_cutoff' => $isAfterCutoff,
            'cutoff_message' => $isAfterCutoff 
                ? 'První dodávka bude odeslána ' . $nextShipping->format('d.m.Y')
                : 'První dodávka bude odeslána ' . $nextShipping->format('d.m.Y') . ' (do ' . $cycleEnd->format('d.m.') . ' lze upravit)',
        ];
    }
}

