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
            $table->foreignId('coupon_id')->nullable()->after('subscription_plan_id')->constrained()->onDelete('set null');
            $table->string('coupon_code')->nullable()->after('coupon_id');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('coupon_code'); // Sleva na každou platbu
            $table->integer('discount_months_remaining')->nullable()->after('discount_amount'); // Zbývající měsíce se slevou
            $table->integer('discount_months_total')->nullable()->after('discount_months_remaining'); // Celkový počet měsíců se slevou
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
            $table->dropColumn(['coupon_id', 'coupon_code', 'discount_amount', 'discount_months_remaining', 'discount_months_total']);
        });
    }
};

