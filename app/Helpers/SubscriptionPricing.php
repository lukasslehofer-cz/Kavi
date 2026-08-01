<?php

namespace App\Helpers;

use App\Models\Subscription;
use App\Models\SubscriptionPayment;

/**
 * Jediný zdroj pravdy o tom, kolik stojí platba předplatného.
 *
 * Než tenhle helper vznikl, byl stejný výpočet zkopírovaný v checkoutu, v cronu,
 * ve Stripe webhooku, na faktuře, v e-mailech i v Meta CAPI – a každá kopie se
 * lišila. Faktura přičítala dopravu dvakrát, opakované strhávání ji nepřičítalo
 * vůbec, admin notifikace ji vynechávala a ještě ji označila jako CZK.
 *
 * Pravidla, která platí bez výjimky:
 *
 * 1. boxPrice() je VŽDY brutto, tedy před slevou. Tomu odpovídá i význam sloupce
 *    configured_price (viz SubscriptionController).
 * 2. Složky se nezaokrouhlují samostatně – zaokrouhluje se až total, jednou, podle
 *    měny. Zaokrouhlení jednotlivých položek by u procentních kupónů posunulo
 *    i CZK součty (590 × 15 % = 88,50 → 89).
 * 3. Měna se čte ze subscription->currency, NIKDY ze session. Cron i Stripe webhook
 *    běží mimo request zákazníka, takže CurrencyHelper::code() by tam vrátil měnu
 *    podle hostname serveru.
 * 4. Doprava se účtuje u KAŽDÉ platby, ne jen u první. Sloupec shipping_cost je už
 *    0 pro země, kde se doprava k předplatnému neúčtuje (ShippingRate::calculateShipping
 *    respektuje applies_to_subscriptions), takže tady se na to není potřeba ptát znovu.
 */
class SubscriptionPricing
{
    /**
     * Měna předplatného, vždy uppercase.
     */
    public static function currencyOf(?Subscription $subscription): string
    {
        return strtoupper($subscription?->currency ?: 'CZK');
    }

    /**
     * Běží na předplatném sleva právě teď?
     *
     * discount_months_remaining === null znamená neomezenou slevu.
     * Tenhle predikát byl doteď zkopírovaný na osmi místech.
     */
    public static function hasActiveDiscount(Subscription $subscription): bool
    {
        return (float) $subscription->discount_amount > 0
            && ($subscription->discount_months_remaining === null
                || (int) $subscription->discount_months_remaining > 0);
    }

    /**
     * Brutto cena boxu před slevou.
     *
     * Fallback na plán jde přes getPriceFor(), ne přes syrový sloupec price –
     * ten je vždy v CZK a u EUR předplatného by se strhl jako eura.
     */
    public static function boxPrice(Subscription $subscription): float
    {
        if ($subscription->configured_price !== null) {
            return (float) $subscription->configured_price;
        }

        return (float) ($subscription->plan?->getPriceFor(self::currencyOf($subscription)) ?? 0);
    }

    public static function discount(Subscription $subscription): float
    {
        return self::hasActiveDiscount($subscription)
            ? (float) $subscription->discount_amount
            : 0.0;
    }

    public static function shippingCost(Subscription $subscription): float
    {
        return (float) ($subscription->shipping_cost ?? 0);
    }

    /**
     * Část dárkového voucheru, která pokryla dopravu.
     *
     * Voucher je platební prostředek, takže kryje celou objednávku včetně dopravy.
     * Uplatní se jen u první platby – u dalších cyklů už je vyčerpaný.
     */
    public static function giftVoucherShippingCredit(Subscription $subscription): float
    {
        return (float) ($subscription->gift_voucher_shipping_credit ?? 0);
    }

    /**
     * Rozpad první platby – jediná, u které se uplatní kredit z dárkového voucheru.
     */
    public static function forFirstPayment(Subscription $subscription): SubscriptionPriceBreakdown
    {
        return self::fromValues(
            self::boxPrice($subscription),
            self::discount($subscription),
            self::shippingCost($subscription),
            self::currencyOf($subscription),
            self::giftVoucherShippingCredit($subscription),
            self::hasActiveDiscount($subscription),
        );
    }

    /**
     * Rozpad opakované platby. Od první se liší jen tím, že voucher je už vyčerpaný.
     */
    public static function forRecurringPayment(Subscription $subscription): SubscriptionPriceBreakdown
    {
        return self::fromValues(
            self::boxPrice($subscription),
            self::discount($subscription),
            self::shippingCost($subscription),
            self::currencyOf($subscription),
            0.0,
            self::hasActiveDiscount($subscription),
        );
    }

    /**
     * Rozpad ke konkrétní zaznamenané platbě – sám pozná, jestli je první.
     */
    public static function forPayment(SubscriptionPayment $payment): SubscriptionPriceBreakdown
    {
        $subscription = $payment->subscription;

        if (! $subscription) {
            return self::fromValues(0.0, 0.0, 0.0, strtoupper($payment->currency ?: 'CZK'), 0.0, false);
        }

        return self::isFirstPayment($payment)
            ? self::forFirstPayment($subscription)
            : self::forRecurringPayment($subscription);
    }

    /**
     * Je tohle první platba předplatného?
     *
     * Podle nejnižšího id, ne podle paid_at – payments() je řazená sestupně
     * podle paid_at a u dvou plateb ve stejný den by se pořadí rozhodovalo náhodně.
     */
    public static function isFirstPayment(SubscriptionPayment $payment): bool
    {
        $firstId = SubscriptionPayment::where('subscription_id', $payment->subscription_id)->min('id');

        return $firstId !== null && (int) $firstId === (int) $payment->id;
    }

    /**
     * Rozpad z holých čísel – pro checkout, kde předplatné ještě neexistuje.
     */
    public static function fromValues(
        float $box,
        float $discount,
        float $shipping,
        ?string $currency,
        float $giftVoucherShippingCredit = 0.0,
        ?bool $hasActiveDiscount = null,
    ): SubscriptionPriceBreakdown {
        $currency = $currency ? strtoupper($currency) : 'CZK';

        // Voucher nemůže pokrýt víc dopravy, než kolik jí je.
        $giftVoucherShippingCredit = max(0.0, min($giftVoucherShippingCredit, $shipping));

        $total = max(0.0, self::roundFor($box - $discount + $shipping - $giftVoucherShippingCredit, $currency));

        return new SubscriptionPriceBreakdown(
            box: $box,
            shipping: $shipping,
            discount: $discount,
            giftVoucherShippingCredit: $giftVoucherShippingCredit,
            total: $total,
            currency: $currency,
            hasActiveDiscount: $hasActiveDiscount ?? ($discount > 0),
        );
    }

    /**
     * Počet desetinných míst dané měny. EUR má centy, CZK se vede v celých korunách.
     */
    public static function decimalsFor(?string $currency): int
    {
        return strtoupper($currency ?: 'CZK') === 'EUR' ? 2 : 0;
    }

    public static function roundFor(float $value, ?string $currency): float
    {
        return round($value, self::decimalsFor($currency));
    }

    /**
     * Částka pro Stripe v nejmenší jednotce měny.
     *
     * Zaokrouhlení musí proběhnout PŘED převodem na centy, ne po něm –
     * (int) (round($x) * 100) tiše zahodí centy u každé EUR částky.
     */
    public static function stripeAmount(float $total, ?string $currency): int
    {
        return CurrencyHelper::toSmallestUnit(self::roundFor($total, $currency));
    }
}
