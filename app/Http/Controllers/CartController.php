<?php

namespace App\Http\Controllers;

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

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product && $product->is_active) {
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->price * $quantity,
                ];
                $total += $product->price * $quantity;
            }
        }

        // Calculate shipping if user country is known
        $shipping = null;
        $shippingMessage = null;
        $freeShippingThreshold = null;
        $remainingForFreeShipping = null;
        $userCountry = auth()->check() && auth()->user()->country ? auth()->user()->country : null;
        
        if ($userCountry) {
            $shipping = $this->shippingService->calculateShippingCost($userCountry, $total, false);
            $remainingForFreeShipping = $this->shippingService->getRemainingForFreeShipping($userCountry, $total);
            
            // Get threshold for display
            $rate = \App\Models\ShippingRate::getForCountry($userCountry);
            $freeShippingThreshold = $rate?->free_shipping_threshold_czk;
        } else {
            $shippingMessage = 'Cena dopravy bude vypočítána v pokladně po zadání adresy';
        }

        return view('cart.index', compact('cartItems', 'total', 'shipping', 'shippingMessage', 'freeShippingThreshold', 'remainingForFreeShipping'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if (!$product->is_active || !$product->isInStock()) {
            return back()->with('error', 'Produkt není k dispozici.');
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
            return back()->with('error', 'Omlouváme se, ale nemáme dostatek zásob.');
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Produkt byl přidán do košíku.');
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
                return back()->with('error', 'Nemáme dostatek zásob.');
            }
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Košík byl aktualizován.');
    }

    public function remove($productId)
    {
        $cart = session()->get('cart', []);
        unset($cart[$productId]);
        session()->put('cart', $cart);

        return back()->with('success', 'Produkt byl odstraněn z košíku.');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Košík byl vyprázdněn.');
    }
}




