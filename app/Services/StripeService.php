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
     * Create checkout session for subscription (predefined plan)
     */
    public function createSubscriptionCheckoutSession(User $user, SubscriptionPlan $plan): StripeSession
    {
        $customerId = $this->getOrCreateCustomer($user);

        // Ensure Stripe price exists
        if (!$plan->stripe_price_id) {
            throw new \Exception('Subscription plan does not have a Stripe price ID configured.');
        }

        return StripeSession::create([
            'customer' => $customerId,
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $plan->stripe_price_id,
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => route('dashboard.subscription') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('subscriptions.show', $plan),
            'metadata' => [
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
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
        // Prepare metadata for subscription
        $subscriptionMetadata = [
            'configuration' => json_encode($configuration),
            'configured_price' => $price,
            'shipping_address' => json_encode($shippingAddress),
        ];

        $sessionData = [
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $this->getStripePriceIdForConfiguration($configuration),
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
     * Get Stripe Price ID based on configuration (amount and frequency)
     */
    private function getStripePriceIdForConfiguration(array $configuration): string
    {
        $amount = $configuration['amount'];
        $frequency = $configuration['frequency'];

        // Build config key based on frequency
        $configKey = "stripe_price_id_{$amount}_bags";
        
        if ($frequency == 2) {
            $configKey .= '_2months';
        } elseif ($frequency == 3) {
            $configKey .= '_3months';
        }

        $priceId = SubscriptionConfig::get($configKey);

        if (!$priceId) {
            throw new \Exception("Stripe Price ID not found for configuration: {$configKey}. Please run 'php artisan stripe:setup-products'");
        }

        return $priceId;
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
        
        $priceId = $this->getStripePriceIdForConfiguration($configuration);

        $subscription = StripeSubscription::create([
            'customer' => $customerId,
            'default_payment_method' => $paymentMethodId,
            'items' => [
                ['price' => $priceId],
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

        if (!$userId) {
            \Log::warning('No user_id in subscription metadata, cannot create subscription', [
                'stripe_subscription_id' => $subscriptionData['id'],
                'customer' => $subscriptionData['customer'] ?? null,
            ]);
            return;
        }

        try {
            $subscriptionRecord = [
                'user_id' => $userId,
                'stripe_subscription_id' => $subscriptionData['id'],
                'status' => 'active',
                'starts_at' => now(),
                'next_billing_date' => isset($subscriptionData['current_period_end']) 
                    ? \Carbon\Carbon::createFromTimestamp($subscriptionData['current_period_end'])
                    : now()->addMonth(),
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
            }

            \Log::info('Creating subscription record', $subscriptionRecord);
            $subscription = Subscription::create($subscriptionRecord);
            \Log::info('Subscription created successfully', ['id' => $subscription->id]);
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
            $status = match($subscriptionData['status']) {
                'active' => 'active',
                'canceled' => 'cancelled',
                'past_due' => 'active', // Keep active but we should notify
                'unpaid' => 'paused',
                default => $subscription->status,
            };

            $subscription->update([
                'status' => $status,
                'next_billing_date' => isset($subscriptionData['current_period_end']) 
                    ? \Carbon\Carbon::createFromTimestamp($subscriptionData['current_period_end'])
                    : null,
                'ends_at' => isset($subscriptionData['canceled_at']) 
                    ? \Carbon\Carbon::createFromTimestamp($subscriptionData['canceled_at'])
                    : null,
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
                // Update last shipment date and next billing date
                $subscription->update([
                    'status' => 'active',
                    'next_billing_date' => isset($invoiceData['period_end']) 
                        ? \Carbon\Carbon::createFromTimestamp($invoiceData['period_end'])
                        : $subscription->next_billing_date,
                ]);

                // TODO: Optionally create an order record for tracking shipments
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
     * Cancel subscription
     */
    public function cancelSubscription(Subscription $subscription): void
    {
        if ($subscription->stripe_subscription_id) {
            $stripeSubscription = StripeSubscription::retrieve($subscription->stripe_subscription_id);
            $stripeSubscription->cancel();
        }

        $subscription->cancel();
    }
}




