<?php

namespace App\Helpers;

class RatingFormat
{
    /**
     * Průměrné hodnocení na jedno desetinné místo: CZ "4,9", EN "4.9"
     */
    public static function average(float $average, ?string $locale = null): string
    {
        $separator = ($locale ?? app()->getLocale()) === 'en' ? '.' : ',';

        return number_format($average, 1, $separator, '');
    }
}
