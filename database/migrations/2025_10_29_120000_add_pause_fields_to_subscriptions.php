<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->integer('paused_iterations')->nullable()->after('frequency_months');
            $table->date('paused_until_date')->nullable()->after('paused_iterations');
            $table->string('pause_reason')->nullable()->after('paused_until_date');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['paused_iterations', 'paused_until_date', 'pause_reason']);
        });
    }
};


