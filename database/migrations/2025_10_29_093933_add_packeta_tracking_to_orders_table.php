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
            $table->string('packeta_packet_id')->nullable()->after('delivered_at');
            $table->string('packeta_tracking_url')->nullable()->after('packeta_packet_id');
            $table->timestamp('packeta_sent_at')->nullable()->after('packeta_tracking_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['packeta_packet_id', 'packeta_tracking_url', 'packeta_sent_at']);
        });
    }
};
