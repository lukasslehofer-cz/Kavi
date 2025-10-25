<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('packeta_packet_id')->nullable()->after('packeta_point_address');
            $table->string('packeta_shipment_status')->default('pending')->after('packeta_packet_id');
            $table->timestamp('packeta_sent_at')->nullable()->after('packeta_shipment_status');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'packeta_packet_id',
                'packeta_shipment_status', 
                'packeta_sent_at'
            ]);
        });
    }
};
