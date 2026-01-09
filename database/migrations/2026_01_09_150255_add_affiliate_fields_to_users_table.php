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
            $table->boolean('is_affiliate_partner')->default(false)->after('is_admin');
            $table->timestamp('affiliate_activated_at')->nullable()->after('is_affiliate_partner');
            
            // Index pro rychlé vyhledávání affiliate partnerů
            $table->index('is_affiliate_partner');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['is_affiliate_partner']);
            $table->dropColumn(['is_affiliate_partner', 'affiliate_activated_at']);
        });
    }
};
