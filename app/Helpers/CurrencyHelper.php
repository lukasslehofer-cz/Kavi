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
        if (self::isEur()) {
            return $priceEur ?? 0;
        }
        return $priceCzk ?? 0;
    }
    
    /**
     * Format a price with the current currency symbol
     * 
     * @param float|null $priceCzk Price in CZK
     * @param float|null $priceEur Price in EUR
     * @param int $decimals Number of decimal places
     * @return string Formatted price with symbol
     */
    public static function format(?float $priceCzk, ?float $priceEur, int $decimals = 0): string
    {
        $price = self::price($priceCzk, $priceEur);
        
        if (self::isEur()) {
            // EUR format: €29 or €29.90
            return '€' . number_format($price, $decimals, '.', ' ');
        }
        
        // CZK format: 690 Kč
        return number_format($price, $decimals, ',', ' ') . ' Kč';
    }
    
    /**
     * Format a single amount in the current currency
     * Use this when you already have the correct amount for the current currency
     * 
     * @param float $amount
     * @param int $decimals
     * @return string
     */
    public static function formatAmount(float $amount, int $decimals = 0): string
    {
        if (self::isEur()) {
            return '€' . number_format($amount, $decimals, '.', ' ');
        }
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
}

