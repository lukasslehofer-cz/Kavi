<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Stripe cesty zapisovaly měnu lowercase, ostatní uppercase. Na subscriptions
        // je uppercase, tak to sjednotíme i tady – od teď to hlídá mutator na modelu.
        DB::table('subscription_payments')
            ->whereNotNull('currency')
            ->update(['currency' => DB::raw('UPPER(currency)')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Zpět není co vracet – původní stav byl nekonzistentní mix.
    }
};
