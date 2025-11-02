<?php

namespace App\Services;

use App\Models\ShippingRate;
use Illuminate\Support\Facades\Log;

class ShippingService
{
    /**
     * Calculate shipping cost for given country and order details
     *
     * @param string $countryCode ISO 3166-1 alpha-2 country code (e.g. 'CZ', 'SK')
     * @param float $subtotal Order subtotal amount
     * @param bool $isSubscription Whether this is a subscription order
     * @return float Shipping cost (0 if free or not available)
     */
    public function calculateShippingCost(string $countryCode, float $subtotal, bool $isSubscription = false): float
    {
        $rate = $this->getShippingRate($countryCode);

        if (!$rate) {
            Log::warning('Shipping rate not found for country', [
                'country_code' => $countryCode,
                'subtotal' => $subtotal,
                'is_subscription' => $isSubscription,
            ]);
            return 0;
        }

        return $rate->calculateShipping($subtotal, $isSubscription);
    }

    /**
     * Get shipping rate for a country
     *
     * @param string $countryCode
     * @return ShippingRate|null
     */
    public function getShippingRate(string $countryCode): ?ShippingRate
    {
        return ShippingRate::getForCountry($countryCode);
    }

    /**
     * Check if shipping is available to a country
     *
     * @param string $countryCode
     * @return bool
     */
    public function isShippingAvailable(string $countryCode): bool
    {
        $rate = $this->getShippingRate($countryCode);
        return $rate && $rate->isAvailable();
    }

    /**
     * Get Packeta widget vendor objects for a country (supports multiple carriers)
     * Returns properly formatted vendor objects for Packeta Widget v6
     *
     * @param string $countryCode
     * @return array Array of vendor objects for Packeta widget vendors parameter
     */
    public function getPacketaWidgetVendorsForCountry(string $countryCode): array
    {
        $rate = $this->getShippingRate($countryCode);
        return $rate ? $rate->getPacketaWidgetVendors() : [];
    }

    /**
     * Get Packeta carrier IDs for a country (multiple carriers supported)
     * @deprecated Use getPacketaWidgetVendorsForCountry() instead
     *
     * @param string $countryCode
     * @return array Array of carrier IDs for Packeta widget vendors parameter
     */
    public function getPacketaCarriersForCountry(string $countryCode): array
    {
        $rate = $this->getShippingRate($countryCode);
        return $rate ? $rate->getPacketaCarrierIds() : [];
    }

    /**
     * @deprecated Use getPacketaWidgetVendorsForCountry() instead
     */
    public function getPacketaCarrierForCountry(string $countryCode): ?string
    {
        $carriers = $this->getPacketaCarriersForCountry($countryCode);
        return $carriers[0] ?? null; // Return first carrier for backward compatibility
    }

    /**
     * Get all available shipping countries
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableCountries()
    {
        return ShippingRate::getAllEnabled();
    }

    /**
     * Format shipping cost for display
     *
     * @param float $cost
     * @return string
     */
    public function formatShippingCost(float $cost): string
    {
        if ($cost == 0) {
            return 'Zdarma';
        }

        return number_format($cost, 0, ',', ' ') . ' Kč';
    }

    /**
     * Calculate remaining amount for free shipping
     *
     * @param string $countryCode
     * @param float $currentSubtotal
     * @return float|null Returns null if no free shipping threshold
     */
    public function getRemainingForFreeShipping(string $countryCode, float $currentSubtotal): ?float
    {
        $rate = $this->getShippingRate($countryCode);

        if (!$rate || !$rate->free_shipping_threshold_czk) {
            return null;
        }

        $remaining = $rate->free_shipping_threshold_czk - $currentSubtotal;

        return $remaining > 0 ? $remaining : 0;
    }
}

