<?php

namespace App\Helpers;

class VatHelper
{
    /**
     * Vrátí dělitel pro výpočet čisté ceny z ceny s DPH
     * Např. pro 12% DPH vrátí 1.12, pro 21% DPH vrátí 1.21
     *
     * @param float $vatRate DPH sazba v procentech (12, 21, atd.)
     * @return float Dělitel pro výpočet
     */
    public static function getDivisor(float $vatRate): float
    {
        return 1 + ($vatRate / 100);
    }

    /**
     * Spočítá částku DPH z ceny s DPH
     *
     * @param float $grossPrice Cena včetně DPH
     * @param float $vatRate DPH sazba v procentech
     * @return float Částka DPH
     */
    public static function calculateVat(float $grossPrice, float $vatRate): float
    {
        $netPrice = self::calculateNet($grossPrice, $vatRate);
        return round($grossPrice - $netPrice, 2);
    }

    /**
     * Spočítá čistou cenu bez DPH z ceny s DPH
     *
     * @param float $grossPrice Cena včetně DPH
     * @param float $vatRate DPH sazba v procentech
     * @return float Čistá cena bez DPH
     */
    public static function calculateNet(float $grossPrice, float $vatRate): float
    {
        return round($grossPrice / self::getDivisor($vatRate), 2);
    }

    /**
     * Vypočítá proporcionální rozdělení dopravy podle DPH sazeb položek
     *
     * Příklad: košík obsahuje kávu za 300 Kč (12% DPH) a accessories za 200 Kč (21% DPH)
     * Doprava 100 Kč se rozdělí: 60 Kč s 12% DPH (60%), 40 Kč s 21% DPH (40%)
     *
     * @param array $itemsByVatRate Asociativní pole [vatRate => totalAmount]
     * @param float $shippingCost Celková cena dopravy
     * @return array Asociativní pole [vatRate => shippingPortion]
     */
    public static function calculateProportionalShipping(array $itemsByVatRate, float $shippingCost): array
    {
        if (empty($itemsByVatRate) || $shippingCost <= 0) {
            return [];
        }

        $totalItems = array_sum($itemsByVatRate);
        if ($totalItems <= 0) {
            return [];
        }

        $shippingByVat = [];

        foreach ($itemsByVatRate as $vatRate => $amount) {
            $proportion = $amount / $totalItems;
            $shippingByVat[$vatRate] = round($shippingCost * $proportion, 2);
        }

        // Ošetření zaokrouhlovacích chyb - poslední položka dostane rozdíl
        $calculatedTotal = array_sum($shippingByVat);
        if ($calculatedTotal !== $shippingCost) {
            $lastVatRate = array_key_last($shippingByVat);
            $shippingByVat[$lastVatRate] += ($shippingCost - $calculatedTotal);
            $shippingByVat[$lastVatRate] = round($shippingByVat[$lastVatRate], 2);
        }

        return $shippingByVat;
    }
}
