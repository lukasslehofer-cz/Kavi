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
            // Add package dimensions columns after packeta_shipment_status
            $table->decimal('package_weight', 8, 2)->nullable()->after('packeta_shipment_status');
            $table->decimal('package_length', 8, 2)->nullable()->after('package_weight');
            $table->decimal('package_width', 8, 2)->nullable()->after('package_length');
            $table->decimal('package_height', 8, 2)->nullable()->after('package_width');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['package_weight', 'package_length', 'package_width', 'package_height']);
        });
    }
};
