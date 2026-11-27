<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Vlastní dostupnost dopravy pro kavibox.sk (vedle kavi.cz a kavibox.com).
 *
 * Výchozí stav převzatý z kavibox.com – slovenský web prodává do stejných zemí;
 * v adminu Dopravy se pak dá nastavit zvlášť. Ceny zůstávají společné s .com
 * (price_eur, free_shipping_threshold_eur).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('shipping_rates', 'available_on_sk')) {
            Schema::table('shipping_rates', function (Blueprint $table) {
                $table->boolean('available_on_sk')->default(false)->after('available_on_com');
            });

            DB::table('shipping_rates')->update(['available_on_sk' => DB::raw('available_on_com')]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('shipping_rates', 'available_on_sk')) {
            Schema::table('shipping_rates', function (Blueprint $table) {
                $table->dropColumn('available_on_sk');
            });
        }
    }
};
