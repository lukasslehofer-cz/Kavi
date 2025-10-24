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
        Schema::create('subscription_configs', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value');
            $table->string('type')->default('string'); // string, integer, decimal, boolean
            $table->string('label');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default values
        DB::table('subscription_configs')->insert([
            [
                'key' => 'price_per_bag',
                'value' => '250',
                'type' => 'decimal',
                'label' => 'Cena za balení (Kč)',
                'description' => 'Základní cena za jedno balení kávy',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'min_bags',
                'value' => '2',
                'type' => 'integer',
                'label' => 'Minimální počet balení',
                'description' => 'Nejmenší možný počet balení v předplatném',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'max_bags',
                'value' => '4',
                'type' => 'integer',
                'label' => 'Maximální počet balení',
                'description' => 'Největší možný počet balení v předplatném',
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
        Schema::dropIfExists('subscription_configs');
    }
};
