<?php

namespace App\Http\Controllers;

use App\Helpers\CurrencyHelper;
use App\Models\Product;
use App\Services\ShippingService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private ShippingService $shippingService)
    {
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        $cartItems = [];
        $total = 0;
        $totalEur = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product && $product->is_active) {
                // Use sale-aware pricing
                if ($product->isOnSale()) {
                    // Get sale price for each currency
                    $priceCzk = CurrencyHelper::isCzk() 
                        ? $product->getSalePrice() 
                        : $this->calculateSalePriceCzk($product);
                    $priceEur = CurrencyHelper::isEur() 
                        ? $product->getSalePrice() 
                        : $this->calculateSalePriceEur($product);
                } else {
                    $priceCzk = $product->price ?? 0;
                    $priceEur = $product->price_eur ?? 0;
                }
                
                $subtotal = $priceCzk * $quantity;
                $subtotalEur = $priceEur * $quantity;
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                    'subtotal_eur' => $subtotalEur,
                ];
                $total += $subtotal;
                $totalEur += $subtotalEur;
            }
        }

        // Calculate shipping if user country is known
        $shipping = null;
        $shippingEur = null;
        $shippingMessage = null;
        $freeShippingThreshold = null;
        $remainingForFreeShipping = null;
        $remainingForFreeShippingEur = null;
        $userCountry = auth()->check() && auth()->user()->country ? auth()->user()->country : null;
        
        // Check if cart qualifies for free shipping (all products have free_shipping flag)
        $cartQualifiesForFreeShipping = Product::cartQualifiesForFreeShipping($cart);
        
        if ($cartQualifiesForFreeShipping) {
            // All products in cart have free_shipping - no shipping charge
            $shipping = 0;
            $shippingEur = 0;
            $shippingMessage = app()->getLocale() === 'en' 
                ? 'Free shipping (digital product)' 
                : 'Doprava zdarma (digitální produkt)';
        } elseif ($userCountry) {
            $shipping = $this->shippingService->calculateShippingCost($userCountry, $total, false);
            $remainingForFreeShipping = $this->shippingService->getRemainingForFreeShipping($userCountry, $total);
            
            // Get threshold and EUR values for display
            $rate = \App\Models\ShippingRate::getForCountry($userCountry);
            $freeShippingThreshold = $rate?->free_shipping_threshold_czk;
            $shippingEur = $rate?->price_eur ?? 0;
            $remainingForFreeShippingEur = $rate?->free_shipping_threshold_eur 
                ? max(0, $rate->free_shipping_threshold_eur - $totalEur)
                : null;
        } else {
            $shippingMessage = app()->getLocale() === 'en' 
                ? 'Shipping cost will be calculated at checkout'
                : 'Cena dopravy bude vypočítána v pokladně po zadání adresy';
        }

        // Get recommended products from same categories
        $cartProductIds = array_keys($cart);
        $cartCategories = collect($cartItems)
            ->pluck('product.category')
            ->flatten()
            ->unique()
            ->filter()
            ->values()
            ->toArray();

        $recommendedProducts = collect();
        if (!empty($cartCategories)) {
            $recommendedProducts = Product::forShop()
                ->where('stock', '>', 0)
                ->whereNotIn('id', $cartProductIds)
                ->where(function($query) use ($cartCategories) {
                    foreach ($cartCategories as $category) {
                        $query->orWhereJsonContains('category', $category);
                    }
                })
                ->inRandomOrder()
                ->limit(4)
                ->get();
        }

        return view('cart.index', compact(
            'cartItems', 
            'total', 
            'totalEur',
            'shipping', 
            'shippingEur',
            'shippingMessage', 
            'freeShippingThreshold', 
            'remainingForFreeShipping',
            'remainingForFreeShippingEur',
            'recommendedProducts'
        ));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if (!$product->is_active || !$product->isInStock()) {
            $errorMessage = app()->getLocale() === 'en' 
                ? 'Product is not available.'
                : 'Produkt není k dispozici.';
            return back()->with('error', $errorMessage);
        }

        $cart = session()->get('cart', []);
        $productId = $product->id;
        $quantity = $request->quantity;

        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        // Check stock
        if ($cart[$productId] > $product->stock) {
            $errorMessage = app()->getLocale() === 'en' 
                ? 'Sorry, we don\'t have enough stock.'
                : 'Omlouváme se, ale nemáme dostatek zásob.';
            return back()->with('error', $errorMessage);
        }

        session()->put('cart', $cart);

        return back()->with('success', __('cart.added'));
    }

    public function update(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = session()->get('cart', []);

        if ($request->quantity == 0) {
            unset($cart[$productId]);
        } else {
            $product = Product::find($productId);
            if ($product && $request->quantity <= $product->stock) {
                $cart[$productId] = $request->quantity;
            } else {
                $errorMessage = app()->getLocale() === 'en' 
                    ? 'We don\'t have enough stock.'
                    : 'Nemáme dostatek zásob.';
                return back()->with('error', $errorMessage);
            }
        }

        session()->put('cart', $cart);

        return back()->with('success', __('cart.updated'));
    }

    public function remove($productId)
    {
        $cart = session()->get('cart', []);
        unset($cart[$productId]);
        session()->put('cart', $cart);

        return back()->with('success', __('cart.removed'));
    }

    public function clear()
    {
        session()->forget('cart');
        $successMessage = app()->getLocale() === 'en' 
            ? 'Cart cleared.'
            : 'Košík byl vyprázdněn.';
        return back()->with('success', $successMessage);
    }

    /**
     * Calculate sale price in CZK for a product (when current currency might be EUR)
     */
    private function calculateSalePriceCzk(Product $product): float
    {
        $originalPrice = $product->price ?? 0;
        
        if ($product->discount_type === 'percent') {
            $discountAmount = $originalPrice * ($product->discount_percent / 100);
            return round(max(0, $originalPrice - $discountAmount));
        } elseif ($product->discount_type === 'amount') {
            return round(max(0, $originalPrice - ($product->discount_amount_czk ?? 0)));
        }
        
        return $originalPrice;
    }

    /**
     * Calculate sale price in EUR for a product (when current currency might be CZK)
     */
    private function calculateSalePriceEur(Product $product): float
    {
        $originalPrice = $product->price_eur ?? 0;
        
        if ($product->discount_type === 'percent') {
            $discountAmount = $originalPrice * ($product->discount_percent / 100);
            return round(max(0, $originalPrice - $discountAmount), 1);
        } elseif ($product->discount_type === 'amount') {
            return round(max(0, $originalPrice - ($product->discount_amount_eur ?? 0)), 1);
        }
        
        return $originalPrice;
    }
}




