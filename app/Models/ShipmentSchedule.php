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
