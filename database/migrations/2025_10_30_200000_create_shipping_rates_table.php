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
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();
            $table->string('country_code', 2)->unique(); // ISO 3166-1 alpha-2
            $table->string('country_name');
            $table->boolean('enabled')->default(true);
            $table->decimal('price_czk', 10, 2)->default(0);
            $table->decimal('price_eur', 10, 2)->default(0);
            $table->boolean('applies_to_subscriptions')->default(false);
            $table->decimal('free_shipping_threshold_czk', 10, 2)->nullable();
            $table->string('packeta_carrier_id')->nullable();
            $table->string('packeta_carrier_name')->nullable();
            $table->timestamps();

            $table->index('country_code');
            $table->index('enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_rates');
    }
};

