<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds EUR price columns for multi-region support (kavi.cz = CZK, kavibox.com = EUR)
     */
    public function up(): void
    {
        // Add price_eur to products table
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price_eur', 10, 2)->nullable()->after('price');
        });

        // Add price_eur to subscription_plans table
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->decimal('price_eur', 10, 2)->nullable()->after('price');
        });

        // Add EUR discount values for coupons (for fixed amount coupons)
        Schema::table('coupons', function (Blueprint $table) {
            $table->decimal('discount_value_order_eur', 10, 2)->nullable()->after('discount_value_order');
            $table->decimal('discount_value_subscription_eur', 10, 2)->nullable()->after('discount_value_subscription');
            $table->decimal('min_order_value_eur', 10, 2)->nullable()->after('min_order_value');
        });

        // Add EUR price configs for subscription pricing (key-value table)
        DB::table('subscription_configs')->insert([
            [
                'key' => 'price_2_bags_eur',
                'value' => '20',
                'type' => 'decimal',
                'label' => 'Price for 2 bags (EUR)',
                'description' => 'Total price for 2 bags subscription in EUR',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'price_3_bags_eur',
                'value' => '29',
                'type' => 'decimal',
                'label' => 'Price for 3 bags (EUR)',
                'description' => 'Total price for 3 bags subscription in EUR',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'price_4_bags_eur',
                'value' => '37',
                'type' => 'decimal',
                'label' => 'Price for 4 bags (EUR)',
                'description' => 'Total price for 4 bags subscription in EUR',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Add region availability to shipping_rates
        Schema::table('shipping_rates', function (Blueprint $table) {
            $table->boolean('available_on_cz')->default(true)->after('enabled');
            $table->boolean('available_on_com')->default(false)->after('available_on_cz');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('price_eur');
        });

        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn('price_eur');
        });

        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(['discount_value_order_eur', 'discount_value_subscription_eur', 'min_order_value_eur']);
        });

        DB::table('subscription_configs')->whereIn('key', [
            'price_2_bags_eur',
            'price_3_bags_eur',
            'price_4_bags_eur',
        ])->delete();

        Schema::table('shipping_rates', function (Blueprint $table) {
            $table->dropColumn(['available_on_cz', 'available_on_com']);
        });
    }
};
