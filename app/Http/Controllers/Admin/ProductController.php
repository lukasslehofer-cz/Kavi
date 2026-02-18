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

    public function index(Request $request)
    {
        $query = Product::query();

        // Filtr podle kategorie
        if ($request->filled('category') && $request->category !== 'all') {
            $category = $request->category;
            
            if ($category === 'omni') {
                // Omni = has both espresso AND filter
                $query->whereJsonContains('category', 'espresso')
                      ->whereJsonContains('category', 'filter');
            } elseif ($category === 'espresso') {
                // Espresso only (not filter)
                $query->whereJsonContains('category', 'espresso')
                      ->whereRaw("NOT JSON_CONTAINS(category, '\"filter\"')");
            } elseif ($category === 'filter') {
                // Filter only (not espresso)
                $query->whereJsonContains('category', 'filter')
                      ->whereRaw("NOT JSON_CONTAINS(category, '\"espresso\"')");
            } else {
                // Decaf, accessories - standard filter
                $query->whereJsonContains('category', $category);
            }
        }

        // Řazení - neaktivní vždy na konci
        $query->orderBy('is_active', 'desc');

        // Sekundární řazení podle vybraného kritéria
        $sort = $request->get('sort', 'default');
        switch ($sort) {
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'stock':
                $query->orderBy('stock', 'desc');
                break;
            case 'roast_date':
                $query->orderByRaw("JSON_UNQUOTE(JSON_EXTRACT(attributes, '$.roast_date')) DESC");
                break;
            default:
                $query->orderBy('sort_order')->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(20)->withQueryString();

        // Statistiky pro přehled
        $stats = $this->getProductStats();

        return view('admin.products.index', compact('products', 'stats'));
    }

    /**
     * Get product statistics for admin overview
     */
    private function getProductStats(): array
    {
        $stats = [
            'total_active' => Product::where('is_active', true)->count(),
            'total_stock' => Product::where('is_digital', false)->sum('stock'),
            'categories' => []
        ];

        // Espresso only (has espresso, NOT filter)
        $stats['categories']['espresso'] = [
            'active' => Product::where('is_active', true)
                ->whereJsonContains('category', 'espresso')
                ->whereRaw("NOT JSON_CONTAINS(category, '\"filter\"')")
                ->count(),
            'stock' => Product::where('is_digital', false)
                ->whereJsonContains('category', 'espresso')
                ->whereRaw("NOT JSON_CONTAINS(category, '\"filter\"')")
                ->sum('stock'),
        ];

        // Filter only (has filter, NOT espresso)
        $stats['categories']['filter'] = [
            'active' => Product::where('is_active', true)
                ->whereJsonContains('category', 'filter')
                ->whereRaw("NOT JSON_CONTAINS(category, '\"espresso\"')")
                ->count(),
            'stock' => Product::where('is_digital', false)
                ->whereJsonContains('category', 'filter')
                ->whereRaw("NOT JSON_CONTAINS(category, '\"espresso\"')")
                ->sum('stock'),
        ];

        // Omni (has BOTH espresso AND filter)
        $stats['categories']['omni'] = [
            'active' => Product::where('is_active', true)
                ->whereJsonContains('category', 'espresso')
                ->whereJsonContains('category', 'filter')
                ->count(),
            'stock' => Product::where('is_digital', false)
                ->whereJsonContains('category', 'espresso')
                ->whereJsonContains('category', 'filter')
                ->sum('stock'),
        ];

        // Decaf
        $stats['categories']['decaf'] = [
            'active' => Product::where('is_active', true)->whereJsonContains('category', 'decaf')->count(),
            'stock' => Product::where('is_digital', false)->whereJsonContains('category', 'decaf')->sum('stock'),
        ];

        // Accessories
        $stats['categories']['accessories'] = [
            'active' => Product::where('is_active', true)->whereJsonContains('category', 'accessories')->count(),
            'stock' => Product::where('is_digital', false)->whereJsonContains('category', 'accessories')->sum('stock'),
        ];

        return $stats;
    }

    public function create()
    {
        $categories = [
            'espresso' => 'Espresso káva',
            'filter' => 'Filtrovaná káva',
            'decaf' => 'Bezkofeinová káva',
            'accessories' => 'Příslušenství'
        ];
        
        $roasteries = \App\Models\Roastery::orderBy('name')->get();

        return view('admin.products.create', compact('categories', 'roasteries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'required|string',
            'description_en' => 'nullable|string',
            'short_description' => 'nullable|string',
            'short_description_en' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'price_eur' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'categories' => 'required|array',
            'categories.*' => 'in:espresso,filter,decaf,accessories',
            'vat_rate' => 'nullable|numeric|min:0|max:100',
            'roastery_id' => 'nullable|exists:roasteries,id',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'facebook_image' => 'nullable|image|mimes:png,jpeg,jpg|max:4096',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'free_shipping' => 'boolean',
            'is_digital' => 'boolean',
            'exclude_from_discounts' => 'boolean',
            'is_coffee_of_month' => 'boolean',
            'coffee_of_month_date' => 'nullable|string|regex:/^\d{4}-\d{2}$/',
            'sort_order' => 'nullable|integer|min:0',
            // Discount fields
            'discount_type' => 'nullable|in:percent,amount',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_amount_czk' => 'nullable|numeric|min:0',
            'discount_amount_eur' => 'nullable|numeric|min:0',
            'sale_start_date' => 'nullable|date',
            'sale_end_date' => 'nullable|date|after_or_equal:sale_start_date',
            'show_discount_percentage' => 'boolean',
            // Attributes for coffee products (CZ)
            'origin' => 'nullable|string|max:255',
            'altitude' => 'nullable|string|max:255',
            'processing' => 'nullable|string|max:255',
            'variety' => 'nullable|string|max:255',
            'flavor_notes' => 'nullable|string|max:1000',
            'weight' => 'nullable|integer|min:1',
            'roast_date' => 'nullable|date',
            // Attributes for coffee products (EN)
            'origin_en' => 'nullable|string|max:255',
            'processing_en' => 'nullable|string|max:255',
            'variety_en' => 'nullable|string|max:255',
            'flavor_notes_en' => 'nullable|string|max:1000',
            // Custom attributes for accessories
            'custom_attributes' => 'nullable|array',
            'custom_attributes.*.label_cs' => 'required_with:custom_attributes|string|max:100',
            'custom_attributes.*.label_en' => 'nullable|string|max:100',
            'custom_attributes.*.value_cs' => 'required_with:custom_attributes|string|max:500',
            'custom_attributes.*.value_en' => 'nullable|string|max:500',
        ]);

        // Slug will be auto-generated by ProductObserver
        $validated['category'] = $validated['categories']; // Store as array
        unset($validated['categories']);
        unset($validated['facebook_image']);
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['free_shipping'] = $request->has('free_shipping');
        $validated['is_digital'] = $request->has('is_digital');
        $validated['exclude_from_discounts'] = $request->has('exclude_from_discounts');
        $validated['is_coffee_of_month'] = $request->has('is_coffee_of_month');
        $validated['show_discount_percentage'] = $request->has('show_discount_percentage');
        
        // Handle discount fields - clear if no type selected
        if (empty($validated['discount_type'])) {
            $validated['discount_type'] = null;
            $validated['discount_percent'] = null;
            $validated['discount_amount_czk'] = null;
            $validated['discount_amount_eur'] = null;
            $validated['sale_start_date'] = null;
            $validated['sale_end_date'] = null;
        }
        
        // If product is coffee of month, price and stock are optional
        if ($validated['is_coffee_of_month']) {
            $validated['price'] = $validated['price'] ?? 0;
            $validated['stock'] = $validated['stock'] ?? 0;
        }

        // Build attributes array - CATEGORY-BASED
        $attributes = [];

        // Check if product is coffee or accessory
        $isCoffee = !empty(array_intersect($validated['category'], ['espresso', 'filter', 'decaf']));

        if ($isCoffee) {
            // Coffee: use fixed attributes
            $attributeFields = ['origin', 'altitude', 'processing', 'variety', 'flavor_notes', 'weight', 'roast_date',
                               'origin_en', 'processing_en', 'variety_en', 'flavor_notes_en'];

            foreach ($attributeFields as $field) {
                if (!empty($validated[$field])) {
                    $attributes[$field] = $validated[$field];
                }
            }
        } else {
            // Accessories: use custom attributes
            if (!empty($validated['custom_attributes'])) {
                foreach ($validated['custom_attributes'] as $attr) {
                    if (!empty($attr['label_cs']) && !empty($attr['value_cs'])) {
                        // Generate internal key from Czech label
                        $key = \Illuminate\Support\Str::slug($attr['label_cs'], '_');

                        // Store labels
                        $attributes[$key . '_label'] = $attr['label_cs'];
                        if (!empty($attr['label_en'])) {
                            $attributes[$key . '_label_en'] = $attr['label_en'];
                        }

                        // Store values
                        $attributes[$key] = $attr['value_cs'];
                        if (!empty($attr['value_en'])) {
                            $attributes[$key . '_en'] = $attr['value_en'];
                        }
                    }
                }
            }
        }

        if (!empty($attributes)) {
            $validated['attributes'] = $attributes;
        }

        // Remove individual attribute fields
        $fieldsToRemove = array_merge(
            ['origin', 'altitude', 'processing', 'variety', 'flavor_notes', 'weight', 'roast_date',
             'origin_en', 'processing_en', 'variety_en', 'flavor_notes_en'],
            ['custom_attributes']
        );

        foreach ($fieldsToRemove as $field) {
            unset($validated[$field]);
        }

        // Handle gallery images (max 4)
        if ($request->hasFile('gallery')) {
            $galleryPaths = [];
            $galleryFiles = array_slice($request->file('gallery'), 0, 4); // Max 4 images

            foreach ($galleryFiles as $index => $file) {
                $filename = time() . '_gallery_' . $index . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/products/gallery'), $filename);
                $galleryPaths[] = 'images/products/gallery/' . $filename;
            }

            $validated['images'] = $galleryPaths;
            // Backwards compatibility: set first image as main
            $validated['image'] = $galleryPaths[0] ?? null;
        }

        // Handle Facebook image (PNG/JPG for catalog feed)
        if ($request->hasFile('facebook_image')) {
            $file = $request->file('facebook_image');
            $filename = time() . '_fb_' . $file->getClientOriginalName();
            $file->move(public_path('images/products/facebook'), $filename);
            $validated['facebook_image'] = 'images/products/facebook/' . $filename;
        }

        // Automatically set VAT rate based on category if not provided
        if (!isset($validated['vat_rate']) || $validated['vat_rate'] === null) {
            $validated['vat_rate'] = Product::isCoffeeCategory($validated['category']) ? 12.00 : 21.00;
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
            'decaf' => 'Bezkofeinová káva',
            'accessories' => 'Příslušenství'
        ];
        
        $roasteries = \App\Models\Roastery::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories', 'roasteries'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'required|string',
            'description_en' => 'nullable|string',
            'short_description' => 'nullable|string',
            'short_description_en' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'price_eur' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'categories' => 'required|array',
            'categories.*' => 'in:espresso,filter,decaf,accessories',
            'vat_rate' => 'nullable|numeric|min:0|max:100',
            'roastery_id' => 'nullable|exists:roasteries,id',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'remove_gallery' => 'nullable|array',
            'facebook_image' => 'nullable|image|mimes:png,jpeg,jpg|max:4096',
            'remove_facebook_image' => 'boolean',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'free_shipping' => 'boolean',
            'is_digital' => 'boolean',
            'exclude_from_discounts' => 'boolean',
            'is_coffee_of_month' => 'boolean',
            'coffee_of_month_date' => 'nullable|string|regex:/^\d{4}-\d{2}$/',
            'sort_order' => 'nullable|integer|min:0',
            // Discount fields
            'discount_type' => 'nullable|in:percent,amount',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_amount_czk' => 'nullable|numeric|min:0',
            'discount_amount_eur' => 'nullable|numeric|min:0',
            'sale_start_date' => 'nullable|date',
            'sale_end_date' => 'nullable|date|after_or_equal:sale_start_date',
            'show_discount_percentage' => 'boolean',
            // Attributes for coffee products (CZ)
            'origin' => 'nullable|string|max:255',
            'altitude' => 'nullable|string|max:255',
            'processing' => 'nullable|string|max:255',
            'variety' => 'nullable|string|max:255',
            'flavor_notes' => 'nullable|string|max:1000',
            'weight' => 'nullable|integer|min:1',
            'roast_date' => 'nullable|date',
            // Attributes for coffee products (EN)
            'origin_en' => 'nullable|string|max:255',
            'processing_en' => 'nullable|string|max:255',
            'variety_en' => 'nullable|string|max:255',
            'flavor_notes_en' => 'nullable|string|max:1000',
            // Custom attributes for accessories
            'custom_attributes' => 'nullable|array',
            'custom_attributes.*.label_cs' => 'required_with:custom_attributes|string|max:100',
            'custom_attributes.*.label_en' => 'nullable|string|max:100',
            'custom_attributes.*.value_cs' => 'required_with:custom_attributes|string|max:500',
            'custom_attributes.*.value_en' => 'nullable|string|max:500',
        ]);

        // Slug will be auto-generated/updated by ProductObserver if name or category changed
        $validated['category'] = $validated['categories']; // Store as array
        unset($validated['categories']);
        unset($validated['facebook_image']);
        unset($validated['remove_facebook_image']);
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['free_shipping'] = $request->has('free_shipping');
        $validated['is_digital'] = $request->has('is_digital');
        $validated['exclude_from_discounts'] = $request->has('exclude_from_discounts');
        $validated['is_coffee_of_month'] = $request->has('is_coffee_of_month');
        $validated['show_discount_percentage'] = $request->has('show_discount_percentage');
        
        // Handle discount fields - clear if no type selected
        if (empty($validated['discount_type'])) {
            $validated['discount_type'] = null;
            $validated['discount_percent'] = null;
            $validated['discount_amount_czk'] = null;
            $validated['discount_amount_eur'] = null;
            $validated['sale_start_date'] = null;
            $validated['sale_end_date'] = null;
        }
        
        // If product is coffee of month, price and stock are optional
        if ($validated['is_coffee_of_month']) {
            $validated['price'] = $validated['price'] ?? 0;
            $validated['stock'] = $validated['stock'] ?? 0;
        }

        // Build attributes array - CATEGORY-BASED
        $attributes = [];

        // Check if product is coffee or accessory
        $isCoffee = !empty(array_intersect($validated['category'], ['espresso', 'filter', 'decaf']));

        if ($isCoffee) {
            // Coffee: use fixed attributes
            $attributeFields = ['origin', 'altitude', 'processing', 'variety', 'flavor_notes', 'weight', 'roast_date',
                               'origin_en', 'processing_en', 'variety_en', 'flavor_notes_en'];

            foreach ($attributeFields as $field) {
                if (!empty($validated[$field])) {
                    $attributes[$field] = $validated[$field];
                }
            }
        } else {
            // Accessories: use custom attributes
            if (!empty($validated['custom_attributes'])) {
                foreach ($validated['custom_attributes'] as $attr) {
                    if (!empty($attr['label_cs']) && !empty($attr['value_cs'])) {
                        // Generate internal key from Czech label
                        $key = \Illuminate\Support\Str::slug($attr['label_cs'], '_');

                        // Store labels
                        $attributes[$key . '_label'] = $attr['label_cs'];
                        if (!empty($attr['label_en'])) {
                            $attributes[$key . '_label_en'] = $attr['label_en'];
                        }

                        // Store values
                        $attributes[$key] = $attr['value_cs'];
                        if (!empty($attr['value_en'])) {
                            $attributes[$key . '_en'] = $attr['value_en'];
                        }
                    }
                }
            }
        }

        if (!empty($attributes)) {
            $validated['attributes'] = $attributes;
        }

        // Remove individual attribute fields
        $fieldsToRemove = array_merge(
            ['origin', 'altitude', 'processing', 'variety', 'flavor_notes', 'weight', 'roast_date',
             'origin_en', 'processing_en', 'variety_en', 'flavor_notes_en'],
            ['custom_attributes']
        );

        foreach ($fieldsToRemove as $field) {
            unset($validated[$field]);
        }

        // Handle gallery images
        $existingGallery = $product->images ?? [];

        // Remove selected gallery images
        if ($request->has('remove_gallery')) {
            foreach ($request->remove_gallery as $imageToRemove) {
                if (($key = array_search($imageToRemove, $existingGallery)) !== false) {
                    // Delete file from disk
                    if (file_exists(public_path($imageToRemove))) {
                        unlink(public_path($imageToRemove));
                    }
                    unset($existingGallery[$key]);
                }
            }
            $existingGallery = array_values($existingGallery); // Reindex array
        }

        // Add new gallery images (max 4 total)
        if ($request->hasFile('gallery')) {
            $remainingSlots = 4 - count($existingGallery);
            $newFiles = array_slice($request->file('gallery'), 0, $remainingSlots);

            foreach ($newFiles as $index => $file) {
                $filename = time() . '_gallery_' . $index . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/products/gallery'), $filename);
                $existingGallery[] = 'images/products/gallery/' . $filename;
            }
        }

        $validated['images'] = $existingGallery;
        // Backwards compatibility: set first image as main
        $validated['image'] = $existingGallery[0] ?? $product->image;

        // Handle Facebook image
        if ($request->has('remove_facebook_image') && $product->facebook_image) {
            if (file_exists(public_path($product->facebook_image))) {
                unlink(public_path($product->facebook_image));
            }
            $validated['facebook_image'] = null;
        }

        if ($request->hasFile('facebook_image')) {
            if ($product->facebook_image && file_exists(public_path($product->facebook_image))) {
                unlink(public_path($product->facebook_image));
            }
            $file = $request->file('facebook_image');
            $filename = time() . '_fb_' . $file->getClientOriginalName();
            $file->move(public_path('images/products/facebook'), $filename);
            $validated['facebook_image'] = 'images/products/facebook/' . $filename;
        }

        // Automatically set VAT rate based on category if not provided
        if (!isset($validated['vat_rate']) || $validated['vat_rate'] === null) {
            $validated['vat_rate'] = Product::isCoffeeCategory($validated['category']) ? 12.00 : 21.00;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produkt byl úspěšně aktualizován.');
    }

    public function destroy(Product $product)
    {
        // Delete product image if exists
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        // Delete gallery images
        if ($product->images) {
            foreach ($product->images as $image) {
                if (file_exists(public_path($image))) {
                    unlink(public_path($image));
                }
            }
        }

        // Delete Facebook image
        if ($product->facebook_image && file_exists(public_path($product->facebook_image))) {
            unlink(public_path($product->facebook_image));
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produkt byl úspěšně smazán.');
    }

    /**
     * Show bulk discount form
     */
    public function bulkDiscount()
    {
        $productsCount = Product::where('exclude_from_discounts', false)->count();
        $excludedCount = Product::where('exclude_from_discounts', true)->count();
        $currentlyOnSale = Product::whereNotNull('discount_type')->count();

        return view('admin.products.bulk-discount', compact('productsCount', 'excludedCount', 'currentlyOnSale'));
    }

    /**
     * Apply bulk discount to all eligible products
     */
    public function applyBulkDiscount(Request $request)
    {
        $validated = $request->validate([
            'discount_type' => 'required|in:percent,amount',
            'discount_percent' => 'required_if:discount_type,percent|nullable|numeric|min:0|max:100',
            'discount_amount_czk' => 'required_if:discount_type,amount|nullable|numeric|min:0',
            'discount_amount_eur' => 'required_if:discount_type,amount|nullable|numeric|min:0',
            'sale_start_date' => 'nullable|date',
            'sale_end_date' => 'nullable|date|after_or_equal:sale_start_date',
            'show_discount_percentage' => 'boolean',
        ]);

        $updateData = [
            'discount_type' => $validated['discount_type'],
            'sale_start_date' => $validated['sale_start_date'] ?? null,
            'sale_end_date' => $validated['sale_end_date'] ?? null,
            'show_discount_percentage' => $request->has('show_discount_percentage'),
        ];

        if ($validated['discount_type'] === 'percent') {
            $updateData['discount_percent'] = $validated['discount_percent'];
            $updateData['discount_amount_czk'] = null;
            $updateData['discount_amount_eur'] = null;
        } else {
            $updateData['discount_percent'] = null;
            $updateData['discount_amount_czk'] = $validated['discount_amount_czk'];
            $updateData['discount_amount_eur'] = $validated['discount_amount_eur'];
        }

        $affectedCount = Product::where('exclude_from_discounts', false)
            ->update($updateData);

        return redirect()->route('admin.products.bulk-discount')
            ->with('success', "Sleva byla aplikována na {$affectedCount} produktů.");
    }

    /**
     * Remove all discounts from all products
     */
    public function clearAllDiscounts()
    {
        $affectedCount = Product::whereNotNull('discount_type')
            ->update([
                'discount_type' => null,
                'discount_percent' => null,
                'discount_amount_czk' => null,
                'discount_amount_eur' => null,
                'sale_start_date' => null,
                'sale_end_date' => null,
                'show_discount_percentage' => true,
            ]);

        return redirect()->route('admin.products.bulk-discount')
            ->with('success', "Slevy byly odstraněny z {$affectedCount} produktů.");
    }
}




