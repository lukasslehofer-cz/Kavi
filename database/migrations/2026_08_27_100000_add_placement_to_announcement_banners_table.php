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
        Schema::table('announcement_banners', function (Blueprint $table) {
            // Kde se hláška zobrazí. Defaulty zachovají chování stávajících hlášek (jen záhlaví).
            $table->boolean('show_in_header')->default(true)->after('icon');
            $table->boolean('show_in_checkout')->default(false)->after('show_in_header');
            $table->boolean('show_in_subscription_checkout')->default(false)->after('show_in_checkout');

            // Volitelný nadpis - používá se jen v pokladně, v záhlaví se ignoruje
            $table->string('title_cs')->nullable()->after('show_in_subscription_checkout');
            $table->string('title_en')->nullable()->after('title_cs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcement_banners', function (Blueprint $table) {
            $table->dropColumn([
                'show_in_header',
                'show_in_checkout',
                'show_in_subscription_checkout',
                'title_cs',
                'title_en',
            ]);
        });
    }
};
