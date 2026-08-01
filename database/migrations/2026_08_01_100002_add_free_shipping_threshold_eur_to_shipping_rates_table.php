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
        Schema::table('shipping_rates', function (Blueprint $table) {
            // Práh pro dopravu zdarma v eurech. Doteď existoval jen CZK sloupec a výpočet
            // byl navíc podmíněný CurrencyHelper::isCzk(), takže EUR objednávka nedostala
            // dopravu zdarma nikdy, ať byla jakkoli velká.
            // NULL = pro tuto zemi se doprava zdarma v EUR neuplatňuje.
            $table->decimal('free_shipping_threshold_eur', 10, 2)
                ->nullable()
                ->after('free_shipping_threshold_czk')
                ->comment('Práh pro dopravu zdarma v EUR (NULL = neuplatňuje se)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_rates', function (Blueprint $table) {
            $table->dropColumn('free_shipping_threshold_eur');
        });
    }
};
