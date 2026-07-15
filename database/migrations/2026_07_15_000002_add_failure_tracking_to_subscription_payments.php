<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Persist a durable record of failed charges. Until now only 'paid' rows were
     * ever written, so a failed subscription charge left no per-period trace and
     * the admin could not surface an unpaid shipment. A single 'failed' row per
     * (subscription, period) accumulates the retry attempts.
     */
    public function up(): void
    {
        Schema::table('subscription_payments', function (Blueprint $table) {
            $table->text('failure_reason')->nullable()->after('status');
            $table->unsignedInteger('attempts')->default(0)->after('failure_reason');
            $table->timestamp('last_attempt_at')->nullable()->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('subscription_payments', function (Blueprint $table) {
            $table->dropColumn(['failure_reason', 'attempts', 'last_attempt_at']);
        });
    }
};
