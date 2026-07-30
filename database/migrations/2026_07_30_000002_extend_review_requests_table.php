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
            // Kolikátým doručením byla žádost vyvolána. Dedupe se dřív dělal jen
            // na (user_id, subscription_id), takže za celý život předplatného
            // mohla odejít jediná žádost.
            $table->unsignedInteger('milestone')->default(0)->after('review_type');

            // Hvězdička kliknutá přímo v e-mailu (1-5)
            $table->unsignedTinyInteger('rating')->nullable()->after('clicked_at');

            // E-mail příjemce - hosté bez účtu nemají user_id
            $table->string('email')->nullable()->after('user_id');

            $table->timestamp('reminded_at')->nullable()->after('email_sent_at');

            $table->index(['email', 'review_type']);
            $table->index(['user_id', 'subscription_id', 'milestone']);
        });

        // Hosté nemají účet, takže user_id musí být nullable
        DB::statement('ALTER TABLE review_requests MODIFY COLUMN user_id BIGINT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_requests', function (Blueprint $table) {
            $table->dropIndex(['email', 'review_type']);
            $table->dropIndex(['user_id', 'subscription_id', 'milestone']);
            $table->dropColumn(['milestone', 'rating', 'email', 'reminded_at']);
        });
    }
};
