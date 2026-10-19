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
        'name_en',
        'slug',
        'website_url',
        'instagram',
        'image',
        'country',
        'country_en',
        'country_flag',
        'city',
        'city_en',
        'address',
        'short_description',
        'short_description_en',
        'full_description',
        'full_description_en',
        'gallery',
        'is_active',
        'sort_order',
        'featured_month',
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get translated name based on current locale
     */
    public function getName(): string
    {
        if (app()->getLocale() === 'en' && !empty($this->name_en)) {
            return $this->name_en;
        }
        return $this->name;
    }

    /**
     * Get translated short description based on current locale
     */
    public function getShortDescription(): ?string
    {
        if (app()->getLocale() === 'en' && !empty($this->short_description_en)) {
            return $this->short_description_en;
        }
        return $this->short_description;
    }

    /**
     * Get translated full description based on current locale
     */
    public function getFullDescription(): ?string
    {
        if (app()->getLocale() === 'en' && !empty($this->full_description_en)) {
            return $this->full_description_en;
        }
        return $this->full_description;
    }

    /**
     * Get translated country name based on current locale
     */
    public function getCountry(): ?string
    {
        // If EN locale, try country_en first, then translation file, then fallback to original
        if (app()->getLocale() === 'en') {
            if (!empty($this->country_en)) {
                return $this->country_en;
            }
            // Try translation file
            $translated = __('countries.' . $this->country);
            if ($translated !== 'countries.' . $this->country) {
                return $translated;
            }
        }
        return $this->country;
    }

    /**
     * Get translated city name based on current locale
     */
    public function getCity(): ?string
    {
        if (app()->getLocale() === 'en' && !empty($this->city_en)) {
            return $this->city_en;
        }
        return $this->city;
    }

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
        // Měsíc i dotaz určuje CoffeeOfMonthService (stejná uzávěrka jako objednávky)
        return app(\App\Services\CoffeeOfMonthService::class)->lineup()->roasteries;
    }

    /**
     * Scope to get roastery by specific month
     */
    public function scopeFeaturedMonth($query, $month)
    {
        return $query->where('featured_month', $month);
    }
}

