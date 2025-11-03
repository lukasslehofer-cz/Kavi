<?php
/**
 * Testovací skript pro odeslání migration emailu
 * Spustit: php send_test_migration_email.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Mail\WelcomeAfterMigration;
use Illuminate\Support\Facades\Mail;

// Vytvoř testovacího uživatele (nebo použij existujícího)
$testUser = User::firstOrCreate(
    ['email' => 'lukas.slehofer@gmail.com'],
    [
        'name' => 'Lukáš Šlehofer',
        'password' => bcrypt('test123'),
        'password_set_by_user' => false,
        'phone' => '+420123456789',
        'address' => 'Testovací 123',
        'city' => 'Praha',
        'postal_code' => '12000',
        'country' => 'CZ',
        'stripe_customer_id' => 'cus_test_' . uniqid(),
    ]
);

// Vytvoř testovací subscription (volitelné)
$testSubscription = $testUser->subscriptions()->first();
if (!$testSubscription) {
    $testSubscription = \App\Models\Subscription::create([
        'subscription_number' => 'KVS-TEST-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
        'user_id' => $testUser->id,
        'stripe_subscription_id' => 'sub_test_' . uniqid(),
        'status' => 'active',
        'starts_at' => now()->subMonths(3),
        'next_billing_date' => now()->addMonth(),
        'configuration' => ['frequency' => 1, 'coffees' => []],
        'configured_price' => 899.00,
        'frequency_months' => 1,
        'shipping_address' => [
            'name' => 'Lukáš Šlehofer',
            'email' => 'lukas.slehofer@gmail.com',
            'phone' => '+420123456789',
            'billing_address' => 'Testovací 123',
            'billing_city' => 'Praha',
            'billing_postal_code' => '12000',
            'country' => 'CZ',
        ],
    ]);
}

echo "📧 Odesílám testovací email na lukas.slehofer@gmail.com...\n\n";

try {
    // Odeslat email
    Mail::to('lukas.slehofer@gmail.com')->send(new WelcomeAfterMigration($testUser, $testSubscription));
    
    echo "✅ Email úspěšně odeslán!\n\n";
    echo "Testovací uživatel:\n";
    echo "  - Email: {$testUser->email}\n";
    echo "  - ID: {$testUser->id}\n";
    echo "  - Subscription ID: {$testSubscription->id}\n";
    echo "  - Stripe Customer ID: {$testUser->stripe_customer_id}\n";
    echo "  - Stripe Subscription ID: {$testSubscription->stripe_subscription_id}\n";
    
} catch (\Exception $e) {
    echo "❌ Chyba při odesílání emailu:\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n✅ Hotovo!\n";

