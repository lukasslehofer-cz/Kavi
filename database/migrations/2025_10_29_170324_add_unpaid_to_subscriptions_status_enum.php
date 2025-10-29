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
        // MySQL ENUM modification requires raw SQL
        DB::statement("ALTER TABLE subscriptions MODIFY COLUMN status ENUM('active', 'unpaid', 'paused', 'cancelled', 'expired') NOT NULL DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'unpaid' from the enum
        DB::statement("ALTER TABLE subscriptions MODIFY COLUMN status ENUM('active', 'paused', 'cancelled', 'expired') NOT NULL DEFAULT 'active'");
    }
};
