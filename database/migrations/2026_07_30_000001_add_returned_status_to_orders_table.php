<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * packeta:update-delivery-status zapisuje status 'returned', ale enum tuhle
     * hodnotu neobsahoval - ve strict mode zápis selhal, jinak se uložil prázdný
     * řetězec.
     */
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite ukládá status jako TEXT, žádná změna schématu není potřeba
        } else {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'paid', 'processing', 'submitted', 'shipped', 'delivered', 'returned', 'cancelled') DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            // No change needed for SQLite
        } else {
            DB::statement("UPDATE orders SET status = 'cancelled' WHERE status = 'returned'");
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'paid', 'processing', 'submitted', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending'");
        }
    }
};
