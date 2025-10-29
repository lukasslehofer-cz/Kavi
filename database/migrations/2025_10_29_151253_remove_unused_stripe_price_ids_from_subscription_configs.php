<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Remove old Stripe Price IDs that are no longer used.
     * System now uses dynamic pricing with a single base product ID.
     */
    public function up(): void
    {
        // Remove old Stripe Price IDs - no longer used with dynamic pricing
        DB::table('subscription_configs')->whereIn('key', [
            'stripe_price_id_2_bags',
            'stripe_price_id_2_bags_2months',
            'stripe_price_id_2_bags_3months',
            'stripe_price_id_3_bags',
            'stripe_price_id_3_bags_2months',
            'stripe_price_id_3_bags_3months',
            'stripe_price_id_4_bags',
            'stripe_price_id_4_bags_2months',
            'stripe_price_id_4_bags_3months',
        ])->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to restore these - they were auto-generated and would be invalid anyway
    }
};
