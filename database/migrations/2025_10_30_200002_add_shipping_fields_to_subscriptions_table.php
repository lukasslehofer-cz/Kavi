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
            $table->foreignId('shipping_rate_id')->nullable()->after('configured_price')->constrained('shipping_rates')->nullOnDelete();
            $table->string('shipping_country', 2)->nullable()->after('shipping_rate_id');
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('shipping_country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['shipping_rate_id']);
            $table->dropColumn(['shipping_rate_id', 'shipping_country', 'shipping_cost']);
        });
    }
};

