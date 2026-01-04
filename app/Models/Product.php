<?php

namespace App\Models;

use App\Helpers\CurrencyHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'description',
        'description_en',
        'short_description',
        'short_description_en',
        'price',
        'price_eur',
        'stock',
        'image',
        'images',
        'category',
        'roastery_id',
        'attributes',
        'is_active',
        'is_featured',
        'free_shipping',
        'is_digital',
        'exclude_from_discounts',
        'is_coffee_of_month',
        'coffee_of_month_date',
        'sort_order',
        // Discount fields
        'discount_type',
        'discount_percent',
        'discount_amount_czk',
        'discount_amount_eur',
        'sale_start_date',
        'sale_end_date',
        'show_discount_percentage',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_eur' => 'decimal:2',
        'stock' => 'integer',
        'images' => 'array',
        'attributes' => 'array',
        'category' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'free_shipping' => 'boolean',
        'is_digital' => 'boolean',
        'exclude_from_discounts' => 'boolean',
        'is_coffee_of_month' => 'boolean',
        // Discount casts
        'discount_percent' => 'decimal:2',
        'discount_amount_czk' => 'decimal:2',
        'discount_amount_eur' => 'decimal:2',
        'sale_start_date' => 'datetime',
        'sale_end_date' => 'datetime',
        'show_discount_percentage' => 'boolean',
    ];

    /**
     * Get the price in the current currency (CZK or EUR)
     * Returns sale price if product is on sale
     */
    public function getPrice(): float
    {
        if ($this->isOnSale()) {
            return $this->getSalePrice();
        }
        return CurrencyHelper::price($this->price, $this->price_eur);
    }

    /**
     * Get the formatted price with currency symbol
     * Returns sale price if product is on sale
     */
    public function getFormattedPrice(int $decimals = 0): string
    {
        if ($this->isOnSale()) {
            return $this->getFormattedSalePrice($decimals);
        }
        return CurrencyHelper::format($this->price, $this->price_eur, $decimals);
    }

    /**
     * Check if the product is currently on sale
     * Respects time constraints and exclude_from_discounts flag
     */
    public function isOnSale(): bool
    {
        // Product excluded from discounts
        if ($this->exclude_from_discounts) {
            return false;
        }

        // No discount type set
        if (empty($this->discount_type)) {
            return false;
        }

        // Check if discount value is set
        if ($this->discount_type === 'percent' && (empty($this->discount_percent) || $this->discount_percent <= 0)) {
            return false;
        }

        if ($this->discount_type === 'amount') {
            $hasDiscountCzk = !empty($this->discount_amount_czk) && $this->discount_amount_czk > 0;
            $hasDiscountEur = !empty($this->discount_amount_eur) && $this->discount_amount_eur > 0;
            
            // For amount discount, check if the current currency has a discount set
            if (CurrencyHelper::isEur() && !$hasDiscountEur) {
                return false;
            }
            if (CurrencyHelper::isCzk() && !$hasDiscountCzk) {
                return false;
            }
        }

        // Check time constraints
        $now = now();
        
        if ($this->sale_start_date && $now->lt($this->sale_start_date)) {
            return false;
        }
        
        if ($this->sale_end_date && $now->gt($this->sale_end_date)) {
            return false;
        }

        return true;
    }

    /**
     * Get the original price in current currency (before discount)
     */
    public function getOriginalPrice(): float
    {
        return CurrencyHelper::price($this->price, $this->price_eur);
    }

    /**
     * Get the formatted original price with currency symbol
     */
    public function getFormattedOriginalPrice(int $decimals = 0): string
    {
        return CurrencyHelper::format($this->price, $this->price_eur, $decimals);
    }

    /**
     * Get the sale price in current currency
     * CZK: rounded to whole numbers
     * EUR: rounded to 1 decimal place
     */
    public function getSalePrice(): float
    {
        $originalPrice = $this->getOriginalPrice();
        
        if ($this->discount_type === 'percent') {
            $discountAmount = $originalPrice * ($this->discount_percent / 100);
            $salePrice = $originalPrice - $discountAmount;
        } elseif ($this->discount_type === 'amount') {
            if (CurrencyHelper::isEur()) {
                $salePrice = $originalPrice - ($this->discount_amount_eur ?? 0);
            } else {
                $salePrice = $originalPrice - ($this->discount_amount_czk ?? 0);
            }
        } else {
            return $originalPrice;
        }

        // Ensure price doesn't go below 0
        $salePrice = max(0, $salePrice);

        // Round based on currency
        if (CurrencyHelper::isEur()) {
            return round($salePrice, 1); // EUR: round to 1 decimal
        }
        
        return round($salePrice); // CZK: round to whole numbers
    }

    /**
     * Get the formatted sale price with currency symbol
     */
    public function getFormattedSalePrice(int $decimals = 0): string
    {
        $salePrice = $this->getSalePrice();
        
        // For EUR, use 1 decimal if decimals is 0
        if (CurrencyHelper::isEur() && $decimals === 0) {
            $decimals = 1;
        }
        
        return CurrencyHelper::formatAmount($salePrice, $decimals);
    }

    /**
     * Get the discount percentage (calculated)
     * Returns null if not on sale
     */
    public function getDiscountPercentage(): ?int
    {
        if (!$this->isOnSale()) {
            return null;
        }

        if ($this->discount_type === 'percent') {
            return (int) round($this->discount_percent);
        }

        // Calculate percentage from amount
        $originalPrice = $this->getOriginalPrice();
        if ($originalPrice <= 0) {
            return null;
        }

        $salePrice = $this->getSalePrice();
        $percentage = (($originalPrice - $salePrice) / $originalPrice) * 100;
        
        return (int) round($percentage);
    }

    /**
     * Check if discount percentage should be displayed
     */
    public function shouldShowDiscountPercentage(): bool
    {
        return $this->isOnSale() && $this->show_discount_percentage;
    }

    /**
     * Get the discount amount saved in current currency
     */
    public function getDiscountAmount(): float
    {
        if (!$this->isOnSale()) {
            return 0;
        }
        
        return $this->getOriginalPrice() - $this->getSalePrice();
    }

    /**
     * Clear all discount fields
     */
    public function clearDiscount(): void
    {
        $this->update([
            'discount_type' => null,
            'discount_percent' => null,
            'discount_amount_czk' => null,
            'discount_amount_eur' => null,
            'sale_start_date' => null,
            'sale_end_date' => null,
            'show_discount_percentage' => true,
        ]);
    }

    /**
     * Get translated name based on current locale
     */
    public function getName(): string
    {
        if (app()->getLocale() === 'en' && !empty($this->name_en)) {
            return $this->name_en;
        }
        return $this->name;
    }

    /**
     * Get translated description based on current locale
     */
    public function getDescription(): string
    {
        if (app()->getLocale() === 'en' && !empty($this->description_en)) {
            return $this->description_en;
        }
        return $this->description ?? '';
    }

    /**
     * Get translated short description based on current locale
     */
    public function getShortDescription(): ?string
    {
        if (app()->getLocale() === 'en' && !empty($this->short_description_en)) {
            return $this->short_description_en;
        }
        return $this->short_description;
    }

    /**
     * Get translated attribute value based on current locale
     * Looks for {key}_en in attributes array when in EN mode
     * 
     * @param string $key The attribute key (e.g., 'origin', 'processing')
     * @return mixed
     */
    public function getTranslatedAttribute(string $key)
    {
        $attributes = $this->getAttribute('attributes') ?? [];
        
        if (app()->getLocale() === 'en') {
            $enKey = $key . '_en';
            if (!empty($attributes[$enKey])) {
                return $attributes[$enKey];
            }
        }
        
        return $attributes[$key] ?? null;
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function roastery()
    {
        return $this->belongsTo(Roastery::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForShop($query)
    {
        // Pro eshop - aktivní produkty, které NEJSOU kávy měsíce
        return $query->where('is_active', true)
                     ->where('is_coffee_of_month', false);
    }

    /**
     * Filter products that have a valid price in the current currency
     * EUR: price_eur must be set and > 0
     * CZK: price must be set and > 0
     */
    public function scopeWithPriceInCurrentCurrency($query)
    {
        if (CurrencyHelper::isEur()) {
            return $query->whereNotNull('price_eur')->where('price_eur', '>', 0);
        }
        return $query->whereNotNull('price')->where('price', '>', 0);
    }

    /**
     * Check if the product has a valid price in the current currency
     */
    public function hasPriceInCurrentCurrency(): bool
    {
        if (CurrencyHelper::isEur()) {
            return $this->price_eur !== null && $this->price_eur > 0;
        }
        return $this->price !== null && $this->price > 0;
    }

    public function scopeCoffeeOfMonth($query)
    {
        return $query->where('is_coffee_of_month', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeCategory($query, $category)
    {
        return $query->whereJsonContains('category', $category);
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get coffees of the month based on current date
     * Logic: Show coffees for next month starting from billing_date + 1
     */
    public static function getCoffeesOfMonth()
    {
        $today = now();
        
        // Get billing_date for current month from ShipmentSchedule
        $currentSchedule = \App\Models\ShipmentSchedule::getForMonth($today->year, $today->month);
        
        // Determine display cutoff date (billing_date + 1 day)
        if ($currentSchedule && $currentSchedule->billing_date) {
            $cutoffDate = $currentSchedule->billing_date->copy()->addDay();
        } else {
            // Fallback to 16th if no schedule configured
            $cutoffDate = $today->copy()->day(16);
        }
        
        // If today is on or after cutoff date, show next month
        if ($today->greaterThanOrEqualTo($cutoffDate)) {
            $targetMonth = $today->copy()->addMonthNoOverflow()->format('Y-m');
        } else {
            $targetMonth = $today->format('Y-m');
        }
        
        return self::with('roastery')
            ->where('is_coffee_of_month', true)
            ->where('coffee_of_month_date', $targetMonth)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Scope to get products by coffee_of_month_date (format: Y-m)
     */
    public function scopeByMonthDate($query, $month)
    {
        return $query->where('coffee_of_month_date', $month);
    }

    /**
     * Check if cart qualifies for free shipping (all products have free_shipping = true)
     *
     * @param array $cart Array of product_id => quantity
     * @return bool
     */
    public static function cartQualifiesForFreeShipping(array $cart): bool
    {
        if (empty($cart)) {
            return false;
        }

        $productIds = array_keys($cart);
        
        // Count products that don't have free_shipping
        $nonFreeShippingCount = self::whereIn('id', $productIds)
            ->where('free_shipping', false)
            ->count();

        return $nonFreeShippingCount === 0;
    }

    /**
     * Check if cart contains only digital products (all products have is_digital = true)
     *
     * @param array $cart Array of product_id => quantity
     * @return bool
     */
    public static function cartContainsOnlyDigitalProducts(array $cart): bool
    {
        if (empty($cart)) {
            return false;
        }

        $productIds = array_keys($cart);
        
        // Count products that are NOT digital
        $nonDigitalCount = self::whereIn('id', $productIds)
            ->where('is_digital', false)
            ->count();

        return $nonDigitalCount === 0;
    }
}




