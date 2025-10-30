<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_code',
        'country_name',
        'enabled',
        'price_czk',
        'price_eur',
        'applies_to_subscriptions',
        'free_shipping_threshold_czk',
        'packeta_carrier_id',
        'packeta_carrier_name',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'applies_to_subscriptions' => 'boolean',
        'price_czk' => 'decimal:2',
        'price_eur' => 'decimal:2',
        'free_shipping_threshold_czk' => 'decimal:2',
    ];

    /**
     * Get shipping rate for a specific country
     */
    public static function getForCountry(string $countryCode): ?self
    {
        return static::where('country_code', strtoupper($countryCode))
            ->where('enabled', true)
            ->first();
    }

    /**
     * Get all enabled shipping rates
     */
    public static function getAllEnabled()
    {
        return static::where('enabled', true)
            ->orderBy('country_name')
            ->get();
    }

    /**
     * Calculate shipping cost for this rate
     */
    public function calculateShipping(float $subtotal, bool $isSubscription = false): float
    {
        // If shipping doesn't apply to subscriptions and this is a subscription, return 0
        if ($isSubscription && !$this->applies_to_subscriptions) {
            return 0;
        }

        // Check free shipping threshold (only for one-time orders)
        if (!$isSubscription && $this->free_shipping_threshold_czk && $subtotal >= $this->free_shipping_threshold_czk) {
            return 0;
        }

        return (float) $this->price_czk;
    }

    /**
     * Check if shipping is available for this country
     */
    public function isAvailable(): bool
    {
        return $this->enabled;
    }

    /**
     * Get EU countries list
     */
    public static function getEUCountries(): array
    {
        return [
            'AT' => 'Rakousko',
            'BE' => 'Belgie',
            'BG' => 'Bulharsko',
            'HR' => 'Chorvatsko',
            'CY' => 'Kypr',
            'CZ' => 'Česká republika',
            'DK' => 'Dánsko',
            'EE' => 'Estonsko',
            'FI' => 'Finsko',
            'FR' => 'Francie',
            'DE' => 'Německo',
            'GR' => 'Řecko',
            'HU' => 'Maďarsko',
            'IE' => 'Irsko',
            'IT' => 'Itálie',
            'LV' => 'Lotyšsko',
            'LT' => 'Litva',
            'LU' => 'Lucembursko',
            'MT' => 'Malta',
            'NL' => 'Nizozemsko',
            'PL' => 'Polsko',
            'PT' => 'Portugalsko',
            'RO' => 'Rumunsko',
            'SK' => 'Slovensko',
            'SI' => 'Slovinsko',
            'ES' => 'Španělsko',
            'SE' => 'Švédsko',
        ];
    }

    /**
     * Relationships
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}

