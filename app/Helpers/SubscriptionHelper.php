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
        // KROK 7: Jediný výpočtový engine je SubscriptionShipmentService (ledger-first).
        // Tento helper zůstává jen tenkým delegátorem kvůli zpětné kompatibilitě volajících.
        return app(\App\Services\SubscriptionShipmentService::class)
            ->calculateNextShipmentDate($subscription);
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
        // KROK 7: delegace na jediný engine (ledger-first coverage).
        return app(\App\Services\SubscriptionShipmentService::class)
            ->hasPaidCoverageForDate($subscription, $date);
    }

    /**
     * Detect if the given date is the initial shipment that is implicitly covered by the activation payment.
     * This applies when no shipment has been sent yet and the date equals the first scheduled shipment date.
     */
    public static function isInitialShipmentCovered($subscription, Carbon $date): bool
    {
        // KROK 7: delegace na jediný engine.
        return app(\App\Services\SubscriptionShipmentService::class)
            ->isInitialShipmentCovered($subscription, $date);
    }

    /**
     * Get the first unpaid shipment date starting from the next scheduled shipment
     * Skips all shipment dates that are within any paid coverage period
     */
    public static function getFirstUnpaidShipmentDate($subscription): Carbon
    {
        // KROK 7: delegace na jediný engine; fallback zajistí nenullový návrat.
        return app(\App\Services\SubscriptionShipmentService::class)
            ->getFirstUnpaidShipmentDate($subscription)
            ?? self::getNextShippingDate();
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
     * Uses billing_date cutoff logic to determine the correct shipping date
     */
    public static function getShippingDateInfo(): array
    {
        $today = Carbon::now();
        
        // Use billing_date cutoff to determine the target month
        $targetMonth = self::getOrderingTargetMonth();
        $targetSchedule = ShipmentSchedule::getForMonth($targetMonth->year, $targetMonth->month);
        
        // Get shipping date from target month's schedule, or fallback to 20th
        if ($targetSchedule && $targetSchedule->shipment_date) {
            $nextShipping = $targetSchedule->shipment_date->copy()->startOfDay();
        } else {
            // Fallback: 20th of target month
            $nextShipping = $targetMonth->copy()->day(20)->startOfDay();
        }
        
        // Get billing date from target month's schedule, or fallback to 15th
        if ($targetSchedule && $targetSchedule->billing_date) {
            $nextBilling = $targetSchedule->billing_date->copy()->startOfDay();
        } else {
            // Fallback: 15th of target month
            $nextBilling = $targetMonth->copy()->day(15)->startOfDay();
        }
        
        // Check if we're after the billing cutoff for current month
        $currentSchedule = ShipmentSchedule::getForMonth($today->year, $today->month);
        $isAfterCutoff = false;
        if ($currentSchedule && $currentSchedule->billing_date) {
            $isAfterCutoff = $today->greaterThan($currentSchedule->billing_date);
        } else {
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

