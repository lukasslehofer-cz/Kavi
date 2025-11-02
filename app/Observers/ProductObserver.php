<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Str;

class ProductObserver
{
    /**
     * Handle the Product "creating" event.
     */
    public function creating(Product $product): void
    {
        // Always generate slug when creating
        if (empty($product->slug)) {
            $product->slug = $this->generateUniqueSlug($product);
        }
    }

    /**
     * Handle the Product "updating" event.
     */
    public function updating(Product $product): void
    {
        // Regenerate slug only if name or category changed
        if ($product->isDirty('name') || $product->isDirty('category')) {
            $product->slug = $this->generateUniqueSlug($product);
        }
    }

    /**
     * Generate a unique slug for the product.
     * 
     * Logic:
     * 1. Try base slug from name
     * 2. If exists, try name + first coffee category (espresso/filter/decaf)
     * 3. If still exists, add number suffix
     */
    private function generateUniqueSlug(Product $product): string
    {
        $baseSlug = Str::slug($product->name);
        $slug = $baseSlug;
        
        // Get first coffee category if exists
        $coffeeCategories = ['espresso', 'filter', 'decaf'];
        $firstCoffeeCategory = null;
        
        if (is_array($product->category)) {
            foreach ($product->category as $cat) {
                if (in_array($cat, $coffeeCategories)) {
                    $firstCoffeeCategory = $cat;
                    break;
                }
            }
        }
        
        // Check if base slug exists
        if ($this->slugExists($slug, $product->id)) {
            // Try with category first
            if ($firstCoffeeCategory) {
                $slug = $baseSlug . '-' . $firstCoffeeCategory;
            }
            
            // If still exists, add number
            if ($this->slugExists($slug, $product->id)) {
                $counter = 2;
                $baseSlugWithCategory = $firstCoffeeCategory 
                    ? $baseSlug . '-' . $firstCoffeeCategory 
                    : $baseSlug;
                
                do {
                    $slug = $baseSlugWithCategory . '-' . $counter;
                    $counter++;
                } while ($this->slugExists($slug, $product->id));
            }
        }
        
        return $slug;
    }

    /**
     * Check if a slug already exists (excluding current product if updating).
     */
    private function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $query = Product::where('slug', $slug);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }
}

