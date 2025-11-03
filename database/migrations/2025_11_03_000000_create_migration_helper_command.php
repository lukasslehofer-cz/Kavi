<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - creates temporary table for import tracking
     */
    public function up(): void
    {
        Schema::create('woocommerce_migration_log', function (Blueprint $table) {
            $table->id();
            $table->string('wc_user_id')->nullable();
            $table->string('wc_subscription_id')->nullable();
            $table->string('wc_order_id')->nullable();
            $table->foreignId('kavi_user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('kavi_subscription_id')->nullable()->constrained('subscriptions')->onDelete('cascade');
            $table->foreignId('kavi_order_id')->nullable()->constrained('orders')->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, success, failed
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('woocommerce_migration_log');
    }
};


