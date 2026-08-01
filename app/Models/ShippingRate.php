<?php

namespace App\Models;

use App\Helpers\CurrencyHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_code',
        'country_name',
        'enabled',
        'available_on_cz',
        'available_on_com',
        'price_czk',
        'price_eur',
        'applies_to_subscriptions',
        'free_shipping_threshold_czk',
        'free_shipping_threshold_eur',
        'packeta_carrier_id',
        'packeta_carrier_name',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'available_on_cz' => 'boolean',
        'available_on_com' => 'boolean',
        'applies_to_subscriptions' => 'boolean',
        'price_czk' => 'decimal:2',
        'price_eur' => 'decimal:2',
        'free_shipping_threshold_czk' => 'decimal:2',
        'free_shipping_threshold_eur' => 'decimal:2',
    ];

    /**
     * Get the shipping price in the current currency
     */
    public function getPrice(): float
    {
        return $this->getPriceFor(null);
    }

    /**
     * Get the shipping price for an explicitly given currency
     *
     * Na rozdíl od getPrice() nečte session – použij mimo request zákazníka.
     */
    public function getPriceFor(?string $currency): float
    {
        return CurrencyHelper::priceFor($currency, $this->price_czk, $this->price_eur);
    }

    /**
     * Práh pro dopravu zdarma v dané měně; null = pro tuto měnu se neuplatňuje.
     *
     * Vrací null, ne 0 – nulový práh by jinak znamenal "doprava vždy zdarma".
     */
    public function getFreeShippingThresholdFor(?string $currency = null): ?float
    {
        $threshold = strtoupper($currency ?: CurrencyHelper::code()) === 'EUR'
            ? $this->free_shipping_threshold_eur
            : $this->free_shipping_threshold_czk;

        return $threshold !== null && (float) $threshold > 0 ? (float) $threshold : null;
    }

    /**
     * Get formatted shipping price
     */
    public function getFormattedPrice(): string
    {
        return CurrencyHelper::format($this->price_czk, $this->price_eur);
    }

    /**
     * Get shipping rate for a specific country (filtered by current region)
     */
    public static function getForCountry(string $countryCode): ?self
    {
        $query = static::where('country_code', strtoupper($countryCode))
            ->where('enabled', true);
        
        // Filter by region availability
        if (CurrencyHelper::isCzRegion()) {
            $query->where('available_on_cz', true);
        } else {
            $query->where('available_on_com', true);
        }
        
        return $query->first();
    }

    /**
     * Get shipping rate for a specific country regardless of region (for admin)
     */
    public static function getForCountryAdmin(string $countryCode): ?self
    {
        return static::where('country_code', strtoupper($countryCode))
            ->where('enabled', true)
            ->first();
    }

    /**
     * Get all enabled shipping rates for the current region
     */
    public static function getAllEnabled()
    {
        $query = static::where('enabled', true);
        
        // Filter by region availability
        if (CurrencyHelper::isCzRegion()) {
            $query->where('available_on_cz', true);
        } else {
            $query->where('available_on_com', true);
        }
        
        return $query->orderBy('country_name')->get();
    }

    /**
     * Get all enabled shipping rates regardless of region (for admin)
     */
    public static function getAllEnabledAdmin()
    {
        return static::where('enabled', true)
            ->orderBy('country_name')
            ->get();
    }

    /**
     * Calculate shipping cost for this rate
     */
    public function calculateShipping(float $subtotal, bool $isSubscription = false, ?string $currency = null): float
    {
        // If shipping doesn't apply to subscriptions and this is a subscription, return 0
        if ($isSubscription && !$this->applies_to_subscriptions) {
            return 0;
        }

        // Check free shipping threshold (only for one-time orders)
        // Práh se bere v měně objednávky – dřív byl výpočet podmíněný isCzk(),
        // takže EUR objednávka dopravu zdarma nedostala nikdy.
        if (!$isSubscription) {
            $threshold = $this->getFreeShippingThresholdFor($currency);
            if ($threshold !== null && $subtotal >= $threshold) {
                return 0;
            }
        }

        return $this->getPriceFor($currency);
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

    /**
     * Many-to-many relationship with Packeta carriers
     * Allows selecting multiple carriers per shipping rate
     */
    public function packetaCarriers()
    {
        return $this->belongsToMany(
            PacketaCarrier::class,
            'shipping_rate_carriers',
            'shipping_rate_id',
            'packeta_carrier_id'
        )->withTimestamps();
    }

    /**
     * Get array of carrier IDs for Packeta widget (legacy method)
     * @deprecated Use getPacketaWidgetVendors() instead
     */
    public function getPacketaCarrierIds(): array
    {
        return $this->packetaCarriers->pluck('carrier_id')->toArray();
    }

    /**
     * Get array of vendor objects for Packeta Widget v6
     * Returns properly formatted vendor objects for both Packeta own network and external carriers
     */
    public function getPacketaWidgetVendors(): array
    {
        return $this->packetaCarriers->map(function ($carrier) {
            return $carrier->getWidgetVendorObject();
        })->toArray();
    }

    /**
     * Get comma-separated list of carrier names for display
     */
    public function getPacketaCarrierNames(): string
    {
        return $this->packetaCarriers->pluck('name')->join(', ');
    }
}

