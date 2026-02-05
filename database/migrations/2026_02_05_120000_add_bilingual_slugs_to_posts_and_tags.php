<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update posts table
        Schema::table('posts', function (Blueprint $table) {
            $table->renameColumn('slug', 'slug_cs');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->string('slug_en')->nullable()->unique()->after('slug_cs');
        });

        // Update tags table
        Schema::table('tags', function (Blueprint $table) {
            $table->renameColumn('slug', 'slug_cs');
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->string('slug_en')->nullable()->unique()->after('slug_cs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert tags table
        Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn('slug_en');
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->renameColumn('slug_cs', 'slug');
        });

        // Revert posts table
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('slug_en');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->renameColumn('slug_cs', 'slug');
        });
    }
};
