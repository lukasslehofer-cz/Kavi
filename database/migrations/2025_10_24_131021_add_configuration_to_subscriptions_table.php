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
            $table->json('configuration')->nullable()->after('subscription_plan_id');
            $table->decimal('configured_price', 10, 2)->nullable()->after('configuration');
            $table->integer('frequency_months')->nullable()->after('configured_price')->comment('Delivery frequency in months: 1, 2, or 3');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['configuration', 'configured_price', 'frequency_months']);
        });
    }
};
