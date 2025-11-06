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
        // Add package dimensions and weight configs for each box size
        DB::table('subscription_configs')->insert([
            // M Box (2× 250g) dimensions
            [
                'key' => 'package_2_length',
                'value' => '30',
                'type' => 'decimal',
                'label' => 'M Box (2× 250g) - Délka (cm)',
                'description' => 'Délka balíku pro M Box v centimetrech',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'package_2_width',
                'value' => '20',
                'type' => 'decimal',
                'label' => 'M Box (2× 250g) - Šířka (cm)',
                'description' => 'Šířka balíku pro M Box v centimetrech',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'package_2_height',
                'value' => '10',
                'type' => 'decimal',
                'label' => 'M Box (2× 250g) - Výška (cm)',
                'description' => 'Výška balíku pro M Box v centimetrech',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'package_2_weight',
                'value' => '0.75',
                'type' => 'decimal',
                'label' => 'M Box (2× 250g) - Hmotnost (kg)',
                'description' => 'Hmotnost balíku pro M Box v kilogramech',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // L Box (3× 250g) dimensions
            [
                'key' => 'package_3_length',
                'value' => '35',
                'type' => 'decimal',
                'label' => 'L Box (3× 250g) - Délka (cm)',
                'description' => 'Délka balíku pro L Box v centimetrech',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'package_3_width',
                'value' => '25',
                'type' => 'decimal',
                'label' => 'L Box (3× 250g) - Šířka (cm)',
                'description' => 'Šířka balíku pro L Box v centimetrech',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'package_3_height',
                'value' => '12',
                'type' => 'decimal',
                'label' => 'L Box (3× 250g) - Výška (cm)',
                'description' => 'Výška balíku pro L Box v centimetrech',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'package_3_weight',
                'value' => '1.0',
                'type' => 'decimal',
                'label' => 'L Box (3× 250g) - Hmotnost (kg)',
                'description' => 'Hmotnost balíku pro L Box v kilogramech',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // XL Box (4× 250g) dimensions
            [
                'key' => 'package_4_length',
                'value' => '40',
                'type' => 'decimal',
                'label' => 'XL Box (4× 250g) - Délka (cm)',
                'description' => 'Délka balíku pro XL Box v centimetrech',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'package_4_width',
                'value' => '30',
                'type' => 'decimal',
                'label' => 'XL Box (4× 250g) - Šířka (cm)',
                'description' => 'Šířka balíku pro XL Box v centimetrech',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'package_4_height',
                'value' => '15',
                'type' => 'decimal',
                'label' => 'XL Box (4× 250g) - Výška (cm)',
                'description' => 'Výška balíku pro XL Box v centimetrech',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'package_4_weight',
                'value' => '1.25',
                'type' => 'decimal',
                'label' => 'XL Box (4× 250g) - Hmotnost (kg)',
                'description' => 'Hmotnost balíku pro XL Box v kilogramech',
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
        DB::table('subscription_configs')->whereIn('key', [
            'package_2_length', 'package_2_width', 'package_2_height', 'package_2_weight',
            'package_3_length', 'package_3_width', 'package_3_height', 'package_3_weight',
            'package_4_length', 'package_4_width', 'package_4_height', 'package_4_weight',
        ])->delete();
    }
};
