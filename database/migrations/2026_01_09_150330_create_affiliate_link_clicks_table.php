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
        Schema::create('affiliate_link_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_link_id')->constrained()->onDelete('cascade');
            $table->string('ip_address', 45)->nullable()->comment('IPv4 nebo IPv6 adresa');
            $table->text('user_agent')->nullable();
            $table->string('referrer', 512)->nullable()->comment('Odkud uživatel přišel');
            $table->string('session_id', 100)->index()->comment('Laravel session ID');
            $table->timestamp('clicked_at')->useCurrent();
            
            // Indexes
            $table->index('affiliate_link_id');
            $table->index('clicked_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_link_clicks');
    }
};
