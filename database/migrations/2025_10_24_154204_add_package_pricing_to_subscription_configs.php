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
        // Remove old price_per_bag config
        DB::table('subscription_configs')->where('key', 'price_per_bag')->delete();
        
        // Add tiered pricing configs
        DB::table('subscription_configs')->insert([
            [
                'key' => 'price_2_bags',
                'value' => '500',
                'type' => 'decimal',
                'label' => 'Cena za 2 balení (Kč)',
                'description' => 'Celková cena při objednávce 2 balení kávy',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'price_3_bags',
                'value' => '720',
                'type' => 'decimal',
                'label' => 'Cena za 3 balení (Kč)',
                'description' => 'Celková cena při objednávce 3 balení kávy',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'price_4_bags',
                'value' => '920',
                'type' => 'decimal',
                'label' => 'Cena za 4 balení (Kč)',
                'description' => 'Celková cena při objednávce 4 balení kávy',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove tiered pricing configs
        DB::table('subscription_configs')->whereIn('key', [
            'price_2_bags',
            'price_3_bags',
            'price_4_bags'
        ])->delete();
        
        // Restore old price_per_bag config
        DB::table('subscription_configs')->insert([
            'key' => 'price_per_bag',
            'value' => '250',
            'type' => 'decimal',
            'label' => 'Cena za balení (Kč)',
            'description' => 'Základní cena za jedno balení kávy',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
