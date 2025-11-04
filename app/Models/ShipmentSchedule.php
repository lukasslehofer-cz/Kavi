<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'month',
        'billing_date',
        'shipment_date',
        'coffee_product_id',
        'roastery_name',
        'promo_image',
        'notes',
        'coffee_slot_e1',
        'coffee_slot_e2',
        'coffee_slot_e3',
        'coffee_slot_f1',
        'coffee_slot_f2',
        'coffee_slot_f3',
        'coffee_slot_d',
    ];

    protected $casts = [
        'billing_date' => 'date',
        'shipment_date' => 'date',
    ];

    /**
     * Vztah k produktu (káva měsíce)
     */
    public function coffeeProduct()
    {
        return $this->belongsTo(Product::class, 'coffee_product_id');
    }

    /**
     * Coffee slot relationships
     */
    public function coffeeSlotE1()
    {
        return $this->belongsTo(Product::class, 'coffee_slot_e1');
    }

    public function coffeeSlotE2()
    {
        return $this->belongsTo(Product::class, 'coffee_slot_e2');
    }

    public function coffeeSlotE3()
    {
        return $this->belongsTo(Product::class, 'coffee_slot_e3');
    }

    public function coffeeSlotF1()
    {
        return $this->belongsTo(Product::class, 'coffee_slot_f1');
    }

    public function coffeeSlotF2()
    {
        return $this->belongsTo(Product::class, 'coffee_slot_f2');
    }

    public function coffeeSlotF3()
    {
        return $this->belongsTo(Product::class, 'coffee_slot_f3');
    }

    public function coffeeSlotD()
    {
        return $this->belongsTo(Product::class, 'coffee_slot_d');
    }

    /**
     * Stock reservations for this shipment
     */
    public function stockReservations()
    {
        return $this->hasMany(StockReservation::class);
    }

    /**
     * Coffee allocations for this shipment
     */
    public function coffeeAllocations()
    {
        return $this->hasMany(SubscriptionCoffeeAllocation::class);
    }

    /**
     * Orders that will be shipped with this subscription shipment (addon items)
     */
    public function addonOrders()
    {
        return $this->hasMany(Order::class)->where('shipped_with_subscription', true);
    }

    /**
     * Get all coffee slots as an array
     */
    public function getCoffeeSlotsArray(): array
    {
        return [
            'e1' => $this->coffee_slot_e1,
            'e2' => $this->coffee_slot_e2,
            'e3' => $this->coffee_slot_e3,
            'f1' => $this->coffee_slot_f1,
            'f2' => $this->coffee_slot_f2,
            'f3' => $this->coffee_slot_f3,
            'd' => $this->coffee_slot_d,
        ];
    }

    /**
     * Check if all required coffee slots are filled
     */
    public function hasCoffeeSlotsConfigured(): bool
    {
        $slots = $this->getCoffeeSlotsArray();
        
        // At minimum, need E1, E2, F1, F2
        return !empty($slots['e1']) && !empty($slots['e2']) && 
               !empty($slots['f1']) && !empty($slots['f2']);
    }

    /**
     * Získat plánovanou rozesílku pro daný měsíc
     */
    public static function getForMonth(int $year, int $month): ?self
    {
        return static::where('year', $year)
            ->where('month', $month)
            ->first();
    }

    /**
     * Získat nejbližší budoucí rozesílku
     */
    public static function getNextShipment(): ?self
    {
        return static::where('shipment_date', '>=', now()->startOfDay())
            ->orderBy('shipment_date', 'asc')
            ->first();
    }

    /**
     * Získat všechny rozesílky pro daný rok
     */
    public static function getForYear(int $year)
    {
        return static::where('year', $year)
            ->orderBy('month', 'asc')
            ->get();
    }

    /**
     * Zjistit, jestli je rozesílka v minulosti
     */
    public function isPast(): bool
    {
        return $this->shipment_date->lt(now()->startOfDay());
    }

    /**
     * Získat formátovaný název měsíce
     */
    public function getMonthNameAttribute(): string
    {
        $months = [
            1 => 'Leden', 2 => 'Únor', 3 => 'Březen', 4 => 'Duben',
            5 => 'Květen', 6 => 'Červen', 7 => 'Červenec', 8 => 'Srpen',
            9 => 'Září', 10 => 'Říjen', 11 => 'Listopad', 12 => 'Prosinec'
        ];
        
        return $months[$this->month] ?? '';
    }

    /**
     * Get the next billing date after a given date (used for first subscription payment)
     * Returns the billing_date from the nearest future shipment schedule
     * 
     * Uzávěrka je až o půlnoci - pokud je dnes billing_date, je stále platný.
     * Např. 15.11. lze ještě koupit subscription s nearest billing = 15.11.
     * 
     * @param Carbon|null $afterDate The date after which to find billing date (defaults to today)
     * @return Carbon|null The next billing date, or null if no future schedule exists
     */
    public static function getNextBillingDate(?Carbon $afterDate = null): ?Carbon
    {
        $afterDate = $afterDate ?? Carbon::now();
        
        // Use >= to include today's billing_date (cutoff is at midnight)
        $schedule = static::where('billing_date', '>=', $afterDate->startOfDay())
            ->orderBy('billing_date', 'asc')
            ->first();
        
        if ($schedule) {
            return $schedule->billing_date->copy();
        }
        
        // Fallback: if no future schedule exists, try next month with default day (15th)
        $nextMonth = $afterDate->copy()->addMonthNoOverflow();
        return Carbon::create($nextMonth->year, $nextMonth->month, 15)->startOfDay();
    }

    /**
     * Get billing date for a specific month and year
     * If schedule doesn't exist, returns 15th of that month as fallback
     * 
     * @param int $year
     * @param int $month
     * @return Carbon The billing date for specified month
     */
    public static function getBillingDateForMonth(int $year, int $month): Carbon
    {
        $schedule = static::getForMonth($year, $month);
        
        if ($schedule) {
            return $schedule->billing_date->copy();
        }
        
        // Fallback: 15th of the month
        \Log::warning('ShipmentSchedule not found, using fallback date', [
            'year' => $year,
            'month' => $month,
            'fallback_date' => "$year-$month-15",
        ]);
        
        return Carbon::create($year, $month, 15)->startOfDay();
    }

    /**
     * Get billing date X months after a given schedule
     * Used for calculating next billing date based on subscription frequency
     * 
     * @param Carbon $currentBillingDate The current billing date
     * @param int $frequencyMonths Number of months to add (1, 2, or 3)
     * @return Carbon The next billing date
     */
    public static function getBillingDateAfterMonths(Carbon $currentBillingDate, int $frequencyMonths): Carbon
    {
        // Calculate target year and month
        $currentMonth = $currentBillingDate->month;
        $currentYear = $currentBillingDate->year;
        
        $targetMonth = $currentMonth + $frequencyMonths;
        $targetYear = $currentYear;
        
        // Handle year overflow
        while ($targetMonth > 12) {
            $targetMonth -= 12;
            $targetYear++;
        }
        
        \Log::info('Calculating billing date after months', [
            'current_date' => $currentBillingDate->toDateString(),
            'frequency_months' => $frequencyMonths,
            'target_year' => $targetYear,
            'target_month' => $targetMonth,
        ]);
        
        return static::getBillingDateForMonth($targetYear, $targetMonth);
    }

    /**
     * Get billing date with frequency offset from today
     * Used for first subscription checkout when applying frequency
     * 
     * Business logic:
     * - First payment is IMMEDIATE (covers period until next billing_date)
     * - next_billing_date = billing_date AFTER the nearest one
     * - For frequency > 1, add additional months
     * 
     * Example: Purchase on 4.11., nearest billing_date = 15.11.
     * - First payment on 4.11. covers period until 15.11.
     * - next_billing_date = 15.12. (the NEXT billing_date after 15.11.)
     * 
     * @param int $frequencyMonths The subscription frequency (1, 2, or 3 months)
     * @return Carbon The calculated first billing date
     */
    public static function getFirstBillingDateWithFrequency(int $frequencyMonths): Carbon
    {
        $today = Carbon::now();
        
        // Find the NEAREST billing date after today (this is covered by first payment)
        $nearestBillingDate = static::getNextBillingDate($today);
        
        if (!$nearestBillingDate) {
            // Extreme fallback
            return $today->copy()->addMonths($frequencyMonths)->setDay(15)->startOfDay();
        }
        
        \Log::info('Calculating first billing date with frequency', [
            'today' => $today->toDateString(),
            'nearest_billing_date' => $nearestBillingDate->toDateString(),
            'frequency_months' => $frequencyMonths,
        ]);
        
        // The nearest billing_date is already covered by first payment
        // So next_billing_date should be frequency months AFTER that
        $nextBillingDate = static::getBillingDateAfterMonths($nearestBillingDate, $frequencyMonths);
        
        \Log::info('First billing date calculated', [
            'next_billing_date' => $nextBillingDate->toDateString(),
        ]);
        
        return $nextBillingDate;
    }

    /**
     * Ensure schedule exists for a given month, create with defaults if missing
     * Auto-creates missing schedules to prevent fallback to hardcoded dates
     * 
     * @param int $year
     * @param int $month
     * @return ShipmentSchedule
     */
    public static function getOrCreateForMonth(int $year, int $month): self
    {
        $schedule = static::getForMonth($year, $month);
        
        if (!$schedule) {
            \Log::info('Auto-creating missing ShipmentSchedule', [
                'year' => $year,
                'month' => $month,
            ]);
            
            $schedule = static::create([
                'year' => $year,
                'month' => $month,
                'billing_date' => Carbon::create($year, $month, 15),
                'shipment_date' => Carbon::create($year, $month, 20),
            ]);
        }
        
        return $schedule;
    }
}
