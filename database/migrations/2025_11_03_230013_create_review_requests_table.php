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
        Schema::create('review_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('review_type', ['order', 'subscription']);
            $table->string('tracking_token')->unique(); // Pro tracking kliknutí
            $table->timestamp('email_sent_at')->nullable();
            $table->timestamp('clicked_at')->nullable(); // Kdy klikl na button
            $table->string('clicked_ip')->nullable(); // IP adresa při kliknutí
            $table->timestamps();
            
            // Indexes pro rychlé vyhledávání
            $table->index(['user_id', 'review_type']);
            $table->index(['clicked_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_requests');
    }
};
