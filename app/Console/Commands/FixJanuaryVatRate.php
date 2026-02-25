<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Subscription;
use Illuminate\Console\Command;

class FixJanuaryVatRate extends Command
{
    protected $signature = 'fix:january-vat-rate
                            {--dry-run : Show changes without saving}';

    protected $description = 'One-time fix: correct vat_rate from 21% to 12% for January 2026 orders and subscriptions';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - no changes will be saved');
        }

        $itemsFixed = 0;
        $ordersFixed = 0;
        $subsFixed = 0;

        // === OBJEDNÁVKY ===
        $orders = Order::with('items')
            ->whereBetween('created_at', ['2026-01-01 00:00:00', '2026-01-31 23:59:59'])
            ->get();

        $this->info("\n📦 Found {$orders->count()} January orders\n");

        foreach ($orders as $order) {
            $oldTax = $order->tax;
            $newTax = round($order->total * 12 / 112, 2);

            foreach ($order->items as $item) {
                $this->line("  Order #{$order->id} Item #{$item->id}: vat_rate {$item->vat_rate} → 12.00");
                if (!$dryRun) {
                    $item->update(['vat_rate' => 12.00]);
                }
                $itemsFixed++;
            }

            $this->line("  Order #{$order->id}: tax {$oldTax} → {$newTax}");
            if (!$dryRun) {
                $order->update(['tax' => $newTax]);
            }
            $ordersFixed++;
        }

        // === PŘEDPLATNÁ ===
        $subs = Subscription::where('vat_rate', 21)->get();

        $this->info("\n📋 Found {$subs->count()} subscriptions with vat_rate = 21\n");

        foreach ($subs as $sub) {
            $this->line("  Subscription #{$sub->id} (user #{$sub->user_id}): vat_rate 21 → 12");
            if (!$dryRun) {
                $sub->update(['vat_rate' => 12.00]);
            }
            $subsFixed++;
        }

        // === SOUHRN ===
        $this->newLine();
        $this->info('═══════════════════════════════════════');
        $this->info('📊 Summary' . ($dryRun ? ' (DRY RUN)' : '') . ':');
        $this->info("   Orders processed:     {$ordersFixed}");
        $this->info("   Order items updated:  {$itemsFixed}");
        $this->info("   Subscriptions fixed:  {$subsFixed}");
        $this->info('═══════════════════════════════════════');

        if ($dryRun) {
            $this->warn('No changes were saved. Run without --dry-run to apply.');
        }

        return 0;
    }
}
