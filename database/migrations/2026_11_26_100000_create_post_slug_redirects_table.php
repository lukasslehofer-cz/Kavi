<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Staré slugy blogových článků, ze kterých se přesměrovává (301) na aktuální slug.
     * Plní je PostObserver při změně slugu.
     */
    public function up(): void
    {
        Schema::create('post_slug_redirects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->string('old_slug')->unique();
            $table->string('locale', 2)->default('cs');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_slug_redirects');
    }
};
