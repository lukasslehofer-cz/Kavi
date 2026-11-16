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
        // Kdy kupec dostal v e-mailu o doručení objednávky nabídku předplatného.
        // Od tohoto data se počítá, kolik kupců do 60 dnů na předplatné přešlo
        // (SubscriptionOfferService::stats()). Null = e-mail odešel bez nabídky.
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('subscription_offer_sent_at')->nullable()->after('delivered_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('subscription_offer_sent_at');
        });
    }
};
