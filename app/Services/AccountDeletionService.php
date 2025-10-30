<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Stripe\Stripe;

class AccountDeletionService
{
    protected StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Check if user can delete their account
     * Returns array with 'can_delete' boolean and 'reasons' array
     */
    public function canDeleteAccount(User $user): array
    {
        $reasons = [];

        // Check for undelivered paid orders
        $undeliveredOrders = Order::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->count();

        if ($undeliveredOrders > 0) {
            $reasons[] = "Máte {$undeliveredOrders} zaplacených objednávek, které ještě nebyly doručeny.";
        }

        // Check for active subscriptions
        $activeSubscriptions = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();

        if ($activeSubscriptions > 0) {
            $reasons[] = "Máte {$activeSubscriptions} aktivních předplatných. Před smazáním účtu je musíte zrušit.";
        }

        // Check for paused subscriptions (optional - we could auto-cancel these)
        $pausedSubscriptions = Subscription::where('user_id', $user->id)
            ->where('status', 'paused')
            ->count();

        if ($pausedSubscriptions > 0) {
            $reasons[] = "Máte {$pausedSubscriptions} pozastavených předplatných. Před smazáním účtu je musíte zrušit.";
        }

        return [
            'can_delete' => empty($reasons),
            'reasons' => $reasons,
        ];
    }

    /**
     * Delete user account with anonymization
     * 
     * @throws \Exception if account cannot be deleted
     */
    public function deleteAccount(User $user): void
    {
        // Double-check if account can be deleted
        $canDelete = $this->canDeleteAccount($user);
        if (!$canDelete['can_delete']) {
            throw new \Exception('Účet nelze smazat: ' . implode(' ', $canDelete['reasons']));
        }

        \DB::beginTransaction();

        try {
            \Log::info('Starting account deletion process', ['user_id' => $user->id]);

            // 1. Cancel all unpaid subscriptions
            $this->cancelUnpaidSubscriptions($user);

            // 2. Delete/cancel unpaid orders
            $this->deleteUnpaidOrders($user);

            // 3. Remove payment methods from Stripe
            $this->removePaymentMethods($user);

            // 4. Anonymize delivered orders
            $this->anonymizeOrders($user);

            // 5. Anonymize subscriptions
            $this->anonymizeSubscriptions($user);

            // 6. Remove from newsletter
            $this->removeFromNewsletter($user);

            // 7. Anonymize user account
            $originalEmail = $user->email;
            $this->anonymizeUser($user);

            \DB::commit();

            \Log::info('Account deletion completed successfully', [
                'user_id' => $user->id,
                'original_email' => $originalEmail,
            ]);

            // 8. Send confirmation email (before session logout)
            try {
                \Mail::to($originalEmail)->send(new \App\Mail\AccountDeleted($originalEmail));
            } catch (\Exception $e) {
                \Log::error('Failed to send account deletion email', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
                // Don't fail the whole process if email fails
            }

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Account deletion failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Cancel all unpaid subscriptions
     */
    protected function cancelUnpaidSubscriptions(User $user): void
    {
        $unpaidSubscriptions = Subscription::where('user_id', $user->id)
            ->where('status', 'unpaid')
            ->get();

        foreach ($unpaidSubscriptions as $subscription) {
            try {
                // Cancel in Stripe if exists
                if ($subscription->stripe_subscription_id) {
                    try {
                        \Stripe\Subscription::update($subscription->stripe_subscription_id, [
                            'cancel_at_period_end' => false, // Cancel immediately
                        ]);
                        \Stripe\Subscription::cancel($subscription->stripe_subscription_id);
                    } catch (\Stripe\Exception\InvalidRequestException $e) {
                        // Subscription might already be cancelled in Stripe
                        \Log::warning('Stripe subscription not found during deletion', [
                            'subscription_id' => $subscription->id,
                            'stripe_subscription_id' => $subscription->stripe_subscription_id,
                        ]);
                    }
                }

                $subscription->update([
                    'status' => 'cancelled',
                    'ends_at' => now(),
                ]);

                \Log::info('Cancelled unpaid subscription', [
                    'subscription_id' => $subscription->id,
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to cancel unpaid subscription', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
                // Continue with other subscriptions
            }
        }
    }

    /**
     * Delete unpaid orders
     */
    protected function deleteUnpaidOrders(User $user): void
    {
        $unpaidOrders = Order::where('user_id', $user->id)
            ->where('payment_status', 'unpaid')
            ->get();

        foreach ($unpaidOrders as $order) {
            try {
                // Delete order items first
                $order->items()->delete();
                
                // Delete the order
                $order->delete();

                \Log::info('Deleted unpaid order', ['order_id' => $order->id]);
            } catch (\Exception $e) {
                \Log::error('Failed to delete unpaid order', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
                // Continue with other orders
            }
        }
    }

    /**
     * Remove payment methods from Stripe Customer
     */
    protected function removePaymentMethods(User $user): void
    {
        if (!$user->stripe_customer_id) {
            return;
        }

        try {
            // Get all payment methods for this customer
            $paymentMethods = \Stripe\PaymentMethod::all([
                'customer' => $user->stripe_customer_id,
                'limit' => 100,
            ]);

            // Detach each payment method
            foreach ($paymentMethods->data as $paymentMethod) {
                try {
                    $paymentMethod->detach();
                    \Log::info('Detached payment method', [
                        'user_id' => $user->id,
                        'payment_method_id' => $paymentMethod->id,
                    ]);
                } catch (\Exception $e) {
                    \Log::warning('Failed to detach payment method', [
                        'payment_method_id' => $paymentMethod->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Note: We keep the Stripe Customer for invoice/payment history
            // But remove the default payment method
            try {
                \Stripe\Customer::update($user->stripe_customer_id, [
                    'invoice_settings' => [
                        'default_payment_method' => null,
                    ],
                ]);
            } catch (\Exception $e) {
                \Log::warning('Failed to clear default payment method', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('Failed to remove payment methods', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            // Don't fail the whole process
        }
    }

    /**
     * Anonymize orders (keep for accounting purposes)
     */
    protected function anonymizeOrders(User $user): void
    {
        $orders = Order::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->get();

        foreach ($orders as $order) {
            try {
                // Anonymize shipping and billing addresses
                $anonymizedShipping = $this->anonymizeAddress($order->shipping_address ?? []);
                $anonymizedBilling = $this->anonymizeAddress($order->billing_address ?? []);

                $order->update([
                    'shipping_address' => $anonymizedShipping,
                    'billing_address' => $anonymizedBilling,
                ]);

                \Log::info('Anonymized order', ['order_id' => $order->id]);
            } catch (\Exception $e) {
                \Log::error('Failed to anonymize order', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Anonymize subscriptions
     */
    protected function anonymizeSubscriptions(User $user): void
    {
        $subscriptions = Subscription::where('user_id', $user->id)->get();

        foreach ($subscriptions as $subscription) {
            try {
                // Anonymize shipping address
                $anonymizedShipping = $this->anonymizeAddress($subscription->shipping_address ?? []);

                $subscription->update([
                    'shipping_address' => $anonymizedShipping,
                    'delivery_notes' => null,
                ]);

                \Log::info('Anonymized subscription', ['subscription_id' => $subscription->id]);
            } catch (\Exception $e) {
                \Log::error('Failed to anonymize subscription', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Anonymize address data
     */
    protected function anonymizeAddress(?array $address): array
    {
        if (!$address) {
            return [];
        }

        return [
            'name' => 'Anonymizováno',
            'email' => 'anonymized@example.com',
            'phone' => null,
            'address' => 'Anonymizováno',
            'billing_address' => 'Anonymizováno',
            'city' => 'Anonymizováno',
            'billing_city' => 'Anonymizováno',
            'postal_code' => 'XXXXX',
            'billing_postal_code' => 'XXXXX',
            'country' => $address['country'] ?? 'CZ', // Keep country for statistics
        ];
    }

    /**
     * Remove user from newsletter
     */
    protected function removeFromNewsletter(User $user): void
    {
        try {
            NewsletterSubscriber::where('email', $user->email)->delete();
            \Log::info('Removed from newsletter', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            \Log::error('Failed to remove from newsletter', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Anonymize user account data
     */
    protected function anonymizeUser(User $user): void
    {
        $timestamp = time();
        
        $user->update([
            'name' => "Smazaný uživatel #{$user->id}",
            'email' => "deleted_{$user->id}_{$timestamp}@anonymized.local",
            'password' => Hash::make(Str::random(64)), // Make account inaccessible
            'remember_token' => null,
            'phone' => null,
            'address' => null,
            'city' => null,
            'postal_code' => null,
            'country' => 'XX', // Use XX for anonymized country (cannot be NULL in DB)
            'packeta_point_id' => null,
            'packeta_point_name' => null,
            'packeta_point_address' => null,
            'deleted_at' => now(),
            'anonymized_at' => now(),
            // Keep stripe_customer_id and fakturoid_subject_id for accounting/invoice purposes
        ]);

        \Log::info('Anonymized user account', [
            'user_id' => $user->id,
            'new_email' => $user->email,
        ]);
    }
}

