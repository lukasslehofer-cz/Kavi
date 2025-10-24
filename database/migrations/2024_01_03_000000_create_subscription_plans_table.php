<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Espresso BOX", "Filtr BOX"
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->enum('interval', ['monthly', 'quarterly', 'yearly'])->default('monthly');
            $table->integer('coffee_count')->default(2); // Number of coffee bags
            $table->integer('coffee_weight')->default(250); // Weight per bag in grams
            $table->enum('coffee_type', ['espresso', 'filter', 'mixed']);
            $table->string('stripe_price_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('features')->nullable(); // List of included features
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};




