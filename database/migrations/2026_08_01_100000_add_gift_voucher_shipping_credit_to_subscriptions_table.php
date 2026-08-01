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
        Schema::table('subscriptions', function (Blueprint $table) {
            // Část dárkového voucheru, která pokryla dopravu u první platby.
            // Doteď se jen logovala, takže se z uloženého předplatného nedal
            // zrekonstruovat rozpad první platby a faktura nesouhlasila se Stripem.
            // NULL = starší řádek, u kterého kredit neznáme.
            $table->decimal('gift_voucher_shipping_credit', 10, 2)
                ->nullable()
                ->after('shipping_cost')
                ->comment('Část dárkového voucheru uplatněná na dopravu u první platby');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('gift_voucher_shipping_credit');
        });
    }
};
