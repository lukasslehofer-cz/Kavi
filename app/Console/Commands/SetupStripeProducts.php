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
    protected $description = 'Create Stripe Products and Prices for subscription configurator';

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

        $this->info('Setting up Stripe Products and Prices...');
        $this->newLine();

        try {
            // Get pricing from database
            $price2Bags = SubscriptionConfig::get('price_2_bags', 500);
            $price3Bags = SubscriptionConfig::get('price_3_bags', 720);
            $price4Bags = SubscriptionConfig::get('price_4_bags', 920);

            $this->info('Current pricing from database:');
            $this->line("  2 bags: {$price2Bags} CZK");
            $this->line("  3 bags: {$price3Bags} CZK");
            $this->line("  4 bags: {$price4Bags} CZK");
            $this->newLine();

            // Create base subscription products
            $products = [
                [
                    'name' => 'Kavi Předplatné - 2 balení',
                    'description' => 'Měsíční kávové předplatné - 2 balení kávy po 250g',
                    'amount' => 2,
                    'price_czk' => $price2Bags,
                    'config_key' => 'stripe_price_id_2_bags',
                ],
                [
                    'name' => 'Kavi Předplatné - 3 balení',
                    'description' => 'Měsíční kávové předplatné - 3 balení kávy po 250g',
                    'amount' => 3,
                    'price_czk' => $price3Bags,
                    'config_key' => 'stripe_price_id_3_bags',
                ],
                [
                    'name' => 'Kavi Předplatné - 4 balení',
                    'description' => 'Měsíční kávové předplatné - 4 balení kávy po 250g',
                    'amount' => 4,
                    'price_czk' => $price4Bags,
                    'config_key' => 'stripe_price_id_4_bags',
                ],
            ];

            foreach ($products as $productData) {
                $this->createSubscriptionProduct($productData);
            }

            $this->newLine();
            $this->info('✅ Stripe setup completed successfully!');
            $this->newLine();
            $this->info('Next steps:');
            $this->line('1. Verify products in Stripe Dashboard: https://dashboard.stripe.com/products');
            $this->line('2. Test subscription creation in your application');
            $this->line('3. Configure webhook: https://dashboard.stripe.com/webhooks');
            $this->line("   Webhook URL: " . route('stripe.webhook'));
            $this->line('   Events to listen: checkout.session.completed, customer.subscription.*, invoice.payment_*');

            return 0;

        } catch (\Exception $e) {
            $this->error('Error setting up Stripe: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Create a subscription product and its price in Stripe
     */
    private function createSubscriptionProduct(array $data): void
    {
        $this->info("Creating product: {$data['name']}");

        // Check if price already exists in config
        $existingPriceId = SubscriptionConfig::get($data['config_key']);
        
        if ($existingPriceId && !$this->option('force')) {
            $this->line("  ⚠️  Price ID already exists: {$existingPriceId}");
            
            if (!$this->confirm('  Do you want to create a new one?', false)) {
                $this->line("  ⏭️  Skipped");
                return;
            }
        }

        try {
            // Create Product
            $product = StripeProduct::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'metadata' => [
                    'amount' => $data['amount'],
                    'type' => 'subscription',
                ],
            ]);

            $this->line("  ✓ Product created: {$product->id}");

            // Create recurring Price (monthly)
            $price = StripePrice::create([
                'product' => $product->id,
                'unit_amount' => (int)($data['price_czk'] * 100), // Convert to haléře
                'currency' => 'czk',
                'recurring' => [
                    'interval' => 'month',
                    'interval_count' => 1,
                ],
                'metadata' => [
                    'amount' => $data['amount'],
                ],
            ]);

            $this->line("  ✓ Monthly price created: {$price->id} ({$data['price_czk']} CZK/month)");

            // Create recurring Price (bi-monthly - every 2 months)
            $priceBiMonthly = StripePrice::create([
                'product' => $product->id,
                'unit_amount' => (int)($data['price_czk'] * 100),
                'currency' => 'czk',
                'recurring' => [
                    'interval' => 'month',
                    'interval_count' => 2,
                ],
                'metadata' => [
                    'amount' => $data['amount'],
                    'frequency' => '2_months',
                ],
            ]);

            $this->line("  ✓ Bi-monthly price created: {$priceBiMonthly->id} ({$data['price_czk']} CZK/2 months)");

            // Create recurring Price (quarterly - every 3 months)
            $priceQuarterly = StripePrice::create([
                'product' => $product->id,
                'unit_amount' => (int)($data['price_czk'] * 100),
                'currency' => 'czk',
                'recurring' => [
                    'interval' => 'month',
                    'interval_count' => 3,
                ],
                'metadata' => [
                    'amount' => $data['amount'],
                    'frequency' => '3_months',
                ],
            ]);

            $this->line("  ✓ Quarterly price created: {$priceQuarterly->id} ({$data['price_czk']} CZK/3 months)");

            // Save the price IDs to config (using updateOrCreate to handle new keys)
            $this->saveStripePriceId($data['config_key'], $price->id);
            $this->saveStripePriceId($data['config_key'] . '_2months', $priceBiMonthly->id);
            $this->saveStripePriceId($data['config_key'] . '_3months', $priceQuarterly->id);

            $this->line("  ✓ Saved to database: {$data['config_key']}");
            $this->newLine();

        } catch (\Stripe\Exception\ApiErrorException $e) {
            $this->error("  ✗ Failed: {$e->getMessage()}");
        }
    }

    /**
     * Save or update Stripe Price ID in config
     */
    private function saveStripePriceId(string $key, string $priceId): void
    {
        SubscriptionConfig::updateOrCreate(
            ['key' => $key],
            [
                'value' => $priceId,
                'type' => 'string',
                'label' => 'Stripe Price ID - ' . str_replace('stripe_price_id_', '', $key),
                'description' => 'Automatically generated Stripe Price ID',
            ]
        );
    }
}

