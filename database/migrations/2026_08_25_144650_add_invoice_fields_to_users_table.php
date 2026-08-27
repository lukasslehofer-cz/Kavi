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
        Schema::table('users', function (Blueprint $table) {
            // Vlastní fakturační údaje zákazníka pro Fakturoid. Prefix "invoice_",
            // aby se pole nepletla s JSON sloupcem orders.billing_address a s klíči
            // billing_* uvnitř shipping_address. Názvy street/city/zip/country
            // odpovídají polím subjektu ve Fakturoid API.
            $table->boolean('invoice_override')->default(false)->after('country');
            $table->string('invoice_company')->nullable()->after('invoice_override');
            $table->string('invoice_registration_no', 20)->nullable()->after('invoice_company');
            $table->string('invoice_vat_no', 30)->nullable()->after('invoice_registration_no');
            $table->string('invoice_name')->nullable()->after('invoice_vat_no');
            $table->string('invoice_street')->nullable()->after('invoice_name');
            $table->string('invoice_city', 100)->nullable()->after('invoice_street');
            $table->string('invoice_zip', 20)->nullable()->after('invoice_city');
            $table->string('invoice_country', 2)->nullable()->after('invoice_zip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_override',
                'invoice_company',
                'invoice_registration_no',
                'invoice_vat_no',
                'invoice_name',
                'invoice_street',
                'invoice_city',
                'invoice_zip',
                'invoice_country',
            ]);
        });
    }
};
