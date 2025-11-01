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
        Schema::table('orders', function (Blueprint $table) {
            // Vazba na konkrétní rozesílku předplatného
            $table->foreignId('shipment_schedule_id')
                ->nullable()
                ->after('subscription_id')
                ->constrained('shipment_schedules')
                ->onDelete('set null');
            
            // Označení, že objednávka bude odeslána s předplatným
            $table->boolean('shipped_with_subscription')
                ->default(false)
                ->after('shipment_schedule_id');
            
            // Počet slotů, které objednávka zabírá
            $table->unsignedInteger('subscription_addon_slots_used')
                ->default(0)
                ->after('shipped_with_subscription');
            
            // Index pro rychlé vyhledávání
            $table->index(['shipment_schedule_id', 'shipped_with_subscription']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['shipment_schedule_id', 'shipped_with_subscription']);
            $table->dropForeign(['shipment_schedule_id']);
            $table->dropColumn([
                'shipment_schedule_id',
                'shipped_with_subscription',
                'subscription_addon_slots_used',
            ]);
        });
    }
};
