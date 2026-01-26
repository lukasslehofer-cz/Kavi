<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Console\Command;
use Stripe\Stripe;
use Stripe\Customer as StripeCustomer;
use Stripe\PaymentMethod;
use Stripe\PaymentIntent;

class FixMissingStripeCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:fix-stripe-customers 
                            {--dry-run : Run without making changes}
                            {--user= : Fix only a specific user ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Stripe customers for users with active subscriptions but no stripe_customer_id';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        
        $dryRun = $this->option('dry-run');
        $specificUserId = $this->option('user');
        
        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
        }
        
        $this->info('Looking for users with active subscriptions but no stripe_customer_id...');
        
        // Find users with active subscriptions but no stripe_customer_id
        $query = User::whereNull('stripe_customer_id')
            ->whereHas('subscriptions', function ($q) {
                $q->where('status', 'active');
            })
            ->with(['subscriptions' => function ($q) {
                $q->where('status', 'active')->latest();
            }]);
        
        if ($specificUserId) {
            $query->where('id', $specificUserId);
        }
        
        $users = $query->get();
        
        if ($users->isEmpty()) {
            $this->info('✓ No users found that need fixing.');
            return 0;
        }
        
        $this->info("Found {$users->count()} user(s) to fix");
        $this->newLine();
        
        $fixed = 0;
        $failed = 0;
        $skipped = 0;
        
        foreach ($users as $user) {
            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->info("User #{$user->id}: {$user->name} ({$user->email})");
            
            // Get the latest active subscription
            $subscription = $user->subscriptions->first();
            
            if (!$subscription) {
                $this->warn("  ⚠️ No active subscription found - skipping");
                $skipped++;
                continue;
            }
            
            $this->line("  Subscription: {$subscription->subscription_number}");
            $this->line("  Payment Intent: " . ($subscription->stripe_payment_intent_id ?? 'null'));
            
            if (!$subscription->stripe_payment_intent_id) {
                $this->warn("  ⚠️ No payment intent ID found - skipping");
                $skipped++;
                continue;
            }
            
            try {
                // Step 1: Retrieve the PaymentIntent to get the payment method
                $this->line("  → Retrieving PaymentIntent from Stripe...");
                $paymentIntent = PaymentIntent::retrieve($subscription->stripe_payment_intent_id);
                
                $paymentMethodId = $paymentIntent->payment_method;
                
                if (!$paymentMethodId) {
                    $this->warn("  ⚠️ No payment method found on PaymentIntent - skipping");
                    $skipped++;
                    continue;
                }
                
                $this->line("  → Payment method found: {$paymentMethodId}");
                
                // Check if payment method is already attached to a customer
                $paymentMethod = PaymentMethod::retrieve($paymentMethodId);
                
                if ($paymentMethod->customer) {
                    // Payment method already attached to a customer
                    $existingCustomerId = $paymentMethod->customer;
                    $this->line("  → Payment method already attached to customer: {$existingCustomerId}");
                    
                    if ($dryRun) {
                        $this->comment("  [DRY RUN] Would update user with existing customer ID: {$existingCustomerId}");
                        $fixed++;
                    } else {
                        $user->update(['stripe_customer_id' => $existingCustomerId]);
                        $this->info("  ✓ Updated user with existing Stripe customer ID: {$existingCustomerId}");
                        $fixed++;
                    }
                    continue;
                }
                
                // Step 2: Create a new Stripe customer
                $this->line("  → Creating new Stripe customer...");
                
                $shippingAddress = $subscription->shipping_address ?? [];
                
                $customerData = [
                    'email' => $user->email,
                    'name' => $user->name,
                    'phone' => $user->phone ?? $shippingAddress['phone'] ?? null,
                    'metadata' => [
                        'user_id' => $user->id,
                        'source' => 'fix_missing_stripe_customers',
                        'fixed_at' => now()->toIso8601String(),
                    ],
                ];
                
                // Add address if available
                $address = $user->address ?? $shippingAddress['billing_address'] ?? null;
                if ($address) {
                    $customerData['address'] = [
                        'line1' => $address,
                        'city' => $user->city ?? $shippingAddress['billing_city'] ?? null,
                        'postal_code' => $user->postal_code ?? $shippingAddress['billing_postal_code'] ?? null,
                        'country' => $user->country ?? $shippingAddress['country'] ?? 'CZ',
                    ];
                }
                
                if ($dryRun) {
                    $this->comment("  [DRY RUN] Would create Stripe customer with:");
                    $this->comment("    - email: {$customerData['email']}");
                    $this->comment("    - name: {$customerData['name']}");
                    $this->comment("  [DRY RUN] Would attach payment method: {$paymentMethodId}");
                    $this->comment("  [DRY RUN] Would update user.stripe_customer_id");
                    $fixed++;
                    continue;
                }
                
                $customer = StripeCustomer::create($customerData);
                $this->line("  → Created customer: {$customer->id}");
                
                // Step 3: Attach the payment method to the customer
                $this->line("  → Attaching payment method to customer...");
                $paymentMethod->attach(['customer' => $customer->id]);
                
                // Step 4: Set as default payment method
                $this->line("  → Setting as default payment method...");
                StripeCustomer::update($customer->id, [
                    'invoice_settings' => [
                        'default_payment_method' => $paymentMethodId,
                    ],
                ]);
                
                // Step 5: Update user in database
                $user->update(['stripe_customer_id' => $customer->id]);
                
                $this->info("  ✓ Successfully fixed user!");
                $this->info("    - Stripe Customer ID: {$customer->id}");
                $this->info("    - Payment Method: {$paymentMethodId}");
                $fixed++;
                
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                $this->error("  ✗ Stripe API Error: " . $e->getMessage());
                $failed++;
                
                \Log::error('Failed to fix Stripe customer', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
                
            } catch (\Exception $e) {
                $this->error("  ✗ Error: " . $e->getMessage());
                $failed++;
                
                \Log::error('Failed to fix Stripe customer', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }
        
        $this->newLine();
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("📊 SUMMARY:");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total users', $users->count()],
                ['Fixed', $fixed],
                ['Failed', $failed],
                ['Skipped', $skipped],
            ]
        );
        
        if ($dryRun && $fixed > 0) {
            $this->newLine();
            $this->info("💡 Run without --dry-run to apply changes");
        }
        
        return $failed > 0 ? 1 : 0;
    }
}
