<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('affiliate_rewards', function (Blueprint $table) {
            $table->string('reward_tier', 20)
                ->nullable()
                ->after('reward_type')
                ->comment('order | initial | followup');
        });

        // Doplň sazbu u existujících odměn – všechny vznikly ještě před zavedením
        // navazující sazby, takže jsou z definice "initial" (resp. "order").
        DB::table('affiliate_rewards')
            ->where('reward_type', 'order')
            ->update(['reward_tier' => 'order']);

        DB::table('affiliate_rewards')
            ->where('reward_type', 'subscription')
            ->update(['reward_tier' => 'initial']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliate_rewards', function (Blueprint $table) {
            $table->dropColumn('reward_tier');
        });
    }
};
