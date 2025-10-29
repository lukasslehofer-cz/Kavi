<?php

namespace App\Services;

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
     */
    public function getOrCreateCustomer(User $user): string
    {
        if ($user->stripe_customer_id) {
            return $user->stripe_customer_id;
        }

        $customer = StripeCustomer::create([
            'email' => $user->email,
            'name' => $user->name,
            'metadata' => [
                'user_id' => $user->id,
            ],
        ]);

        $user->update(['stripe_customer_id' => $customer->id]);

        return $customer->id;
    }

    /**
     * Create checkout session for one-time payment (order)
     */
    public function createOrderCheckoutSession(Order $order): StripeSession
    {
        $customerId = $this->getOrCreateCustomer($order->user);

        $lineItems = $order->items->map(function ($item) {
            return [
                'price_data' => [
                    'currency' => 'czk',
                    'product_data' => [
                        'name' => $item->product_name,
                        'images' => $item->product_image ? [$item->product_image] : [],
                    ],
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
            'cancel_url' => route('cart.index'),
            'metadata' => [
                'order_id' => $order->id,
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
        array $shippingAddress
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
                'country' => 'CZ',
            ]),
            'packeta_point_id' => $shippingAddress['packeta_point_id'],
            'packeta_point_name' => $shippingAddress['packeta_point_name'],
            'packeta_point_address' => $shippingAddress['packeta_point_address'] ?? null,
            'delivery_notes' => $shippingAddress['delivery_notes'] ?? null,
        ];

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
            
            if ($customer->invoice_settings->default_payment_method) {
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
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Handle successful payment webhook
     */
    public function handlePaymentSuccess(array $session): void
    {
        if (isset($session['metadata']['order_id'])) {
            $order = Order::find($session['metadata']['order_id']);
            if ($order) {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                    'stripe_payment_intent_id' => $session['payment_intent'] ?? null,
                    'paid_at' => now(),
                ]);

                // Create invoice in Fakturoid and download PDF
                try {
                    $fakturoidService = app(\App\Services\FakturoidService::class);
                    $fakturoidService->processInvoiceForOrder($order);
                } catch (\Exception $e) {
                    \Log::error('Failed to create Fakturoid invoice after payment', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                    ]);
                    // Don't fail the webhook if Fakturoid fails
                }
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
                : $subscriptionStartDate->copy()->addMonth()->setDay(15);
            
            // Get frequency from configuration if available, otherwise default to 1 month
            $frequencyMonths = 1;
            if ($configuration && isset($configuration['frequency'])) {
                $frequencyMonths = $configuration['frequency'];
            }
            
            $nextBillingDate = $currentBillingCycleEnd->copy()->addMonths($frequencyMonths);
            
            $subscriptionRecord = [
                'subscription_number' => Subscription::generateSubscriptionNumber(),
                'user_id' => $userId,
                'stripe_subscription_id' => $subscriptionData['id'],
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
                }
                
                // Add delivery notes if available
                if (isset($subscriptionData['metadata']['delivery_notes'])) {
                    $subscriptionRecord['delivery_notes'] = $subscriptionData['metadata']['delivery_notes'];
                }
            }

            \Log::info('Creating subscription record', $subscriptionRecord);
            $subscription = Subscription::create($subscriptionRecord);
            \Log::info('Subscription created successfully', ['id' => $subscription->id]);
            
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
                'unpaid' => 'paused',
                'active' => 'active',
                'past_due' => 'active',
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
                // Update subscription
                $subscription->update([
                    'status' => 'active',
                    'next_billing_date' => isset($invoiceData['period_end']) 
                        ? \Carbon\Carbon::createFromTimestamp($invoiceData['period_end'])
                        : $subscription->next_billing_date,
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
     * Handle failed invoice payment
     */
    public function handleInvoicePaymentFailed(array $invoiceData): void
    {
        $subscriptionId = $invoiceData['subscription'] ?? null;
        
        if ($subscriptionId) {
            $subscription = Subscription::where('stripe_subscription_id', $subscriptionId)->first();
            
            if ($subscription) {
                // TODO: Send notification email to customer
                // Consider pausing subscription after multiple failures
                
                \Log::warning('Invoice payment failed for subscription', [
                    'subscription_id' => $subscription->id,
                    'stripe_subscription_id' => $subscriptionId,
                    'invoice_id' => $invoiceData['id'] ?? null,
                ]);
            }
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
}




