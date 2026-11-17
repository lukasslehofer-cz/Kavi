<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('review_requests', function (Blueprint $table) {
            // clicked_at drží první klik. Recenze ale vzniká těsně po tom
            // posledním (třeba až z výzvy k dokončení), proto druhý sloupec.
            $table->timestamp('last_clicked_at')->nullable()->after('clicked_at');

            // Kdy jsme na Googlu našli recenzi, která na klik navazuje. Je to
            // okamžik zjištění, ne čas recenze - z Googlu se neukládá nic.
            $table->timestamp('google_review_found_at')->nullable()->after('rating');

            // Výzva k dokončení pro toho, kdo kliknul a recenzi nedopsal
            $table->timestamp('nudged_at')->nullable()->after('google_review_found_at');
        });

        DB::table('review_requests')
            ->whereNotNull('clicked_at')
            ->update(['last_clicked_at' => DB::raw('clicked_at')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_requests', function (Blueprint $table) {
            $table->dropColumn(['last_clicked_at', 'google_review_found_at', 'nudged_at']);
        });
    }
};
