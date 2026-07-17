<?php

namespace App\Services;

use App\Helpers\CurrencyHelper;
use App\Models\NewsletterSubscriber;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\SubscriptionConfig;
use App\Models\User;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Customer as StripeCustomer;
use Stripe\Stripe;
use Stripe\Subscription as StripeSubscription;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create or get Stripe customer for user
     * If customer was deleted on Stripe, creates a new one and updates DB
     */
    public function getOrCreateCustomer(User $user): string
    {
        // Pokud má user stripe_customer_id, zkontroluj že stále existuje na Stripe
        if ($user->stripe_customer_id) {
            try {
                // Zkus načíst zákazníka ze Stripe
                $customer = StripeCustomer::retrieve($user->stripe_customer_id);

                // Pokud existuje a není smazaný, vrať ho
                if ($customer && ! isset($customer->deleted)) {
                    return $customer->id;
                }

                // Zákazník byl smazán, pokračuj k vytvoření nového
                \Log::warning('Stripe customer was deleted, creating new one', [
                    'user_id' => $user->id,
                    'old_customer_id' => $user->stripe_customer_id,
                ]);

            } catch (\Stripe\Exception\InvalidRequestException $e) {
                // Zákazník neexistuje (404), pokračuj k vytvoření nového
                \Log::warning('Stripe customer not found, creating new one', [
                    'user_id' => $user->id,
                    'old_customer_id' => $user->stripe_customer_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Vytvoř nového zákazníka
        $customer = StripeCustomer::create([
            'email' => $user->email,
            'name' => $user->name,
            'metadata' => [
                'user_id' => $user->id,
            ],
        ]);

        // Aktualizuj databázi s novým ID
        $user->update(['stripe_customer_id' => $customer->id]);

        \Log::info('Created new Stripe customer', [
            'user_id' => $user->id,
            'customer_id' => $customer->id,
        ]);

        return $customer->id;
    }

    /**
     * Create Stripe customer from shipping address (for guest checkout)
     * This ensures guest customers have a real cus_ ID for recurring payments
     */
    public function createCustomerFromAddress(array $shippingAddress): string
    {
        $customer = StripeCustomer::create([
            'email' => $shippingAddress['email'],
            'name' => $shippingAddress['name'],
            'phone' => $shippingAddress['phone'] ?? null,
            'address' => [
                'line1' => $shippingAddress['billing_address'] ?? null,
                'city' => $shippingAddress['billing_city'] ?? null,
                'postal_code' => $shippingAddress['billing_postal_code'] ?? null,
                'country' => $shippingAddress['billing_country'] ?? $shippingAddress['country'] ?? 'CZ',
            ],
            'metadata' => [
                'source' => 'guest_checkout',
            ],
        ]);

        \Log::info('Created Stripe customer for guest checkout', [
            'customer_id' => $customer->id,
            'email' => $shippingAddress['email'],
            'name' => $shippingAddress['name'],
        ]);

        return $customer->id;
    }

    /**
     * Create checkout session for one-time payment (order)
     */
    public function createOrderCheckoutSession(Order $order): StripeSession
    {
        \Log::info('createOrderCheckoutSession called', [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'payment_status' => $order->payment_status,
        ]);

        // Load user and items relationships
        $order->load(['user', 'items']);

        \Log::info('Order relationships loaded', [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'user_exists' => $order->user !== null,
            'items_count' => $order->items->count(),
        ]);

        if (! $order->user) {
            \Log::error('Order has no user', ['order_id' => $order->id]);
            throw new \Exception('Objednávka nemá přiřazeného uživatele.');
        }

        $customerId = $this->getOrCreateCustomer($order->user);

        // Calculate gross total (items + shipping) for adjusted discount calculation
        $itemsTotal = $order->items->sum(fn ($item) => $item->price * $item->quantity);
        $grossTotal = $itemsTotal + $order->shipping;

        // Displayed total (rounded) - what user sees on checkout page
        $displayedTotal = round($order->total);

        $lineItems = $order->items->map(function ($item) {
            $productData = [
                'name' => $item->product_name,
            ];

            // Add image only if it exists and is valid
            if ($item->product_image && ! empty(trim($item->product_image))) {
                try {
                    $imageUrl = str_starts_with($item->product_image, 'http')
                        ? $item->product_image
                        : url($item->product_image); // Use url() instead of asset()

                    // Validate URL format
                    if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                        $productData['images'] = [$imageUrl];
                    } else {
                        \Log::warning('Invalid product image URL', [
                            'product_name' => $item->product_name,
                            'image_path' => $item->product_image,
                            'generated_url' => $imageUrl,
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to generate product image URL', [
                        'product_name' => $item->product_name,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Use exact price (not rounded) - rounding happens at total level
            return [
                'price_data' => [
                    'currency' => CurrencyHelper::stripeCode(),
                    'product_data' => $productData,
                    'unit_amount' => (int) round($item->price * 100),
                ],
                'quantity' => $item->quantity,
            ];
        })->toArray();

        // Add shipping if applicable (exact price, not rounded)
        if ($order->shipping > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => CurrencyHelper::stripeCode(),
                    'product_data' => [
                        'name' => CurrencyHelper::isCzk() ? 'Doprava' : 'Shipping',
                    ],
                    'unit_amount' => (int) round($order->shipping * 100),
                ],
                'quantity' => 1,
            ];
        }

        // Build session data
        $sessionData = [
            'customer' => $customerId,
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('order.confirmation', $order).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.index').'?order_id='.$order->id,
            'metadata' => [
                'order_id' => $order->id,
            ],
        ];

        // Add discount if order has a coupon applied
        if ($order->discount_amount > 0) {
            try {
                // Calculate adjusted discount so Stripe total matches displayed total
                // adjustedDiscount = grossTotal - displayedTotal
                // This ensures: items + shipping - adjustedDiscount = displayedTotal
                $adjustedDiscount = $grossTotal - $displayedTotal;

                // Only create coupon if there's a positive discount
                if ($adjustedDiscount > 0) {
                    $stripeCoupon = \Stripe\Coupon::create([
                        'amount_off' => (int) round($adjustedDiscount * 100),
                        'currency' => CurrencyHelper::stripeCode(),
                        'duration' => 'once',
                        'name' => $order->coupon_code ?? (CurrencyHelper::isCzk() ? 'Sleva' : 'Discount'),
                    ]);

                    $sessionData['discounts'] = [['coupon' => $stripeCoupon->id]];

                    \Log::info('Stripe coupon created for order discount', [
                        'order_id' => $order->id,
                        'coupon_code' => $order->coupon_code,
                        'original_discount' => $order->discount_amount,
                        'adjusted_discount' => $adjustedDiscount,
                        'gross_total' => $grossTotal,
                        'displayed_total' => $displayedTotal,
                        'stripe_coupon_id' => $stripeCoupon->id,
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to create Stripe coupon for order discount', [
                    'order_id' => $order->id,
                    'discount_amount' => $order->discount_amount,
                    'error' => $e->getMessage(),
                ]);
                // Continue without discount - better to complete payment than fail entirely
            }
        }

        return StripeSession::create($sessionData);
    }

    /**
     * Create one-time payment checkout session for a subscription box
     */
    public function createOneTimeBoxCheckoutSession(
        Subscription $subscription,
        float $price,
        array $shippingAddress,
        float $shipping = 0,
        ?\App\Models\ShippingRate $shippingRate = null
    ): StripeSession {
        $subscription->load('user');

        if (! $subscription->user) {
            throw new \Exception('Subscription nemá přiřazeného uživatele.');
        }

        $customerId = $this->getOrCreateCustomer($subscription->user);

        // Build product description
        $config = is_string($subscription->configuration)
            ? json_decode($subscription->configuration, true)
            : $subscription->configuration;

        $productName = $this->buildProductName($config, 'one_time');

        $lineItems = [
            [
                'price_data' => [
                    'currency' => CurrencyHelper::stripeCode(),
                    'product_data' => [
                        'name' => $productName,
                        'description' => CurrencyHelper::isCzk() ? 'Jednorázový kávový box bez předplatného' : 'One-time coffee box without subscription',
                    ],
                    'unit_amount' => (int) (round($price) * 100),
                ],
                'quantity' => 1,
            ],
        ];

        // Add shipping if applicable
        if ($shipping > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => CurrencyHelper::stripeCode(),
                    'product_data' => [
                        'name' => CurrencyHelper::isCzk() ? 'Doprava' : 'Shipping',
                    ],
                    'unit_amount' => (int) (round($shipping) * 100),
                ],
                'quantity' => 1,
            ];
        }

        return StripeSession::create([
            'customer' => $customerId,
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => localizedRoute('subscriptions.checkout').'?session_id={CHECKOUT_SESSION_ID}&success=1',
            'cancel_url' => localizedRoute('subscriptions.index').'?payment=cancelled',
            'metadata' => [
                'subscription_id' => $subscription->id,
                'is_one_time_box' => 'true',
                'meta_fbp' => $subscription->meta_fbp ?? '',
                'meta_fbc' => $subscription->meta_fbc ?? '',
            ],
        ]);
    }

    /**
     * Create checkout session for custom configured subscription
     * CUSTOM BILLING: Uses one-time payment + saves card for future recurring payments
     */
    public function createConfiguredSubscriptionCheckoutSession(
        ?User $user,
        array $configuration,
        float $price,
        array $shippingAddress,
        ?\App\Models\Coupon $coupon = null,
        float $discount = 0,
        ?int $discountMonths = null,
        float $shipping = 0,
        ?\App\Models\ShippingRate $shippingRate = null,
        float $giftVoucherShippingCredit = 0
    ): StripeSession {
        // Core address fields only — Stripe limits each metadata value to 500 chars.
        // Packeta/carrier fields are sent as separate top-level metadata keys below
        // and stored in dedicated DB columns by the webhook handler.
        $shippingAddressData = [
            'name' => $shippingAddress['name'],
            'email' => $shippingAddress['email'],
            'phone' => $shippingAddress['phone'] ?? null,
            'billing_address' => $shippingAddress['billing_address'],
            'billing_city' => $shippingAddress['billing_city'],
            'billing_postal_code' => $shippingAddress['billing_postal_code'],
            'country' => $shippingAddress['billing_country'] ?? 'CZ',
        ];

        $subscriptionMetadata = [
            'type' => 'custom_billing_subscription', // Mark as custom billing
            'configuration' => json_encode($configuration),
            'configured_price' => $price,
            'shipping_address' => json_encode($shippingAddressData),
            // Keep Packeta data as separate keys for backward compatibility
            'packeta_point_id' => $shippingAddress['packeta_point_id'],
            'packeta_point_name' => $shippingAddress['packeta_point_name'],
            'packeta_point_address' => $shippingAddress['packeta_point_address'] ?? null,
            'carrier_id' => $shippingAddress['carrier_id'] ?? null,
            'carrier_pickup_point' => $shippingAddress['carrier_pickup_point'] ?? null,
            'delivery_notes' => $shippingAddress['delivery_notes'] ?? null,
            // Shipping cost information
            'shipping_cost' => $shipping,
            'shipping_country' => $shippingAddress['billing_country'] ?? 'CZ',
            'shipping_rate_id' => $shippingRate?->id,
        ];

        // Add Meta tracking cookies for CAPI deduplication
        $subscriptionMetadata['meta_fbp'] = request()->cookie('_fbp') ?? '';
        $subscriptionMetadata['meta_fbc'] = request()->cookie('_fbc') ?? '';

        // Add coupon info to metadata if present
        if ($coupon) {
            $subscriptionMetadata['coupon_id'] = $coupon->id;
            $subscriptionMetadata['coupon_code'] = $coupon->code;
            $subscriptionMetadata['discount_amount'] = $discount;
            $subscriptionMetadata['discount_months_total'] = $discountMonths;
            $subscriptionMetadata['discount_months_remaining'] = $discountMonths;
        }

        // Get base product ID for all subscriptions
        $productId = $this->getOrCreateBaseSubscriptionProduct();

        // Calculate next billing date (15th of month based on today)
        $frequencyMonths = $configuration['frequency'] ?? 1;
        $nextBillingDate = $this->calculateFirstBillingDate($frequencyMonths);
        $subscriptionMetadata['next_billing_date'] = $nextBillingDate->toDateString();
        $subscriptionMetadata['frequency_months'] = $frequencyMonths;

        // Build product description
        $productName = $this->buildProductName($configuration, 'first_payment');

        // Calculate actual payment amount (full price minus discount)
        $paymentAmount = $price - $discount;

        // For gift vouchers, remaining credit covers shipping
        $effectiveShipping = $shipping - $giftVoucherShippingCredit;

        // Calculate displayed total (what user sees on checkout page)
        // This ensures Stripe total exactly matches what user sees: number_format($paymentAmount + $effectiveShipping, 0)
        $displayedTotal = round($paymentAmount + $effectiveShipping);

        // Build line items array - use single line item with full total to avoid rounding discrepancies
        $lineItems = [[
            'price_data' => [
                'currency' => CurrencyHelper::stripeCode(),
                'unit_amount' => (int) ($displayedTotal * 100), // Exact displayed total in cents/haléře
                'product_data' => [
                    'name' => $productName,
                    'description' => CurrencyHelper::isCzk()
                        ? ('Platba předplatného'.($effectiveShipping > 0 ? ' včetně dopravy' : ''))
                        : ('Subscription payment'.($effectiveShipping > 0 ? ' including shipping' : '')),
                ],
            ],
            'quantity' => 1,
        ]];

        \Log::info('Subscription checkout total calculated', [
            'original_price' => $price,
            'discount' => $discount,
            'payment_amount' => $paymentAmount,
            'shipping' => $shipping,
            'gift_voucher_shipping_credit' => $giftVoucherShippingCredit,
            'effective_shipping' => $effectiveShipping,
            'displayed_total' => $displayedTotal,
        ]);

        // Add user_id or guest_email to metadata BEFORE creating $sessionData
        // This ensures metadata is included in both session and payment_intent copies
        if ($user) {
            $subscriptionMetadata['user_id'] = $user->id;
        } else {
            $subscriptionMetadata['guest_email'] = $shippingAddress['email'];
        }

        // Create session with ONE-TIME payment + save payment method for future
        $sessionData = [
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment', // ONE-TIME payment, NOT subscription!
            'metadata' => $subscriptionMetadata, // Metadata directly in session for webhook handling
            'payment_intent_data' => [
                'setup_future_usage' => 'off_session', // Save card for future automated payments
                'metadata' => $subscriptionMetadata, // Also in payment_intent for backup/redundancy
            ],
            'success_url' => localizedRoute('subscriptions.checkout').'?session_id={CHECKOUT_SESSION_ID}&success=1',
            'cancel_url' => localizedRoute('subscriptions.checkout'),
        ];

        // Add customer to session data - ALWAYS create real customer for recurring payments
        if ($user) {
            $sessionData['customer'] = $this->getOrCreateCustomer($user);
        } else {
            // Guest checkout - create full Stripe customer (not just customer_email!)
            // This is required for saving payment methods and recurring payments
            $sessionData['customer'] = $this->createCustomerFromAddress($shippingAddress);
        }

        \Log::info('Creating custom billing subscription checkout', [
            'user_id' => $user?->id,
            'customer' => $sessionData['customer'],
            'price' => $price,
            'next_billing_date' => $nextBillingDate->toDateString(),
            'frequency_months' => $frequencyMonths,
        ]);

        return StripeSession::create($sessionData);
    }

    /**
     * Build localized product name for Stripe
     *
     * @param  array  $configuration  Subscription configuration
     * @param  string  $type  'one_time', 'first_payment', 'recurring'
     */
    private function buildProductName(array $configuration, string $type = 'recurring'): string
    {
        $isEur = CurrencyHelper::isEur();

        // Box sizes - same in both languages
        $boxSize = [
            '2' => 'M Box (2× 250g)',
            '3' => 'L Box (3× 250g)',
            '4' => 'XL Box (4× 250g)',
        ];

        // Box types - localized
        $boxType = $isEur
            ? ['espresso' => 'Espresso', 'filter' => 'Filter', 'mix' => 'Mix']
            : ['espresso' => 'Espresso', 'filter' => 'Filtr', 'mix' => 'Mix'];

        $defaultCoffee = $isEur ? 'Coffee' : 'Káva';

        $productName = ($boxSize[$configuration['amount']] ?? 'Box').' - '.($boxType[$configuration['type']] ?? $defaultCoffee);

        if ($configuration['isDecaf'] ?? false) {
            $productName .= ' + Decaf';
        }

        // Add suffix based on type
        switch ($type) {
            case 'one_time':
                $productName .= $isEur ? ' (One-time)' : ' (Jednorázově)';
                break;
            case 'first_payment':
                $productName .= $isEur ? ' (First payment)' : ' (První platba)';
                break;
                // 'recurring' - no suffix
        }

        return $productName;
    }

    /**
     * Calculate first billing date for custom billing subscriptions
     * Uses ShipmentSchedule billing_date from admin konfigurator
     *
     * Logic:
     * - Find next billing_date after today from ShipmentSchedule
     * - Apply frequency offset if > 1 month
     * - Fallback to 15th of month if schedule missing
     */
    private function calculateFirstBillingDate(int $frequencyMonths): \Carbon\Carbon
    {
        $billingDate = \App\Models\ShipmentSchedule::getFirstBillingDateWithFrequency($frequencyMonths);

        \Log::info('Calculated first billing date from ShipmentSchedule', [
            'frequency_months' => $frequencyMonths,
            'billing_date' => $billingDate->toDateString(),
            'today' => \Carbon\Carbon::now()->toDateString(),
        ]);

        return $billingDate;
    }

    /**
     * Get or create base subscription product in Stripe
     * Used for all dynamic subscription pricing
     */
    private function getOrCreateBaseSubscriptionProduct(): string
    {
        // Check if we have a product ID stored in config
        $productId = SubscriptionConfig::get('stripe_base_product_id');

        if ($productId) {
            // Verify the product exists in Stripe
            try {
                \Stripe\Product::retrieve($productId);

                return $productId;
            } catch (\Exception $e) {
                // Product doesn't exist, create a new one
                \Log::warning('Stored Stripe product ID not found, creating new one', [
                    'stored_id' => $productId,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Create a new base product
        $product = \Stripe\Product::create([
            'name' => 'Kavi Kávové Předplatné',
            'description' => 'Prémiové kávové předplatné s vlastní konfigurací',
            'metadata' => [
                'type' => 'configurable_subscription',
            ],
        ]);

        // Store the product ID in config for future use
        SubscriptionConfig::updateOrCreate(
            ['key' => 'stripe_base_product_id'],
            [
                'value' => $product->id,
                'type' => 'string',
                'label' => 'Stripe Base Product ID',
                'description' => 'Base product for all configurable subscriptions',
            ]
        );

        \Log::info('Created new Stripe base product', ['product_id' => $product->id]);

        return $product->id;
    }

    /**
     * Create subscription directly with existing payment method (for migration)
     */
    public function createSubscriptionWithPaymentMethod(
        User $user,
        array $configuration,
        float $price,
        string $paymentMethodId
    ): array {
        $customerId = $this->getOrCreateCustomer($user);

        // Get base product ID
        $productId = $this->getOrCreateBaseSubscriptionProduct();

        // Create a Price object with dynamic pricing
        $stripePrice = \Stripe\Price::create([
            'currency' => CurrencyHelper::stripeCode(),
            'product' => $productId,
            'recurring' => [
                'interval' => 'month',
                'interval_count' => $configuration['frequency'] ?? 1,
            ],
            'unit_amount' => (int) (round($price) * 100),
        ]);

        $subscription = StripeSubscription::create([
            'customer' => $customerId,
            'default_payment_method' => $paymentMethodId,
            'items' => [
                ['price' => $stripePrice->id],
            ],
            'metadata' => [
                'user_id' => $user->id,
                'configuration' => json_encode($configuration),
                'configured_price' => $price,
            ],
        ]);

        return [
            'stripe_subscription_id' => $subscription->id,
            'status' => $subscription->status,
        ];
    }

    /**
     * Get customer's default payment method
     */
    public function getCustomerDefaultPaymentMethod(string $customerId): ?string
    {
        try {
            // Retry wrapper for transient network errors
            $customer = retry(3, function () use ($customerId) {
                return StripeCustomer::retrieve($customerId);
            }, 2000, function ($e) {
                return $e instanceof \Stripe\Exception\ApiConnectionException;
            });

            // Ošetři případ kdy customer neexistuje nebo je smazaný
            if (! $customer || isset($customer->deleted)) {
                \Log::warning('Customer not found or deleted in getCustomerDefaultPaymentMethod', [
                    'customer_id' => $customerId,
                ]);

                return null;
            }

            // Bezpečný přístup k invoice_settings
            if ($customer->invoice_settings->default_payment_method ?? null) {
                return $customer->invoice_settings->default_payment_method;
            }

            // Fallback: get first payment method
            $paymentMethods = retry(3, function () use ($customerId) {
                return \Stripe\PaymentMethod::all([
                    'customer' => $customerId,
                    'type' => 'card',
                    'limit' => 1,
                ]);
            }, 2000, function ($e) {
                return $e instanceof \Stripe\Exception\ApiConnectionException;
            });

            if (count($paymentMethods->data) > 0) {
                return $paymentMethods->data[0]->id;
            }

            return null;
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Síťový problém — propagovat výše, NEINTERPRETOVAT jako "nemá platební metodu"
            \Log::error('Network error getting customer payment method', [
                'customer_id' => $customerId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Zákazník neexistuje
            \Log::warning('Customer not found in getCustomerDefaultPaymentMethod', [
                'customer_id' => $customerId,
                'error' => $e->getMessage(),
            ]);

            return null;
        } catch (\Exception $e) {
            \Log::error('Failed to get customer default payment method', [
                'customer_id' => $customerId,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Handle successful payment webhook (checkout.session.completed)
     */
    public function handlePaymentSuccess(array $session): void
    {
        // CUSTOM BILLING: Handle new subscription with custom billing cycle
        if (isset($session['payment_intent']) && isset($session['metadata']['type']) && $session['metadata']['type'] === 'custom_billing_subscription') {
            $this->handleCustomBillingSubscriptionPayment($session);

            return;
        }

        // Handle subscription checkout - store session_id for later use
        if (isset($session['mode']) && $session['mode'] === 'subscription' && isset($session['subscription'])) {
            $stripeSubscriptionId = $session['subscription'];
            $sessionId = $session['id'];

            // Store session_id in cache for 10 minutes (enough time for subscription.created webhook)
            \Cache::put("stripe_session_{$stripeSubscriptionId}", $sessionId, now()->addMinutes(10));

            \Log::info('Stored session_id for subscription in cache', [
                'stripe_subscription_id' => $stripeSubscriptionId,
                'session_id' => $sessionId,
            ]);

            return;
        }

        // Handle manual invoice payment for subscription
        if (isset($session['metadata']['type']) && $session['metadata']['type'] === 'manual_invoice_payment') {
            $subscriptionId = $session['metadata']['subscription_id'] ?? null;
            $invoiceId = $session['metadata']['invoice_id'] ?? null;

            if ($subscriptionId) {
                $subscription = Subscription::find($subscriptionId);

                if ($subscription) {
                    // If there's a real Stripe invoice, try to pay it
                    if ($invoiceId && $invoiceId !== 'manual_payment' && ! str_starts_with($invoiceId, 'in_test_')) {
                        try {
                            $invoice = \Stripe\Invoice::retrieve($invoiceId);
                            if ($invoice->status !== 'paid') {
                                $invoice->pay();
                            }
                            \Log::info('Stripe invoice paid after manual payment', [
                                'subscription_id' => $subscription->id,
                                'invoice_id' => $invoiceId,
                            ]);
                        } catch (\Exception $e) {
                            \Log::error('Failed to pay Stripe invoice after manual payment', [
                                'subscription_id' => $subscription->id,
                                'invoice_id' => $invoiceId,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }

                    // Record payment
                    $paymentIntentId = $session['payment_intent'] ?? null;
                    $amount = $subscription->pending_invoice_amount;
                    $currency = $subscription->currency ?? 'CZK';
                    $frequencyMonths = $subscription->frequency_months ?? 1;

                    $payment = \App\Models\SubscriptionPayment::create([
                        'subscription_id' => $subscription->id,
                        'stripe_payment_intent_id' => $paymentIntentId,
                        'amount' => $amount,
                        'currency' => $currency,
                        'status' => 'paid',
                        'paid_at' => now(),
                        'period_start' => $subscription->next_billing_date?->copy()->subMonths($frequencyMonths),
                        'period_end' => $subscription->next_billing_date,
                    ]);

                    // Clear unpaid status, restore subscription and advance billing date
                    $nextBillingDate = $this->calculateNextBillingDate(
                        $subscription->next_billing_date,
                        $frequencyMonths
                    );

                    $subscription->update([
                        'status' => 'active',
                        'next_billing_date' => $nextBillingDate,
                        'payment_failure_count' => 0,
                        'last_payment_failure_at' => null,
                        'last_payment_failure_reason' => null,
                        'pending_invoice_id' => null,
                        'pending_invoice_amount' => null,
                    ]);

                    // Handle coupon countdown
                    if ($subscription->coupon_id && $subscription->discount_amount > 0) {
                        app(\App\Services\CouponService::class)->decrementSubscriptionDiscountMonth($subscription);
                    }

                    // Link payment to shipment
                    try {
                        $shipmentService = app(\App\Services\SubscriptionShipmentService::class);
                        $shipmentService->linkPaymentToShipment($payment, $subscription);
                    } catch (\Exception $e) {
                        \Log::error('Failed to link manual payment to shipment', [
                            'payment_id' => $payment->id,
                            'subscription_id' => $subscription->id,
                            'error' => $e->getMessage(),
                        ]);
                    }

                    // Create Fakturoid invoice
                    if (! $subscription->isComplimentary()) {
                        try {
                            $fakturoidService = app(\App\Services\FakturoidService::class);
                            $fakturoidService->processInvoiceForSubscriptionPayment($payment);
                        } catch (\Exception $e) {
                            \Log::error('Failed to create Fakturoid invoice for manual payment', [
                                'payment_id' => $payment->id,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }

                    // Send confirmation email
                    try {
                        $email = $subscription->shipping_address['email'] ?? $subscription->user?->email;
                        if ($email) {
                            \Mail::to($email)->send(new \App\Mail\SubscriptionPaymentSuccess($subscription, $payment));
                        }
                    } catch (\Exception $e) {
                        \Log::error('Failed to send manual payment confirmation email', [
                            'subscription_id' => $subscription->id,
                            'error' => $e->getMessage(),
                        ]);
                    }

                    \Log::info('Manual invoice payment successful - subscription restored', [
                        'subscription_id' => $subscription->id,
                        'payment_id' => $payment->id,
                        'invoice_id' => $invoiceId ?? 'manual_payment',
                        'next_billing_date' => $nextBillingDate->toDateString(),
                    ]);
                }
            }

            return;
        }

        // Handle one-time box payment
        if (isset($session['metadata']['is_one_time_box']) && $session['metadata']['is_one_time_box'] === 'true') {
            $subscriptionId = $session['metadata']['subscription_id'] ?? null;

            if ($subscriptionId) {
                $subscription = Subscription::find($subscriptionId);

                if ($subscription && $subscription->frequency_months == 0) {
                    // Activate one-time box subscription
                    $subscription->update([
                        'status' => 'active',
                        'starts_at' => now(),
                    ]);

                    // Record the payment
                    $paymentIntentId = $session['payment_intent'] ?? null;
                    $payment = null;

                    try {
                        $totalAmount = ($subscription->configured_price ?? 0) + ($subscription->shipping_cost ?? 0);

                        if ($totalAmount > 0 && $paymentIntentId) {
                            $payment = \App\Models\SubscriptionPayment::create([
                                'subscription_id' => $subscription->id,
                                'stripe_payment_intent_id' => $paymentIntentId,
                                'amount' => $totalAmount,
                                'currency' => $subscription->currency ?? 'czk',
                                'status' => 'paid',
                                'paid_at' => now(),
                                'period_start' => now(),
                                'period_end' => now(),
                            ]);

                            \Log::info('Created payment record for one-time box', [
                                'payment_id' => $payment->id,
                                'subscription_id' => $subscription->id,
                                'amount' => $totalAmount,
                            ]);
                        }
                    } catch (\Exception $e) {
                        \Log::error('Failed to create payment record for one-time box', [
                            'subscription_id' => $subscription->id,
                            'error' => $e->getMessage(),
                        ]);
                    }

                    // Create pending shipment for one-time box
                    $shipmentService = app(\App\Services\SubscriptionShipmentService::class);
                    try {
                        $shipmentService->ensurePendingShipmentExists($subscription);
                    } catch (\Exception $e) {
                        \Log::error('Failed to create pending shipment for one-time box', [
                            'subscription_id' => $subscription->id,
                            'error' => $e->getMessage(),
                        ]);
                    }

                    // Link payment to the shipment
                    if ($payment) {
                        try {
                            $shipmentService->linkPaymentToShipment($payment, $subscription);
                        } catch (\Exception $e) {
                            \Log::error('Failed to link payment to shipment for one-time box', [
                                'payment_id' => $payment->id,
                                'subscription_id' => $subscription->id,
                                'error' => $e->getMessage(),
                            ]);
                        }

                        // Create Fakturoid invoice
                        try {
                            $fakturoidService = app(\App\Services\FakturoidService::class);
                            $fakturoidService->processInvoiceForSubscriptionPayment($payment);
                        } catch (\Exception $e) {
                            \Log::error('Failed to create Fakturoid invoice for one-time box', [
                                'subscription_id' => $subscription->id,
                                'payment_id' => $payment->id,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }

                    // Send confirmation email (same template as regular subscriptions, with one-time conditionals)
                    try {
                        \Mail::to($subscription->user->email)->send(new \App\Mail\SubscriptionConfirmation($subscription));
                    } catch (\Exception $e) {
                        \Log::error('Failed to send one-time box confirmation email: '.$e->getMessage());
                    }

                    // Notify admins about new one-time box order
                    try {
                        \App\Mail\AdminOrderNotification::notifyAdmins($subscription);
                    } catch (\Exception $e) {
                        \Log::error('Failed to send admin subscription notification', [
                            'subscription_id' => $subscription->id,
                            'error' => $e->getMessage(),
                        ]);
                    }

                    // Send Meta CAPI Purchase event
                    $this->sendMetaCapiForSubscription($subscription);

                    \Log::info('One-time box payment successful', [
                        'subscription_id' => $subscription->id,
                        'subscription_number' => $subscription->subscription_number,
                    ]);
                }
            }

            return;
        }

        // Handle regular order payment
        if (isset($session['metadata']['order_id'])) {
            $order = Order::find($session['metadata']['order_id']);
            if ($order) {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                    'stripe_payment_intent_id' => $session['payment_intent'] ?? null,
                    'paid_at' => now(),
                ]);

                // Mark this checkout as completed in cache (to prevent payment method changed email)
                if ($order->user && $order->user->stripe_customer_id) {
                    \Cache::put("recent_checkout_{$order->user->stripe_customer_id}", true, now()->addMinutes(3));
                    \Log::info('Marked recent checkout for order', [
                        'order_id' => $order->id,
                        'customer_id' => $order->user->stripe_customer_id,
                    ]);
                }

                // Create invoice in Fakturoid ONLY if not already created (backup for webhook)
                if (! $order->fakturoid_invoice_id) {
                    try {
                        $fakturoidService = app(\App\Services\FakturoidService::class);
                        $fakturoidService->processInvoiceForOrder($order);
                        // Refresh order to get updated invoice_pdf_path
                        $order->refresh();

                        \Log::info('Fakturoid invoice created via webhook', [
                            'order_id' => $order->id,
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Failed to create Fakturoid invoice after payment', [
                            'order_id' => $order->id,
                            'error' => $e->getMessage(),
                        ]);
                        // Don't fail the webhook if Fakturoid fails
                    }
                } else {
                    \Log::info('Fakturoid invoice already exists, skipping webhook creation', [
                        'order_id' => $order->id,
                        'invoice_id' => $order->fakturoid_invoice_id,
                    ]);
                }

                // Send confirmation email if not already sent
                // This handles race condition where webhook arrives before user sees confirmation page
                if (! $order->confirmation_email_sent_at) {
                    try {
                        $email = $order->shipping_address['email'] ?? $order->user?->email;
                        if ($email) {
                            \Mail::to($email)->send(new \App\Mail\OrderConfirmation($order));

                            // Mark email as sent
                            $order->update(['confirmation_email_sent_at' => now()]);

                            \Log::info('Order confirmation email sent via webhook', [
                                'order_id' => $order->id,
                                'email' => $email,
                            ]);
                        }
                    } catch (\Exception $e) {
                        \Log::error('Failed to send order confirmation email via webhook', [
                            'order_id' => $order->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                } else {
                    \Log::info('Order confirmation email already sent, skipping webhook email', [
                        'order_id' => $order->id,
                        'sent_at' => $order->confirmation_email_sent_at,
                    ]);
                }

                // Create affiliate reward if applicable
                if ($order->coupon_id) {
                    try {
                        $coupon = $order->coupon;
                        if ($coupon && $coupon->hasAffiliateOrderReward()) {
                            $affiliateService = app(\App\Services\AffiliateService::class);
                            $reward = $affiliateService->createOrderReward($order, $coupon);

                            if ($reward) {
                                \Log::info('Affiliate reward created for order', [
                                    'order_id' => $order->id,
                                    'reward_id' => $reward->id,
                                    'reward_amount' => $reward->reward_amount,
                                    'partner_id' => $reward->affiliate_partner_id,
                                ]);
                            }
                        }
                    } catch (\Exception $e) {
                        \Log::error('Failed to create affiliate reward for order', [
                            'order_id' => $order->id,
                            'error' => $e->getMessage(),
                        ]);
                        // Don't fail the webhook if affiliate reward creation fails
                    }
                }

                // Notify admins about new order
                try {
                    \App\Mail\AdminOrderNotification::notifyAdmins($order);
                } catch (\Exception $e) {
                    \Log::error('Failed to send admin order notification', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                    ]);
                }

                // Send Meta CAPI Purchase event
                $this->sendMetaCapiForOrder($order);

                \Log::info('Order payment completed via webhook', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ]);
            }
        }
    }

    /**
     * Handle subscription created webhook
     */
    public function handleSubscriptionCreated(array $subscriptionData): void
    {
        \Log::info('handleSubscriptionCreated called', [
            'subscription_id' => $subscriptionData['id'] ?? 'unknown',
            'metadata' => $subscriptionData['metadata'] ?? [],
            'customer' => $subscriptionData['customer'] ?? null,
        ]);

        $userId = $subscriptionData['metadata']['user_id'] ?? null;
        $planId = $subscriptionData['metadata']['subscription_plan_id'] ?? null;
        $configuration = isset($subscriptionData['metadata']['configuration'])
            ? json_decode($subscriptionData['metadata']['configuration'], true)
            : null;

        \Log::info('Parsed metadata', [
            'user_id' => $userId,
            'plan_id' => $planId,
            'configuration' => $configuration,
        ]);

        // Check if subscription already exists
        $existingSubscription = Subscription::where('stripe_subscription_id', $subscriptionData['id'])->first();

        if ($existingSubscription) {
            \Log::info('Subscription already exists, updating', ['id' => $existingSubscription->id]);
            $existingSubscription->update([
                'status' => 'active',
            ]);

            return;
        }

        // Support guest subscriptions (no user_id)
        $guestEmail = $subscriptionData['metadata']['guest_email'] ?? null;

        if (! $userId && ! $guestEmail) {
            \Log::warning('No user_id or guest_email in subscription metadata, cannot create subscription', [
                'stripe_subscription_id' => $subscriptionData['id'],
                'customer' => $subscriptionData['customer'] ?? null,
            ]);

            return;
        }

        try {
            // Calculate next billing date (15th of the next billing cycle)
            // Billing cycle: 16th of one month to 15th of next month
            $subscriptionStartDate = now();
            $currentBillingCycleEnd = $subscriptionStartDate->day <= 15
                ? $subscriptionStartDate->copy()->setDay(15)
                : $subscriptionStartDate->copy()->addMonthNoOverflow()->setDay(15);

            // Get frequency from configuration if available, otherwise default to 1 month
            $frequencyMonths = 1;
            if ($configuration && isset($configuration['frequency'])) {
                $frequencyMonths = $configuration['frequency'];
            }

            $nextBillingDate = $currentBillingCycleEnd->copy()->addMonths($frequencyMonths);

            // Retrieve session_id from cache (stored by checkout.session.completed webhook)
            $sessionId = \Cache::get("stripe_session_{$subscriptionData['id']}");
            if ($sessionId) {
                \Log::info('Retrieved session_id from cache', [
                    'stripe_subscription_id' => $subscriptionData['id'],
                    'session_id' => $sessionId,
                ]);
            }

            $subscriptionRecord = [
                'subscription_number' => Subscription::generateSubscriptionNumber(),
                'user_id' => $userId,
                'stripe_subscription_id' => $subscriptionData['id'],
                'stripe_session_id' => $sessionId,
                'status' => 'active',
                'starts_at' => $subscriptionStartDate,
                'next_billing_date' => $nextBillingDate,
                'meta_event_id' => (string) \Illuminate\Support\Str::uuid(),
                'meta_fbp' => $subscriptionData['metadata']['meta_fbp'] ?? null,
                'meta_fbc' => $subscriptionData['metadata']['meta_fbc'] ?? null,
            ];

            if ($planId) {
                // Predefined plan
                $subscriptionRecord['subscription_plan_id'] = $planId;
            } elseif ($configuration) {
                // Custom configuration
                $subscriptionRecord['configuration'] = $configuration;
                $subscriptionRecord['configured_price'] = $subscriptionData['metadata']['configured_price'] ?? null;
                $subscriptionRecord['frequency_months'] = $configuration['frequency'] ?? 1;

                // Add shipping address if available
                if (isset($subscriptionData['metadata']['shipping_address'])) {
                    $subscriptionRecord['shipping_address'] = json_decode($subscriptionData['metadata']['shipping_address'], true);
                }

                // Add Packeta data if available
                if (isset($subscriptionData['metadata']['packeta_point_id'])) {
                    $subscriptionRecord['packeta_point_id'] = $subscriptionData['metadata']['packeta_point_id'];
                    $subscriptionRecord['packeta_point_name'] = $subscriptionData['metadata']['packeta_point_name'] ?? null;
                    $subscriptionRecord['packeta_point_address'] = $subscriptionData['metadata']['packeta_point_address'] ?? null;
                    $subscriptionRecord['carrier_id'] = $subscriptionData['metadata']['carrier_id'] ?? null;
                    $subscriptionRecord['carrier_pickup_point'] = $subscriptionData['metadata']['carrier_pickup_point'] ?? null;
                }

                // Add delivery notes if available
                if (isset($subscriptionData['metadata']['delivery_notes'])) {
                    $subscriptionRecord['delivery_notes'] = $subscriptionData['metadata']['delivery_notes'];
                }

                // Add coupon info if available
                if (isset($subscriptionData['metadata']['coupon_id'])) {
                    $subscriptionRecord['coupon_id'] = $subscriptionData['metadata']['coupon_id'];
                    $subscriptionRecord['coupon_code'] = $subscriptionData['metadata']['coupon_code'] ?? null;
                    $subscriptionRecord['discount_amount'] = $subscriptionData['metadata']['discount_amount'] ?? 0;
                    $subscriptionRecord['discount_months_total'] = $subscriptionData['metadata']['discount_months_total'] ?? null;
                    $subscriptionRecord['discount_months_remaining'] = $subscriptionData['metadata']['discount_months_remaining'] ?? null;
                }
            }

            \Log::info('Creating subscription record', $subscriptionRecord);
            $subscription = Subscription::create($subscriptionRecord);
            \Log::info('Subscription created successfully', ['id' => $subscription->id]);

            // Create pending shipment for new subscription
            try {
                $shipmentService = app(\App\Services\SubscriptionShipmentService::class);
                $shipmentService->ensurePendingShipmentExists($subscription);
            } catch (\Exception $e) {
                \Log::error('Failed to create pending shipment for new subscription', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
            }

            // Record coupon usage if coupon was applied
            if ($subscription->coupon_id && $subscription->discount_amount > 0) {
                try {
                    $coupon = \App\Models\Coupon::find($subscription->coupon_id);
                    if ($coupon) {
                        $couponService = app(\App\Services\CouponService::class);
                        $couponService->recordUsage(
                            $coupon,
                            $subscription->user,
                            'subscription',
                            $subscription->discount_amount,
                            null,
                            $subscription
                        );

                        \Log::info('Coupon usage recorded in subscription.created webhook', [
                            'coupon_id' => $coupon->id,
                            'subscription_id' => $subscription->id,
                            'discount_amount' => $subscription->discount_amount,
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to record coupon usage in subscription.created webhook', [
                        'subscription_id' => $subscription->id,
                        'coupon_id' => $subscription->coupon_id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Add customer email to newsletter
            try {
                $email = $subscription->shipping_address['email'] ?? null;
                if ($email) {
                    NewsletterSubscriber::firstOrCreate(
                        ['email' => $email],
                        [
                            'source' => 'customer',
                            'user_id' => $subscription->user_id,
                        ]
                    );
                    \Log::info('Added subscription email to newsletter', ['email' => $email]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to add subscription email to newsletter: '.$e->getMessage());
            }

            // Create user account for guest subscriptions
            if (! $userId && $guestEmail) {
                try {
                    $name = $subscription->shipping_address['name'] ?? 'Zákazník';
                    // Get Stripe customer ID from subscription data
                    $stripeCustomerId = $subscriptionData['customer'] ?? null;

                    // Check if user already exists (shouldn't, but just in case)
                    $existingUser = User::where('email', $guestEmail)->first();

                    if (! $existingUser) {
                        $newUser = User::create([
                            'name' => $name,
                            'email' => $guestEmail,
                            'locale' => EmailService::getLocaleFromSubscription($subscription),
                            'password' => \Hash::make(\Str::random(32)), // Random password
                            'password_set_by_user' => false, // User didn't set this password
                            'phone' => $subscription->shipping_address['phone'] ?? null,
                            'address' => $subscription->shipping_address['billing_address'] ?? null,
                            'city' => $subscription->shipping_address['billing_city'] ?? null,
                            'postal_code' => $subscription->shipping_address['billing_postal_code'] ?? null,
                            'packeta_point_id' => $subscription->packeta_point_id ?? null,
                            'packeta_point_name' => $subscription->packeta_point_name ?? null,
                            'packeta_point_address' => $subscription->packeta_point_address ?? null,
                            'stripe_customer_id' => $stripeCustomerId, // Save Stripe customer ID
                        ]);

                        // Link subscription to the new user
                        $subscription->update(['user_id' => $newUser->id]);

                        \Log::info('Created user account for guest subscription', [
                            'user_id' => $newUser->id,
                            'subscription_id' => $subscription->id,
                            'stripe_customer_id' => $stripeCustomerId,
                        ]);
                    } else {
                        // Link subscription to existing user and update stripe_customer_id if missing
                        $subscription->update(['user_id' => $existingUser->id]);

                        if (! $existingUser->stripe_customer_id && $stripeCustomerId) {
                            $existingUser->update(['stripe_customer_id' => $stripeCustomerId]);
                            \Log::info('Updated existing user with Stripe customer ID', [
                                'user_id' => $existingUser->id,
                                'stripe_customer_id' => $stripeCustomerId,
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to create user account for guest subscription: '.$e->getMessage());
                }
            }

            // Send subscription confirmation email
            try {
                // Get email from shipping address or user (support guest subscriptions)
                $email = $subscription->shipping_address['email'] ?? null;
                if (! $email && $subscription->user) {
                    $email = $subscription->user->email;
                }

                if ($email) {
                    \Mail::to($email)->send(new \App\Mail\SubscriptionConfirmation($subscription));
                    \Log::info('Subscription confirmation email sent', [
                        'subscription_id' => $subscription->id,
                        'email' => $email,
                        'is_guest' => $subscription->user_id === null,
                    ]);
                } else {
                    \Log::warning('No email found for subscription confirmation', ['subscription_id' => $subscription->id]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send subscription confirmation email: '.$e->getMessage());
            }

            // Notify admins about new subscription
            try {
                \App\Mail\AdminOrderNotification::notifyAdmins($subscription);
            } catch (\Exception $e) {
                \Log::error('Failed to send admin subscription notification', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
            }

            // Send Meta CAPI Purchase event
            $this->sendMetaCapiForSubscription($subscription);
        } catch (\Exception $e) {
            \Log::error('Failed to create subscription', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $subscriptionRecord ?? [],
            ]);
            throw $e;
        }
    }

    /**
     * Handle custom billing subscription payment (first payment)
     * Creates subscription record in DB without Stripe subscription object
     */
    private function handleCustomBillingSubscriptionPayment(array $session): void
    {
        // Get Stripe customer ID from session (this is the real cus_ ID we created)
        $stripeCustomerId = $session['customer'] ?? null;

        \Log::info('Handling custom billing subscription payment', [
            'session_id' => $session['id'],
            'payment_intent' => $session['payment_intent'],
            'stripe_customer_id' => $stripeCustomerId,
        ]);

        try {
            // Use metadata from session webhook payload first (avoids an extra Stripe API call).
            // Metadata is stored on both session and payment_intent, so the session payload
            // already contains everything we need. Fall back to PaymentIntent::retrieve only
            // if session metadata is missing the required fields.
            $sessionMetadata = $session['metadata'] ?? [];
            $hasRequiredMetadata = ! empty($sessionMetadata['type']) &&
                (! empty($sessionMetadata['user_id']) || ! empty($sessionMetadata['guest_email']));

            if ($hasRequiredMetadata) {
                $metadata = $sessionMetadata;
                $paymentIntentId = $session['payment_intent'];
                $paymentIntentAmount = $session['amount_total'];
                $paymentIntentCurrency = $session['currency'];
            } else {
                // Fallback: fetch from Stripe (legacy sessions or missing metadata)
                $paymentIntent = \Stripe\PaymentIntent::retrieve($session['payment_intent']);
                $metadata = $paymentIntent->metadata->toArray();
                $paymentIntentId = $paymentIntent->id;
                $paymentIntentAmount = $paymentIntent->amount;
                $paymentIntentCurrency = $paymentIntent->currency;
            }

            // Extract subscription data from metadata
            $userId = $metadata['user_id'] ?? null;
            $guestEmail = $metadata['guest_email'] ?? null;
            $configuration = isset($metadata['configuration']) ? json_decode($metadata['configuration'], true) : null;
            $shippingAddress = isset($metadata['shipping_address']) ? json_decode($metadata['shipping_address'], true) : null;

            if (! $userId && ! $guestEmail) {
                throw new \Exception('No user_id or guest_email in payment intent metadata');
            }

            // Check for duplicate (idempotency)
            $existingSubscription = Subscription::where('stripe_payment_intent_id', $paymentIntentId)->first();
            if ($existingSubscription) {
                \Log::info('Subscription already exists for this payment intent', [
                    'subscription_id' => $existingSubscription->id,
                ]);

                return;
            }

            // Check if subscription was pre-created by SubscriptionController
            $preCreatedSubscription = null;
            if (isset($metadata['subscription_id'])) {
                $preCreatedSubscription = Subscription::find($metadata['subscription_id']);
                if ($preCreatedSubscription) {
                    \Log::info('Found pre-created subscription, updating instead of creating new', [
                        'subscription_id' => $preCreatedSubscription->id,
                        'payment_intent' => $paymentIntentId,
                    ]);
                }
            }

            // Build subscription record
            $subscriptionRecord = [
                'subscription_number' => Subscription::generateSubscriptionNumber(),
                'user_id' => $userId,
                'stripe_payment_intent_id' => $paymentIntentId, // Store first payment intent
                'stripe_session_id' => $session['id'],
                'stripe_subscription_id' => null, // No Stripe subscription object!
                'status' => 'active',
                'starts_at' => now(),
                'next_billing_date' => isset($metadata['next_billing_date'])
                    ? \Carbon\Carbon::parse($metadata['next_billing_date'])
                    : now()->addMonth()->setDay(15),
                'frequency_months' => $metadata['frequency_months'] ?? 1,
                'configuration' => $configuration,
                'configured_price' => $metadata['configured_price'] ?? null,
                'currency' => strtoupper($paymentIntentCurrency),
                'shipping_address' => $shippingAddress,
                'meta_event_id' => (string) \Illuminate\Support\Str::uuid(),
                'meta_fbp' => ! empty($metadata['meta_fbp']) ? $metadata['meta_fbp'] : null,
                'meta_fbc' => ! empty($metadata['meta_fbc']) ? $metadata['meta_fbc'] : null,
            ];

            // Add Packeta data if available
            if (isset($metadata['packeta_point_id'])) {
                $subscriptionRecord['packeta_point_id'] = $metadata['packeta_point_id'];
                $subscriptionRecord['packeta_point_name'] = $metadata['packeta_point_name'] ?? null;
                $subscriptionRecord['packeta_point_address'] = $metadata['packeta_point_address'] ?? null;
                $subscriptionRecord['carrier_id'] = $metadata['carrier_id'] ?? null;
                $subscriptionRecord['carrier_pickup_point'] = $metadata['carrier_pickup_point'] ?? null;
            }

            // Add delivery notes if available
            if (isset($metadata['delivery_notes'])) {
                $subscriptionRecord['delivery_notes'] = $metadata['delivery_notes'];
            }

            // Add shipping data if available
            if (isset($metadata['shipping_cost'])) {
                $subscriptionRecord['shipping_cost'] = $metadata['shipping_cost'];
                $subscriptionRecord['shipping_country'] = $metadata['shipping_country'] ?? null;
                $subscriptionRecord['shipping_rate_id'] = $metadata['shipping_rate_id'] ?? null;
            }

            // Add coupon info if available
            if (isset($metadata['coupon_id'])) {
                $subscriptionRecord['coupon_id'] = $metadata['coupon_id'];
                $subscriptionRecord['coupon_code'] = $metadata['coupon_code'] ?? null;
                $subscriptionRecord['discount_amount'] = $metadata['discount_amount'] ?? 0;
                $subscriptionRecord['discount_months_total'] = $metadata['discount_months_total'] ?? null;
                $subscriptionRecord['discount_months_remaining'] = $metadata['discount_months_remaining'] ?? null;
            }

            // Create or update subscription
            if ($preCreatedSubscription) {
                // Update pre-created subscription
                $updateData = [
                    'stripe_payment_intent_id' => $paymentIntentId,
                    'stripe_session_id' => $session['id'],
                    'status' => 'active',
                    'starts_at' => now(),
                ];
                if (! $preCreatedSubscription->meta_event_id) {
                    $updateData['meta_event_id'] = (string) \Illuminate\Support\Str::uuid();
                }
                $preCreatedSubscription->update($updateData);
                $subscription = $preCreatedSubscription;
                \Log::info('Updated pre-created subscription', ['id' => $subscription->id]);
            } else {
                // Create new subscription
                \Log::info('Creating custom billing subscription record', $subscriptionRecord);
                $subscription = Subscription::create($subscriptionRecord);
                \Log::info('Custom billing subscription created successfully', ['id' => $subscription->id]);
            }

            // Record the first payment
            // period_end = nearest billing date (end of period this payment covers)
            // This is different from next_billing_date which is when NEXT payment is due
            $payment = \App\Models\SubscriptionPayment::create([
                'subscription_id' => $subscription->id,
                'stripe_payment_intent_id' => $paymentIntentId,
                'amount' => $paymentIntentAmount / 100, // Convert from cents
                'currency' => $paymentIntentCurrency,
                'status' => 'paid',
                'paid_at' => now(),
                'period_start' => now(),
                'period_end' => \App\Models\ShipmentSchedule::getNextBillingDate(now()),
            ]);

            // Link payment to pending shipment
            try {
                $shipmentService = app(\App\Services\SubscriptionShipmentService::class);
                $shipmentService->linkPaymentToShipment($payment, $subscription);
            } catch (\Exception $e) {
                \Log::error('Failed to link payment to shipment', [
                    'payment_id' => $payment->id,
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
            }

            // Create affiliate reward for first payment (if applicable)
            if ($subscription->coupon_id) {
                try {
                    $coupon = $subscription->coupon;
                    if ($coupon && $coupon->hasAffiliateSubscriptionReward()) {
                        $affiliateService = app(\App\Services\AffiliateService::class);
                        $reward = $affiliateService->createSubscriptionReward($subscription, $coupon, 1);

                        if ($reward) {
                            \Log::info('Affiliate reward created for first subscription payment', [
                                'subscription_id' => $subscription->id,
                                'payment_number' => 1,
                                'reward_id' => $reward->id,
                                'reward_amount' => $reward->reward_amount,
                                'partner_id' => $reward->affiliate_partner_id,
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to create affiliate reward for first subscription payment', [
                        'subscription_id' => $subscription->id,
                        'error' => $e->getMessage(),
                    ]);
                    // Don't fail the webhook if affiliate reward creation fails
                }
            }

            // Create Fakturoid invoice for first payment (BEFORE decrementing discount months,
            // so the invoice reflects the discount that was applied to this payment)
            try {
                $payment = $subscription->payments()->first();
                if ($payment) {
                    $fakturoidService = app(\App\Services\FakturoidService::class);
                    $result = $fakturoidService->processInvoiceForSubscriptionPayment($payment);
                    if (! $result) {
                        \App\Jobs\CreateFakturoidInvoiceJob::dispatch($payment->id)
                            ->delay(now()->addMinutes(5));
                        \Log::warning('Fakturoid invoice creation failed, dispatched retry job', [
                            'subscription_id' => $subscription->id,
                            'payment_id' => $payment->id,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Failed to create Fakturoid invoice for first payment', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
                $payment = $subscription->payments()->first();
                if ($payment) {
                    \App\Jobs\CreateFakturoidInvoiceJob::dispatch($payment->id)
                        ->delay(now()->addMinutes(5));
                }
            }

            // Decrement discount months for first payment (if applicable)
            if ($subscription->discount_months_remaining > 0) {
                $couponService = app(\App\Services\CouponService::class);
                $couponService->decrementSubscriptionDiscountMonth($subscription);

                \Log::info('Decremented discount months after first payment', [
                    'subscription_id' => $subscription->id,
                    'discount_months_remaining' => $subscription->fresh()->discount_months_remaining,
                ]);
            }

            // Record coupon usage if coupon was applied (only for newly created subscriptions)
            if (! $preCreatedSubscription && $subscription->coupon_id && $subscription->discount_amount > 0) {
                try {
                    $coupon = \App\Models\Coupon::find($subscription->coupon_id);
                    if ($coupon) {
                        $couponService = app(\App\Services\CouponService::class);
                        $couponService->recordUsage(
                            $coupon,
                            $subscription->user,
                            'subscription',
                            $subscription->discount_amount,
                            null,
                            $subscription
                        );

                        \Log::info('Coupon usage recorded in webhook', [
                            'coupon_id' => $coupon->id,
                            'subscription_id' => $subscription->id,
                            'discount_amount' => $subscription->discount_amount,
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to record coupon usage in webhook', [
                        'subscription_id' => $subscription->id,
                        'coupon_id' => $subscription->coupon_id,
                        'error' => $e->getMessage(),
                    ]);
                }
            } elseif ($preCreatedSubscription && $subscription->coupon_id) {
                \Log::info('Skipping coupon usage recording for pre-created subscription (already recorded)', [
                    'subscription_id' => $subscription->id,
                    'coupon_id' => $subscription->coupon_id,
                ]);
            }

            // Add customer email to newsletter
            try {
                $email = $shippingAddress['email'] ?? $guestEmail;
                if ($email) {
                    NewsletterSubscriber::firstOrCreate(
                        ['email' => $email],
                        [
                            'source' => 'customer',
                            'user_id' => $subscription->user_id,
                        ]
                    );
                    \Log::info('Added subscription email to newsletter', ['email' => $email]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to add subscription email to newsletter: '.$e->getMessage());
            }

            // Create user account for guest subscriptions
            if (! $userId && $guestEmail) {
                try {
                    $name = $shippingAddress['name'] ?? 'Zákazník';

                    // Check if user already exists
                    $existingUser = User::where('email', $guestEmail)->first();

                    if (! $existingUser) {
                        $newUser = User::create([
                            'name' => $name,
                            'email' => $guestEmail,
                            'locale' => EmailService::getLocaleFromSubscription($subscription),
                            'password' => \Hash::make(\Str::random(32)),
                            'password_set_by_user' => false,
                            'phone' => $shippingAddress['phone'] ?? null,
                            'address' => $shippingAddress['billing_address'] ?? null,
                            'city' => $shippingAddress['billing_city'] ?? null,
                            'postal_code' => $shippingAddress['billing_postal_code'] ?? null,
                            'packeta_point_id' => $subscription->packeta_point_id ?? null,
                            'packeta_point_name' => $subscription->packeta_point_name ?? null,
                            'packeta_point_address' => $subscription->packeta_point_address ?? null,
                            'stripe_customer_id' => $stripeCustomerId, // Save Stripe customer ID from session
                        ]);

                        // Link subscription to the new user
                        $subscription->update(['user_id' => $newUser->id]);

                        \Log::info('Created user account for guest custom billing subscription', [
                            'user_id' => $newUser->id,
                            'subscription_id' => $subscription->id,
                            'stripe_customer_id' => $stripeCustomerId,
                        ]);
                    } else {
                        // Link subscription to existing user and update stripe_customer_id if missing
                        $subscription->update(['user_id' => $existingUser->id]);

                        if (! $existingUser->stripe_customer_id && $stripeCustomerId) {
                            $existingUser->update(['stripe_customer_id' => $stripeCustomerId]);
                            \Log::info('Updated existing user with Stripe customer ID', [
                                'user_id' => $existingUser->id,
                                'stripe_customer_id' => $stripeCustomerId,
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to create user account for guest subscription: '.$e->getMessage());
                }
            }

            // Send subscription confirmation email
            try {
                $email = $shippingAddress['email'] ?? $guestEmail;
                if (! $email && $subscription->user) {
                    $email = $subscription->user->email;
                }

                if ($email) {
                    \Mail::to($email)->send(new \App\Mail\SubscriptionConfirmation($subscription));
                    \Log::info('Custom billing subscription confirmation email sent', [
                        'subscription_id' => $subscription->id,
                        'email' => $email,
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send subscription confirmation email: '.$e->getMessage());
            }

            // Notify admins about new subscription
            try {
                \App\Mail\AdminOrderNotification::notifyAdmins($subscription);
            } catch (\Exception $e) {
                \Log::error('Failed to send admin subscription notification', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
            }

            // Fakturoid invoice already created above (before discount month decrement)

            // Mark this checkout as completed in cache
            // This helps differentiate between new subscription payment methods vs. changed payment methods
            $customerId = $stripeCustomerId; // already available from session['customer']
            if ($customerId) {
                \Cache::put("recent_checkout_{$customerId}", true, now()->addMinutes(3));
                \Log::info('Marked recent checkout for customer', [
                    'customer_id' => $customerId,
                    'subscription_id' => $subscription->id,
                ]);
            }

            // Send Meta CAPI Purchase event
            $this->sendMetaCapiForSubscription($subscription);

        } catch (\Exception $e) {
            \Log::error('Failed to create custom billing subscription', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => $session['id'],
            ]);
            throw $e;
        }
    }

    /**
     * Handle subscription updated webhook
     */
    public function handleSubscriptionUpdated(array $subscriptionData): void
    {
        $subscription = Subscription::where('stripe_subscription_id', $subscriptionData['id'])->first();

        if ($subscription) {
            $stripeStatus = $subscriptionData['status'] ?? null;
            $hasPauseCollection = isset($subscriptionData['pause_collection']) && ! empty($subscriptionData['pause_collection']);
            $hasCancelAtPeriodEnd = isset($subscriptionData['cancel_at_period_end']) && $subscriptionData['cancel_at_period_end'] === true;

            $mappedStatus = match ($stripeStatus) {
                'canceled' => 'cancelled',
                'unpaid' => 'unpaid',
                'past_due' => 'unpaid',
                'active' => 'active',
                default => 'active',
            };

            // If Stripe has pause_collection enabled, we treat it as paused locally
            if ($hasPauseCollection) {
                $mappedStatus = 'paused';
            }

            // If Stripe has cancel_at_period_end enabled, treat as cancelled
            if ($hasCancelAtPeriodEnd) {
                $mappedStatus = 'cancelled';
            }

            // Do not override a locally paused subscription back to active
            if ($subscription->status === 'paused' && $mappedStatus === 'active') {
                $mappedStatus = 'paused';
            }

            // Do not override a locally cancelled subscription back to active
            if ($subscription->status === 'cancelled' && $mappedStatus === 'active') {
                $mappedStatus = 'cancelled';
            }

            $subscription->update([
                'status' => $mappedStatus,
                'next_billing_date' => isset($subscriptionData['current_period_end'])
                    ? \Carbon\Carbon::createFromTimestamp($subscriptionData['current_period_end'])
                    : $subscription->next_billing_date,
                'ends_at' => isset($subscriptionData['canceled_at'])
                    ? \Carbon\Carbon::createFromTimestamp($subscriptionData['canceled_at'])
                    : $subscription->ends_at,
            ]);
        }
    }

    /**
     * Handle subscription deleted webhook
     */
    public function handleSubscriptionDeleted(array $subscriptionData): void
    {
        $subscription = Subscription::where('stripe_subscription_id', $subscriptionData['id'])->first();

        if ($subscription) {
            $subscription->update([
                'status' => 'cancelled',
                'ends_at' => now(),
            ]);
        }
    }

    /**
     * Handle successful invoice payment
     */
    public function handleInvoicePaymentSucceeded(array $invoiceData): void
    {
        $subscriptionId = $invoiceData['subscription'] ?? null;

        if ($subscriptionId) {
            $subscription = Subscription::where('stripe_subscription_id', $subscriptionId)->first();

            if ($subscription) {
                // Update subscription and clear payment failure tracking
                $subscription->update([
                    'status' => 'active',
                    'next_billing_date' => isset($invoiceData['period_end'])
                        ? \Carbon\Carbon::createFromTimestamp($invoiceData['period_end'])
                        : $subscription->next_billing_date,
                    'payment_failure_count' => 0,
                    'last_payment_failure_at' => null,
                    'last_payment_failure_reason' => null,
                    'pending_invoice_id' => null,
                    'pending_invoice_amount' => null,
                ]);

                // Create payment record
                $payment = \App\Models\SubscriptionPayment::create([
                    'subscription_id' => $subscription->id,
                    'stripe_invoice_id' => $invoiceData['id'],
                    'stripe_payment_intent_id' => $invoiceData['payment_intent'] ?? null,
                    'amount' => ($invoiceData['amount_paid'] ?? 0) / 100, // Convert from cents
                    'currency' => $invoiceData['currency'] ?? 'czk',
                    'status' => 'paid',
                    'paid_at' => now(),
                    'period_start' => isset($invoiceData['period_start'])
                        ? \Carbon\Carbon::createFromTimestamp($invoiceData['period_start'])
                        : null,
                    'period_end' => isset($invoiceData['period_end'])
                        ? \Carbon\Carbon::createFromTimestamp($invoiceData['period_end'])
                        : null,
                ]);

                // Link payment to pending shipment
                try {
                    $shipmentService = app(\App\Services\SubscriptionShipmentService::class);
                    $shipmentService->linkPaymentToShipment($payment, $subscription);
                } catch (\Exception $e) {
                    \Log::error('Failed to link payment to shipment (invoice)', [
                        'payment_id' => $payment->id,
                        'subscription_id' => $subscription->id,
                        'error' => $e->getMessage(),
                    ]);
                }

                // Create affiliate reward if applicable
                if ($subscription->coupon_id) {
                    try {
                        $coupon = $subscription->coupon;
                        if ($coupon && $coupon->hasAffiliateSubscriptionReward()) {
                            $affiliateService = app(\App\Services\AffiliateService::class);

                            // Zjisti, kolikátá platba to je (včetně první)
                            $paymentNumber = \App\Models\SubscriptionPayment::where('subscription_id', $subscription->id)
                                ->where('status', 'paid')
                                ->count();

                            $reward = $affiliateService->createSubscriptionReward($subscription, $coupon, $paymentNumber);

                            if ($reward) {
                                \Log::info('Affiliate reward created for subscription payment', [
                                    'subscription_id' => $subscription->id,
                                    'payment_number' => $paymentNumber,
                                    'reward_id' => $reward->id,
                                    'reward_amount' => $reward->reward_amount,
                                    'partner_id' => $reward->affiliate_partner_id,
                                ]);
                            }
                        }
                    } catch (\Exception $e) {
                        \Log::error('Failed to create affiliate reward for subscription', [
                            'subscription_id' => $subscription->id,
                            'error' => $e->getMessage(),
                        ]);
                        // Don't fail the webhook if affiliate reward creation fails
                    }
                }

                // Handle coupon discount countdown
                if ($subscription->coupon_id && $subscription->discount_amount > 0 && $subscription->discount_months_remaining > 0) {
                    $couponService = app(\App\Services\CouponService::class);

                    // Snížit počet zbývajících měsíců slevy
                    $couponService->decrementSubscriptionDiscountMonth($subscription);

                    \Log::info('Decremented discount months after recurring payment', [
                        'subscription_id' => $subscription->id,
                        'payment_id' => $payment->id,
                        'discount_months_remaining' => $subscription->fresh()->discount_months_remaining,
                    ]);

                    // Pokud skončily měsíce se slevou, aktualizovat Stripe subscription s novou cenou
                    if ($subscription->fresh()->discount_months_remaining === 0) {
                        try {
                            $newPrice = $subscription->configured_price ?? $subscription->plan?->price ?? 0;

                            // Use subscription's stored currency
                            $subscriptionCurrency = strtolower($subscription->currency ?? 'CZK');

                            // Získat aktuální Stripe subscription
                            $stripeSubscription = \Stripe\Subscription::retrieve($subscription->stripe_subscription_id);

                            // Vytvořit nový Price objekt s plnou cenou
                            $productId = $this->getOrCreateBaseSubscriptionProduct();
                            $newStripePrice = \Stripe\Price::create([
                                'currency' => $subscriptionCurrency,
                                'product' => $productId,
                                'recurring' => [
                                    'interval' => 'month',
                                    'interval_count' => $subscription->frequency_months ?? 1,
                                ],
                                'unit_amount' => (int) (round($newPrice) * 100),
                            ]);

                            // Aktualizovat subscription s novou cenou
                            \Stripe\Subscription::update($subscription->stripe_subscription_id, [
                                'items' => [
                                    [
                                        'id' => $stripeSubscription->items->data[0]->id,
                                        'price' => $newStripePrice->id,
                                    ],
                                ],
                                'proration_behavior' => 'none', // Bez proration
                            ]);

                            \Log::info('Stripe subscription price updated after coupon expiry', [
                                'subscription_id' => $subscription->id,
                                'old_price' => $newPrice - $subscription->discount_amount,
                                'new_price' => $newPrice,
                            ]);
                        } catch (\Exception $e) {
                            \Log::error('Failed to update Stripe subscription price after coupon expiry', [
                                'subscription_id' => $subscription->id,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }
                }

                // Create invoice in Fakturoid
                try {
                    $fakturoidService = app(\App\Services\FakturoidService::class);
                    $result = $fakturoidService->processInvoiceForSubscriptionPayment($payment);

                    if ($result) {
                        \Log::info('Fakturoid invoice created for subscription payment', [
                            'payment_id' => $payment->id,
                            'subscription_id' => $subscription->id,
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to create Fakturoid invoice for subscription payment', [
                        'payment_id' => $payment->id,
                        'subscription_id' => $subscription->id,
                        'error' => $e->getMessage(),
                    ]);
                    // Don't fail the webhook if Fakturoid fails
                }
            }
        }
    }

    /**
     * Handle failed order payment (one-time payment)
     */
    public function handleOrderPaymentFailed(array $paymentIntentData): void
    {
        $paymentIntentId = $paymentIntentData['id'] ?? null;

        if (! $paymentIntentId) {
            return;
        }

        // Find order by payment intent ID
        $order = Order::where('stripe_payment_intent_id', $paymentIntentId)->first();

        if ($order) {
            // Extract failure reason
            $failureReason = null;
            if (isset($paymentIntentData['last_payment_error'])) {
                $failureReason = $paymentIntentData['last_payment_error']['message'] ??
                                $paymentIntentData['last_payment_error']['code'] ??
                                'Unknown error';
            }

            // Update order with failure information
            $order->update([
                'payment_status' => 'unpaid',
                'payment_failure_count' => $order->payment_failure_count + 1,
                'last_payment_failure_at' => now(),
                'last_payment_failure_reason' => $failureReason,
                'pending_payment_intent_id' => $paymentIntentId,
            ]);

            \Log::warning('Order payment failed', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'payment_intent_id' => $paymentIntentId,
                'failure_count' => $order->payment_failure_count,
                'reason' => $failureReason,
            ]);

            // Send notification email to customer
            try {
                $email = $order->shipping_address['email'] ?? $order->user?->email;

                if ($email) {
                    \Mail::to($email)->send(new \App\Mail\OrderPaymentFailed($order, $failureReason));
                    \Log::info('Order payment failure email sent', [
                        'order_id' => $order->id,
                        'email' => $email,
                    ]);
                } else {
                    \Log::warning('No email found for order payment failure notification', [
                        'order_id' => $order->id,
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send order payment failure email', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Handle failed invoice payment
     */
    public function handleInvoicePaymentFailed(array $invoiceData): void
    {
        $subscriptionId = $invoiceData['subscription'] ?? null;

        if ($subscriptionId) {
            $subscription = Subscription::where('stripe_subscription_id', $subscriptionId)->first();

            if ($subscription) {
                // Extract failure reason
                $failureReason = null;
                if (isset($invoiceData['last_payment_error'])) {
                    $failureReason = $invoiceData['last_payment_error']['message'] ??
                                    $invoiceData['last_payment_error']['code'] ??
                                    'Unknown error';
                }

                // Update subscription with failure information
                $subscription->update([
                    'status' => 'unpaid',
                    'payment_failure_count' => $subscription->payment_failure_count + 1,
                    'last_payment_failure_at' => now(),
                    'last_payment_failure_reason' => $failureReason,
                    'pending_invoice_id' => $invoiceData['id'] ?? null,
                    'pending_invoice_amount' => isset($invoiceData['amount_due']) ? ($invoiceData['amount_due'] / 100) : null,
                ]);

                \Log::warning('Invoice payment failed for subscription', [
                    'subscription_id' => $subscription->id,
                    'stripe_subscription_id' => $subscriptionId,
                    'invoice_id' => $invoiceData['id'] ?? null,
                    'failure_count' => $subscription->payment_failure_count,
                    'reason' => $failureReason,
                ]);

                // Send notification email to customer
                try {
                    $email = $subscription->shipping_address['email'] ?? $subscription->user?->email;

                    if ($email) {
                        \Mail::to($email)->send(new \App\Mail\SubscriptionPaymentFailed($subscription, $failureReason));
                        \Log::info('Payment failure email sent', [
                            'subscription_id' => $subscription->id,
                            'email' => $email,
                        ]);
                    } else {
                        \Log::warning('No email found for payment failure notification', [
                            'subscription_id' => $subscription->id,
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to send payment failure email', [
                        'subscription_id' => $subscription->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
    }

    /**
     * Create checkout session for manual order payment
     */
    public function createOrderPaymentSession(Order $order): ?string
    {
        if ($order->payment_status === 'paid') {
            \Log::info('Order already paid', [
                'order_id' => $order->id,
            ]);

            return null;
        }

        try {
            // Create a checkout session for the order
            $session = $this->createOrderCheckoutSession($order);

            \Log::info('Manual order payment checkout session created', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'session_id' => $session->id,
                'amount' => $order->total,
            ]);

            return $session->url;
        } catch (\Exception $e) {
            \Log::error('Failed to create order payment session', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Create checkout session for manual invoice payment
     */
    public function createInvoicePaymentSession(Subscription $subscription): ?string
    {
        if (! $subscription->pending_invoice_amount) {
            \Log::warning('No pending invoice amount for subscription', [
                'subscription_id' => $subscription->id,
            ]);

            return null;
        }

        try {
            $customerId = $this->getOrCreateCustomer($subscription->user);

            // Check if we have a real Stripe invoice (not a test one)
            $hasRealInvoice = $subscription->pending_invoice_id &&
                             ! str_starts_with($subscription->pending_invoice_id, 'in_test_');

            if ($hasRealInvoice) {
                // Try to retrieve the invoice from Stripe
                try {
                    $invoice = \Stripe\Invoice::retrieve($subscription->pending_invoice_id);

                    // Check if invoice is still unpaid
                    if ($invoice->status === 'paid') {
                        \Log::info('Invoice already paid', [
                            'invoice_id' => $subscription->pending_invoice_id,
                            'subscription_id' => $subscription->id,
                        ]);

                        // Clear the unpaid status
                        $subscription->update([
                            'status' => 'active',
                            'payment_failure_count' => 0,
                            'pending_invoice_id' => null,
                            'pending_invoice_amount' => null,
                        ]);

                        return null;
                    }

                    $currency = $invoice->currency;
                    $amount = $invoice->amount_due;
                } catch (\Exception $e) {
                    \Log::warning('Could not retrieve invoice from Stripe, creating generic payment', [
                        'invoice_id' => $subscription->pending_invoice_id,
                        'error' => $e->getMessage(),
                    ]);
                    $hasRealInvoice = false;
                }
            }

            // If no real invoice, create a generic one-time payment
            if (! $hasRealInvoice) {
                $currency = strtolower($subscription->currency ?? 'CZK');
                $amount = (int) (round($subscription->pending_invoice_amount) * 100); // Convert to cents
            }

            // Create a checkout session for payment
            $session = StripeSession::create([
                'customer' => $customerId,
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => $currency,
                        'product_data' => [
                            'name' => (CurrencyHelper::isCzk() ? 'Platba předplatného - ' : 'Subscription payment - ').($subscription->subscription_number ?? '#'.$subscription->id),
                            'description' => CurrencyHelper::isCzk() ? 'Neuhrazená platba za kávové předplatné' : 'Unpaid subscription payment',
                        ],
                        'unit_amount' => $amount,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('dashboard.subscription').'?payment=success',
                'cancel_url' => route('dashboard.subscription').'?payment=cancelled',
                'metadata' => [
                    'subscription_id' => $subscription->id,
                    'invoice_id' => $subscription->pending_invoice_id ?? 'manual_payment',
                    'type' => 'manual_invoice_payment',
                ],
            ]);

            \Log::info('Manual payment checkout session created', [
                'subscription_id' => $subscription->id,
                'invoice_id' => $subscription->pending_invoice_id ?? 'manual_payment',
                'session_id' => $session->id,
                'amount' => $amount / 100,
            ]);

            return $session->url;
        } catch (\Exception $e) {
            \Log::error('Failed to create invoice payment session', [
                'subscription_id' => $subscription->id,
                'invoice_id' => $subscription->pending_invoice_id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Cancel subscription in Stripe (at period end)
     */
    public function cancelSubscription(Subscription $subscription): void
    {
        if (! $subscription->stripe_subscription_id) {
            return;
        }

        try {
            StripeSubscription::update($subscription->stripe_subscription_id, [
                'cancel_at_period_end' => true,
            ]);
        } catch (\Exception $e) {
            \Log::error('Stripe cancelSubscription failed', [
                'stripe_subscription_id' => $subscription->stripe_subscription_id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Pause subscription billing in Stripe
     */
    public function pauseSubscription(Subscription $subscription): void
    {
        if (! $subscription->stripe_subscription_id) {
            return;
        }

        try {
            StripeSubscription::update($subscription->stripe_subscription_id, [
                'pause_collection' => [
                    // don't invoice during pause
                    'behavior' => 'void',
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Stripe pauseSubscription failed', [
                'stripe_subscription_id' => $subscription->stripe_subscription_id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Resume subscription billing in Stripe
     */
    public function resumeSubscription(Subscription $subscription): void
    {
        if (! $subscription->stripe_subscription_id) {
            return;
        }

        try {
            StripeSubscription::update($subscription->stripe_subscription_id, [
                'pause_collection' => '', // clear pause
            ]);
        } catch (\Exception $e) {
            // Some Stripe libraries require null instead of empty string
            try {
                StripeSubscription::update($subscription->stripe_subscription_id, [
                    'pause_collection' => null,
                ]);
            } catch (\Exception $e2) {
                \Log::error('Stripe resumeSubscription failed', [
                    'stripe_subscription_id' => $subscription->stripe_subscription_id,
                    'error' => $e2->getMessage(),
                ]);
                throw $e2;
            }
        }
    }

    /**
     * Get detailed payment method information for a customer
     */
    public function getPaymentMethodDetails(string $customerId): ?array
    {
        try {
            $customer = StripeCustomer::retrieve($customerId);

            // Ošetři případ kdy customer neexistuje nebo je smazaný
            if (! $customer || isset($customer->deleted)) {
                \Log::warning('Customer not found or deleted in getPaymentMethodDetails', [
                    'customer_id' => $customerId,
                ]);

                return null;
            }

            // Bezpečný přístup k invoice_settings a default_payment_method
            $paymentMethodId = $customer->invoice_settings->default_payment_method ?? null;

            // If no default, try to get the first payment method
            if (! $paymentMethodId) {
                $paymentMethods = \Stripe\PaymentMethod::all([
                    'customer' => $customerId,
                    'type' => 'card',
                    'limit' => 1,
                ]);

                if (count($paymentMethods->data) > 0) {
                    $paymentMethodId = $paymentMethods->data[0]->id;
                }
            }

            if (! $paymentMethodId) {
                return null;
            }

            // Retrieve full payment method details
            $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentMethodId);

            if ($paymentMethod->type === 'card') {
                return [
                    'id' => $paymentMethod->id,
                    'type' => 'card',
                    'brand' => $paymentMethod->card->brand, // visa, mastercard, etc.
                    'last4' => $paymentMethod->card->last4,
                    'exp_month' => $paymentMethod->card->exp_month,
                    'exp_year' => $paymentMethod->card->exp_year,
                ];
            }

            return null;
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Zákazník neexistuje
            \Log::warning('Customer not found in getPaymentMethodDetails', [
                'customer_id' => $customerId,
                'error' => $e->getMessage(),
            ]);

            return null;
        } catch (\Exception $e) {
            \Log::error('Failed to get payment method details', [
                'customer_id' => $customerId,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Create Stripe Customer Portal session for managing payment methods
     */
    public function createCustomerPortalSession(User $user, string $returnUrl): string
    {
        $customerId = $this->getOrCreateCustomer($user);

        try {
            $session = \Stripe\BillingPortal\Session::create([
                'customer' => $customerId,
                'return_url' => $returnUrl,
            ]);

            return $session->url;
        } catch (\Exception $e) {
            \Log::error('Failed to create Customer Portal session', [
                'user_id' => $user->id,
                'customer_id' => $customerId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Handle payment method attached event (modern Stripe API)
     */
    public function handlePaymentMethodAttached(array $paymentMethodData): void
    {
        try {
            $customerId = $paymentMethodData['customer'] ?? null;
            $paymentMethodId = $paymentMethodData['id'] ?? null;

            if (! $customerId || ! $paymentMethodId) {
                \Log::warning('Payment method attached event missing required data', [
                    'customer_id' => $customerId,
                    'payment_method_id' => $paymentMethodId,
                ]);

                return;
            }

            // Find user by Stripe customer ID
            $user = User::where('stripe_customer_id', $customerId)->first();

            if (! $user) {
                \Log::warning('User not found for payment method attached event', [
                    'customer_id' => $customerId,
                ]);

                return;
            }

            // Check if this payment method is from a recent checkout (last 3 minutes)
            // If yes, don't send "payment method changed" email - user will get subscription confirmation instead
            $recentCheckout = \Cache::get("recent_checkout_{$customerId}");
            if ($recentCheckout) {
                \Log::info('Skipping payment method changed email - recent checkout detected', [
                    'user_id' => $user->id,
                    'customer_id' => $customerId,
                    'payment_method_id' => $paymentMethodId,
                ]);

                return;
            }

            // Also check if this is the first payment method for this customer
            // (for backwards compatibility with older subscriptions)
            try {
                $paymentMethods = \Stripe\PaymentMethod::all([
                    'customer' => $customerId,
                    'type' => 'card',
                    'limit' => 10,
                ]);

                $activePaymentMethodCount = count($paymentMethods->data);

                // If this is the first or only payment method, skip the notification
                if ($activePaymentMethodCount <= 1) {
                    \Log::info('Skipping payment method changed email - first payment method for customer', [
                        'user_id' => $user->id,
                        'customer_id' => $customerId,
                        'payment_method_id' => $paymentMethodId,
                    ]);

                    return;
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to check payment method count, proceeding with notification', [
                    'error' => $e->getMessage(),
                ]);
            }

            // Get payment method details from Stripe
            $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentMethodId);

            if ($paymentMethod->type === 'card') {
                $cardBrand = ucfirst($paymentMethod->card->brand);
                $cardLast4 = $paymentMethod->card->last4;

                // DISABLED: Email notification temporarily disabled
                // TODO: Re-enable when we have better detection of payment method source (checkout vs. manual change)
                /*
                try {
                    \Mail::to($user->email)->send(new \App\Mail\PaymentMethodChanged(
                        $user,
                        $cardLast4,
                        $cardBrand
                    ));

                    \Log::info('Payment method changed email sent', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'payment_method_id' => $paymentMethodId,
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to send payment method changed email', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                    ]);
                }
                */

                \Log::info('Payment method attached processed (email disabled)', [
                    'user_id' => $user->id,
                    'payment_method_id' => $paymentMethodId,
                    'card_brand' => $cardBrand,
                    'card_last4' => $cardLast4,
                ]);
            }

            \Log::info('Payment method attached processed', [
                'user_id' => $user->id,
                'customer_id' => $customerId,
                'payment_method_id' => $paymentMethodId,
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to process payment method attached event', [
                'error' => $e->getMessage(),
                'data' => $paymentMethodData,
            ]);
        }
    }

    /**
     * Handle payment method/source updated event (legacy Stripe API)
     */
    public function handlePaymentMethodUpdated(array $sourceData): void
    {
        try {
            $customerId = $sourceData['customer'] ?? null;

            if (! $customerId) {
                \Log::warning('Payment method updated event missing customer ID');

                return;
            }

            // Find user by Stripe customer ID
            $user = User::where('stripe_customer_id', $customerId)->first();

            if (! $user) {
                \Log::warning('User not found for payment method updated event', [
                    'customer_id' => $customerId,
                ]);

                return;
            }

            // Check if this payment method is from a recent checkout (last 3 minutes)
            // If yes, don't send "payment method changed" email - user will get subscription confirmation instead
            $recentCheckout = \Cache::get("recent_checkout_{$customerId}");
            if ($recentCheckout) {
                \Log::info('Skipping payment method changed email (legacy) - recent checkout detected', [
                    'user_id' => $user->id,
                    'customer_id' => $customerId,
                ]);

                return;
            }

            // Also check if this is the first payment method for this customer
            // (for backwards compatibility with older subscriptions)
            try {
                $paymentMethods = \Stripe\PaymentMethod::all([
                    'customer' => $customerId,
                    'type' => 'card',
                    'limit' => 10,
                ]);

                $activePaymentMethodCount = count($paymentMethods->data);

                // If this is the first or only payment method, skip the notification
                if ($activePaymentMethodCount <= 1) {
                    \Log::info('Skipping payment method changed email (legacy) - first payment method for customer', [
                        'user_id' => $user->id,
                        'customer_id' => $customerId,
                    ]);

                    return;
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to check payment method count (legacy), proceeding with notification', [
                    'error' => $e->getMessage(),
                ]);
            }

            // Get current payment method details
            $paymentMethodDetails = $this->getPaymentMethodDetails($customerId);

            if ($paymentMethodDetails && $paymentMethodDetails['type'] === 'card') {
                $cardBrand = ucfirst($paymentMethodDetails['brand']);
                $cardLast4 = $paymentMethodDetails['last4'];

                // DISABLED: Email notification temporarily disabled
                // TODO: Re-enable when we have better detection of payment method source (checkout vs. manual change)
                /*
                try {
                    \Mail::to($user->email)->send(new \App\Mail\PaymentMethodChanged(
                        $user,
                        $cardLast4,
                        $cardBrand
                    ));

                    \Log::info('Payment method changed email sent (legacy event)', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to send payment method changed email (legacy)', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                    ]);
                }
                */

                \Log::info('Payment method updated processed (legacy, email disabled)', [
                    'user_id' => $user->id,
                    'card_brand' => $cardBrand,
                    'card_last4' => $cardLast4,
                ]);
            }

            \Log::info('Payment method updated processed (legacy)', [
                'user_id' => $user->id,
                'customer_id' => $customerId,
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to process payment method updated event', [
                'error' => $e->getMessage(),
                'data' => $sourceData,
            ]);
        }
    }

    /**
     * Charge subscription payment using saved payment method
     * This is the core method for custom billing cycle management
     *
     * @param  Subscription  $subscription  The subscription to charge
     * @return array ['success' => bool, 'payment_intent_id' => string|null, 'error' => string|null]
     */
    public function chargeSubscriptionPayment(Subscription $subscription): array
    {
        // Idempotency check - has this been charged today already?
        if ($subscription->payments()->whereDate('paid_at', today())->exists()) {
            \Log::warning('Subscription already charged today - skipping', [
                'subscription_id' => $subscription->id,
                'subscription_number' => $subscription->subscription_number,
            ]);

            return [
                'success' => false,
                'payment_intent_id' => null,
                'error' => 'already_charged_today',
            ];
        }

        try {
            // Get user and customer
            $user = $subscription->user;
            if (! $user) {
                throw new \Exception('Subscription has no user');
            }

            $customerId = $user->stripe_customer_id;
            if (! $customerId) {
                throw new \Exception('User has no Stripe customer ID');
            }

            // Get saved payment method
            $paymentMethodId = $this->getCustomerDefaultPaymentMethod($customerId);
            if (! $paymentMethodId) {
                throw new \Exception('No payment method found for customer');
            }

            // Calculate amount (with coupon discount if applicable)
            $amount = $this->calculatePendingAmount($subscription);

            // Use subscription's stored currency (not session currency)
            $subscriptionCurrency = strtolower($subscription->currency ?? 'CZK');
            $isEur = $subscriptionCurrency === 'eur';

            \Log::info('Attempting to charge subscription', [
                'subscription_id' => $subscription->id,
                'subscription_number' => $subscription->subscription_number,
                'customer_id' => $customerId,
                'payment_method_id' => $paymentMethodId,
                'amount' => $amount,
                'currency' => $subscriptionCurrency,
            ]);

            // Create and confirm payment intent
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => (int) (round($amount) * 100), // Convert to cents
                'currency' => $subscriptionCurrency,
                'customer' => $customerId,
                'payment_method' => $paymentMethodId,
                'confirm' => true,
                'off_session' => true, // Important: tells Stripe this is automated
                'description' => ($isEur ? 'Coffee subscription - ' : 'Kávové předplatné - ').($subscription->subscription_number ?? '#'.$subscription->id),
                'metadata' => [
                    'subscription_id' => $subscription->id,
                    'subscription_number' => $subscription->subscription_number ?? '',
                    'billing_date' => now()->toDateString(),
                ],
            ]);

            // Check if payment succeeded
            if ($paymentIntent->status === 'succeeded') {
                // Catch-up: pokud je next_billing_date v minulosti (po stuck pauze,
                // manuálním zásahu, --subscription override), advancuj period_end po
                // frequency-měsíčních skocích, dokud není >= today. Bez toho by se
                // stale datum uložilo do paymentu, expected_shipment_date by ukazoval
                // na zaniklý měsíc a linkPaymentToShipment by vytvořil duplicitní
                // shipment místo trefení existujícího pending řádku.
                $frequencyMonths = $subscription->frequency_months ?? 1;
                $periodEnd = $subscription->next_billing_date?->copy();

                if ($periodEnd) {
                    $safetyLimit = 24;
                    while ($periodEnd->lt(today()) && $safetyLimit-- > 0) {
                        $periodEnd->addMonths($frequencyMonths);
                    }
                }

                $periodStart = $periodEnd?->copy()->subMonths($frequencyMonths);

                // Record successful payment
                $payment = \App\Models\SubscriptionPayment::create([
                    'subscription_id' => $subscription->id,
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'amount' => $amount,
                    'currency' => $subscriptionCurrency,
                    'status' => 'paid',
                    'paid_at' => now(),
                    'period_start' => $periodStart,
                    'period_end' => $periodEnd,
                ]);

                // Update subscription
                $nextBillingDate = $this->calculateNextBillingDate($periodEnd, $frequencyMonths);

                $updates = [
                    'next_billing_date' => $nextBillingDate,
                    'payment_failure_count' => 0,
                    // Úspěšná platba ruší sérii neuhrazených rozesílek.
                    'consecutive_unpaid_shipments' => 0,
                    'last_payment_failure_at' => null,
                    'last_payment_failure_reason' => null,
                    'pending_invoice_id' => null,
                    'pending_invoice_amount' => null,
                ];

                // Úspěšný retry během 'unpaid' fáze → návrat na 'active'.
                // Pro 'paused' self-heal se status flipne dřív v ChargeSubscriptionPayments,
                // ostatní stavy (complimentary atd.) nesahat.
                if ($subscription->status === 'unpaid') {
                    $updates['status'] = 'active';
                }

                $subscription->update($updates);

                // Handle coupon countdown
                if ($subscription->coupon_id && $subscription->discount_amount > 0) {
                    app(\App\Services\CouponService::class)->decrementSubscriptionDiscountMonth($subscription);
                }

                // Link payment to pending shipment
                try {
                    $shipmentService = app(\App\Services\SubscriptionShipmentService::class);
                    $shipmentService->linkPaymentToShipment($payment, $subscription);
                } catch (\Exception $e) {
                    \Log::error('Failed to link payment to shipment', [
                        'payment_id' => $payment->id,
                        'subscription_id' => $subscription->id,
                        'error' => $e->getMessage(),
                    ]);
                }

                // Create affiliate reward if applicable
                if ($subscription->coupon_id) {
                    try {
                        $coupon = $subscription->coupon;
                        if ($coupon && $coupon->hasAffiliateSubscriptionReward()) {
                            $affiliateService = app(\App\Services\AffiliateService::class);

                            // Zjisti, kolikátá platba to je (včetně právě vytvořené)
                            $paymentNumber = \App\Models\SubscriptionPayment::where('subscription_id', $subscription->id)
                                ->where('status', 'paid')
                                ->count();

                            $reward = $affiliateService->createSubscriptionReward($subscription, $coupon, $paymentNumber);

                            if ($reward) {
                                \Log::info('Affiliate reward created for subscription payment', [
                                    'subscription_id' => $subscription->id,
                                    'payment_number' => $paymentNumber,
                                    'reward_id' => $reward->id,
                                    'reward_amount' => $reward->reward_amount,
                                    'partner_id' => $reward->affiliate_partner_id,
                                ]);
                            }
                        }
                    } catch (\Exception $e) {
                        \Log::error('Failed to create affiliate reward for subscription payment', [
                            'subscription_id' => $subscription->id,
                            'error' => $e->getMessage(),
                        ]);
                        // Don't fail the whole payment if affiliate reward creation fails
                    }
                }

                // Create Fakturoid invoice (skip for complimentary subscriptions)
                if (! $subscription->isComplimentary()) {
                    try {
                        $fakturoidService = app(\App\Services\FakturoidService::class);
                        $fakturoidService->processInvoiceForSubscriptionPayment($payment);
                    } catch (\Exception $e) {
                        \Log::error('Failed to create Fakturoid invoice', [
                            'payment_id' => $payment->id,
                            'error' => $e->getMessage(),
                        ]);
                        // Don't fail the whole payment if Fakturoid fails
                    }
                } else {
                    \Log::info('Skipping Fakturoid invoice for complimentary subscription', [
                        'subscription_id' => $subscription->id,
                        'payment_id' => $payment->id,
                    ]);
                }

                // Send success confirmation email
                // Using \Throwable to catch both Exception and Error (e.g., Fatal errors in Mailable)
                try {
                    $email = $subscription->shipping_address['email'] ?? $subscription->user?->email;
                    if ($email) {
                        retry(3, function () use ($email, $subscription, $payment) {
                            \Mail::to($email)->send(new \App\Mail\SubscriptionPaymentSuccess($subscription, $payment));
                        }, 5000);
                        \Log::info('Subscription payment confirmation email sent', [
                            'subscription_id' => $subscription->id,
                            'payment_id' => $payment->id,
                            'email' => $email,
                        ]);
                    }
                } catch (\Throwable $e) {
                    \Log::error('Failed to send payment confirmation email', [
                        'subscription_id' => $subscription->id,
                        'payment_id' => $payment->id,
                        'error' => $e->getMessage(),
                        'type' => get_class($e),
                    ]);
                    // Don't fail the whole payment if email fails
                }

                \Log::info('Subscription payment successful', [
                    'subscription_id' => $subscription->id,
                    'payment_id' => $payment->id,
                    'payment_intent_id' => $paymentIntent->id,
                    'next_billing_date' => $nextBillingDate->toDateString(),
                ]);

                return [
                    'success' => true,
                    'payment_intent_id' => $paymentIntent->id,
                    'error' => null,
                ];
            } else {
                // Payment requires action (3D Secure, etc.)
                throw new \Exception('Payment requires additional action: '.$paymentIntent->status);
            }

        } catch (\Stripe\Exception\CardException $e) {
            // Card was declined
            $errorMessage = $e->getMessage();

            \Log::error('Subscription payment card declined', [
                'subscription_id' => $subscription->id,
                'error' => $errorMessage,
            ]);

            return [
                'success' => false,
                'payment_intent_id' => null,
                'error' => $errorMessage,
                'requires_failure_handling' => true,
            ];

        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Network error — DON'T mark subscription as failed (transient issue)
            $errorMessage = $e->getMessage();

            \Log::error('Subscription payment network error (not marking as failed)', [
                'subscription_id' => $subscription->id,
                'error' => $errorMessage,
            ]);

            return [
                'success' => false,
                'payment_intent_id' => null,
                'error' => $errorMessage,
                'network_error' => true,
            ];

        } catch (\Exception $e) {
            // Other error
            $errorMessage = $e->getMessage();

            \Log::error('Subscription payment failed', [
                'subscription_id' => $subscription->id,
                'error' => $errorMessage,
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'payment_intent_id' => null,
                'error' => $errorMessage,
                'requires_failure_handling' => true,
            ];
        }
    }

    /**
     * Handle subscription payment failure
     */
    public function handleSubscriptionPaymentFailure(Subscription $subscription, string $errorMessage): void
    {
        $failureCount = $subscription->payment_failure_count + 1;

        $subscription->update([
            'status' => 'unpaid',
            'payment_failure_count' => $failureCount,
            'last_payment_failure_at' => now(),
            'last_payment_failure_reason' => $errorMessage,
            'pending_invoice_id' => 'manual_'.($subscription->subscription_number ?? $subscription->id),
            'pending_invoice_amount' => $this->calculatePendingAmount($subscription),
        ]);

        // Send failure email
        try {
            $email = $subscription->shipping_address['email'] ?? $subscription->user?->email;
            if ($email) {
                \Mail::to($email)->send(new \App\Mail\SubscriptionPaymentFailed($subscription, $errorMessage));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send payment failure email', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Po 3. (a každém dalším) failure předplatné "vynechá" měsíc jako
        // při běžné uživatelské pauze: pauseSubscription označí aktuální zásilku
        // jako skipped, vytvoří post-pause pending řádek a nastaví paused_until_date.
        // Příští měsíc převezme cron subscriptions:resume-paused a billing cron
        // zkusí naúčtovat znovu. Bez tohohle by status='paused' + NULL
        // paused_until_date byl zombie, kterého žádný cron nezachytí.
        if ($failureCount >= 3) {
            // Remindery pro TUTO rozesílku jsou vyčerpány → rozesílka se přeskočí.
            // payment_failure_count resetujeme, aby příští rozesílka začínala načisto:
            // dřív byl čítač kumulativní za celý život předplatného (nulovala ho jen
            // úspěšná platba), takže staré předplatné překročilo práh hned prvním
            // selháním a o 3 remindery přišlo.
            $consecutiveUnpaid = $subscription->consecutive_unpaid_shipments + 1;

            $subscription->update([
                'payment_failure_count' => 0,
                'consecutive_unpaid_shipments' => $consecutiveUnpaid,
            ]);

            // 3 neuhrazené rozesílky za sebou → předplatné zrušit.
            if ($consecutiveUnpaid >= 3) {
                $this->cancelSubscriptionForNonPayment($subscription, $errorMessage);

                return;
            }

            try {
                app(\App\Services\SubscriptionShipmentService::class)
                    ->pauseSubscription($subscription, 1, 'payment_failure_skip');

                \Log::warning('Subscription paused for one cycle after 3 payment failures', [
                    'subscription_id' => $subscription->id,
                    'subscription_number' => $subscription->subscription_number,
                    'consecutive_unpaid_shipments' => $consecutiveUnpaid,
                ]);
            } catch (\Exception $e) {
                // Fallback — pokud pauseSubscription selže (např. stock check),
                // alespoň přepneme status, ať předplatné nejedeme dál v retry smyčce.
                $subscription->update(['status' => 'paused']);
                \Log::error('pauseSubscription failed during 3-failure handler — falling back to bare paused status', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Zrušení předplatného po 3 neuhrazených rozesílkách za sebou.
     *
     * Nepoužívá Subscription::cancel() — ten dopočítává "paid coverage" a posunul by
     * ends_at na poslední zaplacenou rozesílku. Tady rušíme okamžitě.
     */
    private function cancelSubscriptionForNonPayment(Subscription $subscription, string $errorMessage): void
    {
        $subscription->update([
            'status' => 'cancelled',
            'ends_at' => now(),
            'paused_iterations' => null,
            'paused_until_date' => null,
            'pause_reason' => null,
            'cancellation_reason' => 'payment_failure_auto',
            // Zrušené předplatné nedluží — rozesílky se přeskočily, není za co vybírat.
            'pending_invoice_id' => null,
            'pending_invoice_amount' => null,
        ]);

        \Log::warning('Subscription auto-cancelled after 3 consecutive unpaid shipments', [
            'subscription_id' => $subscription->id,
            'subscription_number' => $subscription->subscription_number,
            'last_error' => $errorMessage,
        ]);

        try {
            $email = $subscription->shipping_address['email'] ?? $subscription->user?->email;
            if ($email) {
                \Mail::to($email)->send(new \App\Mail\SubscriptionCancelled($subscription));
            }
        } catch (\Throwable $e) {
            \Log::error('Failed to send auto-cancel email', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);
        }

        try {
            $adminEmail = config('mail.from.address');
            if ($adminEmail) {
                \Mail::raw(
                    'Předplatné '.($subscription->subscription_number ?? '#'.$subscription->id).
                    " bylo automaticky zrušeno po 3 neuhrazených rozesílkách za sebou.\n\n".
                    'Poslední chyba: '.$errorMessage,
                    function ($message) use ($adminEmail, $subscription) {
                        $message->to($adminEmail)
                            ->subject('Automatické zrušení předplatného (neplacení) - '.($subscription->subscription_number ?? '#'.$subscription->id));
                    }
                );
            }
        } catch (\Throwable $e) {
            \Log::error('Failed to send admin auto-cancel alert', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Calculate the pending payment amount for a subscription (with coupon discount if applicable)
     */
    private function calculatePendingAmount(Subscription $subscription): float
    {
        $amount = $subscription->configured_price ?? $subscription->plan?->price ?? 0;
        if ($subscription->discount_amount > 0 && ($subscription->discount_months_remaining === null || $subscription->discount_months_remaining > 0)) {
            $amount -= $subscription->discount_amount;
        }

        return $amount;
    }

    /**
     * Calculate next billing date based on current date and frequency
     * Uses ShipmentSchedule billing_date from admin konfigurator
     *
     * This implements "Varianta A" (Frozen next_billing_date):
     * - Current subscription's next_billing_date stays unchanged
     * - NEXT billing date (after payment) uses CURRENT ShipmentSchedule
     * - Allows admin to change future billing dates without affecting already planned payments
     */
    private function calculateNextBillingDate(\Carbon\Carbon $currentBillingDate, int $frequencyMonths): \Carbon\Carbon
    {
        $nextBillingDate = \App\Models\ShipmentSchedule::getBillingDateAfterMonths($currentBillingDate, $frequencyMonths);

        \Log::info('Calculated next billing date from ShipmentSchedule', [
            'current_billing_date' => $currentBillingDate->toDateString(),
            'frequency_months' => $frequencyMonths,
            'next_billing_date' => $nextBillingDate->toDateString(),
        ]);

        return $nextBillingDate;
    }

    /**
     * Send Meta CAPI Purchase event for an order.
     */
    private function sendMetaCapiForOrder(Order $order): void
    {
        try {
            $region = \App\Helpers\MetaPixelHelper::regionFromCurrency($order->currency);
            $metaService = MetaConversionsService::forRegion($region);
            if (! $metaService->isConfigured() || $order->meta_capi_sent_at) {
                return;
            }

            $address = $order->shipping_address ?? [];
            $user = $order->user;
            $nameParts = explode(' ', $address['name'] ?? '', 2);

            $success = $metaService->sendPurchaseEvent(
                eventId: $order->meta_event_id,
                value: (float) $order->total,
                currency: $order->currency ?? 'CZK',
                contentIds: $order->items->pluck('product_id')->toArray(),
                contentType: 'product',
                userData: [
                    'email' => $address['email'] ?? $user?->email,
                    'phone' => $address['phone'] ?? $user?->phone,
                    'firstName' => $nameParts[0] ?? null,
                    'lastName' => $nameParts[1] ?? null,
                    'city' => $address['billing_city'] ?? null,
                    'postalCode' => $address['billing_postal_code'] ?? null,
                    'country' => $address['billing_country'] ?? null,
                    'externalId' => $user?->id,
                ],
                fbp: $order->meta_fbp,
                fbc: $order->meta_fbc,
                sourceUrl: route('order.confirmation', $order),
            );

            if ($success) {
                $order->update(['meta_capi_sent_at' => now()]);
            }
        } catch (\Exception $e) {
            \Log::error('Meta CAPI failed for order', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send Meta CAPI Purchase event for a subscription.
     */
    private function sendMetaCapiForSubscription(Subscription $subscription): void
    {
        try {
            $region = \App\Helpers\MetaPixelHelper::regionFromCurrency($subscription->currency);
            $metaService = MetaConversionsService::forRegion($region);
            if (! $metaService->isConfigured() || $subscription->meta_capi_sent_at) {
                return;
            }

            $address = $subscription->shipping_address ?? [];
            $user = $subscription->user;
            $nameParts = explode(' ', $address['name'] ?? '', 2);
            $config = is_array($subscription->configuration) ? $subscription->configuration : json_decode($subscription->configuration, true);
            $amount = $config['amount'] ?? 3;
            $contentId = 'subscription-'.$amount;
            $value = (float) ($subscription->configured_price - ($subscription->discount_amount ?? 0) + ($subscription->shipping_cost ?? 0));

            $success = $metaService->sendPurchaseEvent(
                eventId: $subscription->meta_event_id,
                value: $value,
                currency: $subscription->currency ?? 'CZK',
                contentIds: [$contentId],
                contentType: 'product',
                userData: [
                    'email' => $address['email'] ?? $user?->email,
                    'phone' => $address['phone'] ?? $user?->phone,
                    'firstName' => $nameParts[0] ?? null,
                    'lastName' => $nameParts[1] ?? null,
                    'city' => $address['billing_city'] ?? null,
                    'postalCode' => $address['billing_postal_code'] ?? null,
                    'country' => $address['country'] ?? null,
                    'externalId' => $user?->id,
                ],
                fbp: $subscription->meta_fbp,
                fbc: $subscription->meta_fbc,
                sourceUrl: route('subscriptions.confirmation', $subscription),
            );

            if ($success) {
                $subscription->update(['meta_capi_sent_at' => now()]);
            }
        } catch (\Exception $e) {
            \Log::error('Meta CAPI failed for subscription', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
