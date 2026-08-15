<?php

namespace App\Console\Commands;

use App\Models\ShipmentSchedule;
use App\Models\Subscription;
use App\Models\SubscriptionShipment;
use App\Services\StripeService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Dodatečné stržení platby za box, který se odešle DŘÍV, než na něj proběhla platba.
 *
 * Proč to nezvládne subscriptions:charge-payments --subscription=N:
 * markAsShipped() přepne řádek na 'sent' a rovnou založí 'pending' na příští měsíc.
 * Billing cron ale hledá due zásilku výhradně mezi 'pending' řádky bez platby, takže
 * sáhne po příštím měsíci a safety check ho odmítne jako "příliš brzy". Kdyby se spustil
 * po billing_date dalšího měsíce, strhl by peníze a navázal je na ŠPATNÝ box – ledger by
 * se natrvalo posunul o měsíc (přesně to už KVS-2025-021 jednou potkalo,
 * viz subscriptions:reconcile-orphan-links-20260730).
 *
 * Tenhle příkaz jen dohledá správný cílový řádek (i mezi odeslanými) a předá ho
 * StripeService::chargeSubscriptionPayment(). Žádná vlastní billing logika – payment řádek,
 * navázání na zásilku, posun next_billing_date, faktura, affiliate odměna i mail
 * o úspěšné platbě běží stávající cestou.
 *
 * Bezpečné default = náhled; stržení až s --apply.
 */
class ChargeMissedSubscriptionPayment extends Command
{
    protected $signature = 'subscriptions:charge-missed
                            {--subscription= : ID předplatného (povinné)}
                            {--shipment= : ID konkrétní zásilky, když je nezaplacených víc}
                            {--apply : Skutečně strhnout (bez tohoto jen náhled)}';

    protected $description = 'Dodatečně strhne platbu za už odeslaný box, na který billing cron nedosáhl';

    public function handle(StripeService $stripeService): int
    {
        $apply = (bool) $this->option('apply');
        $subscriptionId = $this->option('subscription');

        if (! $subscriptionId) {
            $this->error('✗ Chybí --subscription=ID');

            return 1;
        }

        $this->info($apply ? '⚙️  APPLY' : '🧪 NÁHLED (bez --apply se nic nestrhne)');
        $this->newLine();

        // --- 1. Předplatné + základní guardy -------------------------------

        $subscription = Subscription::with('user')->find($subscriptionId);

        if (! $subscription) {
            $this->error("✗ Předplatné ID {$subscriptionId} neexistuje.");

            return 1;
        }

        $label = $subscription->subscription_number ?? '#'.$subscription->id;

        if (! $subscription->user) {
            $this->error("✗ {$label}: předplatné nemá uživatele.");

            return 1;
        }

        $customerId = $subscription->user->stripe_customer_id;
        if (! $customerId) {
            $this->error("✗ {$label}: uživatel nemá stripe_customer_id.");

            return 1;
        }

        // Ruční doúčtování dává smysl jen na běžícím předplatném. Cancelled/completed
        // /complimentary a admin-lock se strhávat nesmí ani omylem.
        if (! in_array($subscription->status, ['active', 'unpaid'], true)) {
            $this->error("✗ {$label}: status je '{$subscription->status}', povoleno jen 'active' nebo 'unpaid'.");

            return 1;
        }

        if ($subscription->isAdminLocked()) {
            $this->error("✗ {$label}: předplatné je zamčené adminem (pause_reason=admin_lock).");

            return 1;
        }

        if (! $subscription->next_billing_date) {
            $this->error("✗ {$label}: next_billing_date je NULL – nelze určit fakturační období.");

            return 1;
        }

        $this->line("Předplatné:        {$label} (ID {$subscription->id})");
        $this->line("Status:            {$subscription->status}");
        $this->line('next_billing_date: '.$subscription->next_billing_date->toDateString());
        $this->line('Frekvence:         '.($subscription->frequency_months ?? 1).' měs.');
        $this->newLine();

        // --- 2. Cílová zásilka --------------------------------------------

        // Oproti cronu bereme i 'sent'/'delivered' – přesně o ty tu jde.
        // reorder() je nutný: relace shipments() má zabudované orderBy(desc), pouhé
        // ->orderBy('asc') by se jen přidalo za něj a DESC by pořadí přebilo.
        $orphans = $subscription->shipments()
            ->whereNull('subscription_payment_id')
            ->whereIn('status', ['pending', 'sent', 'delivered'])
            ->reorder('shipment_date', 'asc')
            ->get()
            ->filter(fn (SubscriptionShipment $box) => today()->gte($this->billingDateFor($box)))
            ->values();

        if ($orphans->isEmpty()) {
            $this->error("✗ {$label}: žádná nezaplacená zásilka se splatným billing datem – není co doúčtovat.");

            return 1;
        }

        $requestedShipmentId = $this->option('shipment');

        if ($requestedShipmentId) {
            $target = $orphans->firstWhere('id', (int) $requestedShipmentId);

            if (! $target) {
                $this->error("✗ Zásilka #{$requestedShipmentId} není mezi nezaplacenými splatnými zásilkami tohoto předplatného.");
                $this->listOrphans($orphans);

                return 1;
            }
        } elseif ($orphans->count() > 1) {
            // Víc nezaplacených boxů = víc plateb = rozhodnutí pro člověka, ne pro skript.
            $this->error("✗ {$label}: nezaplacených splatných zásilek je {$orphans->count()}. Vyber jednu přes --shipment=ID.");
            $this->listOrphans($orphans);

            return 1;
        } else {
            $target = $orphans->first();
        }

        $targetBillingDate = $this->billingDateFor($target);

        $this->line(sprintf(
            'Cílový box:        #%d  %s  status=%s  odesláno=%s',
            $target->id,
            $target->shipment_date->toDateString(),
            $target->status,
            $target->sent_at?->toDateString() ?? '-'
        ));
        $this->line('Billing datum boxu: '.$targetBillingDate->toDateString());
        $this->newLine();

        // --- 3. Guard na fakturační období --------------------------------

        // Stejná catch-up smyčka jako v StripeService::chargeSubscriptionPayment(),
        // aby náhled ukazoval přesně to období, které se reálně zapíše.
        $frequencyMonths = $subscription->frequency_months ?? 1;
        $periodEnd = $subscription->next_billing_date->copy();
        $safetyLimit = 24;
        while ($periodEnd->lt(today()) && $safetyLimit-- > 0) {
            $periodEnd->addMonths($frequencyMonths);
        }
        $periodStart = $periodEnd->copy()->subMonths($frequencyMonths);

        if (! $periodEnd->isSameDay($targetBillingDate)) {
            $this->error('✗ Období by nesedělo na cílový box – nic se nestrhává.');
            $this->line('   period_end z next_billing_date: '.$periodEnd->toDateString());
            $this->line('   billing datum cílového boxu:    '.$targetBillingDate->toDateString());
            $this->line('   (next_billing_date je posunuté; platba by se zapsala na špatné období)');

            return 1;
        }

        // --- 4. Náhled -----------------------------------------------------

        try {
            $paymentMethodId = $stripeService->getCustomerDefaultPaymentMethod($customerId);
        } catch (\Exception $e) {
            $this->error('✗ Nelze načíst platební metodu ze Stripu: '.$e->getMessage());

            return 1;
        }

        if (! $paymentMethodId) {
            $this->error("✗ {$label}: zákazník nemá u Stripu uloženou platební metodu.");

            return 1;
        }

        $breakdown = \App\Helpers\SubscriptionPricing::forRecurringPayment($subscription);
        $currency = $breakdown->currency;
        $newNextBillingDate = ShipmentSchedule::getBillingDateAfterMonths($periodEnd, $frequencyMonths);
        $idempotencyKey = 'sub_charge_'.$subscription->id.'_'.$subscription->next_billing_date->format('Ymd');

        $this->info('💰 Ke stržení: '.number_format($breakdown->total, 2)." {$currency}");
        $this->line('      Box: '.number_format($breakdown->box, 2)." {$currency}"
            .', Doprava: +'.number_format($breakdown->shipping, 2)." {$currency}"
            .', Sleva: -'.number_format($breakdown->discount, 2)." {$currency}");
        $this->line('💳 Platební metoda:  '.substr($paymentMethodId, 0, 20).'...');
        $this->line('📅 Období platby:    '.$periodStart->toDateString().' → '.$periodEnd->toDateString());
        $this->line('📅 Nové next_billing_date: '.$newNextBillingDate->toDateString());
        $this->line('🔑 Idempotency key:  '.$idempotencyKey);
        $this->newLine();

        if (! $apply) {
            $this->line('   (náhled – nic nestrženo; pro ostrý běh přidej --apply)');

            return 0;
        }

        // --- 5. Ostrý běh ---------------------------------------------------

        // Jeden pokus, bez retry smyčky cronu – idempotency key drží, takže případné
        // opakované spuštění nestrhne podruhé.
        try {
            DB::beginTransaction();

            $result = $stripeService->chargeSubscriptionPayment($subscription, $target);

            if (! ($result['success'] ?? false)) {
                DB::rollBack();

                // ZÁMĚRNÁ odchylka od cronu: nevoláme handleSubscriptionPaymentFailure().
                // Ruční náprava naší vlastní chyby nemá zákazníkovi shodit předplatné
                // na 'unpaid' a poslat mu mail o neúspěšné platbě.
                $this->error('✗ Platba neprošla: '.($result['error'] ?? 'neznámá chyba'));
                $this->line('   (stav předplatného zůstal nezměněn)');

                if ($result['network_error'] ?? false) {
                    $this->warn('   Síťová chyba – spusť znovu, idempotency key ochrání před dvojím stržením.');
                }

                return 1;
            }

            DB::commit();

            $this->info("✅ Strženo – PaymentIntent: {$result['payment_intent_id']}");
        } catch (\Exception $e) {
            DB::rollBack();

            $this->error('✗ Výjimka: '.$e->getMessage());
            $this->line('   (stav předplatného zůstal nezměněn)');

            \Log::error('Manual missed-payment charge failed', [
                'subscription_id' => $subscription->id,
                'shipment_id' => $target->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return 1;
        }

        // --- 6. Výsledek ----------------------------------------------------

        $subscription->refresh();
        $target->refresh();

        $payment = $target->payment;

        $this->newLine();
        $this->line("Box #{$target->id}: platba = ".($target->subscription_payment_id ?? 'NULL')
            .', status = '.$target->status);
        $this->line('Nové next_billing_date: '.($subscription->next_billing_date?->toDateString() ?? 'NULL'));
        $this->line('Faktura: '.($payment?->invoice_number ?? '– zatím nevystavená, zkontroluj log'));
        $this->newLine();
        $this->line('Doporučená kontrola: php artisan subscriptions:check-consistency --details');

        return 0;
    }

    /**
     * Billing datum pro daný box – doslova stejný výraz jako safety check
     * v ChargeSubscriptionPayments, aby se logika nerozešla.
     */
    private function billingDateFor(SubscriptionShipment $shipment): Carbon
    {
        $schedule = ShipmentSchedule::getForMonth(
            $shipment->shipment_date->year,
            $shipment->shipment_date->month
        );

        return $schedule?->billing_date?->copy()
            ?? $shipment->shipment_date->copy()->day(15);
    }

    private function listOrphans(\Illuminate\Support\Collection $orphans): void
    {
        foreach ($orphans as $box) {
            $this->line(sprintf(
                '   box#%-4d %s  %-10s  billing %s',
                $box->id,
                $box->shipment_date->toDateString(),
                $box->status,
                $this->billingDateFor($box)->toDateString()
            ));
        }
    }
}
