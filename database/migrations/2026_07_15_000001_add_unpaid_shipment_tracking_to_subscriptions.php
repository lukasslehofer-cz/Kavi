<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Two independent counters replace the single cumulative payment_failure_count:
     *  - payment_failure_count  = reminder attempts for the CURRENT shipment (0..N),
     *    reset to 0 both on success and when the shipment is abandoned (paused).
     *  - consecutive_unpaid_shipments = how many shipments in a row went unpaid,
     *    reset to 0 only on a successful charge. When it reaches the cancel
     *    threshold the subscription is auto-cancelled.
     */
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->unsignedInteger('consecutive_unpaid_shipments')->default(0)->after('payment_failure_count');
            $table->string('cancellation_reason')->nullable()->after('pause_reason');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['consecutive_unpaid_shipments', 'cancellation_reason']);
        });
    }
};
