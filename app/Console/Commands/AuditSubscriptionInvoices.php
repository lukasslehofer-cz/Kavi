<?php

namespace App\Console\Commands;

use App\Models\SubscriptionPayment;
use App\Services\FakturoidService;
use Illuminate\Console\Command;

class AuditSubscriptionInvoices extends Command
{
    protected $signature = 'invoices:audit-subscriptions
                            {--since= : Jen platby uhrazené od tohoto data (Y-m-d)}
                            {--currency= : Filtr na měnu (CZK / EUR)}
                            {--payment= : Jen konkrétní SubscriptionPayment ID}';

    protected $description = 'Porovná vystavené faktury ve Fakturoidu se skutečně stržnými částkami (read-only)';

    public function handle(FakturoidService $fakturoid): int
    {
        $query = SubscriptionPayment::with('subscription')
            ->whereNotNull('fakturoid_invoice_id')
            ->orderBy('id');

        if ($since = $this->option('since')) {
            $query->whereDate('paid_at', '>=', $since);
        }

        if ($currency = $this->option('currency')) {
            $query->whereRaw('UPPER(currency) = ?', [strtoupper($currency)]);
        }

        if ($paymentId = $this->option('payment')) {
            $query->where('id', $paymentId);
        }

        $payments = $query->get();

        if ($payments->isEmpty()) {
            $this->info('Žádné platby s fakturou neodpovídají zadání.');

            return self::SUCCESS;
        }

        $this->info('Kontroluji '.$payments->count().' faktur ve Fakturoidu…');

        $rows = [];
        $mismatched = 0;
        $unreadable = 0;

        foreach ($payments as $payment) {
            $invoice = $fakturoid->getInvoice((int) $payment->fakturoid_invoice_id);

            if (! $invoice) {
                $unreadable++;
                $rows[] = [
                    $payment->id,
                    $payment->subscription?->subscription_number ?? '–',
                    $payment->fakturoid_invoice_id,
                    '?',
                    '?',
                    number_format((float) $payment->amount, 2),
                    '?',
                    'nenačteno',
                ];

                continue;
            }

            $invoiceTotal = (float) ($invoice['total'] ?? 0);
            $paidAmount = (float) ($invoice['paid_amount'] ?? 0);
            $charged = (float) $payment->amount;
            $delta = round($invoiceTotal - $charged, 2);

            if (abs($delta) > 0.01) {
                $mismatched++;
            }

            $rows[] = [
                $payment->id,
                $payment->subscription?->subscription_number ?? '–',
                $invoice['number'] ?? $payment->fakturoid_invoice_id,
                number_format($invoiceTotal, 2),
                number_format($paidAmount, 2),
                number_format($charged, 2),
                number_format($delta, 2),
                $invoice['status'] ?? '?',
            ];
        }

        $this->newLine();
        $this->table(
            ['Platba', 'Předplatné', 'Faktura', 'Celkem', 'Uhrazeno', 'Strženo', 'Rozdíl', 'Stav'],
            $rows
        );

        if ($unreadable > 0) {
            $this->warn('Nepodařilo se načíst faktur: '.$unreadable);
        }

        if ($mismatched > 0) {
            $this->error('Faktur nesouhlasících se stržnou částkou: '.$mismatched
                .' – kladný rozdíl znamená, že je faktura vyšší než platba (řeší se dobropisem).');

            return self::FAILURE;
        }

        $this->info('Všechny zkontrolované faktury sedí se stržnými částkami.');

        return self::SUCCESS;
    }
}
