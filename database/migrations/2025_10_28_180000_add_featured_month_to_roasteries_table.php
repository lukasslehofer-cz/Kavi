<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roasteries', function (Blueprint $table) {
            $table->string('featured_month')->nullable()->after('sort_order')->comment('Měsíc rozesílky ve formátu YYYY-MM');
        });
    }

    public function down(): void
    {
        Schema::table('roasteries', function (Blueprint $table) {
            $table->dropColumn('featured_month');
        });
    }
};

