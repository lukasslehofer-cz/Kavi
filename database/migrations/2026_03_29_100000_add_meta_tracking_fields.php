<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('meta_event_id')->nullable()->after('package_height');
            $table->string('meta_fbp')->nullable()->after('meta_event_id');
            $table->string('meta_fbc')->nullable()->after('meta_fbp');
            $table->datetime('meta_capi_sent_at')->nullable()->after('meta_fbc');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('meta_event_id')->nullable()->after('shipping_rate_id');
            $table->string('meta_fbp')->nullable()->after('meta_event_id');
            $table->string('meta_fbc')->nullable()->after('meta_fbp');
            $table->datetime('meta_capi_sent_at')->nullable()->after('meta_fbc');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['meta_event_id', 'meta_fbp', 'meta_fbc', 'meta_capi_sent_at']);
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['meta_event_id', 'meta_fbp', 'meta_fbc', 'meta_capi_sent_at']);
        });
    }
};
