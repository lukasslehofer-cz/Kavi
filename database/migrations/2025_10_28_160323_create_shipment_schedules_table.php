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
        Schema::create('shipment_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('month'); // 1-12
            $table->date('billing_date'); // Den kdy se strhne platba
            $table->date('shipment_date'); // Den kdy se posílá rozesílka
            $table->foreignId('coffee_product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('roastery_name')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Unique constraint - jeden záznam na měsíc
            $table->unique(['year', 'month']);
            
            // Index pro rychlé vyhledávání
            $table->index(['year', 'month']);
        });

        // Automaticky vytvoříme záznamy pro aktuální a příští rok
        $this->seedInitialSchedules();
    }

    /**
     * Seed initial schedules for current and next year
     */
    private function seedInitialSchedules(): void
    {
        $currentYear = now()->year;
        $years = [$currentYear, $currentYear + 1];

        foreach ($years as $year) {
            for ($month = 1; $month <= 12; $month++) {
                // Standardní datumy: billing 15., rozesílka 20.
                $billingDate = \Carbon\Carbon::create($year, $month, 15);
                $shipmentDate = \Carbon\Carbon::create($year, $month, 20);

                DB::table('shipment_schedules')->insert([
                    'year' => $year,
                    'month' => $month,
                    'billing_date' => $billingDate,
                    'shipment_date' => $shipmentDate,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_schedules');
    }
};
