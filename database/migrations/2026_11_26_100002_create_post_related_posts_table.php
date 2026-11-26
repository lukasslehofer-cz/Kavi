<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ručně přiřazené související články (max. 3, v pořadí podle position).
     * Článek bez přiřazení používá automatický výběr podle štítků.
     */
    public function up(): void
    {
        Schema::create('post_related_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('related_post_id')->constrained('posts')->cascadeOnDelete();
            $table->unsignedTinyInteger('position')->default(1);
            $table->timestamps();

            $table->unique(['post_id', 'related_post_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_related_posts');
    }
};
