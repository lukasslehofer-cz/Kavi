<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('subscriptions')
            ->where('discount_months_remaining', 0)
            ->where('discount_amount', '>', 0)
            ->update(['discount_amount' => 0]);
    }

    public function down(): void
    {
    }
};
