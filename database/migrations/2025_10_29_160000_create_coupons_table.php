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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Kód kupónu
            $table->string('name')->nullable(); // Název pro admin
            $table->text('description')->nullable(); // Popis
            
            // Typ slevy pro jednorázový nákup
            $table->enum('discount_type_order', ['percentage', 'fixed', 'none'])->default('none');
            $table->decimal('discount_value_order', 10, 2)->nullable(); // Hodnota slevy pro objednávky
            
            // Typ slevy pro předplatné
            $table->enum('discount_type_subscription', ['percentage', 'fixed', 'none'])->default('none');
            $table->decimal('discount_value_subscription', 10, 2)->nullable(); // Hodnota slevy pro předplatné
            $table->integer('subscription_discount_months')->nullable(); // Počet měsíců slevy (null = neomezeně)
            
            // Doprava zdarma
            $table->boolean('free_shipping')->default(false); // Doprava zdarma pro jednorázové nákupy
            
            // Minimální hodnota objednávky
            $table->decimal('min_order_value', 10, 2)->nullable();
            
            // Platnost
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            
            // Limity použití
            $table->integer('usage_limit_total')->nullable(); // Celkový limit použití (null = neomezeně)
            $table->integer('usage_limit_per_user')->nullable(); // Limit na uživatele (null = neomezeně)
            
            // Statistiky
            $table->integer('times_used')->default(0); // Počet použití
            
            // Status
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            // Indexy
            $table->index('code');
            $table->index('is_active');
            $table->index(['valid_from', 'valid_until']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};

