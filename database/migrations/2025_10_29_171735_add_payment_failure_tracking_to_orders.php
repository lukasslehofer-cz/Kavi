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
        Schema::table('orders', function (Blueprint $table) {
            // Tracking payment failures
            $table->integer('payment_failure_count')->default(0)->after('payment_status');
            $table->timestamp('last_payment_failure_at')->nullable()->after('payment_failure_count');
            $table->text('last_payment_failure_reason')->nullable()->after('last_payment_failure_at');
            
            // Stripe payment intent that needs to be paid
            $table->string('pending_payment_intent_id')->nullable()->after('stripe_payment_intent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_failure_count',
                'last_payment_failure_at',
                'last_payment_failure_reason',
                'pending_payment_intent_id',
            ]);
        });
    }
};
