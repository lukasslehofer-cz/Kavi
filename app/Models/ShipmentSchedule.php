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
}
