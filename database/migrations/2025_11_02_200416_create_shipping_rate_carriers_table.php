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
        Schema::create('shipping_rate_carriers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_rate_id')->constrained('shipping_rates')->onDelete('cascade');
            $table->foreignId('packeta_carrier_id')->constrained('packeta_carriers')->onDelete('cascade');
            $table->timestamps();
            
            // Unique constraint - prevent duplicates
            $table->unique(['shipping_rate_id', 'packeta_carrier_id'], 'rate_carrier_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_rate_carriers');
    }
};
