<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * KROK 4 revize evidence předplatných.
 *
 * Zavádí unique(subscription_id, shipment_schedule_id) – fyzicky znemožní vznik
 * více řádků ledgeru pro tentýž box (měsíc) téhož předplatného. Vztahuje se na
 * VŠECHNY statusy (MySQL neumí filtrovaný unique index).
 *
 * Předpoklad: KROK 2 (backfill schedule_id) + KROK 3 (dedupe) proběhly a
 * `subscriptions:check-consistency` hlásí 0 v obou BLOCKER třídách.
 *
 * Pozn.: shipment_schedule_id zůstává NULLABLE (FK má onDelete('set null'),
 * NOT NULL by s tím kolidovalo). MySQL bere NULL jako různé hodnoty, takže index
 * nebrání teoretickým NULL řádkům; non-null zajišťuje jediný creator
 * (getOrCreateForSchedule) + konzistenční kontrola.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_shipments', function (Blueprint $table) {
            $table->unique(
                ['subscription_id', 'shipment_schedule_id'],
                'subscription_shipments_sub_schedule_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('subscription_shipments', function (Blueprint $table) {
            $table->dropUnique('subscription_shipments_sub_schedule_unique');
        });
    }
};
