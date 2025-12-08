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
    ];

    /**
     * Get the price in the current currency (CZK or EUR)
     */
    public function getPrice(): float
    {
        return CurrencyHelper::price($this->price, $this->price_eur);
    }

    /**
     * Get the formatted price with currency symbol
     */
    public function getFormattedPrice(int $decimals = 0): string
    {
        return CurrencyHelper::format($this->price, $this->price_eur, $decimals);
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
        $attributes = $this->attributes ?? [];
        
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




