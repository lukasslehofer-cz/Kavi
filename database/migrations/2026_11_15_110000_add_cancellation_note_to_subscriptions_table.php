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
        // Vlastní slova zákazníka u důvodu "Jiný důvod" při zrušení v účtu. Samotný
        // důvod (kód) žije v cancellation_reason. Při smazání účtu se text maže.
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->text('cancellation_note')->nullable()->after('cancellation_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('cancellation_note');
        });
    }
};
