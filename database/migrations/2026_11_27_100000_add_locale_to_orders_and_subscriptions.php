<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Jazyk webu, na kterém objednávka / předplatné vzniklo (cs, en, sk).
 *
 * Dřív se jazyk e-mailů a faktur odvozoval z měny (EUR = angličtina). S kavibox.sk
 * (EUR, slovenština) to nestačí. Stávající řádky dostanou jazyk podle měny, takže
 * se pro ně nic nemění.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (['orders', 'subscriptions'] as $table) {
            if (! Schema::hasColumn($table, 'locale')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->string('locale', 5)->nullable()->after('currency');
                });
            }

            DB::table($table)->whereNull('locale')->update([
                'locale' => DB::raw("CASE WHEN UPPER(currency) = 'EUR' THEN 'en' ELSE 'cs' END"),
            ]);
        }
    }

    public function down(): void
    {
        foreach (['orders', 'subscriptions'] as $table) {
            if (Schema::hasColumn($table, 'locale')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->dropColumn('locale');
                });
            }
        }
    }
};
