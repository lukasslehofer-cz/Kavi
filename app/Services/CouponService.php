<?php

namespace App\Services;

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

        if (!$coupon) {
            return ['valid' => false, 'message' => 'Kupón nebyl nalezen.'];
        }

        if (!$coupon->isValid()) {
            return ['valid' => false, 'message' => 'Kupón již není platný.'];
        }

        if ($coupon->hasReachedTotalLimit()) {
            return ['valid' => false, 'message' => 'Kupón dosáhl maximálního počtu použití.'];
        }

        if ($coupon->hasUserReachedLimit($user?->id)) {
            return ['valid' => false, 'message' => 'Již jste tento kupón použili maximální počet krát.'];
        }

        // Kontrola typu kupónu
        if ($type === 'order' && !$coupon->hasOrderDiscount()) {
            return ['valid' => false, 'message' => 'Tento kupón nelze použít pro jednorázové objednávky.'];
        }

        if ($type === 'subscription' && !$coupon->hasSubscriptionDiscount()) {
            return ['valid' => false, 'message' => 'Tento kupón nelze použít pro předplatné.'];
        }

        // Kontrola minimální hodnoty objednávky
        if ($orderValue !== null && !$coupon->meetsMinimumOrderValue($orderValue)) {
            return [
                'valid' => false,
                'message' => "Minimální hodnota objednávky pro tento kupón je {$coupon->min_order_value} Kč."
            ];
        }

        return ['valid' => true, 'coupon' => $coupon];
    }

    /**
     * Aplikuje kupón na objednávku a vrátí upravené ceny
     */
    public function applyToOrder(Coupon $coupon, float $subtotal, float $shipping): array
    {
        $discount = 0;
        $freeShipping = false;

        // Sleva z částky
        if ($coupon->hasOrderDiscount()) {
            $discount = $coupon->calculateOrderDiscount($subtotal);
        }

        // Doprava zdarma
        if ($coupon->free_shipping) {
            $freeShipping = true;
            $shipping = 0;
        }

        $newSubtotal = $subtotal - $discount;
        $newTotal = $newSubtotal + $shipping;

        // Přepočet DPH (ceny včetně 21% DPH)
        $totalWithoutVat = round($newTotal / 1.21, 2);
        $vat = round($newTotal - $totalWithoutVat, 2);

        return [
            'discount' => $discount,
            'subtotal' => $newSubtotal,
            'shipping' => $shipping,
            'total' => $newTotal,
            'total_without_vat' => $totalWithoutVat,
            'vat' => $vat,
            'free_shipping' => $freeShipping,
        ];
    }

    /**
     * Aplikuje kupón na předplatné a vrátí upravenou cenu
     */
    public function applyToSubscription(Coupon $coupon, float $price): array
    {
        $discount = $coupon->calculateSubscriptionDiscount($price);
        $newPrice = $price - $discount;

        // Přepočet DPH (ceny včetně 21% DPH)
        $priceWithoutVat = round($newPrice / 1.21, 2);
        $vat = round($newPrice - $priceWithoutVat, 2);

        return [
            'discount' => $discount,
            'price' => $newPrice,
            'price_without_vat' => $priceWithoutVat,
            'vat' => $vat,
            'months' => $coupon->subscription_discount_months, // null = neomezeně
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
        // Nejprve zkusit session (aktuální požadavek)
        $code = session('coupon_code');
        
        if (!$code) {
            // Zkusit cookie (uložené z linku)
            $code = request()->cookie('coupon_code');
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
        if (!$subscription->coupon_id || !$subscription->discount_amount) {
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
}

