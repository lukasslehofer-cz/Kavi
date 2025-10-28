<?php

namespace App\Helpers;

use App\Models\ShipmentSchedule;
use Carbon\Carbon;

class SubscriptionHelper
{
    /**
     * Get the next shipping date based on shipment schedule
     * Falls back to 20th if no schedule is found
     */
    public static function getNextShippingDate(): Carbon
    {
        $today = Carbon::now();
        
        // Try to get the next scheduled shipment
        $nextShipment = ShipmentSchedule::getNextShipment();
        
        if ($nextShipment) {
            return $nextShipment->shipment_date->copy()->startOfDay();
        }
        
        // Fallback to default logic (20th of month)
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
     * Get the next billing date based on shipment schedule
     * Falls back to 15th if no schedule is found
     */
    public static function getNextBillingDate(): Carbon
    {
        $today = Carbon::now();
        $currentYear = $today->year;
        $currentMonth = $today->month;
        
        // Get current month's schedule
        $currentSchedule = ShipmentSchedule::getForMonth($currentYear, $currentMonth);
        
        if ($currentSchedule && $today->lessThan($currentSchedule->billing_date)) {
            // Current month's billing hasn't happened yet
            return $currentSchedule->billing_date->copy()->startOfDay();
        }
        
        // Get next month's schedule
        $nextMonth = $today->copy()->addMonth();
        $nextSchedule = ShipmentSchedule::getForMonth($nextMonth->year, $nextMonth->month);
        
        if ($nextSchedule) {
            return $nextSchedule->billing_date->copy()->startOfDay();
        }
        
        // Fallback to default logic (15th of month)
        $dayOfMonth = $today->day;
        
        if ($dayOfMonth >= 16) {
            // Next cycle ends on 15th of next month
            return Carbon::now()->addMonth()->day(15)->startOfDay();
        } else {
            // Current cycle ends on 15th of this month
            return Carbon::now()->day(15)->startOfDay();
        }
    }

    /**
     * Get the next billing cycle end date (for compatibility)
     * @deprecated Use getNextBillingDate() instead
     */
    public static function getNextBillingCycleEnd(): Carbon
    {
        return self::getNextBillingDate()->endOfDay();
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
            // First shipment - find next scheduled shipment
            $createdAt = $subscription->created_at;
            $createdYear = $createdAt->year;
            $createdMonth = $createdAt->month;
            
            // Get current month's schedule
            $currentSchedule = ShipmentSchedule::getForMonth($createdYear, $createdMonth);
            
            if ($currentSchedule && $createdAt->lessThan($currentSchedule->billing_date)) {
                // Created before billing date, can get this month's shipment
                return $currentSchedule->shipment_date->copy()->startOfDay();
            }
            
            // Otherwise, get next month's schedule
            $nextMonth = $createdAt->copy()->addMonth();
            $nextSchedule = ShipmentSchedule::getForMonth($nextMonth->year, $nextMonth->month);
            
            if ($nextSchedule) {
                return $nextSchedule->shipment_date->copy()->startOfDay();
            }
            
            // Fallback to old logic
            if ($createdAt->day >= 16) {
                return Carbon::parse($createdAt)->addMonth()->day(20)->startOfDay();
            } else {
                return Carbon::parse($createdAt)->day(20)->startOfDay();
            }
        }
        
        // Calculate next shipment based on last one + frequency
        $nextDate = Carbon::parse($lastShipmentDate)->addMonths($frequencyMonths);
        $nextSchedule = ShipmentSchedule::getForMonth($nextDate->year, $nextDate->month);
        
        if ($nextSchedule) {
            return $nextSchedule->shipment_date->copy()->startOfDay();
        }
        
        // Fallback
        return $nextDate->day(20)->startOfDay();
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
        $nextBilling = self::getNextBillingDate();
        $today = Carbon::now();
        
        // Check if we're after the billing cutoff for this month's shipment
        $currentYear = $today->year;
        $currentMonth = $today->month;
        $currentSchedule = ShipmentSchedule::getForMonth($currentYear, $currentMonth);
        
        $isAfterCutoff = false;
        if ($currentSchedule) {
            $isAfterCutoff = $today->greaterThanOrEqualTo($currentSchedule->billing_date);
        } else {
            // Fallback to old logic
            $isAfterCutoff = $today->day >= 16;
        }
        
        return [
            'next_shipping_date' => $nextShipping,
            'next_shipping_formatted' => $nextShipping->format('d.m.Y'),
            'next_billing_date' => $nextBilling,
            'cycle_end' => $nextBilling->endOfDay(), // For backward compatibility
            'is_after_cutoff' => $isAfterCutoff,
            'cutoff_message' => $isAfterCutoff 
                ? 'První dodávka bude odeslána ' . $nextShipping->format('d.m.Y')
                : 'První dodávka bude odeslána ' . $nextShipping->format('d.m.Y') . ' (do ' . $nextBilling->format('d.m.') . ' lze upravit)',
        ];
    }
}

