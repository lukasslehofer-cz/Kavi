<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/**
 * Firemní dárky (stránka Pro firmy). Stránka je jen česky, jen pro kavi.cz.
 */
class CorporateHelper
{
    private const TIMEZONE = 'Europe/Prague';

    /**
     * Zobrazovat stránku Pro firmy a odkazy na ni? Stejný vzor jako GiftHelper::enabled().
     */
    public static function enabled(): bool
    {
        return app()->getLocale() === 'cs' && Route::has('corporate.index');
    }

    /**
     * Pásma množstevní slevy z config/corporate.php, seřazená od nejmenšího.
     * value je hodnota pole "Přibližný počet kusů" ve formuláři (10-24, 25-49, 50+).
     *
     * @return array<int, array{value: string, from: int, to: ?int, percent: int|float}>
     */
    public static function tiers(): array
    {
        $discounts = config('corporate.discounts', []);
        ksort($discounts);
        $froms = array_keys($discounts);

        $tiers = [];
        foreach ($froms as $i => $from) {
            $to = isset($froms[$i + 1]) ? $froms[$i + 1] - 1 : null;
            $tiers[] = [
                'value' => $to !== null ? $from . '-' . $to : $from . '+',
                'from' => (int) $from,
                'to' => $to,
                'percent' => $discounts[$from],
            ];
        }

        return $tiers;
    }

    public static function maxDiscount(): int|float
    {
        return max(config('corporate.discounts', [0]) ?: [0]);
    }

    /**
     * Číslo pro text: 10 → "10", 7.5 → "7,5"
     */
    public static function formatNumber(int|float $value): string
    {
        return rtrim(rtrim(number_format($value, 1, ',', ''), '0'), ',');
    }

    /**
     * Vánoční termíny pro štítek v hero a větu v FAQ, nebo null, když se nemají ukázat:
     * jsou vypnuté v configu, data nejsou vyplněná, termín poptávky už minul (platí celý den),
     * nebo je před říjnem.
     *
     * @return array{year: int, inquiry_by: string, deliver_by: string}|null
     */
    public static function christmas(): ?array
    {
        $inquiryBy = config('corporate.christmas.inquiry_by');
        $deliverBy = config('corporate.christmas.deliver_by');

        if (! config('corporate.christmas.enabled', true) || ! $inquiryBy || ! $deliverBy) {
            return null;
        }

        $inquiry = Carbon::parse($inquiryBy, self::TIMEZONE)->endOfDay();
        $now = now(self::TIMEZONE);

        if ($now->gt($inquiry) || $now->month < 10) {
            return null;
        }

        // "1. 12." s pevnou mezerou, aby se datum nezalomilo
        return [
            'year' => $inquiry->year,
            'inquiry_by' => DateFormat::dayMonth($inquiry, 'cs'),
            'deliver_by' => DateFormat::dayMonth(Carbon::parse($deliverBy, self::TIMEZONE), 'cs'),
        ];
    }
}
