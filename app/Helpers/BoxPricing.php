<?php

namespace App\Helpers;

use App\Models\SubscriptionConfig;

/**
 * Ceny kávových boxů M / L / XL pro zobrazení (tarify, cena za gram, dárky).
 *
 * Zdrojem jsou klíče price_{2,3,4}_bags[_eur] v SubscriptionConfig, stejně jako
 * v konfigurátoru. Výpočet ceny v pokladně (SubscriptionController::configureCheckout)
 * a SubscriptionPricing pro existující předplatné tuhle třídu nepoužívají.
 */
class BoxPricing
{
    /** Počet balíčků => označení boxu */
    public const SIZES = [2 => 'M', 3 => 'L', 4 => 'XL'];

    public const GRAMS_PER_BAG = 250;

    /** Box, který se zvýrazňuje jako nejoblíbenější */
    public const POPULAR = 3;

    private const DEFAULT_PRICES = [
        'CZK' => [2 => 500, 3 => 720, 4 => 920],
        'EUR' => [2 => 20, 3 => 29, 4 => 37],
    ];

    /** Příplatek za jednorázový box (stejná částka jako v SubscriptionController::configureCheckout) */
    private const ONE_TIME_SURCHARGE = ['CZK' => 100, 'EUR' => 5];

    public static function boxPrice(int $bags, ?string $currency = null): float
    {
        $currency = self::currency($currency);
        $key = 'price_' . $bags . '_bags' . ($currency === 'EUR' ? '_eur' : '');

        return (float) SubscriptionConfig::get($key, self::DEFAULT_PRICES[$currency][$bags] ?? 0);
    }

    public static function grams(int $bags): int
    {
        return $bags * self::GRAMS_PER_BAG;
    }

    /**
     * Cena za gram včetně dopravy, zaokrouhlená na 2 desetinná místa
     */
    public static function pricePerGram(int $bags, ?string $currency = null): float
    {
        return round(self::boxPrice($bags, $currency) / self::grams($bags), 2);
    }

    /**
     * Jednotková cena k zobrazení: "1,59 Kč za gram", v eurech "€5.90 per 100 g"
     * (v eurech vychází gram na pár centů, což nic neřekne).
     */
    public static function formatUnitPrice(int $bags, ?string $currency = null): string
    {
        $currency = self::currency($currency);

        if ($currency === 'EUR') {
            $per100 = round(self::boxPrice($bags, $currency) / self::grams($bags) * 100, 2);

            return __('components.unit_price.per_100g', [
                'price' => CurrencyHelper::formatByCurrency($per100, 'EUR', 2),
            ]);
        }

        return __('components.unit_price.per_gram', [
            'price' => CurrencyHelper::formatByCurrency(self::pricePerGram($bags, $currency), 'CZK', 2),
        ]);
    }

    /**
     * Data pro řádky tarifů
     *
     * @return array<int, array{bags:int, code:string, grams:int, price:float, unit_price:string, popular:bool}>
     */
    public static function tiers(?string $currency = null): array
    {
        $tiers = [];

        foreach (self::SIZES as $bags => $code) {
            $tiers[] = [
                'bags' => $bags,
                'code' => $code,
                'grams' => self::grams($bags),
                'price' => self::boxPrice($bags, $currency),
                'unit_price' => self::formatUnitPrice($bags, $currency),
                'popular' => $bags === self::POPULAR,
            ];
        }

        return $tiers;
    }

    /**
     * Cena nejlevnějšího boxu (pro "od … Kč")
     */
    public static function cheapest(?string $currency = null): float
    {
        return min(array_map(fn ($bags) => self::boxPrice($bags, $currency), array_keys(self::SIZES)));
    }

    /**
     * Dárkové předplatné: cena boxu × počet měsíců
     */
    public static function giftSubscriptionPrice(int $bags, int $months, ?string $currency = null): float
    {
        return self::boxPrice($bags, $currency) * $months;
    }

    public static function oneTimeSurcharge(?string $currency = null): float
    {
        return (float) self::ONE_TIME_SURCHARGE[self::currency($currency)];
    }

    /**
     * Ceny pro JS konfigurátoru ve tvaru ['2' => 890.0, ...]
     */
    public static function pricingMap(?string $currency = null): array
    {
        $map = [];
        foreach (array_keys(self::SIZES) as $bags) {
            $map[(string) $bags] = self::boxPrice($bags, $currency);
        }

        return $map;
    }

    private static function currency(?string $currency): string
    {
        return strtoupper($currency ?? CurrencyHelper::code()) === 'EUR' ? 'EUR' : 'CZK';
    }
}
