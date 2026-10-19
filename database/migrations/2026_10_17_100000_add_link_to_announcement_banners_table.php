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
            // Volitelný odkaz za textem hlášky (např. "Darovat kávu" na stránku dárků).
            // URL zvlášť pro CZ a EN, protože se lokalizované cesty liší.
            $table->string('link_label_cs', 80)->nullable()->after('title_en');
            $table->string('link_url_cs')->nullable()->after('link_label_cs');
            $table->string('link_label_en', 80)->nullable()->after('link_url_cs');
            $table->string('link_url_en')->nullable()->after('link_label_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcement_banners', function (Blueprint $table) {
            $table->dropColumn(['link_label_cs', 'link_url_cs', 'link_label_en', 'link_url_en']);
        });
    }
};
