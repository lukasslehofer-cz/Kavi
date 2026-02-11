<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Nastavit 12% DPH pro kávové produkty (espresso, filter, decaf)
        Product::where(function($query) {
            $query->whereJsonContains('category', 'espresso')
                  ->orWhereJsonContains('category', 'filter')
                  ->orWhereJsonContains('category', 'decaf');
        })->update(['vat_rate' => 12.00]);

        // Accessories a ostatní zůstávají na 21% (výchozí hodnota)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Vrátit všechny produkty zpět na 21%
        Product::query()->update(['vat_rate' => 21.00]);
    }
};
