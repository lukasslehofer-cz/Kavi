<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds English translation columns to products and roasteries tables.
     */
    public function up(): void
    {
        // Products - add English translation columns
        Schema::table('products', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->text('description_en')->nullable()->after('description');
            $table->string('short_description_en')->nullable()->after('short_description');
            // Note: attributes JSON already supports _en keys (origin_en, processing_en, etc.)
        });

        // Roasteries - add English translation columns
        Schema::table('roasteries', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->text('short_description_en')->nullable()->after('short_description');
            $table->text('full_description_en')->nullable()->after('full_description');
            $table->string('country_en')->nullable()->after('country');
            $table->string('city_en')->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'description_en', 'short_description_en']);
        });

        Schema::table('roasteries', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'short_description_en', 'full_description_en', 'country_en', 'city_en']);
        });
    }
};
