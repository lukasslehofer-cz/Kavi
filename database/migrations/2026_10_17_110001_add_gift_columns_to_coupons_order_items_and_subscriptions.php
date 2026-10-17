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
        // Kupon vygenerovaný z dárku (subscription | voucher). U ručně zakládaných
        // kuponů zůstává null a žádná z dárkových pojistek se jich netýká.
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('gift_type', 20)->nullable()->after('is_gift_voucher')->index();
        });

        // Položka objednávky za dárek (product_id je null, data v gift_purchases)
        Schema::table('order_items', function (Blueprint $table) {
            $table->boolean('is_gift')->default(false)->after('vat_rate');
        });

        // Předplatné vzniklé uplatněním dárku (skončí po N boxech, nic se neúčtuje)
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreignId('gift_purchase_id')->nullable()->unique()->after('coupon_code')
                ->constrained('gift_purchases')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('gift_purchase_id');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('is_gift');
        });

        Schema::table('coupons', function (Blueprint $table) {
            $table->dropIndex(['gift_type']);
            $table->dropColumn('gift_type');
        });
    }
};
