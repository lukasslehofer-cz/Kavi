<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * KROK 10 revize evidence předplatných.
 *
 * Přidává robustní vazbu addon objednávky (doprava "Doručení s předplatným") přímo na
 * KONKRÉTNÍ řádek zásilky předplatného (subscription_shipments), místo dosavadní vazby
 * jen na měsíc (shipment_schedule_id). Řádek je díky KROKŮM 2–4 identitně stabilní a
 * deduplikovaný, takže addon míří na trvalý cíl, který přežije přeplánování.
 *
 * `shipment_schedule_id` na orders ZŮSTÁVÁ přechodně (zpětná kompatibilita čtenářů).
 *
 * Backfill: pro každou addon objednávku dohledá zásilku téhož předplatného pro daný
 * schedule a nastaví FK. onDelete('set null') = pokud zásilka zanikne, objednávka se
 * odpojí (drží se k ruční revizi), nikdy nekaskáduje smazání objednávky.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('subscription_shipment_id')
                ->nullable()
                ->after('shipment_schedule_id')
                ->constrained('subscription_shipments')
                ->nullOnDelete();
        });

        // Backfill z existující vazby (subscription_id, shipment_schedule_id).
        $addonOrders = DB::table('orders')
            ->where('shipped_with_subscription', true)
            ->whereNotNull('subscription_id')
            ->whereNotNull('shipment_schedule_id')
            ->get(['id', 'subscription_id', 'shipment_schedule_id']);

        $updated = 0;
        foreach ($addonOrders as $order) {
            $shipmentId = DB::table('subscription_shipments')
                ->where('subscription_id', $order->subscription_id)
                ->where('shipment_schedule_id', $order->shipment_schedule_id)
                ->value('id');

            if ($shipmentId) {
                DB::table('orders')->where('id', $order->id)->update([
                    'subscription_shipment_id' => $shipmentId,
                ]);
                $updated++;
            }
        }

        \Log::info('KROK 10 backfill orders.subscription_shipment_id', [
            'addon_orders' => $addonOrders->count(),
            'linked' => $updated,
        ]);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['subscription_shipment_id']);
            $table->dropColumn('subscription_shipment_id');
        });
    }
};
