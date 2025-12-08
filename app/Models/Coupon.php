<?php

namespace App\Models;

use App\Helpers\CurrencyHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type_order',
        'discount_value_order',
        'discount_value_order_eur',
        'discount_type_subscription',
        'discount_value_subscription',
        'discount_value_subscription_eur',
        'subscription_discount_months',
        'free_shipping',
        'min_order_value',
        'min_order_value_eur',
        'valid_from',
        'valid_until',
        'usage_limit_total',
        'usage_limit_per_user',
        'times_used',
        'is_active',
    ];

    protected $casts = [
        'discount_value_order' => 'decimal:2',
        'discount_value_order_eur' => 'decimal:2',
        'discount_value_subscription' => 'decimal:2',
        'discount_value_subscription_eur' => 'decimal:2',
        'min_order_value' => 'decimal:2',
        'min_order_value_eur' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'free_shipping' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the order discount value in current currency
     */
    public function getOrderDiscountValue(): float
    {
        return CurrencyHelper::price($this->discount_value_order, $this->discount_value_order_eur);
    }

    /**
     * Get the subscription discount value in current currency
     */
    public function getSubscriptionDiscountValue(): float
    {
        return CurrencyHelper::price($this->discount_value_subscription, $this->discount_value_subscription_eur);
    }

    /**
     * Get the minimum order value in current currency
     */
    public function getMinOrderValue(): ?float
    {
        if ($this->min_order_value === null && $this->min_order_value_eur === null) {
            return null;
        }
        return CurrencyHelper::price($this->min_order_value ?? 0, $this->min_order_value_eur ?? 0);
    }

    /**
     * Vztah k použitím kupónu
     */
    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    /**
     * Vztah k objednávkám
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Vztah k předplatným
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Scope pro aktivní kupóny
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Ověří, zda je kupón platný (aktivní a v platnosti)
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_until && $now->gt($this->valid_until)) {
            return false;
        }

        return true;
    }

    /**
     * Ověří, zda není překročený celkový limit použití
     */
    public function hasReachedTotalLimit(): bool
    {
        if ($this->usage_limit_total === null) {
            return false;
        }

        return $this->times_used >= $this->usage_limit_total;
    }

    /**
     * Ověří, zda uživatel nepřekročil svůj limit použití
     */
    public function hasUserReachedLimit(?int $userId): bool
    {
        if ($this->usage_limit_per_user === null) {
            return false;
        }

        if ($userId === null) {
            return false; // Guest nemá omezení
        }

        $userUsageCount = $this->usages()->where('user_id', $userId)->count();

        return $userUsageCount >= $this->usage_limit_per_user;
    }

    /**
     * Ověří, zda je splněna minimální hodnota objednávky
     */
    public function meetsMinimumOrderValue(float $orderValue): bool
    {
        $minValue = $this->getMinOrderValue();
        if ($minValue === null) {
            return true;
        }

        return $orderValue >= $minValue;
    }

    /**
     * Vypočítá slevu pro jednorázový nákup
     */
    public function calculateOrderDiscount(float $subtotal): float
    {
        if ($this->discount_type_order === 'none') {
            return 0;
        }

        if ($this->discount_type_order === 'percentage') {
            return round($subtotal * ($this->discount_value_order / 100), 2);
        }

        if ($this->discount_type_order === 'fixed') {
            $discountValue = $this->getOrderDiscountValue();
            return min($discountValue, $subtotal); // Sleva nemůže být vyšší než subtotal
        }

        return 0;
    }

    /**
     * Vypočítá slevu pro předplatné
     */
    public function calculateSubscriptionDiscount(float $price): float
    {
        if ($this->discount_type_subscription === 'none') {
            return 0;
        }

        if ($this->discount_type_subscription === 'percentage') {
            return round($price * ($this->discount_value_subscription / 100), 2);
        }

        if ($this->discount_type_subscription === 'fixed') {
            $discountValue = $this->getSubscriptionDiscountValue();
            return min($discountValue, $price); // Sleva nemůže být vyšší než cena
        }

        return 0;
    }

    /**
     * Má kupón slevu pro jednorázové objednávky?
     */
    public function hasOrderDiscount(): bool
    {
        return $this->discount_type_order !== 'none' || $this->free_shipping;
    }

    /**
     * Má kupón slevu pro předplatné?
     */
    public function hasSubscriptionDiscount(): bool
    {
        return $this->discount_type_subscription !== 'none';
    }

    /**
     * Zvýší počet použití kupónu
     */
    public function incrementUsage(): void
    {
        $this->increment('times_used');
    }

    /**
     * Získá lidsky čitelný popis slevy pro objednávky
     */
    public function getOrderDiscountDescription(): string
    {
        if ($this->discount_type_order === 'percentage') {
            return "-{$this->discount_value_order}%";
        }

        if ($this->discount_type_order === 'fixed') {
            $value = $this->getOrderDiscountValue();
            return "-" . CurrencyHelper::formatAmount($value);
        }

        if ($this->free_shipping) {
            return CurrencyHelper::isCzk() ? "Doprava zdarma" : "Free shipping";
        }

        return CurrencyHelper::isCzk() ? "Žádná sleva" : "No discount";
    }

    /**
     * Získá lidsky čitelný popis slevy pro předplatné
     */
    public function getSubscriptionDiscountDescription(): string
    {
        if ($this->discount_type_subscription === 'percentage') {
            $desc = "-{$this->discount_value_subscription}%";
        } elseif ($this->discount_type_subscription === 'fixed') {
            $value = $this->getSubscriptionDiscountValue();
            $desc = "-" . CurrencyHelper::formatAmount($value);
        } else {
            return CurrencyHelper::isCzk() ? "Žádná sleva" : "No discount";
        }

        if ($this->subscription_discount_months) {
            if (CurrencyHelper::isCzk()) {
                $desc .= " po dobu {$this->subscription_discount_months} " . $this->getMonthsWord($this->subscription_discount_months);
            } else {
                $desc .= " for {$this->subscription_discount_months} " . ($this->subscription_discount_months === 1 ? 'month' : 'months');
            }
        } else {
            $desc .= CurrencyHelper::isCzk() ? " neomezeně" : " unlimited";
        }

        return $desc;
    }

    /**
     * Skloňování slova "měsíc"
     */
    private function getMonthsWord(int $count): string
    {
        if ($count === 1) {
            return "měsíc";
        } elseif ($count >= 2 && $count <= 4) {
            return "měsíce";
        } else {
            return "měsíců";
        }
    }
}

