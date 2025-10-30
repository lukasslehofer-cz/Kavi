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
        Schema::table('shipment_schedules', function (Blueprint $table) {
            // Coffee slots for monthly allocation
            // E1, E2, E3 = Espresso slots 1, 2, 3
            // F1, F2, F3 = Filter slots 1, 2, 3
            // D = Decaf slot (can be used for both espresso and filter)
            $table->foreignId('coffee_slot_e1')->nullable()->after('promo_image')->constrained('products')->nullOnDelete();
            $table->foreignId('coffee_slot_e2')->nullable()->after('coffee_slot_e1')->constrained('products')->nullOnDelete();
            $table->foreignId('coffee_slot_e3')->nullable()->after('coffee_slot_e2')->constrained('products')->nullOnDelete();
            $table->foreignId('coffee_slot_f1')->nullable()->after('coffee_slot_e3')->constrained('products')->nullOnDelete();
            $table->foreignId('coffee_slot_f2')->nullable()->after('coffee_slot_f1')->constrained('products')->nullOnDelete();
            $table->foreignId('coffee_slot_f3')->nullable()->after('coffee_slot_f2')->constrained('products')->nullOnDelete();
            $table->foreignId('coffee_slot_d')->nullable()->after('coffee_slot_f3')->constrained('products')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipment_schedules', function (Blueprint $table) {
            $table->dropForeign(['coffee_slot_e1']);
            $table->dropForeign(['coffee_slot_e2']);
            $table->dropForeign(['coffee_slot_e3']);
            $table->dropForeign(['coffee_slot_f1']);
            $table->dropForeign(['coffee_slot_f2']);
            $table->dropForeign(['coffee_slot_f3']);
            $table->dropForeign(['coffee_slot_d']);
            
            $table->dropColumn([
                'coffee_slot_e1',
                'coffee_slot_e2',
                'coffee_slot_e3',
                'coffee_slot_f1',
                'coffee_slot_f2',
                'coffee_slot_f3',
                'coffee_slot_d',
            ]);
        });
    }
};

