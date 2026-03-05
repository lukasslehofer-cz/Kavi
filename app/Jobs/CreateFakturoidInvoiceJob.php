<?php

namespace App\Jobs;

use App\Models\SubscriptionPayment;
use App\Services\FakturoidService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateFakturoidInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 300; // 5 minutes between retries

    public function __construct(private readonly int $paymentId)
    {
    }

    public function handle(FakturoidService $fakturoidService): void
    {
        $payment = SubscriptionPayment::find($this->paymentId);

        if (!$payment) {
            Log::warning('CreateFakturoidInvoiceJob: payment not found', ['payment_id' => $this->paymentId]);
            return;
        }

        // Invoice exists but PDF missing - just download PDF
        if ($payment->fakturoid_invoice_id && !$payment->invoice_pdf_path) {
            $pdfPath = $fakturoidService->downloadSubscriptionInvoicePdf(
                $payment->fakturoid_invoice_id, $payment
            );
            if ($pdfPath) {
                $payment->update(['invoice_pdf_path' => $pdfPath]);
                Log::info('CreateFakturoidInvoiceJob: PDF downloaded for existing invoice', [
                    'payment_id' => $this->paymentId,
                    'invoice_id' => $payment->fakturoid_invoice_id,
                ]);
                return;
            }
            throw new \RuntimeException('CreateFakturoidInvoiceJob: failed to download PDF for existing invoice ' . $payment->fakturoid_invoice_id);
        }

        // Both exist - nothing to do
        if ($payment->fakturoid_invoice_id && $payment->invoice_pdf_path) {
            Log::info('CreateFakturoidInvoiceJob: invoice and PDF already exist, skipping', [
                'payment_id' => $this->paymentId,
            ]);
            return;
        }

        // Nothing exists - create invoice from scratch
        $result = $fakturoidService->processInvoiceForSubscriptionPayment($payment);

        if (!$result) {
            throw new \RuntimeException('CreateFakturoidInvoiceJob: invoice creation failed for payment ' . $this->paymentId);
        }

        Log::info('CreateFakturoidInvoiceJob: invoice created successfully', [
            'payment_id' => $this->paymentId,
        ]);
    }
}
