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
            $table->foreignId('shipping_rate_id')->nullable()->after('shipping')->constrained('shipping_rates')->nullOnDelete();
            $table->string('shipping_country', 2)->nullable()->after('shipping_rate_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['shipping_rate_id']);
            $table->dropColumn(['shipping_rate_id', 'shipping_country']);
        });
    }
};

