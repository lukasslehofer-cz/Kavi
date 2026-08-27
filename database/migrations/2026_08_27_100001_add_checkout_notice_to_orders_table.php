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
            // Zmrazený text hlášky z pokladny - email musí ukázat to, co zákazník viděl při objednání
            $table->string('checkout_notice_title')->nullable()->after('customer_notes');
            $table->text('checkout_notice_text')->nullable()->after('checkout_notice_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['checkout_notice_title', 'checkout_notice_text']);
        });
    }
};
