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
            // Affiliate partner relationship
            $table->foreignId('affiliate_partner_id')->nullable()->after('is_active')->constrained('users')->onDelete('set null');
            $table->boolean('affiliate_code_enabled')->default(false)->after('affiliate_partner_id');
            
            // Affiliate rewards for orders (jednorázový nákup)
            $table->enum('affiliate_reward_order_type', ['percentage', 'fixed', 'none'])->default('none')->after('affiliate_code_enabled');
            $table->decimal('affiliate_reward_order_value', 10, 2)->nullable()->after('affiliate_reward_order_type');
            $table->decimal('affiliate_reward_order_value_eur', 10, 2)->nullable()->after('affiliate_reward_order_value');
            $table->decimal('affiliate_reward_order_min_value', 10, 2)->nullable()->after('affiliate_reward_order_value_eur');
            $table->decimal('affiliate_reward_order_min_value_eur', 10, 2)->nullable()->after('affiliate_reward_order_min_value');
            
            // Affiliate rewards for subscriptions (předplatné)
            $table->decimal('affiliate_reward_subscription_value', 10, 2)->nullable()->after('affiliate_reward_order_min_value_eur');
            $table->decimal('affiliate_reward_subscription_value_eur', 10, 2)->nullable()->after('affiliate_reward_subscription_value');
            $table->integer('affiliate_reward_subscription_months')->nullable()->after('affiliate_reward_subscription_value_eur')->comment('Počet opakování, za které se platí odměna');
            
            // Add EUR variants for existing discount fields if they don't exist
            if (!Schema::hasColumn('coupons', 'discount_value_order_eur')) {
                $table->decimal('discount_value_order_eur', 10, 2)->nullable()->after('discount_value_order');
            }
            if (!Schema::hasColumn('coupons', 'discount_value_subscription_eur')) {
                $table->decimal('discount_value_subscription_eur', 10, 2)->nullable()->after('discount_value_subscription');
            }
            if (!Schema::hasColumn('coupons', 'min_order_value_eur')) {
                $table->decimal('min_order_value_eur', 10, 2)->nullable()->after('min_order_value');
            }
            
            // Indexes
            $table->index('affiliate_partner_id');
            $table->index('affiliate_code_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropForeign(['affiliate_partner_id']);
            $table->dropIndex(['affiliate_partner_id']);
            $table->dropIndex(['affiliate_code_enabled']);
            
            $table->dropColumn([
                'affiliate_partner_id',
                'affiliate_code_enabled',
                'affiliate_reward_order_type',
                'affiliate_reward_order_value',
                'affiliate_reward_order_value_eur',
                'affiliate_reward_order_min_value',
                'affiliate_reward_order_min_value_eur',
                'affiliate_reward_subscription_value',
                'affiliate_reward_subscription_value_eur',
                'affiliate_reward_subscription_months',
            ]);
            
            // Note: We don't drop the EUR variants as they might have been added by this migration
            // but could also have existed before. To be safe, we leave them.
        });
    }
};
