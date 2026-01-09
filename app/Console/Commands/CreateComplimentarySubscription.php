<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Console\Command;

class CreateComplimentarySubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:create-complimentary
                            {--user-id= : User ID to create subscription for}
                            {--amount= : Number of bags (2, 3, or 4)}
                            {--type= : Coffee type (espresso, filter, or mix)}
                            {--frequency=1 : Frequency in months (1, 2, or 3)}
                            {--decaf=0 : Is decaf (0 or 1)}
                            {--shipping-name= : Shipping contact name}
                            {--shipping-email= : Shipping email}
                            {--shipping-phone= : Shipping phone}
                            {--shipping-address= : Shipping street address}
                            {--shipping-city= : Shipping city}
                            {--shipping-postal-code= : Shipping postal code}
                            {--shipping-country=CZ : Shipping country code}
                            {--packeta-point-id= : Packeta pickup point ID (optional)}
                            {--packeta-point-name= : Packeta pickup point name (optional)}
                            {--packeta-point-address= : Packeta pickup point address (optional)}
                            {--carrier-id= : Carrier ID (optional)}
                            {--carrier-pickup-point= : Carrier pickup point (optional)}
                            {--notes= : Delivery notes or internal notes}
                            {--currency=CZK : Currency (CZK or EUR)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a complimentary (free) subscription for influencers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🎁 Creating Complimentary Subscription');
        $this->newLine();

        // Validate user
        $userId = $this->option('user-id');
        if (!$userId) {
            $this->error('❌ User ID is required (--user-id)');
            return 1;
        }

        $user = User::find($userId);
        if (!$user) {
            $this->error("❌ User with ID {$userId} not found");
            return 1;
        }

        // Validate amount
        $amount = $this->option('amount');
        if (!in_array($amount, ['2', '3', '4'])) {
            $this->error('❌ Amount must be 2, 3, or 4 (--amount)');
            return 1;
        }

        // Validate type
        $type = $this->option('type');
        if (!in_array($type, ['espresso', 'filter', 'mix'])) {
            $this->error('❌ Type must be espresso, filter, or mix (--type)');
            return 1;
        }

        // Validate frequency
        $frequency = $this->option('frequency');
        if (!in_array($frequency, ['1', '2', '3'])) {
            $this->error('❌ Frequency must be 1, 2, or 3 months (--frequency)');
            return 1;
        }

        // Validate required shipping fields
        $requiredShipping = ['shipping-name', 'shipping-email', 'shipping-phone', 'shipping-address', 'shipping-city', 'shipping-postal-code'];
        foreach ($requiredShipping as $field) {
            if (!$this->option($field)) {
                $this->error("❌ {$field} is required");
                return 1;
            }
        }

        // Build configuration
        $configuration = [
            'amount' => $amount,
            'type' => $type,
            'frequency' => (int)$frequency,
            'isDecaf' => $this->option('decaf') === '1',
        ];

        // Handle mix configuration
        if ($type === 'mix') {
            // Default to equal distribution if not specified
            $totalBags = (int)$amount;
            $espressoBags = floor($totalBags / 2);
            $filterBags = $totalBags - $espressoBags;
            
            $configuration['mix'] = [
                'espresso' => $espressoBags,
                'filter' => $filterBags,
            ];
        }

        // Build shipping address
        $shippingAddress = [
            'name' => $this->option('shipping-name'),
            'email' => $this->option('shipping-email'),
            'phone' => $this->option('shipping-phone'),
            'billing_address' => $this->option('shipping-address'),
            'billing_city' => $this->option('shipping-city'),
            'billing_postal_code' => $this->option('shipping-postal-code'),
            'billing_country' => $this->option('shipping-country'),
        ];

        // Add Packeta details if provided
        if ($this->option('packeta-point-id')) {
            $shippingAddress['packeta_point_id'] = $this->option('packeta-point-id');
            $shippingAddress['packeta_point_name'] = $this->option('packeta-point-name');
            $shippingAddress['packeta_point_address'] = $this->option('packeta-point-address');
        }

        // Add carrier details if provided
        if ($this->option('carrier-id')) {
            $shippingAddress['carrier_id'] = $this->option('carrier-id');
            $shippingAddress['carrier_pickup_point'] = $this->option('carrier-pickup-point');
        }

        // Generate subscription number
        $subscriptionNumber = Subscription::generateSubscriptionNumber();

        // Create subscription
        try {
            $subscription = Subscription::create([
                'subscription_number' => $subscriptionNumber,
                'user_id' => $user->id,
                'subscription_plan_id' => null, // No plan for complimentary
                'status' => 'complimentary',
                'configured_price' => 0,
                'currency' => $this->option('currency'),
                'frequency_months' => (int)$frequency,
                'starts_at' => now(),
                'next_billing_date' => null, // No billing for complimentary
                'configuration' => $configuration,
                'shipping_address' => $shippingAddress,
                'shipping_cost' => 0,
                'shipping_country' => $this->option('shipping-country'),
                'delivery_notes' => $this->option('notes'),
                'packeta_point_id' => $this->option('packeta-point-id'),
                'packeta_point_name' => $this->option('packeta-point-name'),
                'packeta_point_address' => $this->option('packeta-point-address'),
                'carrier_id' => $this->option('carrier-id'),
                'carrier_pickup_point' => $this->option('carrier-pickup-point'),
            ]);

            $this->newLine();
            $this->info('✅ Complimentary subscription created successfully!');
            $this->newLine();
            
            $this->table(
                ['Field', 'Value'],
                [
                    ['ID', $subscription->id],
                    ['Number', $subscription->subscription_number],
                    ['User', $user->email],
                    ['Status', $subscription->status],
                    ['Configuration', $amount . 'x ' . ucfirst($type) . ($configuration['isDecaf'] ? ' (Decaf)' : '')],
                    ['Frequency', $frequency . ' month(s)'],
                    ['Price', '0 ' . $this->option('currency')],
                    ['Started', $subscription->starts_at->format('Y-m-d')],
                    ['Shipping To', $this->option('shipping-name')],
                    ['City', $this->option('shipping-city')],
                ]
            );

            if ($this->option('packeta-point-id')) {
                $this->line('📦 Packeta Point: ' . $this->option('packeta-point-name'));
            }

            if ($this->option('notes')) {
                $this->line('📝 Notes: ' . $this->option('notes'));
            }

            $this->newLine();
            $this->info('💡 Next steps:');
            $this->line('   - Subscription is active and ready for shipments');
            $this->line('   - No billing will occur (complimentary status)');
            $this->line('   - User can view it in their dashboard');
            $this->line('   - Admin can manage it in admin panel');

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Failed to create subscription: ' . $e->getMessage());
            $this->line($e->getTraceAsString());
            return 1;
        }
    }
}
