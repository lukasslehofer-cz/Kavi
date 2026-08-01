<?php

namespace App\Console\Commands;

use App\Helpers\SubscriptionPricing;
use App\Models\SubscriptionPayment;
use App\Services\FakturoidService;
use Illuminate\Console\Command;

class PreviewSubscriptionInvoice extends Command
{
    protected $signature = 'invoices:preview-subscription
                            {payment : SubscriptionPayment ID}
                            {--subject=0 : Fiktivní subject_id, do Fakturoidu se nic neposílá}';

    protected $description = 'Vypíše payload faktury k platbě předplatného, aniž by se cokoliv odeslalo do Fakturoidu';

    public function handle(FakturoidService $fakturoid): int
    {
        $payment = SubscriptionPayment::with('subscription')->find($this->argument('payment'));

        if (! $payment) {
            $this->error('Platba #'.$this->argument('payment').' neexistuje.');

            return self::FAILURE;
        }

        if (! $payment->subscription) {
            $this->error('Platba #'.$payment->id.' nemá navázané předplatné.');

            return self::FAILURE;
        }

        if (! $payment->paid_at) {
            $this->error('Platba #'.$payment->id.' nemá paid_at, faktura by se nesestavila.');

            return self::FAILURE;
        }

        $subscription = $payment->subscription;
        $breakdown = SubscriptionPricing::forPayment($payment);

        $this->info('Předplatné: #'.$subscription->id.' '.$subscription->subscription_number
            .' ('.$subscription->currency.', frequency_months='.$subscription->frequency_months.')');
        $this->line('První platba: '.(SubscriptionPricing::isFirstPayment($payment) ? 'ano' : 'ne'));

        $this->newLine();
        $this->table(
            ['Složka', 'Částka'],
            [
                ['Box (brutto)', number_format($breakdown->box, 2)],
                ['Doprava', number_format($breakdown->shipping, 2)],
                ['Sleva', '-'.number_format($breakdown->discount, 2)],
                ['Voucher na dopravu', '-'.number_format($breakdown->giftVoucherShippingCredit, 2)],
                ['Rozpad celkem', number_format($breakdown->total, 2)],
                ['Skutečně strženo', number_format((float) $payment->amount, 2)],
                ['Rozdíl', number_format($breakdown->deltaAgainst((float) $payment->amount), 2)],
            ]
        );

        if (! $breakdown->reconcilesWith((float) $payment->amount)) {
            $this->warn('Rozpad nesouhlasí se stržnou částkou – payload níže spadne do fallbacku na jednu položku.');
        }

        $payload = $fakturoid->buildSubscriptionInvoicePayload($payment, (int) $this->option('subject'));

        $this->newLine();
        $this->line(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        return self::SUCCESS;
    }
}
