<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Subscription;
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
     * Create checkout session for subscription
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
    public function handleSubscriptionCreated(array $subscription): void
    {
        $userId = $subscription['metadata']['user_id'] ?? null;
        $planId = $subscription['metadata']['subscription_plan_id'] ?? null;

        if ($userId && $planId) {
            Subscription::create([
                'user_id' => $userId,
                'subscription_plan_id' => $planId,
                'stripe_subscription_id' => $subscription['id'],
                'status' => 'active',
                'starts_at' => now(),
                'next_billing_date' => now()->addMonth(),
            ]);
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




