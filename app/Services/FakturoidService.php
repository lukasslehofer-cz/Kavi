<?php

namespace App\Services;

use App\Helpers\CurrencyHelper;
use App\Helpers\SubscriptionPricing;
use App\Helpers\VatHelper;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FakturoidService
{
    private string $apiUrl;

    private string $slug;

    private string $clientId;

    private string $clientSecret;

    private ?string $numberFormat;

    private string $userAgent;

    private ?string $accessToken = null;

    public function __construct()
    {
        $this->apiUrl = 'https://app.fakturoid.cz/api/v3';
        $this->slug = config('services.fakturoid.slug');
        $this->clientId = config('services.fakturoid.client_id');
        $this->clientSecret = config('services.fakturoid.client_secret');
        $this->numberFormat = config('services.fakturoid.number_format');
        $this->userAgent = config('services.fakturoid.user_agent', 'Kavi (info@kavi.cz)');
    }

    /**
     * Get invoice language code based on entity currency
     * Returns 'cz' for CZK or 'en' for EUR
     * Note: Fakturoid uses 'cz' (not 'cs') for Czech language
     */
    private function getInvoiceLanguageFromCurrency(?string $currency): string
    {
        return ($currency === 'EUR') ? 'en' : 'cz';
    }

    /**
     * Get invoice language code based on current locale (fallback for session-based)
     * Returns 'cz' for Czech or 'en' for English
     * Note: Fakturoid uses 'cz' (not 'cs') for Czech language
     */
    private function getInvoiceLanguage(): string
    {
        return CurrencyHelper::isCzk() ? 'cz' : 'en';
    }

    /**
     * Get invoice currency code
     */
    private function getInvoiceCurrency(): string
    {
        return CurrencyHelper::code();
    }

    /**
     * Get localized text for invoice items
     */
    private function getLocalizedText(string $key, ?string $currency = null): string
    {
        $texts = [
            'cz' => [
                'shipping' => 'Doprava',
                'discount' => 'Sleva',
                'gift_voucher' => 'Dárkový voucher',
                'unit' => 'ks',
                'subscription' => 'Předplatné kávy',
                'note' => 'Objednávka z e-shopu Kavi',
                'subscription_note' => 'Platba předplatného Kavi',
                'customer' => 'Zákazník',
            ],
            'en' => [
                'shipping' => 'Shipping',
                'discount' => 'Discount',
                'gift_voucher' => 'Gift voucher',
                'unit' => 'pcs',
                'subscription' => 'Coffee Subscription',
                'note' => 'Order from Kavi e-shop',
                'subscription_note' => 'Kavi subscription payment',
                'customer' => 'Customer',
            ],
        ];

        $lang = $currency ? $this->getInvoiceLanguageFromCurrency($currency) : $this->getInvoiceLanguage();

        return $texts[$lang][$key] ?? $texts['cz'][$key] ?? $key;
    }

    /**
     * Get OAuth 2.0 access token
     */
    private function getAccessToken(): string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        try {
            $response = Http::timeout(15)
                ->retry(3, 2000, function ($exception) {
                    return $exception instanceof \Illuminate\Http\Client\ConnectionException;
                })
                ->withBasicAuth($this->clientId, $this->clientSecret)
                ->withHeaders([
                    'User-Agent' => $this->userAgent,
                    'Accept' => 'application/json',
                ])
                ->asJson()
                ->post('https://app.fakturoid.cz/api/v3/oauth/token', [
                    'grant_type' => 'client_credentials',
                ]);

            if (! $response->successful()) {
                Log::error('Failed to get Fakturoid access token', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new \Exception('Failed to authenticate with Fakturoid');
            }

            $data = $response->json();
            $this->accessToken = $data['access_token'];

            return $this->accessToken;
        } catch (\Exception $e) {
            Log::error('Exception getting Fakturoid access token', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Make authenticated request to Fakturoid API
     */
    private function makeRequest(string $method, string $endpoint, array $data = []): \Illuminate\Http\Client\Response
    {
        $token = $this->getAccessToken();

        $request = Http::timeout(15)
            ->retry(3, 2000, function ($exception) {
                return $exception instanceof \Illuminate\Http\Client\ConnectionException;
            })
            ->withToken($token)
            ->withHeaders([
                'User-Agent' => $this->userAgent,
                'Content-Type' => 'application/json',
            ]);

        return match (strtoupper($method)) {
            'GET' => $request->get($endpoint, $data),
            'POST' => $request->post($endpoint, $data),
            'PATCH' => $request->patch($endpoint, $data),
            'DELETE' => $request->delete($endpoint, $data),
            default => throw new \Exception("Unsupported HTTP method: {$method}"),
        };
    }

    /**
     * Create invoice in Fakturoid for an order
     */
    public function createInvoiceForOrder(Order $order): ?array
    {
        try {
            // First, create or get subject (customer) in Fakturoid
            $subjectId = $this->getOrCreateSubject($order);

            if (! $subjectId) {
                Log::error('Failed to create subject in Fakturoid', ['order_id' => $order->id]);

                return null;
            }

            // Prepare invoice lines from order items
            // E-shop prices include VAT, use vat_price_mode=with_vat
            // With this mode, unit_price should contain the price WITH VAT
            // Fakturoid will calculate the base price and VAT from it

            // Use order's currency for invoice (stored at order creation time)
            $orderCurrency = $order->currency ?? 'CZK';

            $unitName = $this->getLocalizedText('unit', $orderCurrency);
            $lines = $order->items->map(function ($item) use ($unitName) {
                return [
                    'name' => $item->product_name,
                    'quantity' => (string) $item->quantity,
                    'unit_name' => $unitName,
                    'unit_price' => (string) $item->price, // Price WITH VAT when vat_price_mode=with_vat
                    'vat_rate' => (string) $item->vat_rate, // Dynamic VAT rate from order item
                ];
            })->toArray();

            // Add shipping - proporcionálně podle DPH sazeb položek v objednávce
            if ($order->shipping > 0) {
                // Spočítat proporcionální rozdělení dopravy
                $itemsByVatRate = [];
                foreach ($order->items as $item) {
                    $vatRate = $item->vat_rate;
                    if (! isset($itemsByVatRate[$vatRate])) {
                        $itemsByVatRate[$vatRate] = 0;
                    }
                    $itemsByVatRate[$vatRate] += $item->total;
                }

                $shippingByVat = VatHelper::calculateProportionalShipping($itemsByVatRate, $order->shipping);

                // Vytvořit line item pro každou DPH sazbu
                foreach ($shippingByVat as $vatRate => $shippingPortion) {
                    if ($shippingPortion > 0) {
                        $shippingLabel = $this->getLocalizedText('shipping', $orderCurrency);
                        if (count($shippingByVat) > 1) {
                            // Pokud je více DPH sazeb, rozlišit je v názvu
                            $shippingLabel .= ' (DPH '.$vatRate.'%)';
                        }

                        $lines[] = [
                            'name' => $shippingLabel,
                            'quantity' => '1',
                            'unit_name' => $unitName,
                            'unit_price' => (string) $shippingPortion,
                            'vat_rate' => (string) $vatRate,
                        ];
                    }
                }
            }

            // Add discount as a negative line item if applicable
            if ($order->discount_amount > 0) {
                // Dárkový voucher: 0% DPH (voucher je platební metoda, DPH zůstává na plné ceně)
                // Běžná sleva: nejvyšší DPH sazba v objednávce (standardní praxe)
                $isGiftVoucher = $order->coupon?->is_gift_voucher ?? false;
                $discountVatRate = $isGiftVoucher ? 0 : ($order->items->max('vat_rate') ?? 21);
                $discountName = $isGiftVoucher
                    ? $this->getLocalizedText('gift_voucher', $orderCurrency)
                    : $this->getLocalizedText('discount', $orderCurrency);
                $lines[] = [
                    'name' => $discountName.($order->coupon_code ? ' ('.$order->coupon_code.')' : ''),
                    'quantity' => '1',
                    'unit_name' => $unitName,
                    'unit_price' => (string) (-$order->discount_amount),
                    'vat_rate' => (string) $discountVatRate,
                ];
            }

            // Create invoice data
            $invoiceData = [
                'subject_id' => $subjectId,
                'custom_id' => $order->order_number,
                'document_type' => 'invoice',
                'issued_on' => now()->format('Y-m-d'),
                'taxable_fulfillment_due' => now()->format('Y-m-d'),
                'due' => 14,
                'payment_method' => 'card',
                'vat_price_mode' => 'with_vat', // Prices include VAT, Fakturoid calculates base price
                'currency' => $orderCurrency,
                'language' => $this->getInvoiceLanguageFromCurrency($orderCurrency),
                'lines' => $lines,
                'note' => $this->getLocalizedText('note', $orderCurrency),
            ];

            // Add number format ID if configured
            if ($this->numberFormat) {
                $invoiceData['number_format_id'] = (int) $this->numberFormat;
            }

            // Create invoice in Fakturoid
            $response = $this->makeRequest(
                'POST',
                "{$this->apiUrl}/accounts/{$this->slug}/invoices.json",
                $invoiceData
            );

            if (! $response->successful()) {
                Log::error('Fakturoid invoice creation failed', [
                    'order_id' => $order->id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            $invoice = $response->json();

            Log::info('Fakturoid invoice created', [
                'order_id' => $order->id,
                'invoice_id' => $invoice['id'],
                'invoice_number' => $invoice['number'],
            ]);

            // Mark invoice as sent and paid since payment was already received via Stripe
            $this->markInvoiceAsSent($invoice['id']);
            $this->markInvoiceAsPaid($invoice['id'], $order);

            return $invoice;
        } catch (\Exception $e) {
            Log::error('Exception creating Fakturoid invoice', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Download invoice PDF and save to storage
     */
    public function downloadInvoicePdf(int $invoiceId, Order $order): ?string
    {
        try {
            // Try to download PDF (may need to retry if not ready yet)
            $maxRetries = 5;
            $retryDelay = 2; // seconds

            for ($i = 0; $i < $maxRetries; $i++) {
                $token = $this->getAccessToken();
                $response = Http::withToken($token)
                    ->withHeaders(['User-Agent' => $this->userAgent])
                    ->get("{$this->apiUrl}/accounts/{$this->slug}/invoices/{$invoiceId}/download.pdf");

                // PDF is ready
                if ($response->status() === 200) {
                    // Save PDF to storage
                    $filename = "invoices/order_{$order->id}_invoice_{$invoiceId}.pdf";
                    Storage::put($filename, $response->body());

                    Log::info('Fakturoid invoice PDF downloaded', [
                        'order_id' => $order->id,
                        'invoice_id' => $invoiceId,
                        'filename' => $filename,
                    ]);

                    return $filename;
                }

                // PDF not ready yet (204 No Content)
                if ($response->status() === 204) {
                    if ($i < $maxRetries - 1) {
                        Log::info('Fakturoid PDF not ready, retrying...', [
                            'attempt' => $i + 1,
                            'invoice_id' => $invoiceId,
                        ]);
                        sleep($retryDelay);

                        continue;
                    }
                }

                // Other error
                Log::error('Fakturoid PDF download failed', [
                    'order_id' => $order->id,
                    'invoice_id' => $invoiceId,
                    'status' => $response->status(),
                ]);

                return null;
            }

            Log::error('Fakturoid PDF not ready after max retries', [
                'order_id' => $order->id,
                'invoice_id' => $invoiceId,
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Exception downloading Fakturoid PDF', [
                'order_id' => $order->id,
                'invoice_id' => $invoiceId,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Get or create subject (customer) in Fakturoid
     */
    private function getOrCreateSubject(Order $order): ?int
    {
        try {
            $user = $order->user;
            $billingAddress = $order->billing_address;
            $shippingAddress = $order->shipping_address;

            // Use billing address if available, otherwise shipping address
            $address = $billingAddress ?? $shippingAddress;

            // Prepare subject data
            $subjectData = [
                'name' => $address['name'] ?? $user?->name ?? $this->getLocalizedText('customer'),
                'email' => $address['email'] ?? $user?->email ?? '',
                'phone' => $address['phone'] ?? '',
                'street' => $address['address'] ?? $address['billing_address'] ?? '',
                'city' => $address['city'] ?? $address['billing_city'] ?? '',
                'zip' => $address['postal_code'] ?? $address['billing_postal_code'] ?? '',
                // Skutečná země zákazníka, ne natvrdo CZ – zahraniční odběratel se jinak
                // ve Fakturoidu založí jako český subjekt.
                'country' => strtoupper($address['country'] ?? $address['billing_country'] ?? 'CZ'),
            ];

            // Check if user has cached Fakturoid subject ID
            if ($user && $user->fakturoid_subject_id) {
                Log::info('Using cached Fakturoid subject ID from user', [
                    'user_id' => $user->id,
                    'subject_id' => $user->fakturoid_subject_id,
                ]);

                // Update subject with current data
                $this->updateSubject($user->fakturoid_subject_id, $subjectData);

                return $user->fakturoid_subject_id;
            }

            // Try to find existing subject by email
            if (! empty($subjectData['email'])) {
                $searchResponse = $this->makeRequest(
                    'GET',
                    "{$this->apiUrl}/accounts/{$this->slug}/subjects.json",
                    ['query' => $subjectData['email']]
                );

                if ($searchResponse->successful()) {
                    $subjects = $searchResponse->json();
                    if (! empty($subjects) && is_array($subjects)) {
                        // Search API does full-text search, we need to verify email matches
                        $matchingSubject = collect($subjects)->first(function ($subject) use ($subjectData) {
                            return ! empty($subject['email']) &&
                                   strtolower(trim($subject['email'])) === strtolower(trim($subjectData['email']));
                        });

                        if ($matchingSubject) {
                            Log::info('Found existing Fakturoid subject with matching email', [
                                'subject_id' => $matchingSubject['id'],
                                'email' => $subjectData['email'],
                            ]);

                            // Update subject with current data
                            $this->updateSubject($matchingSubject['id'], $subjectData);

                            // Cache subject ID in user
                            if ($user && ! $user->fakturoid_subject_id) {
                                $user->fakturoid_subject_id = $matchingSubject['id'];
                                $user->save();
                            }

                            return $matchingSubject['id'];
                        }

                        Log::info('Search returned subjects but none with matching email', [
                            'searched_email' => $subjectData['email'],
                            'results_count' => count($subjects),
                        ]);
                    }
                }
            }

            // Create new subject
            $response = $this->makeRequest(
                'POST',
                "{$this->apiUrl}/accounts/{$this->slug}/subjects.json",
                $subjectData
            );

            if (! $response->successful()) {
                Log::error('Fakturoid subject creation failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            $subject = $response->json();

            Log::info('Fakturoid subject created', [
                'subject_id' => $subject['id'],
                'name' => $subject['name'],
            ]);

            // Cache subject ID in user
            if ($user && ! $user->fakturoid_subject_id) {
                $user->fakturoid_subject_id = $subject['id'];
                $user->save();
            }

            return $subject['id'];
        } catch (\Exception $e) {
            Log::error('Exception creating Fakturoid subject', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Update existing subject in Fakturoid
     */
    private function updateSubject(int $subjectId, array $subjectData): void
    {
        try {
            $response = $this->makeRequest(
                'PATCH',
                "{$this->apiUrl}/accounts/{$this->slug}/subjects/{$subjectId}.json",
                $subjectData
            );

            if ($response->successful()) {
                Log::info('Fakturoid subject updated', [
                    'subject_id' => $subjectId,
                    'name' => $subjectData['name'],
                ]);
            } else {
                Log::warning('Failed to update Fakturoid subject', [
                    'subject_id' => $subjectId,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('Exception updating Fakturoid subject', [
                'subject_id' => $subjectId,
                'error' => $e->getMessage(),
            ]);
            // Don't throw - updating subject is not critical
        }
    }

    /**
     * Mark invoice as sent
     */
    public function markInvoiceAsSent(int $invoiceId): void
    {
        try {
            $response = $this->makeRequest(
                'POST',
                "{$this->apiUrl}/accounts/{$this->slug}/invoices/{$invoiceId}/fire.json",
                ['event' => 'mark_as_sent']
            );

            if (! $response->successful()) {
                Log::warning('Failed to mark Fakturoid invoice as sent', [
                    'invoice_id' => $invoiceId,
                    'status' => $response->status(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exception marking Fakturoid invoice as sent', [
                'invoice_id' => $invoiceId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Mark invoice as paid
     */
    private function markInvoiceAsPaid(int $invoiceId, Order $order): void
    {
        try {
            // Create payment record for the invoice
            $paymentData = [
                'paid_on' => $order->paid_at?->format('Y-m-d') ?? now()->format('Y-m-d'),
                'amount' => (string) $order->total,
                'payment_method' => 'card',
            ];

            $response = $this->makeRequest(
                'POST',
                "{$this->apiUrl}/accounts/{$this->slug}/invoices/{$invoiceId}/payments.json",
                $paymentData
            );

            if (! $response->successful()) {
                Log::warning('Failed to mark Fakturoid invoice as paid', [
                    'invoice_id' => $invoiceId,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            } else {
                Log::info('Fakturoid invoice marked as paid', [
                    'invoice_id' => $invoiceId,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exception marking Fakturoid invoice as paid', [
                'invoice_id' => $invoiceId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Create invoice and download PDF for order (main method to call)
     */
    public function processInvoiceForOrder(Order $order): bool
    {
        // Create invoice
        $invoice = $this->createInvoiceForOrder($order);

        if (! $invoice) {
            return false;
        }

        // Save invoice ID to order
        $order->update(['fakturoid_invoice_id' => $invoice['id']]);

        // Download PDF
        $pdfPath = $this->downloadInvoicePdf($invoice['id'], $order);

        if ($pdfPath) {
            $order->update(['invoice_pdf_path' => $pdfPath]);

            return true;
        }

        return false;
    }

    /**
     * Create invoice for subscription payment
     */
    public function processInvoiceForSubscriptionPayment(\App\Models\SubscriptionPayment $payment): bool
    {
        $subscription = $payment->subscription;

        // Create invoice
        $invoice = $this->createInvoiceForSubscription($payment);

        if (! $invoice) {
            return false;
        }

        // Save invoice data to payment
        $payment->update([
            'fakturoid_invoice_id' => $invoice['id'],
            'invoice_number' => $invoice['number'] ?? null,
        ]);

        // Download PDF
        $pdfPath = $this->downloadSubscriptionInvoicePdf($invoice['id'], $payment);

        if ($pdfPath) {
            $payment->update(['invoice_pdf_path' => $pdfPath]);

            return true;
        }

        return false;
    }

    /**
     * Sestaví payload faktury k platbě předplatného.
     *
     * Vydělené z createInvoiceForSubscription(), aby šel payload zkontrolovat
     * bez odeslání do Fakturoidu – viz `php artisan invoices:preview-subscription`.
     *
     * Řádky vždy vzniknou jednou cestou: box → doprava → záporná sleva. Dřív tu byly
     * dvě rozbíhající se větve a ta bez slevy brala jako cenu boxu $payment->amount,
     * což už dopravu obsahuje – a pak dopravu přidala ještě jednou.
     */
    public function buildSubscriptionInvoicePayload(\App\Models\SubscriptionPayment $payment, int $subjectId): array
    {
        $subscription = $payment->subscription;

        // Subscription prices include VAT, use vat_price_mode=with_vat
        // With this mode, unit_price should contain the price WITH VAT
        // Fakturoid will calculate the base price and VAT from it
        // Předplatné je vždy káva → 12% DPH
        $vatRate = $subscription->vat_rate ?? 12.00;

        $breakdown = SubscriptionPricing::forPayment($payment);
        $subscriptionCurrency = $breakdown->currency;

        $unitName = $this->getLocalizedText('unit', $subscriptionCurrency);
        $subscriptionName = $this->getLocalizedText('subscription', $subscriptionCurrency);
        $shippingName = $this->getLocalizedText('shipping', $subscriptionCurrency);
        $discountName = $this->getLocalizedText('discount', $subscriptionCurrency);

        $numberSuffix = $subscription->subscription_number
            ? ' ('.$subscription->subscription_number.')'
            : '';

        // Fakturoid chce ceny jako desetinné řetězce.
        $fmt = fn (float $value): string => number_format($value, 2, '.', '');

        // Zaokrouhlení se rozpouští v jedné položce, aby řádky vždy daly přesně total.
        // Doprava zůstává nedotčená – ta musí na faktuře sedět na haléř s ceníkem.
        $totalDiscount = $breakdown->totalDiscount();
        if ($totalDiscount > 0) {
            $boxLine = $breakdown->box;
            $discountLine = round($breakdown->box + $breakdown->shipping - $breakdown->total, 2);
        } else {
            $boxLine = round($breakdown->total - $breakdown->shipping, 2);
            $discountLine = 0.0;
        }

        $lines = [];

        $lines[] = [
            'name' => $subscriptionName.$numberSuffix,
            'quantity' => '1',
            'unit_name' => $unitName,
            'unit_price' => $fmt($boxLine),
            'vat_rate' => (string) $vatRate,
        ];

        if ($breakdown->shipping > 0) {
            $lines[] = [
                'name' => $shippingName,
                'quantity' => '1',
                'unit_name' => $unitName,
                'unit_price' => $fmt($breakdown->shipping),
                'vat_rate' => (string) $vatRate,
            ];
        }

        if ($discountLine > 0) {
            // Dárkový voucher: 0% DPH (voucher je platební metoda)
            $isGiftVoucher = $subscription->coupon?->is_gift_voucher ?? false;
            $discountVatRate = $isGiftVoucher ? 0 : $vatRate;
            $discountLabel = $isGiftVoucher
                ? $this->getLocalizedText('gift_voucher', $subscriptionCurrency)
                : $discountName;

            $lines[] = [
                'name' => $discountLabel.($subscription->coupon_code ? ' ('.$subscription->coupon_code.')' : ''),
                'quantity' => '1',
                'unit_name' => $unitName,
                'unit_price' => $fmt(-$discountLine),
                'vat_rate' => (string) $discountVatRate,
            ];
        }

        // Create invoice data
        $invoiceData = [
            'subject_id' => $subjectId,
            'custom_id' => 'SUB-'.$subscription->id.'-'.$payment->id,
            'document_type' => 'invoice',
            'issued_on' => $payment->paid_at->format('Y-m-d'),
            'taxable_fulfillment_due' => $payment->paid_at->format('Y-m-d'),
            'due' => 0, // Already paid
            'payment_method' => 'card',
            'vat_price_mode' => 'with_vat', // Prices include VAT, Fakturoid calculates base price
            'currency' => $subscriptionCurrency,
            'language' => $this->getInvoiceLanguageFromCurrency($subscriptionCurrency),
            'lines' => $lines,
            'note' => $this->getLocalizedText('subscription_note', $subscriptionCurrency),
        ];

        // Add number format ID if configured
        if ($this->numberFormat) {
            $invoiceData['number_format_id'] = (int) $this->numberFormat;
        }

        // Pojistka: součet faktury se MUSÍ rovnat skutečně stržené částce.
        // Rozpad na položky je kosmetika, součet je účetní fakt – když se rozejdou,
        // vystav radši jednu položku na správnou částku než itemizovanou nesprávnou.
        $lineTotal = round($boxLine + $breakdown->shipping - $discountLine, 2);
        $paidAmount = round((float) $payment->amount, 2);

        if (abs($lineTotal - $paidAmount) > 0.01) {
            Log::error('Fakturoid subscription invoice does not reconcile with captured payment', [
                'payment_id' => $payment->id,
                'subscription_id' => $subscription->id,
                'subscription_number' => $subscription->subscription_number,
                'currency' => $subscriptionCurrency,
                'breakdown' => $breakdown->toArray(),
                'line_total' => $lineTotal,
                'payment_amount' => $paidAmount,
                'delta' => round($lineTotal - $paidAmount, 2),
            ]);
            Cache::increment('fakturoid_invoice_mismatch_'.now()->format('Y-m-d'));

            $invoiceData['lines'] = [[
                'name' => $subscriptionName.$numberSuffix,
                'quantity' => '1',
                'unit_name' => $unitName,
                'unit_price' => $fmt($paidAmount),
                'vat_rate' => (string) $vatRate,
            ]];
            $invoiceData['private_note'] = 'Automatický fallback: rozpad položek ('.$fmt($lineTotal)
                .') nesouhlasil s uhrazenou částkou ('.$fmt($paidAmount).'). Zkontrolovat konfiguraci předplatného.';
        }

        return $invoiceData;
    }

    /**
     * Create invoice in Fakturoid for a subscription payment
     */
    private function createInvoiceForSubscription(\App\Models\SubscriptionPayment $payment): ?array
    {
        try {
            $subscription = $payment->subscription;

            // Get or create subject (customer)
            $subjectId = $this->getOrCreateSubjectForSubscription($subscription);

            if (! $subjectId) {
                Log::error('Failed to create subject in Fakturoid', ['subscription_id' => $subscription->id]);

                return null;
            }

            $invoiceData = $this->buildSubscriptionInvoicePayload($payment, $subjectId);

            // Create invoice in Fakturoid
            $response = $this->makeRequest(
                'POST',
                "{$this->apiUrl}/accounts/{$this->slug}/invoices.json",
                $invoiceData
            );

            if (! $response->successful()) {
                Log::error('Fakturoid invoice creation failed', [
                    'subscription_id' => $subscription->id,
                    'payment_id' => $payment->id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            $invoice = $response->json();

            Log::info('Fakturoid invoice created for subscription', [
                'subscription_id' => $subscription->id,
                'payment_id' => $payment->id,
                'invoice_id' => $invoice['id'],
                'invoice_number' => $invoice['number'],
            ]);

            // Mark invoice as sent and paid
            $this->markInvoiceAsSent($invoice['id']);
            $this->markSubscriptionInvoiceAsPaid($invoice['id'], $payment);

            return $invoice;
        } catch (\Exception $e) {
            Log::error('Exception creating Fakturoid invoice for subscription', [
                'subscription_id' => $subscription->id ?? null,
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Download subscription invoice PDF
     */
    public function downloadSubscriptionInvoicePdf(int $invoiceId, \App\Models\SubscriptionPayment $payment): ?string
    {
        try {
            $maxRetries = 5;
            $retryDelay = 2;

            for ($i = 0; $i < $maxRetries; $i++) {
                $token = $this->getAccessToken();
                $response = Http::withToken($token)
                    ->withHeaders(['User-Agent' => $this->userAgent])
                    ->get("{$this->apiUrl}/accounts/{$this->slug}/invoices/{$invoiceId}/download.pdf");

                if ($response->status() === 200) {
                    $filename = "invoices/subscription_{$payment->subscription_id}_payment_{$payment->id}_invoice_{$invoiceId}.pdf";
                    Storage::put($filename, $response->body());

                    Log::info('Fakturoid subscription invoice PDF downloaded', [
                        'payment_id' => $payment->id,
                        'invoice_id' => $invoiceId,
                        'filename' => $filename,
                    ]);

                    return $filename;
                }

                if ($response->status() === 204) {
                    if ($i < $maxRetries - 1) {
                        Log::info('Fakturoid PDF not ready, retrying...', [
                            'attempt' => $i + 1,
                            'invoice_id' => $invoiceId,
                        ]);
                        sleep($retryDelay);

                        continue;
                    }
                }

                Log::error('Fakturoid PDF download failed', [
                    'payment_id' => $payment->id,
                    'invoice_id' => $invoiceId,
                    'status' => $response->status(),
                ]);

                return null;
            }

            Log::error('Fakturoid PDF not ready after max retries', [
                'payment_id' => $payment->id,
                'invoice_id' => $invoiceId,
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Exception downloading Fakturoid subscription PDF', [
                'payment_id' => $payment->id,
                'invoice_id' => $invoiceId,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Get or create subject for subscription
     */
    private function getOrCreateSubjectForSubscription(\App\Models\Subscription $subscription): ?int
    {
        try {
            $user = $subscription->user;
            $shippingAddress = $subscription->shipping_address;

            $subjectData = [
                'name' => $shippingAddress['name'] ?? $user?->name ?? 'Zákazník',
                'email' => $shippingAddress['email'] ?? $user?->email ?? '',
                'phone' => $shippingAddress['phone'] ?? '',
                'street' => $shippingAddress['billing_address'] ?? $shippingAddress['address'] ?? '',
                'city' => $shippingAddress['billing_city'] ?? $shippingAddress['city'] ?? '',
                'zip' => $shippingAddress['billing_postal_code'] ?? $shippingAddress['postal_code'] ?? '',
                // Skutečná země zákazníka, ne natvrdo CZ – zahraniční odběratel se jinak
                // ve Fakturoidu založí jako český subjekt.
                'country' => strtoupper($shippingAddress['country'] ?? $subscription->shipping_country ?? 'CZ'),
            ];

            // Check if user has cached Fakturoid subject ID
            if ($user && $user->fakturoid_subject_id) {
                Log::info('Using cached Fakturoid subject ID from user', [
                    'user_id' => $user->id,
                    'subject_id' => $user->fakturoid_subject_id,
                ]);

                // Update subject with current data
                $this->updateSubject($user->fakturoid_subject_id, $subjectData);

                return $user->fakturoid_subject_id;
            }

            // Try to find existing subject by email
            if (! empty($subjectData['email'])) {
                $searchResponse = $this->makeRequest(
                    'GET',
                    "{$this->apiUrl}/accounts/{$this->slug}/subjects.json",
                    ['query' => $subjectData['email']]
                );

                if ($searchResponse->successful()) {
                    $subjects = $searchResponse->json();
                    if (! empty($subjects) && is_array($subjects)) {
                        // Search API does full-text search, we need to verify email matches
                        $matchingSubject = collect($subjects)->first(function ($subject) use ($subjectData) {
                            return ! empty($subject['email']) &&
                                   strtolower(trim($subject['email'])) === strtolower(trim($subjectData['email']));
                        });

                        if ($matchingSubject) {
                            Log::info('Found existing Fakturoid subject with matching email', [
                                'subject_id' => $matchingSubject['id'],
                                'email' => $subjectData['email'],
                            ]);

                            // Update subject with current data
                            $this->updateSubject($matchingSubject['id'], $subjectData);

                            // Cache subject ID in user
                            if ($user && ! $user->fakturoid_subject_id) {
                                $user->fakturoid_subject_id = $matchingSubject['id'];
                                $user->save();
                            }

                            return $matchingSubject['id'];
                        }

                        Log::info('Search returned subjects but none with matching email', [
                            'searched_email' => $subjectData['email'],
                            'results_count' => count($subjects),
                        ]);
                    }
                }
            }

            // Create new subject
            $response = $this->makeRequest(
                'POST',
                "{$this->apiUrl}/accounts/{$this->slug}/subjects.json",
                $subjectData
            );

            if (! $response->successful()) {
                Log::error('Fakturoid subject creation failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            $subject = $response->json();

            Log::info('Fakturoid subject created', [
                'subject_id' => $subject['id'],
                'name' => $subject['name'],
            ]);

            // Cache subject ID in user
            if ($user && ! $user->fakturoid_subject_id) {
                $user->fakturoid_subject_id = $subject['id'];
                $user->save();
            }

            return $subject['id'];
        } catch (\Exception $e) {
            Log::error('Exception creating Fakturoid subject for subscription', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Mark subscription invoice as paid
     */
    public function markSubscriptionInvoiceAsPaid(int $invoiceId, \App\Models\SubscriptionPayment $payment): void
    {
        try {
            $paymentData = [
                'paid_on' => $payment->paid_at->format('Y-m-d'),
                'amount' => (string) $payment->amount,
                'payment_method' => 'card',
            ];

            $response = $this->makeRequest(
                'POST',
                "{$this->apiUrl}/accounts/{$this->slug}/invoices/{$invoiceId}/payments.json",
                $paymentData
            );

            if (! $response->successful()) {
                Log::warning('Failed to mark Fakturoid subscription invoice as paid', [
                    'invoice_id' => $invoiceId,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            } else {
                // Částku i měnu logujeme schválně – právě tenhle řádek by odhalil,
                // že se proti faktuře na 55 EUR připisuje platba 51 EUR.
                Log::info('Fakturoid subscription invoice marked as paid', [
                    'invoice_id' => $invoiceId,
                    'payment_id' => $payment->id,
                    'amount' => (float) $payment->amount,
                    'currency' => strtoupper($payment->currency ?: ''),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exception marking Fakturoid subscription invoice as paid', [
                'invoice_id' => $invoiceId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Načte fakturu z Fakturoidu (read-only).
     *
     * makeRequest() je private, takže audit potřebuje tenhle wrapper.
     */
    public function getInvoice(int $invoiceId): ?array
    {
        try {
            $response = $this->makeRequest(
                'GET',
                "{$this->apiUrl}/accounts/{$this->slug}/invoices/{$invoiceId}.json"
            );

            if (! $response->successful()) {
                Log::warning('Failed to fetch Fakturoid invoice', [
                    'invoice_id' => $invoiceId,
                    'status' => $response->status(),
                ]);

                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Exception fetching Fakturoid invoice', [
                'invoice_id' => $invoiceId,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
