<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImportWooCommerceSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'woocommerce:import
                            {file : Path to the CSV file with WooCommerce data}
                            {--dry-run : Run without actually creating records}
                            {--skip-users : Skip user creation (if users already exist)}
                            {--skip-subscriptions : Skip subscription creation}
                            {--skip-orders : Skip order history import}
                            {--send-emails : Send welcome emails to customers}';

    /**
     * The console command description.
     */
    protected $description = 'Import customers and subscriptions from WooCommerce (SUMO Subscriptions)';

    private array $stats = [
        'users_created' => 0,
        'users_updated' => 0,
        'users_failed' => 0,
        'subscriptions_created' => 0,
        'subscriptions_failed' => 0,
        'orders_created' => 0,
        'orders_failed' => 0,
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = $this->argument('file');
        $dryRun = $this->option('dry-run');

        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return 1;
        }

        $this->info("🚀 Starting WooCommerce to Kavi migration...");
        if ($dryRun) {
            $this->warn("🔍 DRY RUN MODE - No data will be created");
        }

        // Read CSV file
        $data = $this->readCsv($file);
        
        if (empty($data)) {
            $this->error("No data found in CSV file");
            return 1;
        }

        $this->info("Found " . count($data) . " records to process\n");

        // Start transaction (if not dry run)
        if (!$dryRun) {
            DB::beginTransaction();
        }

        try {
            // Process each customer
            $progressBar = $this->output->createProgressBar(count($data));
            $progressBar->start();

            foreach ($data as $row) {
                $this->processCustomer($row, $dryRun);
                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine(2);

            if (!$dryRun) {
                DB::commit();
                $this->info("✅ Migration completed successfully!");
            } else {
                $this->info("✅ Dry run completed - no data was created");
            }

            // Show statistics
            $this->showStatistics();

            return 0;
        } catch (\Exception $e) {
            if (!$dryRun) {
                DB::rollBack();
            }

            $this->error("❌ Migration failed: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }

    /**
     * Read CSV file and return array of data
     */
    private function readCsv(string $file): array
    {
        $data = [];
        $headers = [];
        
        if (($handle = fopen($file, 'r')) !== false) {
            $rowIndex = 0;
            
            while (($row = fgetcsv($handle, 10000, ',')) !== false) {
                if ($rowIndex === 0) {
                    // First row is headers
                    $headers = array_map('trim', $row);
                } else {
                    // Map row to associative array
                    $data[] = array_combine($headers, $row);
                }
                $rowIndex++;
            }
            
            fclose($handle);
        }
        
        return $data;
    }

    /**
     * Process a single customer record
     */
    private function processCustomer(array $row, bool $dryRun): void
    {
        try {
            // Import user (if not skipped)
            $user = null;
            if (!$this->option('skip-users')) {
                $user = $this->importUser($row, $dryRun);
            } else {
                // Find existing user by email
                $user = User::where('email', $row['email'])->first();
            }

            if (!$user) {
                $this->stats['users_failed']++;
                $this->logMigration($row, null, null, 'failed', 'User not found or created');
                return;
            }

            // Import subscription (if not skipped and data exists)
            if (!$this->option('skip-subscriptions') && !empty($row['stripe_subscription_id'])) {
                $this->importSubscription($row, $user, $dryRun);
            }

            // Import order history (if not skipped)
            if (!$this->option('skip-orders') && !empty($row['order_history'])) {
                $this->importOrderHistory($row, $user, $dryRun);
            }

            $this->logMigration($row, $user->id, null, 'success', 'Migration completed');

        } catch (\Exception $e) {
            $this->stats['users_failed']++;
            $this->logMigration($row, null, null, 'failed', $e->getMessage());
            
            // Log error but continue with other customers
            $this->warn("\n⚠️  Failed to process customer {$row['email']}: {$e->getMessage()}");
        }
    }

    /**
     * Import/create user
     */
    private function importUser(array $row, bool $dryRun): ?User
    {
        if ($dryRun) {
            $this->stats['users_created']++;
            return new User(['id' => 999, 'email' => $row['email']]);
        }

        // Check if user already exists
        $user = User::where('email', $row['email'])->first();

        $userData = [
            'name' => $row['name'] ?? 'Zákazník',
            'email' => $row['email'],
            'phone' => $row['phone'] ?? null,
            'address' => $row['address'] ?? null,
            'city' => $row['city'] ?? null,
            'postal_code' => $row['postal_code'] ?? null,
            'country' => $row['country'] ?? 'CZ',
            'stripe_customer_id' => $row['stripe_customer_id'] ?? null,
        ];

        if ($user) {
            // Update existing user
            $user->update($userData);
            $this->stats['users_updated']++;
        } else {
            // Create new user with random password
            $userData['password'] = Hash::make(Str::random(32));
            $userData['password_set_by_user'] = false; // User will need to set password
            
            $user = User::create($userData);
            $this->stats['users_created']++;
        }

        return $user;
    }

    /**
     * Import subscription
     */
    private function importSubscription(array $row, User $user, bool $dryRun): ?Subscription
    {
        if ($dryRun) {
            $this->stats['subscriptions_created']++;
            return new Subscription(['id' => 999]);
        }

        try {
            // Check if subscription already exists
            $existing = Subscription::where('stripe_subscription_id', $row['stripe_subscription_id'])->first();
            
            if ($existing) {
                $this->warn("\n⚠️  Subscription {$row['stripe_subscription_id']} already exists, skipping");
                return $existing;
            }

            // Parse configuration from CSV (if provided as JSON)
            $configuration = null;
            if (!empty($row['configuration'])) {
                $configuration = json_decode($row['configuration'], true);
            } else {
                // Default configuration - můžete upravit podle potřeby
                $configuration = [
                    'frequency' => (int)($row['frequency_months'] ?? 1),
                    'coffees' => [], // TODO: Fill from product data
                ];
            }

            // Parse shipping address
            $shippingAddress = [
                'name' => $row['name'] ?? $user->name,
                'email' => $row['email'] ?? $user->email,
                'phone' => $row['phone'] ?? $user->phone,
                'billing_address' => $row['address'] ?? $user->address,
                'billing_city' => $row['city'] ?? $user->city,
                'billing_postal_code' => $row['postal_code'] ?? $user->postal_code,
                'country' => $row['country'] ?? 'CZ',
            ];

            // Parse status
            $status = match($row['status'] ?? 'active') {
                'active', 'Active' => 'active',
                'paused', 'Paused', 'on-hold' => 'paused',
                'cancelled', 'Cancelled', 'canceled' => 'cancelled',
                default => 'active',
            };

            $subscription = Subscription::create([
                'subscription_number' => Subscription::generateSubscriptionNumber(),
                'user_id' => $user->id,
                'stripe_subscription_id' => $row['stripe_subscription_id'],
                'status' => $status,
                'starts_at' => !empty($row['start_date']) ? Carbon::parse($row['start_date']) : now(),
                'next_billing_date' => !empty($row['next_payment_date']) ? Carbon::parse($row['next_payment_date']) : now()->addMonth(),
                'configuration' => $configuration,
                'configured_price' => (float)($row['price'] ?? 0),
                'frequency_months' => (int)($row['frequency_months'] ?? 1),
                'shipping_address' => $shippingAddress,
                'delivery_notes' => $row['delivery_notes'] ?? null,
            ]);

            $this->stats['subscriptions_created']++;

            // Send welcome email if requested
            if ($this->option('send-emails')) {
                $this->sendWelcomeEmail($user, $subscription);
            }

            return $subscription;

        } catch (\Exception $e) {
            $this->stats['subscriptions_failed']++;
            throw $e;
        }
    }

    /**
     * Import order history
     */
    private function importOrderHistory(array $row, User $user, bool $dryRun): void
    {
        if ($dryRun) {
            return;
        }

        // Order history může být v CSV jako JSON array
        // Formát: [{"order_number":"123","date":"2024-01-01","total":899,"status":"completed"}]
        
        try {
            $orderHistory = json_decode($row['order_history'], true);
            
            if (!is_array($orderHistory)) {
                return;
            }

            foreach ($orderHistory as $orderData) {
                $this->createHistoricalOrder($orderData, $user);
            }
        } catch (\Exception $e) {
            $this->warn("\n⚠️  Failed to import order history for {$user->email}: {$e->getMessage()}");
        }
    }

    /**
     * Create historical order record
     */
    private function createHistoricalOrder(array $orderData, User $user): void
    {
        try {
            $order = Order::create([
                'order_number' => $orderData['order_number'] ?? 'WC-' . uniqid(),
                'user_id' => $user->id,
                'subtotal' => (float)($orderData['subtotal'] ?? $orderData['total'] ?? 0),
                'shipping' => (float)($orderData['shipping'] ?? 0),
                'tax' => (float)($orderData['tax'] ?? 0),
                'total' => (float)($orderData['total'] ?? 0),
                'status' => 'delivered', // Historical orders are completed
                'payment_status' => 'paid',
                'payment_method' => 'card',
                'shipping_address' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'address' => $user->address,
                    'city' => $user->city,
                    'postal_code' => $user->postal_code,
                    'country' => $user->country ?? 'CZ',
                ],
                'paid_at' => !empty($orderData['date']) ? Carbon::parse($orderData['date']) : now(),
                'shipped_at' => !empty($orderData['date']) ? Carbon::parse($orderData['date'])->addDays(2) : now(),
                'delivered_at' => !empty($orderData['date']) ? Carbon::parse($orderData['date'])->addDays(5) : now(),
                'created_at' => !empty($orderData['date']) ? Carbon::parse($orderData['date']) : now(),
                'admin_notes' => 'Imported from WooCommerce',
            ]);

            $this->stats['orders_created']++;
        } catch (\Exception $e) {
            $this->stats['orders_failed']++;
            throw $e;
        }
    }

    /**
     * Send welcome email to customer
     */
    private function sendWelcomeEmail(User $user, Subscription $subscription): void
    {
        try {
            // TODO: Create WelcomeAfterMigration mail class
            // \Mail::to($user->email)->send(new \App\Mail\WelcomeAfterMigration($user, $subscription));
            
            $this->info("\n📧 Welcome email would be sent to {$user->email}");
        } catch (\Exception $e) {
            $this->warn("\n⚠️  Failed to send email to {$user->email}: {$e->getMessage()}");
        }
    }

    /**
     * Log migration to tracking table
     */
    private function logMigration(array $row, ?int $kaviUserId, ?int $kaviSubscriptionId, string $status, string $notes): void
    {
        try {
            DB::table('woocommerce_migration_log')->insert([
                'wc_user_id' => $row['wc_user_id'] ?? null,
                'wc_subscription_id' => $row['wc_subscription_id'] ?? null,
                'kavi_user_id' => $kaviUserId,
                'kavi_subscription_id' => $kaviSubscriptionId,
                'status' => $status,
                'notes' => $notes,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Don't fail migration if logging fails
            $this->warn("\n⚠️  Failed to log migration: {$e->getMessage()}");
        }
    }

    /**
     * Show migration statistics
     */
    private function showStatistics(): void
    {
        $this->newLine();
        $this->info("📊 Migration Statistics:");
        $this->table(
            ['Category', 'Count'],
            [
                ['Users Created', $this->stats['users_created']],
                ['Users Updated', $this->stats['users_updated']],
                ['Users Failed', $this->stats['users_failed']],
                ['Subscriptions Created', $this->stats['subscriptions_created']],
                ['Subscriptions Failed', $this->stats['subscriptions_failed']],
                ['Orders Created', $this->stats['orders_created']],
                ['Orders Failed', $this->stats['orders_failed']],
            ]
        );
    }
}


