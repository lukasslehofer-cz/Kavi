<?php

namespace App\Models;

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
        'interval',
        'coffee_count',
        'coffee_weight',
        'coffee_type',
        'stripe_price_id',
        'is_active',
        'features',
    ];

    protected $casts = [
        'price' => 'decimal:2',
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

    public function getPricePerMonthAttribute()
    {
        return match($this->interval) {
            'monthly' => $this->price,
            'quarterly' => $this->price / 3,
            'yearly' => $this->price / 12,
            default => $this->price,
        };
    }
}



