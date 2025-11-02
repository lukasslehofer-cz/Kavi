<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update products with 'merch' category
        // Since category is JSON array, we need to handle it properly
        
        $products = DB::table('products')->whereNotNull('category')->get();
        
        foreach ($products as $product) {
            $categories = json_decode($product->category, true);
            
            if (is_array($categories)) {
                // Remove 'merch' from categories
                $updatedCategories = array_diff($categories, ['merch']);
                
                // If the product only had 'merch' and now has no categories, add 'accessories'
                if (empty($updatedCategories) && in_array('merch', $categories)) {
                    $updatedCategories = ['accessories'];
                }
                
                // Re-index array to avoid gaps
                $updatedCategories = array_values($updatedCategories);
                
                // Update only if categories changed
                if ($updatedCategories !== $categories) {
                    DB::table('products')
                        ->where('id', $product->id)
                        ->update(['category' => json_encode($updatedCategories)]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not reversible - we don't know which products originally had 'merch'
        // This is a one-way data migration
    }
};
