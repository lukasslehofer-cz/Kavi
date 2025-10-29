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
        // For MySQL, we need to use raw SQL to modify ENUM
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status ENUM('pending', 'paid', 'unpaid', 'failed', 'refunded') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'unpaid' from the enum
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status ENUM('pending', 'paid', 'failed', 'refunded') NOT NULL DEFAULT 'pending'");
    }
};
