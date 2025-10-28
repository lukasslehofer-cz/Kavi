<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roasteries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('website_url')->nullable();
            $table->string('instagram')->nullable();
            $table->string('image')->nullable(); // Hlavní foto
            $table->string('country');
            $table->string('country_flag')->nullable(); // URL nebo emoji vlajky
            $table->string('city')->nullable();
            $table->text('address')->nullable(); // Plná adresa
            $table->text('short_description')->nullable();
            $table->text('full_description')->nullable();
            $table->json('gallery')->nullable(); // Max 4 další fotky
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roasteries');
    }
};

