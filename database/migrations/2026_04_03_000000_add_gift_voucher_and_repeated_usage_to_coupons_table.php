<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->boolean('is_gift_voucher')->default(false)->after('free_shipping');
            $table->boolean('allow_repeated_subscription_usage')->default(false)->after('subscription_discount_months');
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(['is_gift_voucher', 'allow_repeated_subscription_usage']);
        });
    }
};
