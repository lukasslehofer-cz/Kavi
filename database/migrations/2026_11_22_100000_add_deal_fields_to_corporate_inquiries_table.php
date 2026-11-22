<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Detaily dealu u poptávek firemních dárků (admin › Dárky › Firemní dárky).
 *
 * Ceny jsou v Kč bez DPH. status_history drží změny stavu [{from, to, at, by}].
 * Stavy: new → communication → offer_sent → realization → done | lost.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('corporate_inquiries', function (Blueprint $table) {
            $table->unsignedInteger('box_count')->nullable()->after('status');
            $table->text('box_contents')->nullable()->after('box_count');
            $table->decimal('unit_price', 10, 2)->nullable()->after('box_contents');
            $table->decimal('total_price', 10, 2)->nullable()->after('unit_price');
            $table->date('agreed_delivery_date')->nullable()->after('total_price');
            $table->text('delivery_address')->nullable()->after('agreed_delivery_date');
            $table->string('billing_ico', 20)->nullable()->after('delivery_address');
            $table->string('billing_dic', 20)->nullable()->after('billing_ico');
            $table->text('billing_address')->nullable()->after('billing_dic');
            $table->string('invoice_number', 50)->nullable()->after('billing_address');
            $table->date('paid_at')->nullable()->after('invoice_number');
            $table->text('internal_note')->nullable()->after('paid_at');
            $table->json('status_history')->nullable()->after('internal_note');
            $table->timestamp('status_changed_at')->nullable()->after('status_history');
        });

        // Stav "won" z první verze nic nezapisovalo, ale kdyby přece
        DB::table('corporate_inquiries')->where('status', 'won')->update(['status' => 'realization']);
    }

    public function down(): void
    {
        Schema::table('corporate_inquiries', function (Blueprint $table) {
            $table->dropColumn([
                'box_count',
                'box_contents',
                'unit_price',
                'total_price',
                'agreed_delivery_date',
                'delivery_address',
                'billing_ico',
                'billing_dic',
                'billing_address',
                'invoice_number',
                'paid_at',
                'internal_note',
                'status_history',
                'status_changed_at',
            ]);
        });
    }
};
