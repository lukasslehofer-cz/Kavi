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
        Schema::create('subscription_coffee_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained('subscriptions')->cascadeOnDelete();
            $table->foreignId('shipment_schedule_id')->constrained('shipment_schedules')->cascadeOnDelete();
            $table->foreignId('coffee_product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('quantity')->default(1); // Number of packages of this coffee
            $table->enum('status', ['allocated', 'shipped', 'cancelled'])->default('allocated');
            $table->timestamp('allocated_at')->useCurrent();
            $table->timestamps();
            
            // Indexes for performance (with shortened names to avoid MySQL limit)
            $table->index(['subscription_id', 'shipment_schedule_id'], 'sca_sub_schedule_idx');
            $table->index(['shipment_schedule_id', 'status'], 'sca_schedule_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_coffee_allocations');
    }
};

