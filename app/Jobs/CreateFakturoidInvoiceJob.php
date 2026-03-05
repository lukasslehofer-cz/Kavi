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

        if ($payment->fakturoid_invoice_id) {
            Log::info('CreateFakturoidInvoiceJob: invoice already exists, skipping', [
                'payment_id' => $this->paymentId,
                'invoice_id' => $payment->fakturoid_invoice_id,
            ]);
            return;
        }

        $result = $fakturoidService->processInvoiceForSubscriptionPayment($payment);

        if (!$result) {
            Log::error('CreateFakturoidInvoiceJob: invoice creation failed', [
                'payment_id' => $this->paymentId,
                'attempt' => $this->attempts(),
            ]);
            $this->fail('Fakturoid invoice creation returned false');
        } else {
            Log::info('CreateFakturoidInvoiceJob: invoice created successfully', [
                'payment_id' => $this->paymentId,
            ]);
        }
    }
}
