<?php

namespace App\Services;

use App\Models\NewsletterSubscriber;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\SubscriptionConfig;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Customer as StripeCustomer;
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
                if ($customer && !isset($customer->deleted)) {
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
        
        if (!$order->user) {
            \Log::error('Order has no user', ['order_id' => $order->id]);
            throw new \Exception('Objednávka nemá přiřazeného uživatele.');
        }
        
        $customerId = $this->getOrCreateCustomer($order->user);

        $lineItems = $order->items->map(function ($item) {
            $productData = [
                'name' => $item->product_name,
            ];
            
            // Add image only if it exists and is valid
            if ($item->product_image && !empty(trim($item->product_image))) {
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
            
            return [
                'price_data' => [
                    'currency' => 'czk',
                    'product_data' => $productData,
                    'unit_amount' => (int)($item->price * 100),
                ],
                'quantity' => $item->quantity,
            ];
        })->toArray();

        // Add shipping if applicable
        if ($order->shipping > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'czk',
                    'product_data' => [
                        'name' => 'Doprava',
                    ],
                    'unit_amount' => (int)($order->shipping * 100),
                ],
                'quantity' => 1,
            ];
        }

        return StripeSession::create([
            'customer' => $customerId,
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('order.confirmation', $order) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.index') . '?order_id=' . $order->id,
            'metadata' => [
                'order_id' => $order->id,
            ],
        ]);
    }

    /**
     * Create one-time payment checkout session for a subscription box
     */
    public function createOneTimeBoxCheckoutSession(
        Subscription $subscription,
        float $price,
        array $shippingAddress
    ): StripeSession
    {
        $subscription->load('user');
        
        if (!$subscription->user) {
            throw new \Exception('Subscription nemá přiřazeného uživatele.');
        }
        
        $customerId = $this->getOrCreateCustomer($subscription->user);
        
        // Build product description
        $config = is_string($subscription->configuration) 
            ? json_decode($subscription->configuration, true) 
            : $subscription->configuration;
        
        $boxSize = ['2' => 'M Box (2× 250g)', '3' => 'L Box (3× 250g)', '4' => 'XL Box (4× 250g)'];
        $boxType = ['espresso' => 'Espresso', 'filter' => 'Filtr', 'mix' => 'Mix'];
        
        $productName = ($boxSize[$config['amount']] ?? 'Box') . ' - ' . ($boxType[$config['type']] ?? 'Káva');
        if ($config['isDecaf'] ?? false) {
            $productName .= ' + Decaf';
        }
        $productName .= ' (Jednorázově)';
        
        $lineItems = [
            [
                'price_data' => [
                    'currency' => 'czk',
                    'product_data' => [
                        'name' => $productName,
                        'description' => 'Jednorázový kávový box bez předplatného',
                    ],
                    'unit_amount' => (int)($price * 100),
                ],
                'quantity' => 1,
            ],
        ];
        
        return StripeSession::create([
            'customer' => $customerId,
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('dashboard.subscription') . '?payment=success',
            'cancel_url' => route('subscriptions.index') . '?payment=cancelled',
            'metadata' => [
                'subscription_id' => $subscription->id,
                'is_one_time_box' => 'true',
            ],
        ]);
    }

    /**
     * Create checkout session for custom configured subscription
     */
    public function createConfiguredSubscriptionCheckoutSession(
        ?User $user, 
        array $configuration, 
        float $price,
        array $shippingAddress,
        ?\App\Models\Coupon $coupon = null,
        float $discount = 0,
        ?int $discountMonths = null
    ): StripeSession {
        // Prepare metadata for subscription (includes Packeta and delivery_notes)
        $subscriptionMetadata = [
            'configuration' => json_encode($configuration),
            'configured_price' => $price,
            'shipping_address' => json_encode([
                'name' => $shippingAddress['name'],
                'email' => $shippingAddress['email'],
                'phone' => $shippingAddress['phone'] ?? null,
                'billing_address' => $shippingAddress['billing_address'],
                'billing_city' => $shippingAddress['billing_city'],
                'billing_postal_code' => $shippingAddress['billing_postal_code'],
                'country' => $shippingAddress['billing_country'] ?? 'CZ',
            ]),
            'packeta_point_id' => $shippingAddress['packeta_point_id'],
            'packeta_point_name' => $shippingAddress['packeta_point_name'],
            'packeta_point_address' => $shippingAddress['packeta_point_address'] ?? null,
            'carrier_id' => $shippingAddress['carrier_id'] ?? null,
            'carrier_pickup_point' => $shippingAddress['carrier_pickup_point'] ?? null,
            'delivery_notes' => $shippingAddress['delivery_notes'] ?? null,
        ];
        
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

        // Create session with dynamic pricing (price_data instead of fixed price ID)
        $sessionData = [
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'czk',
                    'product' => $productId,
                    'recurring' => [
                        'interval' => 'month',
                        'interval_count' => $configuration['frequency'] ?? 1,
                    ],
                    'unit_amount' => (int)($price * 100), // Convert to haléře
                ],
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => route('subscriptions.checkout') . '?session_id={CHECKOUT_SESSION_ID}&success=1',
            'cancel_url' => route('subscriptions.checkout'),
            // Metadata on subscription (this is what gets passed to webhook!)
            'subscription_data' => [
                'metadata' => $subscriptionMetadata,
            ],
        ];

        // Add customer if user is authenticated
        if ($user) {
            $sessionData['customer'] = $this->getOrCreateCustomer($user);
            $sessionData['subscription_data']['metadata']['user_id'] = $user->id;
        } else {
            // Guest checkout - use customer email
            $sessionData['customer_email'] = $shippingAddress['email'];
            $sessionData['subscription_data']['metadata']['guest_email'] = $shippingAddress['email'];
        }

        return StripeSession::create($sessionData);
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
            'currency' => 'czk',
            'product' => $productId,
            'recurring' => [
                'interval' => 'month',
                'interval_count' => $configuration['frequency'] ?? 1,
            ],
            'unit_amount' => (int)($price * 100),
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
            $customer = StripeCustomer::retrieve($customerId);
            
            // Ošetři případ kdy customer neexistuje nebo je smazaný
            if (!$customer || isset($customer->deleted)) {
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
            $paymentMethods = \Stripe\PaymentMethod::all([
                'customer' => $customerId,
                'type' => 'card',
                'limit' => 1,
            ]);

            if (count($paymentMethods->data) > 0) {
                return $paymentMethods->data[0]->id;
            }

            return null;
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
                    if ($invoiceId && $invoiceId !== 'manual_payment' && !str_starts_with($invoiceId, 'in_test_')) {
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
                    
                    // Clear unpaid status and restore subscription
                    $subscription->update([
                        'status' => 'active',
                        'payment_failure_count' => 0,
                        'last_payment_failure_at' => null,
                        'last_payment_failure_reason' => null,
                        'pending_invoice_id' => null,
                        'pending_invoice_amount' => null,
                    ]);
                    
                    \Log::info('Manual invoice payment successful - subscription restored', [
                        'subscription_id' => $subscription->id,
                        'invoice_id' => $invoiceId ?? 'manual_payment',
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
                    
                    // Send confirmation email (one-time box specific template)
                    try {
                        \Mail::to($subscription->user->email)->send(new \App\Mail\OneTimeBoxConfirmation($subscription));
                    } catch (\Exception $e) {
                        \Log::error('Failed to send one-time box confirmation email: ' . $e->getMessage());
                    }
                    
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

                // Create invoice in Fakturoid ONLY if not already created (backup for webhook)
                if (!$order->fakturoid_invoice_id) {
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
                if (!$order->confirmation_email_sent_at) {
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
        
        if (!$userId && !$guestEmail) {
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
                \Log::error('Failed to add subscription email to newsletter: ' . $e->getMessage());
            }
            
            // Create user account for guest subscriptions
            if (!$userId && $guestEmail) {
                try {
                    $name = $subscription->shipping_address['name'] ?? 'Zákazník';
                    
                    // Check if user already exists (shouldn't, but just in case)
                    $existingUser = User::where('email', $guestEmail)->first();
                    
                    if (!$existingUser) {
                        $newUser = User::create([
                            'name' => $name,
                            'email' => $guestEmail,
                            'password' => \Hash::make(\Str::random(32)), // Random password
                            'password_set_by_user' => false, // User didn't set this password
                            'phone' => $subscription->shipping_address['phone'] ?? null,
                            'address' => $subscription->shipping_address['billing_address'] ?? null,
                            'city' => $subscription->shipping_address['billing_city'] ?? null,
                            'postal_code' => $subscription->shipping_address['billing_postal_code'] ?? null,
                            'packeta_point_id' => $subscription->packeta_point_id ?? null,
                            'packeta_point_name' => $subscription->packeta_point_name ?? null,
                            'packeta_point_address' => $subscription->packeta_point_address ?? null,
                        ]);
                        
                        // Link subscription to the new user
                        $subscription->update(['user_id' => $newUser->id]);
                        
                        \Log::info('Created user account for guest subscription', [
                            'user_id' => $newUser->id,
                            'subscription_id' => $subscription->id
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to create user account for guest subscription: ' . $e->getMessage());
                }
            }
            
            // Send subscription confirmation email
            try {
                // Get email from shipping address or user (support guest subscriptions)
                $email = $subscription->shipping_address['email'] ?? null;
                if (!$email && $subscription->user) {
                    $email = $subscription->user->email;
                }
                
                if ($email) {
                    \Mail::to($email)->send(new \App\Mail\SubscriptionConfirmation($subscription));
                    \Log::info('Subscription confirmation email sent', [
                        'subscription_id' => $subscription->id,
                        'email' => $email,
                        'is_guest' => $subscription->user_id === null
                    ]);
                } else {
                    \Log::warning('No email found for subscription confirmation', ['subscription_id' => $subscription->id]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send subscription confirmation email: ' . $e->getMessage());
            }
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
     * Handle subscription updated webhook
     */
    public function handleSubscriptionUpdated(array $subscriptionData): void
    {
        $subscription = Subscription::where('stripe_subscription_id', $subscriptionData['id'])->first();

        if ($subscription) {
            $stripeStatus = $subscriptionData['status'] ?? null;
            $hasPauseCollection = isset($subscriptionData['pause_collection']) && !empty($subscriptionData['pause_collection']);
            $hasCancelAtPeriodEnd = isset($subscriptionData['cancel_at_period_end']) && $subscriptionData['cancel_at_period_end'] === true;

        $mappedStatus = match($stripeStatus) {
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

                // Handle coupon discount countdown
                if ($subscription->coupon_id && $subscription->discount_amount > 0) {
                    $couponService = app(\App\Services\CouponService::class);
                    
                    // Zaznamenat použití kupónu pro tuto platbu
                    $couponService->recordUsage(
                        $subscription->coupon,
                        $subscription->user,
                        'subscription',
                        $subscription->discount_amount,
                        null,
                        $subscription
                    );
                    
                    // Snížit počet zbývajících měsíců slevy
                    $couponService->decrementSubscriptionDiscountMonth($subscription);
                    
                    // Pokud skončily měsíce se slevou, aktualizovat Stripe subscription s novou cenou
                    if ($subscription->fresh()->discount_months_remaining === 0) {
                        try {
                            $newPrice = $subscription->configured_price ?? $subscription->plan?->price ?? 0;
                            
                            // Získat aktuální Stripe subscription
                            $stripeSubscription = \Stripe\Subscription::retrieve($subscription->stripe_subscription_id);
                            
                            // Vytvořit nový Price objekt s plnou cenou
                            $productId = $this->getOrCreateBaseSubscriptionProduct();
                            $newStripePrice = \Stripe\Price::create([
                                'currency' => 'czk',
                                'product' => $productId,
                                'recurring' => [
                                    'interval' => 'month',
                                    'interval_count' => $subscription->frequency_months ?? 1,
                                ],
                                'unit_amount' => (int)($newPrice * 100),
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
        
        if (!$paymentIntentId) {
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
                        'order_id' => $order->id
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
                            'subscription_id' => $subscription->id
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
                'order_id' => $order->id
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
        if (!$subscription->pending_invoice_amount) {
            \Log::warning('No pending invoice amount for subscription', [
                'subscription_id' => $subscription->id
            ]);
            return null;
        }

        try {
            $customerId = $this->getOrCreateCustomer($subscription->user);
            
            // Check if we have a real Stripe invoice (not a test one)
            $hasRealInvoice = $subscription->pending_invoice_id && 
                             !str_starts_with($subscription->pending_invoice_id, 'in_test_');
            
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
            if (!$hasRealInvoice) {
                $currency = 'czk';
                $amount = (int)($subscription->pending_invoice_amount * 100); // Convert to cents
            }

            // Create a checkout session for payment
            $session = StripeSession::create([
                'customer' => $customerId,
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => $currency,
                        'product_data' => [
                            'name' => 'Platba předplatného - ' . ($subscription->subscription_number ?? '#' . $subscription->id),
                            'description' => 'Neuhrazená platba za kávové předplatné',
                        ],
                        'unit_amount' => $amount,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('dashboard.subscription') . '?payment=success',
                'cancel_url' => route('dashboard.subscription') . '?payment=cancelled',
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
        if (!$subscription->stripe_subscription_id) {
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
        if (!$subscription->stripe_subscription_id) {
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
        if (!$subscription->stripe_subscription_id) {
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
            if (!$customer || isset($customer->deleted)) {
                \Log::warning('Customer not found or deleted in getPaymentMethodDetails', [
                    'customer_id' => $customerId,
                ]);
                return null;
            }
            
            // Bezpečný přístup k invoice_settings a default_payment_method
            $paymentMethodId = $customer->invoice_settings->default_payment_method ?? null;
            
            // If no default, try to get the first payment method
            if (!$paymentMethodId) {
                $paymentMethods = \Stripe\PaymentMethod::all([
                    'customer' => $customerId,
                    'type' => 'card',
                    'limit' => 1,
                ]);
                
                if (count($paymentMethods->data) > 0) {
                    $paymentMethodId = $paymentMethods->data[0]->id;
                }
            }
            
            if (!$paymentMethodId) {
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

            if (!$customerId || !$paymentMethodId) {
                \Log::warning('Payment method attached event missing required data', [
                    'customer_id' => $customerId,
                    'payment_method_id' => $paymentMethodId,
                ]);
                return;
            }

            // Find user by Stripe customer ID
            $user = User::where('stripe_customer_id', $customerId)->first();

            if (!$user) {
                \Log::warning('User not found for payment method attached event', [
                    'customer_id' => $customerId,
                ]);
                return;
            }

            // Get payment method details from Stripe
            $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentMethodId);

            if ($paymentMethod->type === 'card') {
                $cardBrand = ucfirst($paymentMethod->card->brand);
                $cardLast4 = $paymentMethod->card->last4;

                // Send notification email
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

            if (!$customerId) {
                \Log::warning('Payment method updated event missing customer ID');
                return;
            }

            // Find user by Stripe customer ID
            $user = User::where('stripe_customer_id', $customerId)->first();

            if (!$user) {
                \Log::warning('User not found for payment method updated event', [
                    'customer_id' => $customerId,
                ]);
                return;
            }

            // Get current payment method details
            $paymentMethodDetails = $this->getPaymentMethodDetails($customerId);

            if ($paymentMethodDetails && $paymentMethodDetails['type'] === 'card') {
                $cardBrand = ucfirst($paymentMethodDetails['brand']);
                $cardLast4 = $paymentMethodDetails['last4'];

                // Send notification email
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
}




