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
        Schema::table('coupons', function (Blueprint $table) {
            // Navazující (dlouhodobá) odměna za rozesílky nad rámec affiliate_reward_subscription_months.
            // NULL = po vyčerpání prvních N rozesílek se už neplatí nic (dosavadní chování).
            $table->decimal('affiliate_reward_subscription_followup_value', 10, 2)
                ->nullable()
                ->after('affiliate_reward_subscription_months')
                ->comment('Odměna za každou další rozesílku po vyčerpání prvních N');
            $table->decimal('affiliate_reward_subscription_followup_value_eur', 10, 2)
                ->nullable()
                ->after('affiliate_reward_subscription_followup_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn([
                'affiliate_reward_subscription_followup_value',
                'affiliate_reward_subscription_followup_value_eur',
            ]);
        });
    }
};
