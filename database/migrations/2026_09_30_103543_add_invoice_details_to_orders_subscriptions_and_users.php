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
        // Firemní údaje, které zákazník zadal v pokladně ("Nakupuji na firmu"),
        // a na uživateli jeho uložené firemní údaje z profilu.
        // Tvar viz App\Helpers\InvoiceDetails. Záměrně bez ->after(), aby MySQL
        // mohl sloupec přidat jako INSTANT i na velké tabulce objednávek.
        Schema::table('orders', function (Blueprint $table) {
            $table->json('invoice_details')->nullable();
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->json('invoice_details')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->json('invoice_details')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('invoice_details');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('invoice_details');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('invoice_details');
        });
    }
};
