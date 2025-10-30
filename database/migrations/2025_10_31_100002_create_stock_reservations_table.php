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
        Schema::create('stock_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('shipment_schedule_id')->constrained('shipment_schedules')->cascadeOnDelete();
            $table->integer('reserved_quantity')->default(0); // Total reserved packages
            $table->integer('actual_quantity')->default(0); // Actual quantity after pauses/cancellations
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            // Unique constraint - one record per product per shipment
            $table->unique(['product_id', 'shipment_schedule_id']);
            
            // Index for performance
            $table->index('shipment_schedule_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_reservations');
    }
};

