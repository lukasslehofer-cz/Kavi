<?php

namespace App\Console\Commands;

use App\Models\SubscriptionConfig;
use Illuminate\Console\Command;
use Stripe\Stripe;
use Stripe\Product as StripeProduct;
use Stripe\Price as StripePrice;

class SetupStripeProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:setup-products {--force : Force recreation of products}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Stripe base product for dynamic subscription pricing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!config('services.stripe.secret')) {
            $this->error('Stripe secret key is not configured. Please add STRIPE_SECRET to your .env file.');
            return 1;
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $this->info('Setting up Stripe Base Product for Dynamic Pricing...');
        $this->newLine();

        try {
            // Check if base product already exists
            $existingProductId = SubscriptionConfig::get('stripe_base_product_id');
            
            if ($existingProductId && !$this->option('force')) {
                $this->line("⚠️  Base product already exists: {$existingProductId}");
                
                if (!$this->confirm('Do you want to create a new one?', false)) {
                    $this->line("⏭️  Skipped - using existing product");
                    $this->showNextSteps();
                    return 0;
                }
            }

            // Create base product for all subscriptions
            $this->info('Creating base subscription product...');
            
            $product = StripeProduct::create([
                'name' => 'Kavi Kávové Předplatné',
                'description' => 'Prémiové kávové předplatné s vlastní konfigurací (množství, typ kávy, frekvence)',
                'metadata' => [
                    'type' => 'configurable_subscription',
                ],
            ]);

            $this->line("  ✓ Product created: {$product->id}");
            $this->newLine();

            // Save to database
            SubscriptionConfig::updateOrCreate(
                ['key' => 'stripe_base_product_id'],
                [
                    'value' => $product->id,
                    'type' => 'string',
                    'label' => 'Stripe Base Product ID',
                    'description' => 'Base product for all configurable subscriptions with dynamic pricing',
                ]
            );

            $this->line("  ✓ Saved to database: stripe_base_product_id = {$product->id}");
            $this->newLine();

            $this->info('✅ Stripe setup completed successfully!');
            $this->newLine();
            
            $this->info('ℹ️  Dynamic Pricing Enabled:');
            $this->line('  - Prices are now calculated dynamically based on configuration');
            $this->line('  - Includes support for decaf surcharges and future add-ons');
            $this->line('  - No need to create fixed Price IDs for each variant');
            $this->newLine();
            
            $this->showNextSteps();

            return 0;

        } catch (\Exception $e) {
            $this->error('Error setting up Stripe: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Show next steps after setup
     */
    private function showNextSteps(): void
    {
        $this->info('Next steps:');
        $this->line('1. Verify product in Stripe Dashboard: https://dashboard.stripe.com/products');
        $this->line('2. Test subscription creation in your application');
        $this->line('3. Configure webhook: https://dashboard.stripe.com/webhooks');
        $this->line("   Webhook URL: " . route('stripe.webhook'));
        $this->line('   Events to listen: checkout.session.completed, customer.subscription.*, invoice.payment_*');
    }

    /**
     * Create a subscription product and its price in Stripe
     * @deprecated No longer used with dynamic pricing. Kept for reference only.
     */
    private function createSubscriptionProduct(array $data): void
    {
        $this->warn("⚠️  This method is deprecated and no longer used.");
        $this->line("The system now uses dynamic pricing instead of fixed Price IDs.");
        
        // Legacy code kept for reference
        // This method created multiple Price IDs for each combination
        // which is no longer necessary with dynamic pricing
    }

    /**
     * Save or update Stripe Price ID in config
     * @deprecated No longer used with dynamic pricing. Kept for reference only.
     */
    private function saveStripePriceId(string $key, string $priceId): void
    {
        // Legacy code - no longer needed
    }
}

