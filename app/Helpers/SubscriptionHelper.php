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
        
        if ($dayOfMonth > 15) {
            // Order is for next period
            return Carbon::now()->addMonthNoOverflow()->day(20)->startOfDay();
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
        $nextMonth = $today->copy()->addMonthNoOverflow();
        $nextSchedule = ShipmentSchedule::getForMonth($nextMonth->year, $nextMonth->month);
        
        if ($nextSchedule) {
            return $nextSchedule->billing_date->copy()->startOfDay();
        }
        
        // Fallback to default logic (15th of month)
        $dayOfMonth = $today->day;
        
        if ($dayOfMonth > 15) {
            // Next cycle ends on 15th of next month
            return Carbon::now()->addMonthNoOverflow()->day(15)->startOfDay();
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
     * Get the target month for new subscription orders based on billing_date cutoff
     * 
     * This determines which month's coffee boxes a new order will receive:
     * - Before billing_date cutoff: order gets current month's box
     * - After billing_date cutoff: order gets next month's box
     * 
     * @return Carbon The target month (year and month components are relevant)
     */
    public static function getOrderingTargetMonth(): Carbon
    {
        $today = Carbon::now();
        $currentSchedule = ShipmentSchedule::getForMonth($today->year, $today->month);
        
        if ($currentSchedule && $currentSchedule->billing_date) {
            $cutoffDate = $currentSchedule->billing_date->copy()->addDay();
        } else {
            // Fallback to 16th if no schedule configured
            $cutoffDate = $today->copy()->day(16);
        }
        
        return $today->greaterThanOrEqualTo($cutoffDate) 
            ? $today->copy()->addMonthNoOverflow() 
            : $today->copy();
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
            
            if ($currentSchedule && $createdAt->lessThanOrEqualTo($currentSchedule->billing_date)) {
                // Created before or ON billing date, can get this month's shipment
                return $currentSchedule->shipment_date->copy()->startOfDay();
            }
            
            // Otherwise, get next month's schedule
            $nextMonth = $createdAt->copy()->addMonthNoOverflow();
            $nextSchedule = ShipmentSchedule::getForMonth($nextMonth->year, $nextMonth->month);
            
            if ($nextSchedule) {
                return $nextSchedule->shipment_date->copy()->startOfDay();
            }
            
            // Fallback to old logic
            if ($createdAt->day > 15) {
                return Carbon::parse($createdAt)->addMonthNoOverflow()->day(20)->startOfDay();
            } else {
                return Carbon::parse($createdAt)->day(20)->startOfDay();
            }
        }
        
        // Special handling for subscriptions that were/are paused
        // If pause has expired (or ends today), calculate from pause end date instead of last shipment
        if ($subscription->paused_until_date && $subscription->paused_until_date->lte(Carbon::now()->endOfDay())) {
            // Calculate next shipment after pause ended
            $baseDate = $subscription->paused_until_date->copy();
            $nextDate = self::getNextShipmentAfterDate($subscription, $baseDate);
            return $nextDate;
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
        // Special handling for one-time boxes (frequency_months = 0)
        if ($subscription->frequency_months == 0) {
            // One-time boxes ship only once
            // Include if: active/pending + not yet shipped
            if (!in_array($subscription->status, ['active', 'pending'])) {
                return false;
            }
            
            // If already shipped, don't include
            if ($subscription->last_shipment_date) {
                return false;
            }
            
            // Calculate when it should ship (next shipping date after creation)
            $createdAt = Carbon::parse($subscription->starts_at ?? $subscription->created_at);
            $schedule = ShipmentSchedule::getForMonth($createdAt->year, $createdAt->month);
            
            // If created after cutoff (15th), ship next month
            if ($createdAt->day > 15) {
                $nextMonth = $createdAt->copy()->addMonthNoOverflow();
                $schedule = ShipmentSchedule::getForMonth($nextMonth->year, $nextMonth->month);
            }
            
            if ($schedule) {
                return $schedule->shipment_date->format('Y-m-d') === $targetShipDate->format('Y-m-d');
            }
            
            // Fallback to 20th of appropriate month
            $shipDate = $createdAt->day > 15 
                ? $createdAt->copy()->addMonthNoOverflow()->day(20)
                : $createdAt->copy()->day(20);
            
            return $shipDate->format('Y-m-d') === $targetShipDate->format('Y-m-d');
        }
        
        // Regular subscription logic
        // Check if pause has expired - if so, treat as active
        if ($subscription->status === 'paused' && $subscription->canResume()) {
            // Pause has expired, treat as active subscription
            // Continue with normal active subscription logic below
        } elseif ($subscription->status === 'paused' || $subscription->status === 'cancelled') {
            // Still paused or cancelled - only allow shipping if there's a PAID period covering target date (already paid box)
            $hasPaidCover = self::hasPaidCoverageForDate($subscription, $targetShipDate);

            if (!$hasPaidCover) {
                return false;
            }
            
            // Has paid coverage for this date - include in shipment list
            // Skip calculateNextShipmentDate check as it doesn't work correctly for paused subscriptions
            // (it calculates based on last_shipment_date which may be months ago)
            return true;
        } elseif ($subscription->status !== 'active') {
            return false;
        }

        $nextShipment = self::calculateNextShipmentDate($subscription);
        if (!$nextShipment) {
            return false;
        }

        return $nextShipment->format('Y-m-d') === $targetShipDate->format('Y-m-d');
    }

    /**
     * Check if a given date is covered by any PAID subscription payment period
     */
    public static function hasPaidCoverageForDate($subscription, Carbon $date): bool
    {
        // If shipment date is before next_billing_date, it's covered.
        // next_billing_date represents the NEXT payment date, so everything before it is already paid.
        // This handles race conditions where payment was processed but subscription_payments
        // record hasn't been created yet (e.g., webhook delay).
        if ($subscription->next_billing_date && $date->lt($subscription->next_billing_date)) {
            return true;
        }

        // Initial shipment coverage: if customer just subscribed and hasn't received any shipment yet,
        // the first scheduled shipment (per configurator rules) is considered covered by the initial payment
        if (self::isInitialShipmentCovered($subscription, $date)) {
            return true;
        }

        // Check payment periods
        // period_end is EXCLUSIVE (it's the next billing date), so use > not >=
        return $subscription->payments()
            ->where('status', 'paid')
            ->whereDate('period_start', '<=', $date->toDateString())
            ->whereDate('period_end', '>', $date->toDateString())
            ->exists();
    }

    /**
     * Detect if the given date is the initial shipment that is implicitly covered by the activation payment.
     * This applies when no shipment has been sent yet and the date equals the first scheduled shipment date.
     */
    public static function isInitialShipmentCovered($subscription, Carbon $date): bool
    {
        // No shipment sent yet
        if ($subscription->last_shipment_date) {
            return false;
        }

        // First scheduled shipment for this subscription
        $firstScheduled = self::calculateNextShipmentDate($subscription);
        if (!$firstScheduled) {
            return false;
        }

        return $firstScheduled->isSameDay($date);
    }

    /**
     * Get the first unpaid shipment date starting from the next scheduled shipment
     * Skips all shipment dates that are within any paid coverage period
     */
    public static function getFirstUnpaidShipmentDate($subscription): Carbon
    {
        $frequencyMonths = max(1, (int)($subscription->frequency_months ?? 1));
        $candidate = self::calculateNextShipmentDate($subscription) ?? self::getNextShippingDate();

        // Ensure we start from a future date (today or later)
        // This fixes the bug where old last_shipment_date would cause past dates to be returned
        $today = Carbon::now()->startOfDay();
        while ($candidate->lt($today)) {
            $candidate = $candidate->copy()->addMonths($frequencyMonths);
        }

        // Iterate through cadence until we find a date not covered by a paid period
        $guard = 0;
        while ($guard < 24) { // prevent infinite loops
            if (!self::hasPaidCoverageForDate($subscription, $candidate)) {
                return $candidate->copy()->startOfDay();
            }
            $candidate = $candidate->copy()->addMonths($frequencyMonths);
            $guard++;
        }

        // Fallback (should not happen)
        return $candidate->copy()->startOfDay();
    }

    /**
     * Get the next shipment date after a given date, aligned to subscription cadence
     * 
     * First checks if the current month's shipment is still available (billing not closed yet).
     * If not, falls back to adding frequency_months to find the next shipment.
     */
    public static function getNextShipmentAfterDate($subscription, Carbon $date): Carbon
    {
        $today = Carbon::now();
        
        // First, check if the current month (of the given date) has an available shipment
        // A shipment is available if: shipment_date is in the future AND billing is still open
        $currentSchedule = ShipmentSchedule::getForMonth($date->year, $date->month);
        
        if ($currentSchedule && 
            $currentSchedule->shipment_date->gte($today->startOfDay()) && 
            $currentSchedule->billing_date->gte($today->startOfDay())) {
            // Current month's shipment is still available
            return $currentSchedule->shipment_date->copy()->startOfDay();
        }
        
        // Current month not available, find next shipment based on frequency
        $frequencyMonths = max(1, (int)($subscription->frequency_months ?? 1));
        $candidate = $date->copy()->addMonths($frequencyMonths);

        $nextSchedule = ShipmentSchedule::getForMonth($candidate->year, $candidate->month);
        if ($nextSchedule) {
            return $nextSchedule->shipment_date->copy()->startOfDay();
        }

        return $candidate->day(20)->startOfDay();
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
            $isAfterCutoff = $today->greaterThan($currentSchedule->billing_date);
        } else {
            // Fallback to old logic
            $isAfterCutoff = $today->day > 15;
        }
        
        // Generate locale-aware cutoff message
        if (app()->getLocale() === 'en') {
            $cutoffMessage = 'Your first coffee box will be shipped on ' . $nextShipping->format('M d, Y');
        } else {
            $cutoffMessage = 'První kávový box bude odeslán ' . $nextShipping->format('d.m.Y');
        }
        
        return [
            'next_shipping_date' => $nextShipping,
            'next_shipping_formatted' => $nextShipping->format('d.m.Y'),
            'next_billing_date' => $nextBilling,
            'cycle_end' => $nextBilling->endOfDay(), // For backward compatibility
            'is_after_cutoff' => $isAfterCutoff,
            'cutoff_message' => $cutoffMessage,
        ];
    }
}

