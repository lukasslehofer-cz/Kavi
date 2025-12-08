<?php

namespace App\Models;

use App\Helpers\CurrencyHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'price_eur',
        'interval',
        'coffee_count',
        'coffee_weight',
        'coffee_type',
        'is_active',
        'features',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_eur' => 'decimal:2',
        'is_active' => 'boolean',
        'features' => 'array',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the price in the current currency (CZK or EUR)
     */
    public function getPrice(): float
    {
        return CurrencyHelper::price($this->price, $this->price_eur);
    }

    /**
     * Get the formatted price with currency symbol
     */
    public function getFormattedPrice(int $decimals = 0): string
    {
        return CurrencyHelper::format($this->price, $this->price_eur, $decimals);
    }

    public function getPricePerMonthAttribute()
    {
        $price = $this->getPrice();
        return match($this->interval) {
            'monthly' => $price,
            'quarterly' => $price / 3,
            'yearly' => $price / 12,
            default => $price,
        };
    }
}




