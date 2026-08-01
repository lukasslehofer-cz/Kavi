<?php

namespace App\Helpers;

/**
 * Neměnný rozpad ceny jedné platby předplatného.
 *
 * Vytváří se výhradně přes SubscriptionPricing – nesestavuj ho ručně, jinak se
 * vrátí přesně ta roztříštěnost výpočtů, kvůli které vznikl.
 */
class SubscriptionPriceBreakdown
{
    public function __construct(
        /** Brutto cena boxu PŘED slevou */
        public readonly float $box,
        /** Doprava; 0 pokud se pro danou zemi neúčtuje */
        public readonly float $shipping,
        /** Aktivní sleva; 0 pokud žádná neběží */
        public readonly float $discount,
        /** Část dárkového voucheru pokrývající dopravu (jen první platba) */
        public readonly float $giftVoucherShippingCredit,
        /** box − sleva + doprava − voucher, zaokrouhleno dle měny, minimálně 0 */
        public readonly float $total,
        /** Vždy uppercase */
        public readonly string $currency,
        public readonly bool $hasActiveDiscount,
    ) {}

    /**
     * Celková sleva včetně části voucheru, která padla na dopravu.
     * Tohle patří na fakturu jako jeden záporný řádek.
     */
    public function totalDiscount(): float
    {
        return $this->discount + $this->giftVoucherShippingCredit;
    }

    /**
     * Sedí rozpad se skutečně stržnou částkou?
     */
    public function reconcilesWith(float $amount, float $tolerance = 0.01): bool
    {
        return abs($this->total - $amount) <= $tolerance;
    }

    public function deltaAgainst(float $amount): float
    {
        return round($this->total - $amount, 2);
    }

    public function toArray(): array
    {
        return [
            'box' => $this->box,
            'shipping' => $this->shipping,
            'discount' => $this->discount,
            'gift_voucher_shipping_credit' => $this->giftVoucherShippingCredit,
            'total' => $this->total,
            'currency' => $this->currency,
        ];
    }
}
