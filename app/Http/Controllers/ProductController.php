<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('roastery')
            ->forShop() // Exclude coffee of month products
            ->withPriceInCurrentCurrency(); // Only show products with price in current currency (EUR/.com or CZK/.cz)

        if ($request->has('category')) {
            $query->category($request->category);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $products = $query->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        $categoryNames = [
            'espresso' => 'Espresso káva',
            'filter' => 'Filtrovaná káva',
            'decaf' => 'Bezkofeinová káva',
            'accessories' => 'Příslušenství'
        ];

        // Get category counts
        $categoryCounts = [];
        foreach ($categoryNames as $key => $name) {
            $categoryCounts[$key] = Product::forShop()
                ->withPriceInCurrentCurrency()
                ->category($key)
                ->count();
        }

        // Get total count for "All"
        $totalCount = Product::forShop()
            ->withPriceInCurrentCurrency()
            ->count();

        // Build categories array with counts
        $categories = [];
        foreach ($categoryNames as $key => $name) {
            $categories[$key] = [
                'name' => $name,
                'count' => $categoryCounts[$key]
            ];
        }

        return view('products.index', compact('products', 'categories', 'totalCount'));
    }

    public function show(Product $product)
    {
        // If product is coffee of month, not active, or doesn't have price in current currency - 404
        if ($product->is_coffee_of_month || !$product->is_active || !$product->hasPriceInCurrentCurrency()) {
            abort(404);
        }

        // Load roastery relation
        $product->load('roastery');

        $relatedProducts = Product::forShop()
            ->withPriceInCurrentCurrency()
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}




