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
        // IP a prohlížeč zákazníka z pokladny pro Meta CAPI. Purchase často odchází
        // z webhooku Stripe, kde request() patří Stripe, ne zákazníkovi. Po odeslání
        // události se hodnoty mažou (MetaConversionsService).
        Schema::table('orders', function (Blueprint $table) {
            $table->string('meta_client_ip', 45)->nullable()->after('meta_fbc');
            $table->string('meta_client_user_agent', 500)->nullable()->after('meta_client_ip');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('meta_client_ip', 45)->nullable()->after('meta_fbc');
            $table->string('meta_client_user_agent', 500)->nullable()->after('meta_client_ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['meta_client_ip', 'meta_client_user_agent']);
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['meta_client_ip', 'meta_client_user_agent']);
        });
    }
};
