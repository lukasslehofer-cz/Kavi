<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add Packeta fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('packeta_point_id')->nullable()->after('stripe_customer_id');
            $table->string('packeta_point_name')->nullable()->after('packeta_point_id');
            $table->text('packeta_point_address')->nullable()->after('packeta_point_name');
        });

        // Add Packeta fields to subscriptions table
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('packeta_point_id')->nullable()->after('payment_method');
            $table->string('packeta_point_name')->nullable()->after('packeta_point_id');
            $table->text('packeta_point_address')->nullable()->after('packeta_point_name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['packeta_point_id', 'packeta_point_name', 'packeta_point_address']);
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['packeta_point_id', 'packeta_point_name', 'packeta_point_address']);
        });
    }
};
