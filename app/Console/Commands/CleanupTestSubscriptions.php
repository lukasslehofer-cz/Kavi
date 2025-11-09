<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionShipment;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupTestSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:cleanup-test
                          {--email= : Smazat předplatné konkrétního uživatele podle emailu}
                          {--id= : Smazat konkrétní předplatné podle ID}
                          {--test : Smazat všechna testovací předplatná (obsahující test, example, demo v emailu)}
                          {--dry-run : Jen zobrazit co bude smazáno bez skutečného mazání}
                          {--force : Přeskočit potvrzení (nebezpečné!)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bezpečně vyčistí testovací předplatná a všechna související data z databáze';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 Čištění testovacích předplatných z databáze');
        $this->newLine();

        // Určit, která předplatná smazat
        $subscriptions = $this->getSubscriptionsToDelete();

        if ($subscriptions->isEmpty()) {
            $this->warn('❌ Nenalezena žádná předplatná k odstranění.');
            return 0;
        }

        // Zobrazit přehled
        $this->displaySummary($subscriptions);

        // Pokud je dry-run, ukončit zde
        if ($this->option('dry-run')) {
            $this->info('🔍 DRY RUN - Žádná data nebyla skutečně smazána.');
            return 0;
        }

        // Vyžádat potvrzení (pokud není --force)
        if (!$this->option('force')) {
            if (!$this->confirm('⚠️  Opravdu chcete smazat tato data? Tato akce je nevratná!', false)) {
                $this->info('❌ Mazání zrušeno.');
                return 0;
            }

            // Druhé potvrzení pro jistotu
            if (!$this->confirm('🚨 POSLEDNÍ KONTROLA: Jste si 100% jisti?', false)) {
                $this->info('❌ Mazání zrušeno.');
                return 0;
            }
        }

        // Provést mazání
        $this->deleteSubscriptions($subscriptions);

        $this->newLine();
        $this->info('✅ Čištění dokončeno!');
        
        return 0;
    }

    /**
     * Získat předplatná k odstranění podle kritérií
     */
    private function getSubscriptionsToDelete()
    {
        $query = Subscription::with(['user', 'plan', 'orders', 'payments', 'shipments']);

        if ($email = $this->option('email')) {
            // Smazat podle konkrétního emailu
            return $query->whereHas('user', function ($q) use ($email) {
                $q->where('email', $email);
            })->get();
        }

        if ($id = $this->option('id')) {
            // Smazat podle ID předplatného
            return $query->where('id', $id)->get();
        }

        if ($this->option('test')) {
            // Smazat všechna testovací předplatná
            return $query->whereHas('user', function ($q) {
                $q->where('email', 'like', '%test%')
                  ->orWhere('email', 'like', '%example%')
                  ->orWhere('email', 'like', '%demo%')
                  ->orWhere('email', 'like', '%+test@%');
            })->get();
        }

        $this->error('❌ Musíte zadat alespoň jeden parametr: --email, --id nebo --test');
        exit(1);
    }

    /**
     * Zobrazit přehled co bude smazáno
     */
    private function displaySummary($subscriptions)
    {
        $this->info('📊 PŘEHLED DAT K ODSTRANĚNÍ:');
        $this->newLine();

        $totalPayments = 0;
        $totalShipments = 0;
        $totalOrders = 0;

        $table = [];

        foreach ($subscriptions as $subscription) {
            $payments = $subscription->payments->count();
            $shipments = $subscription->shipments->count();
            $orders = $subscription->orders->count();

            $totalPayments += $payments;
            $totalShipments += $shipments;
            $totalOrders += $orders;

            $table[] = [
                'ID' => $subscription->id,
                'Číslo' => $subscription->subscription_number ?: 'N/A',
                'Uživatel' => $subscription->user->email,
                'Plán' => $subscription->plan->name ?? 'N/A',
                'Status' => $subscription->status,
                'Platby' => $payments,
                'Zásilky' => $shipments,
                'Objednávky' => $orders,
            ];
        }

        $this->table(
            ['ID', 'Číslo', 'Uživatel', 'Plán', 'Status', 'Platby', 'Zásilky', 'Objednávky'],
            $table
        );

        $this->newLine();
        $this->warn("📈 CELKOVÝ SOUHRN:");
        $this->line("   Předplatných: " . $subscriptions->count());
        $this->line("   Plateb: $totalPayments");
        $this->line("   Zásilek: $totalShipments");
        $this->line("   Objednávek: $totalOrders");
        $this->newLine();
    }

    /**
     * Odstranit předplatná a všechna související data
     */
    private function deleteSubscriptions($subscriptions)
    {
        $this->info('🗑️  Začínám mazání...');
        $this->newLine();

        $progressBar = $this->output->createProgressBar($subscriptions->count());
        $progressBar->start();

        DB::beginTransaction();

        try {
            foreach ($subscriptions as $subscription) {
                $this->deleteSubscriptionData($subscription);
                $progressBar->advance();
            }

            DB::commit();
            $progressBar->finish();
            $this->newLine();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->newLine();
            $this->error('❌ Chyba při mazání: ' . $e->getMessage());
            $this->error('   Všechny změny byly vráceny zpět (rollback).');
            exit(1);
        }
    }

    /**
     * Odstranit data konkrétního předplatného
     */
    private function deleteSubscriptionData(Subscription $subscription)
    {
        // 1. Smazat položky objednávek
        foreach ($subscription->orders as $order) {
            $order->items()->delete();
        }

        // 2. Smazat objednávky
        $subscription->orders()->delete();

        // 3. Smazat platby
        $subscription->payments()->delete();

        // 4. Smazat zásilky
        $subscription->shipments()->delete();

        // 5. Smazat předplatné samotné
        $subscription->delete();
    }
}

