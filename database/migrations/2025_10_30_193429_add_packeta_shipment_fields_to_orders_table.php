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
            $table->string('packeta_point_id')->nullable()->after('packeta_sent_at');
            $table->string('packeta_point_name')->nullable()->after('packeta_point_id');
            $table->text('packeta_point_address')->nullable()->after('packeta_point_name');
            $table->string('packeta_shipment_status')->nullable()->after('packeta_point_address')->comment('pending, submitted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'packeta_point_id',
                'packeta_point_name',
                'packeta_point_address',
                'packeta_shipment_status'
            ]);
        });
    }
};
