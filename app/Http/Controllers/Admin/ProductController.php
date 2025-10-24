<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $products = Product::orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = [
            'espresso' => 'Espresso káva',
            'filter' => 'Filtrovaná káva',
            'accessories' => 'Příslušenství',
            'merch' => 'Merch'
        ];

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'category' => 'required|in:espresso,filter,accessories,merch',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_coffee_of_month' => 'boolean',
            'coffee_of_month_date' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_coffee_of_month'] = $request->has('is_coffee_of_month');
        
        // If product is coffee of month, price and stock are optional
        if ($validated['is_coffee_of_month']) {
            $validated['price'] = $validated['price'] ?? 0;
            $validated['stock'] = $validated['stock'] ?? 0;
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produkt byl úspěšně vytvořen.');
    }

    public function edit(Product $product)
    {
        $categories = [
            'espresso' => 'Espresso káva',
            'filter' => 'Filtrovaná káva',
            'accessories' => 'Příslušenství',
            'merch' => 'Merch'
        ];

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'category' => 'required|in:espresso,filter,accessories,merch',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_coffee_of_month' => 'boolean',
            'coffee_of_month_date' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_coffee_of_month'] = $request->has('is_coffee_of_month');
        
        // If product is coffee of month, price and stock are optional
        if ($validated['is_coffee_of_month']) {
            $validated['price'] = $validated['price'] ?? 0;
            $validated['stock'] = $validated['stock'] ?? 0;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produkt byl úspěšně aktualizován.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produkt byl úspěšně smazán.');
    }
}




