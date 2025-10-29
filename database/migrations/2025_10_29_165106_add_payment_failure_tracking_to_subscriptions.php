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
        Schema::table('subscriptions', function (Blueprint $table) {
            // Tracking payment failures
            $table->integer('payment_failure_count')->default(0)->after('status');
            $table->timestamp('last_payment_failure_at')->nullable()->after('payment_failure_count');
            $table->text('last_payment_failure_reason')->nullable()->after('last_payment_failure_at');
            
            // Stripe invoice that needs to be paid
            $table->string('pending_invoice_id')->nullable()->after('last_payment_failure_reason');
            $table->decimal('pending_invoice_amount', 10, 2)->nullable()->after('pending_invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'payment_failure_count',
                'last_payment_failure_at',
                'last_payment_failure_reason',
                'pending_invoice_id',
                'pending_invoice_amount',
            ]);
        });
    }
};
