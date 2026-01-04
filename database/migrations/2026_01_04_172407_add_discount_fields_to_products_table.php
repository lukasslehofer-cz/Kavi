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
            $table->enum('discount_type', ['percent', 'amount'])->nullable()->after('exclude_from_discounts');
            $table->decimal('discount_percent', 5, 2)->nullable()->after('discount_type');
            $table->decimal('discount_amount_czk', 10, 2)->nullable()->after('discount_percent');
            $table->decimal('discount_amount_eur', 10, 2)->nullable()->after('discount_amount_czk');
            $table->datetime('sale_start_date')->nullable()->after('discount_amount_eur');
            $table->datetime('sale_end_date')->nullable()->after('sale_start_date');
            $table->boolean('show_discount_percentage')->default(true)->after('sale_end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'discount_type',
                'discount_percent',
                'discount_amount_czk',
                'discount_amount_eur',
                'sale_start_date',
                'sale_end_date',
                'show_discount_percentage',
            ]);
        });
    }
};
