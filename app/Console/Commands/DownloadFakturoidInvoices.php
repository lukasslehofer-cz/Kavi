<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\SubscriptionPayment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DownloadFakturoidInvoices extends Command
{
    protected $signature = 'fakturoid:download-invoices
                            {--orders : Download only order invoices}
                            {--subscriptions : Download only subscription payment invoices}
                            {--force : Re-download even if PDF already exists}
                            {--dry-run : Show what would be downloaded without actually downloading}
                            {--from= : Filter records created from this date (Y-m-d, e.g. 2026-01-01)}
                            {--to= : Filter records created up to this date (Y-m-d, e.g. 2026-01-31)}';

    protected $description = 'Download all invoice PDFs from Fakturoid for orders and subscription payments';

    private string $apiUrl;
    private string $slug;
    private string $clientId;
    private string $clientSecret;
    private string $userAgent;
    private ?string $accessToken = null;

    public function handle(): int
    {
        $this->apiUrl = 'https://app.fakturoid.cz/api/v3';
        $this->slug = config('services.fakturoid.slug');
        $this->clientId = config('services.fakturoid.client_id');
        $this->clientSecret = config('services.fakturoid.client_secret');
        $this->userAgent = config('services.fakturoid.user_agent', 'Kavi (info@kavi.cz)');

        if (!$this->slug || !$this->clientId || !$this->clientSecret) {
            $this->error('Fakturoid credentials are not configured. Check your .env file.');
            return 1;
        }

        $onlyOrders = $this->option('orders');
        $onlySubscriptions = $this->option('subscriptions');
        $force = $this->option('force');
        $dryRun = $this->option('dry-run');
        $from = $this->option('from');
        $to = $this->option('to');

        if ($from && !strtotime($from)) {
            $this->error('Invalid --from date format. Use Y-m-d (e.g. 2026-01-01)');
            return 1;
        }
        if ($to && !strtotime($to)) {
            $this->error('Invalid --to date format. Use Y-m-d (e.g. 2026-01-31)');
            return 1;
        }

        if ($from || $to) {
            $this->info('📅 Date filter: ' . ($from ?? '*') . ' → ' . ($to ?? '*'));
        }

        // If neither specified, do both
        $doOrders = !$onlySubscriptions || $onlyOrders;
        $doSubscriptions = !$onlyOrders || $onlySubscriptions;

        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - no files will be downloaded');
        }

        $totalDownloaded = 0;
        $totalSkipped = 0;
        $totalFailed = 0;

        // Download order invoices
        if ($doOrders) {
            $this->info("\n📦 Processing Order Invoices...\n");
            
            $orders = Order::whereNotNull('fakturoid_invoice_id')
                ->when($from, fn($q) => $q->where('created_at', '>=', $from . ' 00:00:00'))
                ->when($to,   fn($q) => $q->where('created_at', '<=', $to   . ' 23:59:59'))
                ->when(!$force, fn($q) => $q->where(function($q) {
                    $q->whereNull('invoice_pdf_path')
                      ->orWhere('invoice_pdf_path', '');
                }))
                ->get();

            $this->info("Found {$orders->count()} orders with Fakturoid invoice ID" . (!$force ? ' (missing PDF)' : ''));

            $bar = $this->output->createProgressBar($orders->count());
            $bar->start();

            foreach ($orders as $order) {
                $result = $this->downloadOrderInvoice($order, $dryRun, $force);
                
                if ($result === 'downloaded') {
                    $totalDownloaded++;
                } elseif ($result === 'skipped') {
                    $totalSkipped++;
                } else {
                    $totalFailed++;
                }
                
                $bar->advance();
                
                // Rate limiting - Fakturoid allows ~100 requests/minute
                if (!$dryRun) {
                    usleep(700000); // 0.7 seconds between requests
                }
            }

            $bar->finish();
            $this->newLine();
        }

        // Download subscription payment invoices
        if ($doSubscriptions) {
            $this->info("\n📋 Processing Subscription Payment Invoices...\n");
            
            $payments = SubscriptionPayment::whereNotNull('fakturoid_invoice_id')
                ->when($from, fn($q) => $q->where('created_at', '>=', $from . ' 00:00:00'))
                ->when($to,   fn($q) => $q->where('created_at', '<=', $to   . ' 23:59:59'))
                ->when(!$force, fn($q) => $q->where(function($q) {
                    $q->whereNull('invoice_pdf_path')
                      ->orWhere('invoice_pdf_path', '');
                }))
                ->get();

            $this->info("Found {$payments->count()} subscription payments with Fakturoid invoice ID" . (!$force ? ' (missing PDF)' : ''));

            $bar = $this->output->createProgressBar($payments->count());
            $bar->start();

            foreach ($payments as $payment) {
                $result = $this->downloadSubscriptionInvoice($payment, $dryRun, $force);
                
                if ($result === 'downloaded') {
                    $totalDownloaded++;
                } elseif ($result === 'skipped') {
                    $totalSkipped++;
                } else {
                    $totalFailed++;
                }
                
                $bar->advance();
                
                if (!$dryRun) {
                    usleep(700000);
                }
            }

            $bar->finish();
            $this->newLine();
        }

        // Summary
        $this->newLine();
        $this->info('═══════════════════════════════════════');
        $this->info('📊 Summary:');
        $this->info("   ✅ Downloaded: {$totalDownloaded}");
        $this->info("   ⏭️  Skipped:    {$totalSkipped}");
        if ($totalFailed > 0) {
            $this->error("   ❌ Failed:     {$totalFailed}");
        }
        $this->info('═══════════════════════════════════════');

        return $totalFailed > 0 ? 1 : 0;
    }

    private function downloadOrderInvoice(Order $order, bool $dryRun, bool $force): string
    {
        $invoiceId = $order->fakturoid_invoice_id;
        $expectedPath = "invoices/order_{$order->id}_invoice_{$invoiceId}.pdf";

        // Check if already exists
        if (!$force && $order->invoice_pdf_path && Storage::exists($order->invoice_pdf_path)) {
            return 'skipped';
        }

        if ($dryRun) {
            $this->line("  Would download: Order #{$order->id} (Invoice {$invoiceId}) -> {$expectedPath}");
            return 'downloaded';
        }

        try {
            $pdfPath = $this->downloadPdf($invoiceId, $expectedPath);
            
            if ($pdfPath) {
                $order->update(['invoice_pdf_path' => $pdfPath]);
                $this->line("  ✅ Order #{$order->id}: Downloaded -> {$pdfPath}");
                return 'downloaded';
            } else {
                $this->warn("  ⚠️  Order #{$order->id}: Failed to download (Invoice {$invoiceId})");
                return 'failed';
            }
        } catch (\Exception $e) {
            $this->error("  ❌ Order #{$order->id}: Error - {$e->getMessage()}");
            Log::error('Failed to download order invoice', [
                'order_id' => $order->id,
                'invoice_id' => $invoiceId,
                'error' => $e->getMessage(),
            ]);
            return 'failed';
        }
    }

    private function downloadSubscriptionInvoice(SubscriptionPayment $payment, bool $dryRun, bool $force): string
    {
        $invoiceId = $payment->fakturoid_invoice_id;
        $expectedPath = "invoices/subscription_{$payment->subscription_id}_payment_{$payment->id}_invoice_{$invoiceId}.pdf";

        // Check if already exists
        if (!$force && $payment->invoice_pdf_path && Storage::exists($payment->invoice_pdf_path)) {
            return 'skipped';
        }

        if ($dryRun) {
            $this->line("  Would download: Payment #{$payment->id} (Invoice {$invoiceId}) -> {$expectedPath}");
            return 'downloaded';
        }

        try {
            $pdfPath = $this->downloadPdf($invoiceId, $expectedPath);
            
            if ($pdfPath) {
                $payment->update(['invoice_pdf_path' => $pdfPath]);
                $this->line("  ✅ Payment #{$payment->id}: Downloaded -> {$pdfPath}");
                return 'downloaded';
            } else {
                $this->warn("  ⚠️  Payment #{$payment->id}: Failed to download (Invoice {$invoiceId})");
                return 'failed';
            }
        } catch (\Exception $e) {
            $this->error("  ❌ Payment #{$payment->id}: Error - {$e->getMessage()}");
            Log::error('Failed to download subscription invoice', [
                'payment_id' => $payment->id,
                'invoice_id' => $invoiceId,
                'error' => $e->getMessage(),
            ]);
            return 'failed';
        }
    }

    private function downloadPdf(int $invoiceId, string $filename): ?string
    {
        $maxRetries = 3;
        $retryDelay = 2;

        for ($i = 0; $i < $maxRetries; $i++) {
            $token = $this->getAccessToken();

            try {
                $response = Http::withToken($token)
                    ->withHeaders(['User-Agent' => $this->userAgent])
                    ->connectTimeout(60)
                    ->timeout(120)
                    ->get("{$this->apiUrl}/accounts/{$this->slug}/invoices/{$invoiceId}/download.pdf");
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                if ($i < $maxRetries - 1) {
                    $this->warn("    Network error for invoice {$invoiceId}, retrying... ({$e->getMessage()})");
                    sleep($retryDelay);
                    continue;
                }
                throw $e;
            }

            if ($response->status() === 200) {
                Storage::put($filename, $response->body());
                return $filename;
            }

            // PDF not ready yet (204)
            if ($response->status() === 204) {
                if ($i < $maxRetries - 1) {
                    sleep($retryDelay);
                    continue;
                }
            }

            // Not found or other error - don't retry
            if ($response->status() === 404) {
                $this->warn("    Invoice {$invoiceId} not found in Fakturoid");
                return null;
            }

            break;
        }

        return null;
    }

    private function getAccessToken(): string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->asForm()
            ->withHeaders(['Accept' => 'application/json'])
            ->post('https://app.fakturoid.cz/api/v3/oauth/token', [
                'grant_type' => 'client_credentials',
            ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to authenticate with Fakturoid: ' . $response->body());
        }

        $this->accessToken = $response->json()['access_token'];
        return $this->accessToken;
    }
}
