<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Add only the missing columns
            if (!Schema::hasColumn('subscriptions', 'shipping_address')) {
                $table->json('shipping_address')->nullable()->after('frequency_months');
            }
            
            if (!Schema::hasColumn('subscriptions', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('shipping_address');
            }
            
            // Make user_id nullable if not already
            $table->foreignId('user_id')->nullable()->change();
            
            // Make subscription_plan_id nullable if not already
            $table->foreignId('subscription_plan_id')->nullable()->change();
        });
        
        // Update status enum to include 'pending' if needed
        DB::statement("ALTER TABLE subscriptions MODIFY COLUMN status ENUM('pending', 'active', 'paused', 'cancelled', 'expired') DEFAULT 'pending'");
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('subscriptions', 'shipping_address')) {
                $table->dropColumn('shipping_address');
            }
            
            if (Schema::hasColumn('subscriptions', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
    }
};

