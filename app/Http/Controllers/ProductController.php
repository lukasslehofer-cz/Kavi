<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::forShop(); // Exclude coffee of month products

        if ($request->has('category')) {
            $query->category($request->category);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $products = $query->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = [
            'espresso' => 'Espresso káva',
            'filter' => 'Filtrovaná káva',
            'accessories' => 'Příslušenství',
            'merch' => 'Merch'
        ];

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        // If product is coffee of month, don't show in regular shop
        if ($product->is_coffee_of_month || !$product->is_active) {
            abort(404);
        }

        $relatedProducts = Product::forShop()
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}




