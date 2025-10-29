<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;

class TestUnpaidSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:unpaid {subscription_id? : ID předplatného (nepovinné)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nastaví předplatné jako neuhrazené pro testování';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Hledání předplatného...');
        
        $subscriptionId = $this->argument('subscription_id');
        
        if ($subscriptionId) {
            $subscription = Subscription::find($subscriptionId);
            if (!$subscription) {
                $this->error("❌ Předplatné #{$subscriptionId} nenalezeno!");
                return 1;
            }
        } else {
            // Najít první aktivní předplatné
            $subscription = Subscription::where('status', 'active')->first();
            
            if (!$subscription) {
                $this->error('❌ Žádné aktivní předplatné nenalezeno!');
                $this->warn('💡 Vytvořte nejdřív předplatné nebo zadejte konkrétní ID:');
                $this->line('   php artisan test:unpaid {ID}');
                return 1;
            }
        }
        
        $this->line('');
        $this->info("✅ Nalezeno předplatné #{$subscription->id}");
        $this->line("   👤 Uživatel: " . ($subscription->user ? $subscription->user->name : 'Host'));
        $this->line("   💰 Cena: " . number_format($subscription->configured_price ?? 0, 0, ',', ' ') . " Kč");
        $this->line("   📊 Aktuální status: " . $subscription->status);
        $this->line('');
        
        if (!$this->confirm('Nastavit toto předplatné jako neuhrazené?', true)) {
            $this->info('Zrušeno.');
            return 0;
        }
        
        $this->info('⚠️  Nastavuji status na "unpaid"...');
        
        $subscription->update([
            'status' => 'unpaid',
            'pending_invoice_id' => 'in_test_' . time(),
            'pending_invoice_amount' => $subscription->configured_price ?? 500.00,
            'payment_failure_count' => 1,
            'last_payment_failure_at' => now(),
            'last_payment_failure_reason' => 'Test - Nedostatek prostředků na účtu. Karta byla zamítnuta.'
        ]);
        
        $subscription->fresh();
        
        $this->line('');
        $this->info('✅ Hotovo!');
        $this->line('');
        
        $this->table(
            ['Vlastnost', 'Hodnota'],
            [
                ['Status', $subscription->status],
                ['Pending Invoice ID', $subscription->pending_invoice_id],
                ['Částka k úhradě', number_format($subscription->pending_invoice_amount, 0, ',', ' ') . ' Kč'],
                ['Počet pokusů', $subscription->payment_failure_count],
                ['Poslední pokus', $subscription->last_payment_failure_at?->format('d.m.Y H:i')],
                ['Důvod selhání', $subscription->last_payment_failure_reason],
            ]
        );
        
        $this->line('');
        $this->info('🌐 Zobrazit v dashboardu:');
        $this->line('   User:  ' . url('/dashboard/predplatne'));
        $this->line('   Admin: ' . url('/admin/subscriptions/' . $subscription->id));
        $this->line('');
        $this->info('💳 Pro test platby použijte button "Zaplatit nyní" v user dashboardu.');
        $this->line('');
        $this->comment('🔄 Pro obnovení zpět na active:');
        $this->line('   php artisan tinker');
        $this->line("   Subscription::find({$subscription->id})->update(['status' => 'active', 'payment_failure_count' => 0]);");
        
        return 0;
    }
}
