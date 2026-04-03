<?php

namespace App\Services;

use App\Helpers\VatHelper;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\User;

class CouponService
{
    /**
     * Validuje kupón a vrátí chybovou zprávu nebo null pokud je OK
     */
    public function validateCoupon(
        string $code,
        ?User $user,
        string $type = 'order', // 'order' nebo 'subscription'
        ?float $orderValue = null
    ): array {
        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon) {
            return ['valid' => false, 'message' => 'Kupón nebyl nalezen.'];
        }

        if (! $coupon->isValid()) {
            return ['valid' => false, 'message' => 'Kupón již není platný.'];
        }

        if ($coupon->hasReachedTotalLimit()) {
            return ['valid' => false, 'message' => 'Kupón dosáhl maximálního počtu použití.'];
        }

        if ($coupon->hasUserReachedLimit($user?->id)) {
            return ['valid' => false, 'message' => 'Již jste tento kupón použili maximální počet krát.'];
        }

        // Kontrola typu kupónu
        if ($type === 'order' && ! $coupon->hasOrderDiscount()) {
            return ['valid' => false, 'message' => 'Tento kupón nelze použít pro jednorázové objednávky.'];
        }

        if ($type === 'subscription' && ! $coupon->hasSubscriptionDiscount()) {
            return ['valid' => false, 'message' => 'Tento kupón nelze použít pro předplatné.'];
        }

        // Kontrola, zda uživatel již někdy použil jakýkoliv kupón na předplatné
        if ($type === 'subscription' && $user !== null) {
            if (! $coupon->allow_repeated_subscription_usage && $this->hasUserEverUsedSubscriptionCoupon($user->id)) {
                return [
                    'valid' => false,
                    'message' => 'Slevový kód pro předplatné lze použít pouze jednou. Již jste v minulosti využili slevu na předplatné.',
                ];
            }
        }

        // Kontrola minimální hodnoty objednávky (pouze pro jednorázové objednávky)
        if ($type === 'order' && $orderValue !== null && ! $coupon->meetsMinimumOrderValue($orderValue)) {
            $formattedMin = \App\Helpers\CurrencyHelper::formatAmount($coupon->getMinOrderValue());

            return [
                'valid' => false,
                'message' => "Minimální hodnota objednávky pro tento kupón je {$formattedMin}.",
            ];
        }

        return ['valid' => true, 'coupon' => $coupon];
    }

    /**
     * Aplikuje kupón na objednávku a vrátí upravené ceny
     *
     * @param  float  $subtotal  Celková cena produktů
     * @param  float  $shipping  Cena dopravy
     * @param  float|null  $discountableSubtotal  Cena produktů, na které se vztahuje sleva (null = použít subtotal)
     * @param  array|null  $items  Pole položek s 'total' a 'vat_rate' pro proporcionální výpočet DPH
     */
    public function applyToOrder(Coupon $coupon, float $subtotal, float $shipping, ?float $discountableSubtotal = null, ?array $items = null): array
    {
        $discount = 0;
        $freeShipping = false;

        // Pokud není specifikován discountableSubtotal, použít celý subtotal
        $discountableAmount = $discountableSubtotal ?? $subtotal;

        // Sleva z částky - pouze z produktů, které nejsou vyloučeny ze slev
        if ($coupon->hasOrderDiscount()) {
            $discount = $coupon->calculateOrderDiscount($discountableAmount);
        }

        // Doprava zdarma
        if ($coupon->free_shipping) {
            $freeShipping = true;
            $shipping = 0;
        }

        $newSubtotal = $subtotal - $discount;
        $newTotal = $newSubtotal + $shipping;

        // Proporcionální výpočet DPH pokud jsou poskytnuty položky
        if ($items !== null && ! empty($items)) {
            // Agregovat položky podle DPH sazeb
            $itemsByVatRate = [];
            foreach ($items as $item) {
                $vatRate = $item['vat_rate'];
                if (! isset($itemsByVatRate[$vatRate])) {
                    $itemsByVatRate[$vatRate] = 0;
                }
                $itemsByVatRate[$vatRate] += $item['total'];
            }

            $totalNet = 0;
            $vat = 0;

            // DPH z položek po slevě (u dárkového voucheru se DPH počítá z plné ceny)
            foreach ($itemsByVatRate as $vatRate => $amount) {
                if ($coupon->is_gift_voucher) {
                    // Gift voucher: DPH z plné ceny (voucher je platební metoda, ne sleva)
                    $totalNet += VatHelper::calculateNet($amount, $vatRate);
                    $vat += VatHelper::calculateVat($amount, $vatRate);
                } else {
                    $proportion = $amount / $subtotal;
                    $discountPortion = $discount * $proportion;
                    $amountAfterDiscount = $amount - $discountPortion;

                    $totalNet += VatHelper::calculateNet($amountAfterDiscount, $vatRate);
                    $vat += VatHelper::calculateVat($amountAfterDiscount, $vatRate);
                }
            }

            // DPH z dopravy (proporcionální rozdělení)
            if ($shipping > 0) {
                $shippingByVat = VatHelper::calculateProportionalShipping($itemsByVatRate, $shipping);
                foreach ($shippingByVat as $vatRate => $shippingPortion) {
                    $totalNet += VatHelper::calculateNet($shippingPortion, $vatRate);
                    $vat += VatHelper::calculateVat($shippingPortion, $vatRate);
                }
            }

            $totalWithoutVat = $totalNet;
        } else {
            // Fallback na starý výpočet pokud items nejsou poskytnuty
            $totalWithoutVat = round($newTotal / 1.21, 2);
            $vat = round($newTotal - $totalWithoutVat, 2);
        }

        return [
            'discount' => $discount,
            'subtotal' => $newSubtotal,
            'shipping' => $shipping,
            'total' => $newTotal,
            'total_without_vat' => $totalWithoutVat,
            'vat' => $vat,
            'free_shipping' => $freeShipping,
            'is_gift_voucher' => $coupon->is_gift_voucher,
        ];
    }

    /**
     * Aplikuje kupón na předplatné a vrátí upravenou cenu
     */
    public function applyToSubscription(Coupon $coupon, float $price): array
    {
        $discount = $coupon->calculateSubscriptionDiscount($price);
        $newPrice = $price - $discount;

        // Přepočet DPH - předplatné je vždy káva = 12% DPH
        // U dárkového voucheru se DPH počítá z plné ceny (voucher je platební metoda)
        $subscriptionVatRate = 12.00;
        $vatBase = $coupon->is_gift_voucher ? $price : $newPrice;
        $priceWithoutVat = VatHelper::calculateNet($vatBase, $subscriptionVatRate);
        $vat = VatHelper::calculateVat($vatBase, $subscriptionVatRate);

        return [
            'discount' => $discount,
            'price' => $newPrice,
            'price_without_vat' => $priceWithoutVat,
            'vat' => $vat,
            'months' => $coupon->subscription_discount_months, // null = neomezeně
            'is_gift_voucher' => $coupon->is_gift_voucher,
        ];
    }

    /**
     * Zaznamená použití kupónu
     */
    public function recordUsage(
        Coupon $coupon,
        ?User $user,
        string $type,
        float $discountAmount,
        ?Order $order = null,
        ?Subscription $subscription = null
    ): CouponUsage {
        // IDEMPOTENCE: Check if usage already exists
        $existingUsage = null;

        if ($order) {
            $existingUsage = CouponUsage::where('coupon_id', $coupon->id)
                ->where('order_id', $order->id)
                ->first();
        } elseif ($subscription) {
            $existingUsage = CouponUsage::where('coupon_id', $coupon->id)
                ->where('subscription_id', $subscription->id)
                ->first();
        }

        if ($existingUsage) {
            \Log::info('Coupon usage already recorded (idempotent)', [
                'coupon_id' => $coupon->id,
                'order_id' => $order?->id,
                'subscription_id' => $subscription?->id,
                'existing_usage_id' => $existingUsage->id,
            ]);

            return $existingUsage;
        }

        // Zvýšit počítadlo použití
        $coupon->incrementUsage();

        // Vytvořit záznam o použití
        return CouponUsage::create([
            'coupon_id' => $coupon->id,
            'user_id' => $user?->id,
            'order_id' => $order?->id,
            'subscription_id' => $subscription?->id,
            'usage_type' => $type,
            'discount_amount' => $discountAmount,
        ]);
    }

    /**
     * Získá kupón z cookie nebo session
     */
    public function getCouponFromStorage(): ?string
    {
        // 1. Nejprve ručně zadaný kód (nejvyšší priorita)
        $code = session('coupon_code');

        if (! $code) {
            // 2. Zkusit cookie (uložené z linku)
            $code = request()->cookie('coupon_code');
        }

        if (! $code) {
            // 3. Fallback na affiliate kód ze session
            $code = session('affiliate_code');
        }

        return $code;
    }

    /**
     * Uloží kupón do cookie (pro aktivaci přes link)
     */
    public function storeCouponInCookie(string $code): \Symfony\Component\HttpFoundation\Cookie
    {
        return cookie('coupon_code', $code, 60 * 24 * 7); // 7 dní
    }

    /**
     * Smaže kupón z cookie a session
     */
    public function clearCouponFromStorage(): void
    {
        session()->forget('coupon_code');
        cookie()->queue(cookie()->forget('coupon_code'));
    }

    /**
     * Zkontroluje, zda předplatné má aktivní slevu
     */
    public function hasActiveDiscount(Subscription $subscription): bool
    {
        if (! $subscription->coupon_id || ! $subscription->discount_amount) {
            return false;
        }

        // Pokud je discount_months_remaining null, sleva je neomezená
        if ($subscription->discount_months_remaining === null) {
            return true;
        }

        // Pokud zbývají měsíce se slevou
        return $subscription->discount_months_remaining > 0;
    }

    /**
     * Sníží počet zbývajících měsíců slevy předplatného
     */
    public function decrementSubscriptionDiscountMonth(Subscription $subscription): void
    {
        if ($subscription->discount_months_remaining === null) {
            // Neomezená sleva, nic nedělat
            return;
        }

        if ($subscription->discount_months_remaining > 0) {
            $subscription->decrement('discount_months_remaining');

            // Pokud došly měsíce, vymazat slevu
            if ($subscription->discount_months_remaining <= 0) {
                $subscription->update([
                    'discount_amount' => 0,
                    'discount_months_remaining' => 0,
                ]);
            }
        }
    }

    /**
     * Získá aktuální cenu předplatného (s případnou slevou)
     */
    public function getSubscriptionPrice(Subscription $subscription): float
    {
        $basePrice = $subscription->configured_price ?? $subscription->plan?->price ?? 0;

        if ($this->hasActiveDiscount($subscription)) {
            return max(0, $basePrice - $subscription->discount_amount);
        }

        return $basePrice;
    }

    /**
     * Zkontroluje, zda uživatel již někdy použil jakýkoliv kupón na předplatné
     */
    public function hasUserEverUsedSubscriptionCoupon(int $userId): bool
    {
        return CouponUsage::where('user_id', $userId)
            ->where('usage_type', 'subscription')
            ->exists();
    }
}
