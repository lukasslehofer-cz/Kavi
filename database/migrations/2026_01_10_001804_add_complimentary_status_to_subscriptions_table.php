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
        // Add 'complimentary' to subscriptions status enum
        // Current values: 'active', 'pending', 'unpaid', 'paused', 'cancelled', 'completed', 'expired'
        // New values: add 'complimentary'
        DB::statement("ALTER TABLE subscriptions MODIFY COLUMN status ENUM('active', 'pending', 'unpaid', 'paused', 'cancelled', 'completed', 'expired', 'complimentary') NOT NULL DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous enum values (remove 'complimentary')
        // Note: This will fail if there are any subscriptions with status='complimentary'
        DB::statement("ALTER TABLE subscriptions MODIFY COLUMN status ENUM('active', 'pending', 'unpaid', 'paused', 'cancelled', 'completed', 'expired') NOT NULL DEFAULT 'active'");
    }
};
