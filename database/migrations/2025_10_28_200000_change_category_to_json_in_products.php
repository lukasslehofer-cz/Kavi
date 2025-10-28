<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add temporary column
        Schema::table('products', function (Blueprint $table) {
            $table->json('category_temp')->nullable()->after('category');
        });
        
        // Convert existing string values to JSON arrays in temp column
        DB::statement("UPDATE products SET category_temp = JSON_ARRAY(category) WHERE category IS NOT NULL");
        
        // Drop old column
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('category');
        });
        
        // Rename temp column to original name
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('category_temp', 'category');
        });
    }

    public function down(): void
    {
        // Convert JSON arrays back to single strings (take first element)
        DB::statement("UPDATE products SET category = JSON_UNQUOTE(JSON_EXTRACT(category, '$[0]')) WHERE category IS NOT NULL");
        
        // Change column back to string
        Schema::table('products', function (Blueprint $table) {
            $table->string('category')->nullable()->change();
        });
    }
};

