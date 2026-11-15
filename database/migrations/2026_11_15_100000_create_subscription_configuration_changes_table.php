<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Změny konfigurace existujícího předplatného (velikost boxu, druh kávy, frekvence).
 *
 * Tabulka slouží dvěma účelům zároveň:
 *  - audit: co se změnilo, kdo o to požádal a kdy se to projevilo,
 *  - úložiště NAPLÁNOVANÉ změny: dokud má předplatné zaplacený, ale neodeslaný box,
 *    nesmí se živá konfigurace změnit (rozesílka, sklad i faktura ji čtou živě).
 *    Změna proto čeká se status = scheduled a použije ji až noční příkaz
 *    subscriptions:apply-configuration-changes.
 *
 * new_configured_price = NULL znamená, že se cena nemění (změna jen druhu/frekvence).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_configuration_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->cascadeOnDelete();
            $table->foreignId('requested_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('source', 20)->default('customer'); // customer | admin | system
            $table->string('status', 20)->default('scheduled'); // scheduled | applied | cancelled | superseded
            $table->string('cancel_reason', 100)->nullable();

            $table->json('old_configuration')->nullable();
            $table->json('new_configuration');
            $table->unsignedTinyInteger('old_frequency_months')->nullable();
            $table->unsignedTinyInteger('new_frequency_months');
            $table->decimal('old_configured_price', 10, 2)->nullable();
            $table->decimal('new_configured_price', 10, 2)->nullable();
            $table->decimal('old_discount_amount', 10, 2)->nullable();
            $table->decimal('new_discount_amount', 10, 2)->nullable();

            // První box, který odejde v nové konfiguraci (u naplánované změny odhad při zadání)
            $table->date('effective_shipment_date')->nullable();
            $table->date('old_next_shipment_date')->nullable();
            $table->date('new_next_shipment_date')->nullable();
            $table->date('old_next_billing_date')->nullable();
            $table->date('new_next_billing_date')->nullable();

            $table->timestamp('applied_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['subscription_id', 'status'], 'sub_config_changes_sub_status_index');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_configuration_changes');
    }
};
