<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Roastery extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'website_url',
        'instagram',
        'image',
        'country',
        'country_flag',
        'city',
        'address',
        'short_description',
        'full_description',
        'gallery',
        'is_active',
        'sort_order',
        'featured_month',
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($roastery) {
            if (empty($roastery->slug)) {
                $roastery->slug = Str::slug($roastery->name);
            }
        });

        static::updating(function ($roastery) {
            if ($roastery->isDirty('name') && empty($roastery->slug)) {
                $roastery->slug = Str::slug($roastery->name);
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function activeProducts()
    {
        return $this->hasMany(Product::class)->where('is_active', true);
    }

    public function coffeeOfMonthProducts()
    {
        return $this->hasMany(Product::class)->where('is_coffee_of_month', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get roasteries of the month based on current date
     * Logic: Show roasteries for next month starting from billing_date + 1
     */
    public static function getRoasteriesOfMonth()
    {
        $today = now();
        
        // Get billing_date for current month from ShipmentSchedule
        $currentSchedule = \App\Models\ShipmentSchedule::getForMonth($today->year, $today->month);
        
        // Determine display cutoff date (billing_date + 1 day)
        if ($currentSchedule && $currentSchedule->billing_date) {
            $cutoffDate = $currentSchedule->billing_date->copy()->addDay();
        } else {
            // Fallback to 16th if no schedule configured
            $cutoffDate = $today->copy()->day(16);
        }
        
        // If today is on or after cutoff date, show next month
        if ($today->greaterThanOrEqualTo($cutoffDate)) {
            $targetMonth = $today->copy()->addMonthNoOverflow()->format('Y-m');
        } else {
            $targetMonth = $today->format('Y-m');
        }
        
        return self::where('featured_month', $targetMonth)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    /**
     * Scope to get roastery by specific month
     */
    public function scopeFeaturedMonth($query, $month)
    {
        return $query->where('featured_month', $month);
    }
}

