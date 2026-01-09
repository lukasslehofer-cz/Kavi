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
        Schema::create('affiliate_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_partner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('subscription_payment_number')->nullable()->comment('Které opakování předplatného (1, 2, 3...)');
            $table->enum('reward_type', ['order', 'subscription'])->comment('Typ odměny');
            $table->decimal('reward_amount', 10, 2)->comment('Výše odměny');
            $table->string('currency', 3)->default('CZK')->comment('Měna odměny');
            $table->enum('status', ['pending', 'approved', 'paid', 'cancelled'])->default('pending')->comment('Stav výplaty');
            $table->timestamp('paid_at')->nullable()->comment('Datum výplaty');
            $table->text('notes')->nullable()->comment('Poznámky admina');
            $table->timestamps();
            
            // Indexes
            $table->index('affiliate_partner_id');
            $table->index('coupon_id');
            $table->index('order_id');
            $table->index('subscription_id');
            $table->index('reward_type');
            $table->index('status');
            $table->index('created_at');
            
            // Composite index pro kontrolu duplicit
            $table->unique(['subscription_id', 'subscription_payment_number'], 'unique_subscription_payment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_rewards');
    }
};
