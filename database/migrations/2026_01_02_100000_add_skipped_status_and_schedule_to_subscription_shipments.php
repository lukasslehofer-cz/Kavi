<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite doesn't support modifying enum columns, so we need to recreate the column
        // For MySQL, we would use: ALTER TABLE ... MODIFY COLUMN ...
        
        // Step 1: Add new status column with expanded enum
        Schema::table('subscription_shipments', function (Blueprint $table) {
            $table->string('status_new')->default('pending')->after('status');
        });
        
        // Step 2: Copy data
        DB::statement('UPDATE subscription_shipments SET status_new = status');
        
        // Step 3: Drop old column and rename new
        Schema::table('subscription_shipments', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('subscription_shipments', function (Blueprint $table) {
            $table->renameColumn('status_new', 'status');
        });
        
        // Step 4: Add index back
        Schema::table('subscription_shipments', function (Blueprint $table) {
            $table->index('status');
        });
        
        // Step 5: Add shipment_schedule_id foreign key
        Schema::table('subscription_shipments', function (Blueprint $table) {
            $table->foreignId('shipment_schedule_id')
                ->nullable()
                ->after('subscription_id')
                ->constrained('shipment_schedules')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_shipments', function (Blueprint $table) {
            $table->dropForeign(['shipment_schedule_id']);
            $table->dropColumn('shipment_schedule_id');
        });
        
        // Revert status column to original enum (would lose 'skipped' entries)
        Schema::table('subscription_shipments', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
        
        Schema::table('subscription_shipments', function (Blueprint $table) {
            $table->string('status_old')->default('pending')->after('status');
        });
        
        // Convert 'skipped' back to 'cancelled'
        DB::statement("UPDATE subscription_shipments SET status_old = CASE WHEN status = 'skipped' THEN 'cancelled' ELSE status END");
        
        Schema::table('subscription_shipments', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('subscription_shipments', function (Blueprint $table) {
            $table->renameColumn('status_old', 'status');
            $table->index('status');
        });
    }
};

