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
        Schema::table('products', function (Blueprint $table) {
            $table->string('google_image')->nullable()->after('facebook_image');
        });

        // Dosavadní FB obrázky jsou nově obrázky pro Google a FB feed je bere jako zálohu,
        // dokud se k produktu nenahraje nový FB obrázek. Soubory zůstávají na původní cestě.
        DB::table('products')
            ->whereNotNull('facebook_image')
            ->update(['google_image' => DB::raw('facebook_image')]);

        DB::table('products')
            ->whereNotNull('google_image')
            ->update(['facebook_image' => null]);
    }

    public function down(): void
    {
        DB::table('products')
            ->whereNull('facebook_image')
            ->whereNotNull('google_image')
            ->update(['facebook_image' => DB::raw('google_image')]);

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('google_image');
        });
    }
};
