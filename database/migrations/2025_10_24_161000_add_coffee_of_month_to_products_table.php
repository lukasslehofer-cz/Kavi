<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_coffee_of_month')->default(false)->after('is_featured');
            $table->date('coffee_of_month_date')->nullable()->after('is_coffee_of_month')->comment('Pro kterou rozesílku je káva určena');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_coffee_of_month', 'coffee_of_month_date']);
        });
    }
};

