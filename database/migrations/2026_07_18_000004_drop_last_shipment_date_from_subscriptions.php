<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * KROK 9 revize evidence předplatných.
 *
 * Odstraňuje `subscriptions.last_shipment_date` – byl to jen denormalizovaný cache
 * posledního odeslaného boxu. Nově se odvozuje z ledgeru: MAX(shipment_date) přes
 * sent/delivered zásilky, přes accessor Subscription::last_shipment_date.
 *
 * Pozn.: `consecutive_unpaid_shipments` ZŮSTÁVÁ – není to duplicitní údaj o zásilce,
 * ale čítač po sobě jdoucích neuhrazených cyklů, který řídí auto-zrušení předplatného
 * (a je korektně udržován v StripeService). Jeho odvození z ledgeru by ohrozilo živou
 * billing/cancel logiku bez úměrného přínosu.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('last_shipment_date');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->date('last_shipment_date')->nullable()->after('next_billing_date');
        });
    }
};
