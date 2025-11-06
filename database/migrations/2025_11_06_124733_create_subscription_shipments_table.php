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
        Schema::create('subscription_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade');
            $table->date('shipment_date'); // Datum rozesílky
            
            // Packeta tracking info
            $table->string('packeta_packet_id')->nullable();
            $table->string('packeta_tracking_url')->nullable();
            $table->string('carrier_id')->nullable();
            $table->string('carrier_pickup_point')->nullable();
            
            // Package dimensions and weight (specific for this shipment)
            $table->decimal('package_weight', 8, 2)->nullable(); // kg
            $table->decimal('package_length', 8, 2)->nullable(); // cm
            $table->decimal('package_width', 8, 2)->nullable(); // cm
            $table->decimal('package_height', 8, 2)->nullable(); // cm
            
            // Link to payment/invoice (stored in subscription_payments table)
            $table->foreignId('subscription_payment_id')->nullable()->constrained('subscription_payments')->onDelete('set null');
            
            // Status and notes
            $table->enum('status', ['pending', 'sent', 'delivered', 'cancelled'])->default('pending');
            $table->text('notes')->nullable(); // Poznámky k této rozesílce
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['subscription_id', 'shipment_date']);
            $table->index('status');
            $table->index('sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_shipments');
    }
};
