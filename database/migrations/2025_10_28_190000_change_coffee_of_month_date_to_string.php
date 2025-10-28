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
            $table->string('coffee_of_month_date_temp', 7)->nullable()->after('coffee_of_month_date');
        });
        
        // Copy data in new format
        DB::statement("UPDATE products SET coffee_of_month_date_temp = DATE_FORMAT(coffee_of_month_date, '%Y-%m') WHERE coffee_of_month_date IS NOT NULL");
        
        // Drop old column
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('coffee_of_month_date');
        });
        
        // Rename temp column to original name
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('coffee_of_month_date_temp', 'coffee_of_month_date');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->date('coffee_of_month_date')->nullable()->change()->comment('Pro kterou rozesílku je káva určena');
        });
    }
};

