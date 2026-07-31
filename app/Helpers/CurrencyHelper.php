<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Get the current currency code (CZK or EUR)
     */
    public static function code(): string
    {
        return session('currency', config('app.currency', 'CZK'));
    }
    
    /**
     * Get the current currency symbol
     */
    public static function symbol(): string
    {
        return self::isEur() ? '€' : 'Kč';
    }
    
    /**
     * Check if current currency is EUR
     */
    public static function isEur(): bool
    {
        return self::code() === 'EUR';
    }
    
    /**
     * Check if current currency is CZK
     */
    public static function isCzk(): bool
    {
        return self::code() === 'CZK';
    }
    
    /**
     * Get the current region code (cz or com)
     */
    public static function region(): string
    {
        return session('region', config('app.region', 'cz'));
    }
    
    /**
     * Check if current region is CZ
     */
    public static function isCzRegion(): bool
    {
        return self::region() === 'cz';
    }
    
    /**
     * Check if current region is COM (international)
     */
    public static function isComRegion(): bool
    {
        return self::region() === 'com';
    }
    
    /**
     * Get the appropriate price based on current currency
     * 
     * @param float|null $priceCzk Price in CZK
     * @param float|null $priceEur Price in EUR
     * @return float
     */
    public static function price(?float $priceCzk, ?float $priceEur): float
    {
        return self::priceFor(self::code(), $priceCzk, $priceEur);
    }

    /**
     * Get the appropriate price for an explicitly given currency
     *
     * Na rozdíl od price() nečte session – použij všude, kde se počítá mimo
     * request (webhooky, cron, artisan příkazy, maily).
     *
     * @param string|null $currency CZK / EUR (null = aktuální měna)
     * @param float|null $priceCzk Price in CZK
     * @param float|null $priceEur Price in EUR
     * @return float
     */
    public static function priceFor(?string $currency, ?float $priceCzk, ?float $priceEur): float
    {
        $currency = $currency ? strtoupper($currency) : self::code();

        if ($currency === 'EUR') {
            return $priceEur ?? 0;
        }

        return $priceCzk ?? 0;
    }

    /**
     * Format a price with the current currency symbol
     * 
     * @param float|null $priceCzk Price in CZK
     * @param float|null $priceEur Price in EUR
     * @param int|null $decimals Number of decimal places (null = auto: 2 for EUR, 0 for CZK)
     * @return string Formatted price with symbol
     */
    public static function format(?float $priceCzk, ?float $priceEur, ?int $decimals = null): string
    {
        $price = self::price($priceCzk, $priceEur);
        
        if (self::isEur()) {
            // EUR format: €29.00 (default 2 decimals)
            $decimals = $decimals ?? 2;
            return '€' . number_format($price, $decimals, '.', ' ');
        }
        
        // CZK format: 690 Kč (default 0 decimals)
        $decimals = $decimals ?? 0;
        return number_format($price, $decimals, ',', ' ') . ' Kč';
    }
    
    /**
     * Format a single amount in the current currency
     * Use this when you already have the correct amount for the current currency
     * 
     * @param float $amount
     * @param int|null $decimals Number of decimals (null = auto: 2 for EUR, 0 for CZK)
     * @return string
     */
    public static function formatAmount(float $amount, ?int $decimals = null): string
    {
        if (self::isEur()) {
            // EUR: default to 2 decimals (e.g., €11.40)
            $decimals = $decimals ?? 2;
            return '€' . number_format($amount, $decimals, '.', ' ');
        }
        // CZK: default to 0 decimals
        $decimals = $decimals ?? 0;
        return number_format($amount, $decimals, ',', ' ') . ' Kč';
    }
    
    /**
     * Convert amount to smallest currency unit (cents/haléře) for Stripe
     * 
     * @param float $amount
     * @return int
     */
    public static function toSmallestUnit(float $amount): int
    {
        return (int) round($amount * 100);
    }
    
    /**
     * Convert from smallest currency unit to regular amount
     * 
     * @param int $amount
     * @return float
     */
    public static function fromSmallestUnit(int $amount): float
    {
        return $amount / 100;
    }
    
    /**
     * Get Stripe-compatible currency code (lowercase)
     */
    public static function stripeCode(): string
    {
        return strtolower(self::code());
    }
    
    /**
     * Format an amount with a specific currency (not session-based)
     * Use this for displaying historical data like orders/subscriptions
     * 
     * @param float $amount The amount to format
     * @param string|null $currency The currency code (CZK or EUR), defaults to current session currency
     * @param int|null $decimals Number of decimal places (null = auto: 2 for EUR, 0 for CZK)
     * @return string Formatted price with symbol
     */
    public static function formatByCurrency(float $amount, ?string $currency = null, ?int $decimals = null): string
    {
        $currency = $currency ?? self::code();
        
        if (strtoupper($currency) === 'EUR') {
            // EUR: default 2 decimals
            $decimals = $decimals ?? 2;
            return '€' . number_format($amount, $decimals, '.', ' ');
        }
        
        // CZK: default 0 decimals
        $decimals = $decimals ?? 0;
        return number_format($amount, $decimals, ',', ' ') . ' Kč';
    }
}

