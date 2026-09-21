<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Sjednocuje typ anglického krátkého popisu s českým.
     *
     * short_description je TEXT, short_description_en byl VARCHAR(255) - delší
     * anglické intro tak při ukládání produktu shodilo insert (SQLSTATE 22001).
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE products MODIFY short_description_en TEXT NULL');
    }

    /**
     * Pozor: rollback projde jen pokud žádný záznam nepřesahuje 255 znaků.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE products MODIFY short_description_en VARCHAR(255) NULL');
    }
};
