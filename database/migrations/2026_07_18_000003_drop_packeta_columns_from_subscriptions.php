<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * KROK 8 revize evidence předplatných.
 *
 * Odstraňuje per-box Packeta tracking sloupce z `subscriptions`. Ty žijí nově výhradně
 * na `subscription_shipments` (ledger); "poslední tracking předplatného" se odvozuje
 * accessorem Subscription::packeta_* → latestShipment.
 *
 * ZŮSTÁVÁ: packeta_point_id / packeta_point_name / packeta_point_address (žádané výdejní
 * místo = konfigurace předplatného, ne stav konkrétního boxu).
 *
 * Předpoklad: všichni čtenáři/zapisovatelé těchto sloupců přesměrováni (KROK 8 kód).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'packeta_packet_id',
                'packeta_tracking_url',
                'packeta_shipment_status',
                'packeta_sent_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('packeta_packet_id')->nullable()->after('carrier_pickup_point');
            $table->string('packeta_tracking_url')->nullable()->after('packeta_packet_id');
            $table->string('packeta_shipment_status')->default('pending')->after('packeta_tracking_url');
            $table->timestamp('packeta_sent_at')->nullable()->after('packeta_shipment_status');
        });
    }
};
