<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Účtování dopravy k předplatnému zvlášť pro EUR (kavibox.com a kavibox.sk).
 *
 * Stávající applies_to_subscriptions dál platí pro CZK (kavi.cz). EUR ceny předplatného
 * počítají s tím, že se k nim doprava přičte – předplatné do ČR přes EUR web by jinak
 * bez dopravy vycházelo levněji než na kavi.cz. Proto se ČR v EUR dopravou účtuje
 * (rozhodnutí 28. 11. 2026), ostatní země přebírají nastavení z CZK.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('shipping_rates', 'applies_to_subscriptions_eur')) {
            Schema::table('shipping_rates', function (Blueprint $table) {
                $table->boolean('applies_to_subscriptions_eur')->default(false);
            });

            DB::table('shipping_rates')->update(['applies_to_subscriptions_eur' => DB::raw('applies_to_subscriptions')]);
            DB::table('shipping_rates')->where('country_code', 'CZ')->update(['applies_to_subscriptions_eur' => true]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('shipping_rates', 'applies_to_subscriptions_eur')) {
            Schema::table('shipping_rates', function (Blueprint $table) {
                $table->dropColumn('applies_to_subscriptions_eur');
            });
        }
    }
};
