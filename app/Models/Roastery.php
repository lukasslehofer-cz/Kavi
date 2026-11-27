<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\HasLocalizedColumns;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Roastery extends Model
{
    use HasFactory, HasLocalizedColumns;

    protected $fillable = [
        'name',
        'name_en',
        'name_sk',
        'slug',
        'website_url',
        'instagram',
        'image',
        'country',
        'country_en',
        'country_sk',
        'country_flag',
        'city',
        'city_en',
        'city_sk',
        'address',
        'short_description',
        'short_description_en',
        'short_description_sk',
        'full_description',
        'full_description_en',
        'full_description_sk',
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
        return $this->localizedValue('name');
    }

    /**
     * Get translated short description based on current locale
     */
    public function getShortDescription(): ?string
    {
        return $this->localizedValue('short_description');
    }

    /**
     * Get translated full description based on current locale
     */
    public function getFullDescription(): ?string
    {
        return $this->localizedValue('full_description');
    }

    /**
     * Get translated country name based on current locale
     */
    public function getCountry(): ?string
    {
        // Outside Czech try country_{locale} first, then translation file, then fallback to original
        $locale = app()->getLocale();
        if ($locale !== \App\Helpers\Site::PRIMARY_LOCALE) {
            $localized = $this->getAttribute('country_' . $locale);
            if (!empty($localized)) {
                return $localized;
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
        return $this->localizedValue('city');
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

