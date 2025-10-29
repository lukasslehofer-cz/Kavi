<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class TestUnpaidOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:unpaid-order {order_id? : ID objednávky (nepovinné)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nastaví objednávku jako neuhrazenou pro testování';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->argument('order_id');
        
        if ($orderId) {
            $order = Order::find($orderId);
            
            if (!$order) {
                $this->error("Objednávka s ID {$orderId} nebyla nalezena.");
                return 1;
            }
        } else {
            // Najdi nějakou objednávku s paid statusem
            $order = Order::where('payment_status', 'paid')->first();
            
            if (!$order) {
                // Pokud není žádná paid, vezmi jakoukoliv
                $order = Order::first();
            }
            
            if (!$order) {
                $this->error('V databázi nebyla nalezena žádná objednávka.');
                return 1;
            }
        }
        
        $this->info("Vybraná objednávka: {$order->order_number} (ID: {$order->id})");
        $this->info("Aktuální status: {$order->payment_status}");
        
        if (!$this->confirm('Chcete nastavit tuto objednávku jako neuhrazenou?', true)) {
            $this->info('Operace zrušena.');
            return 0;
        }
        
        // Nastav objednávku jako unpaid
        $order->update([
            'payment_status' => 'unpaid',
            'pending_payment_intent_id' => 'pi_test_' . time(),
            'payment_failure_count' => 1,
            'last_payment_failure_at' => now(),
            'last_payment_failure_reason' => 'Test - Nedostatek prostředků na účtu',
        ]);
        
        $this->newLine();
        $this->info('✅ Objednávka byla nastavena jako neuhrazená!');
        $this->newLine();
        
        // Zobraz detaily
        $this->table(
            ['Pole', 'Hodnota'],
            [
                ['ID', $order->id],
                ['Číslo objednávky', $order->order_number],
                ['Status platby', $order->payment_status],
                ['Částka', number_format($order->total, 0, ',', ' ') . ' Kč'],
                ['Počet selhání', $order->payment_failure_count],
                ['Datum selhání', $order->last_payment_failure_at],
                ['Důvod', $order->last_payment_failure_reason],
            ]
        );
        
        $this->newLine();
        $this->comment('Můžete nyní zkontrolovat:');
        $this->line('  - User dashboard: ' . route('dashboard'));
        $this->line('  - Admin panel: ' . route('admin.orders.show', $order));
        
        $this->newLine();
        $this->comment('Pro vrácení stavu zpět použijte:');
        $this->line("  php artisan tinker");
        $this->line("  \$order = Order::find({$order->id});");
        $this->line("  \$order->update(['payment_status' => 'paid', 'payment_failure_count' => 0]);");
        
        return 0;
    }
}
