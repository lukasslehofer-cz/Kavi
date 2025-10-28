<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('fakturoid_invoice_id')->nullable()->after('stripe_payment_intent_id');
            $table->string('invoice_pdf_path')->nullable()->after('fakturoid_invoice_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['fakturoid_invoice_id', 'invoice_pdf_path']);
        });
    }
};

