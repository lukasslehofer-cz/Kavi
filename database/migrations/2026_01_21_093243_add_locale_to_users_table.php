<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('locale', 5)->default('cs')->after('email');
        });

        // Update existing users based on their subscriptions/orders currency
        // Users with EUR currency get 'en', others keep 'cs'
        
        // First, update users who have EUR subscriptions
        DB::statement("
            UPDATE users 
            SET locale = 'en' 
            WHERE id IN (
                SELECT DISTINCT user_id 
                FROM subscriptions 
                WHERE currency = 'EUR' AND user_id IS NOT NULL
            )
        ");
        
        // Then, update users who have EUR orders (but don't have subscriptions)
        DB::statement("
            UPDATE users 
            SET locale = 'en' 
            WHERE locale = 'cs' 
            AND id IN (
                SELECT DISTINCT user_id 
                FROM orders 
                WHERE currency = 'EUR' AND user_id IS NOT NULL
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('locale');
        });
    }
};
