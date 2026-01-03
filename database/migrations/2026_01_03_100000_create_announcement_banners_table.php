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
        Schema::create('announcement_banners', function (Blueprint $table) {
            $table->id();
            $table->text('message_cs');
            $table->text('message_en')->nullable();
            $table->string('icon')->default('check'); // check, gift, percent, info, star, truck, heart
            $table->timestamp('active_from')->nullable();
            $table->timestamp('active_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Index for efficient querying of active banners
            $table->index(['is_active', 'active_from', 'active_until']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcement_banners');
    }
};

