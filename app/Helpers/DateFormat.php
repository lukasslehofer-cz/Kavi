<?php

namespace App\Helpers;

use Carbon\Carbon;

/**
 * Krátké formáty dat pro web (CZ "15. 11.", EN "Nov 15").
 *
 * Mezi dnem a měsícem je nezlomitelná mezera, aby se datum v nadpisech nezalomilo.
 */
class DateFormat
{
    private const NBSP = "\u{00A0}";

    public static function dayMonth(Carbon $date, ?string $locale = null): string
    {
        if (self::isEn($locale)) {
            return $date->format('M') . self::NBSP . $date->format('j');
        }

        return $date->format('j.') . self::NBSP . $date->format('n.');
    }

    public static function full(Carbon $date, ?string $locale = null): string
    {
        if (self::isEn($locale)) {
            return $date->format('M') . self::NBSP . $date->format('j, Y');
        }

        return $date->format('j.') . self::NBSP . $date->format('n.') . self::NBSP . $date->format('Y');
    }

    private static function isEn(?string $locale): bool
    {
        return ($locale ?? app()->getLocale()) === 'en';
    }
}
