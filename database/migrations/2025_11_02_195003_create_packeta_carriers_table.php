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
        Schema::create('packeta_carriers', function (Blueprint $table) {
            $table->id();
            $table->string('carrier_id')->comment('Packeta carrier ID (e.g. 131, zpoint, 7987)');
            $table->string('name')->comment('Carrier display name');
            $table->string('country_code', 2)->comment('ISO 3166-1 alpha-2 country code');
            $table->boolean('is_active')->default(true)->comment('Is carrier available for selection');
            $table->integer('sort_order')->default(0)->comment('Manual sort order (0 = alphabetical)');
            $table->timestamps();
            
            // Indexes
            $table->index('country_code');
            $table->unique(['carrier_id', 'country_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packeta_carriers');
    }
};
