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
            $table->string('currency', 3)->default('CZK')->after('total');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('currency', 3)->default('CZK')->after('configured_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('currency');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
    }
};
