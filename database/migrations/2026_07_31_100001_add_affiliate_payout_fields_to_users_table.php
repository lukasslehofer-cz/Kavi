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
        Schema::table('users', function (Blueprint $table) {
            // Fakturační hranice partnera. NULL = použije se výchozí z config/affiliate.php
            $table->decimal('affiliate_payout_threshold', 10, 2)
                ->nullable()
                ->after('affiliate_activated_at')
                ->comment('Hranice pro výplatu odměn (v měně partnera), NULL = výchozí z configu');

            // Kdy naposledy partnerovi odešel mail o dosažení hranice (guard proti opakování)
            $table->timestamp('affiliate_threshold_notified_at')
                ->nullable()
                ->after('affiliate_payout_threshold');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'affiliate_payout_threshold',
                'affiliate_threshold_notified_at',
            ]);
        });
    }
};
