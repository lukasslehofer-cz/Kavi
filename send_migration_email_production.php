<?php
/**
 * Produkční skript pro odeslání migration emailu
 * 
 * POUŽITÍ: php send_migration_email_production.php
 * 
 * Bezpečnostní opatření:
 * - Posílá POUZE na User ID 1 s Subscription ID 34
 * - Zobrazuje preview před odesláním
 * - Vyžaduje explicitní potvrzení
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Subscription;
use App\Mail\WelcomeAfterMigration;
use Illuminate\Support\Facades\Mail;

echo "\n";
echo "========================================\n";
echo "📧 MIGRATION EMAIL - PRODUCTION SEND\n";
echo "========================================\n\n";

// Bezpečnostní konstanta - změň pouze pokud víš co děláš
define('TARGET_USER_ID', 1);
define('TARGET_SUBSCRIPTION_ID', 34);

try {
    // Načti uživatele
    $user = User::find(TARGET_USER_ID);
    
    if (!$user) {
        echo "❌ CHYBA: Uživatel s ID " . TARGET_USER_ID . " nebyl nalezen!\n";
        exit(1);
    }
    
    // Načti předplatné
    $subscription = Subscription::find(TARGET_SUBSCRIPTION_ID);
    
    if (!$subscription) {
        echo "❌ CHYBA: Předplatné s ID " . TARGET_SUBSCRIPTION_ID . " nebylo nalezeno!\n";
        exit(1);
    }
    
    // Ověř, že předplatné patří uživateli
    if ($subscription->user_id !== $user->id) {
        echo "❌ CHYBA: Předplatné ID {$subscription->id} NEPATŘÍ uživateli ID {$user->id}!\n";
        echo "   Předplatné patří uživateli ID: {$subscription->user_id}\n";
        exit(1);
    }
    
    // Zobraz detaily před odesláním
    echo "📋 PREVIEW - Bude odesláno:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    echo "👤 UŽIVATEL:\n";
    echo "   ID:    {$user->id}\n";
    echo "   Jméno: {$user->name}\n";
    echo "   Email: {$user->email}\n";
    echo "   Phone: {$user->phone}\n";
    echo "   Stripe Customer ID: {$user->stripe_customer_id}\n\n";
    
    echo "📦 PŘEDPLATNÉ:\n";
    echo "   ID:     {$subscription->id}\n";
    echo "   Číslo:  {$subscription->subscription_number}\n";
    echo "   Stav:   {$subscription->status}\n";
    echo "   Cena:   {$subscription->configured_price} Kč\n";
    
    if ($subscription->discount_amount > 0) {
        $activeDiscount = ($subscription->discount_months_remaining === null || $subscription->discount_months_remaining > 0) 
            ? $subscription->discount_amount 
            : 0;
        if ($activeDiscount > 0) {
            $displayPrice = $subscription->configured_price - $activeDiscount;
            echo "   Sleva:  -{$activeDiscount} Kč (zobrazená cena: {$displayPrice} Kč)\n";
            echo "   Sleva zbývá: " . ($subscription->discount_months_remaining ?? '∞') . " měsíců\n";
        }
    }
    
    echo "   Frekvence: {$subscription->frequency_months} měsíc(e/ů)\n";
    echo "   Další platba: " . ($subscription->next_billing_date ? $subscription->next_billing_date->format('d.m.Y') : 'N/A') . "\n";
    echo "   Stripe Subscription ID: {$subscription->stripe_subscription_id}\n\n";
    
    echo "📧 EMAIL:\n";
    echo "   Předmět: ☕ Vítejte v novém Kavi obchodě!\n";
    echo "   Šablona: emails.welcome-after-migration\n";
    echo "   Obsahuje: Password set link (platný 7 dní)\n\n";
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    // Kontrola password_set_by_user
    if ($user->password_set_by_user) {
        echo "⚠️  VAROVÁNÍ: Uživatel již má nastavené heslo (password_set_by_user = true)\n";
        echo "   Opravdu chceš poslat email s password reset linkem?\n\n";
    }
    
    // Kontrola app environment
    $appEnv = env('APP_ENV', 'production');
    echo "🌍 Prostředí: {$appEnv}\n";
    
    if ($appEnv !== 'production') {
        echo "⚠️  VAROVÁNÍ: Nejsi v produkčním prostředí!\n\n";
    }
    
    // Kontrola mailu
    $mailDriver = env('MAIL_MAILER', 'smtp');
    echo "📮 Mail driver: {$mailDriver}\n\n";
    
    // Potvrzení
    echo "❓ Opravdu odeslat email na {$user->email}?\n";
    echo "   Napiš 'ODESLAT' pro potvrzení (cokoliv jiného = zrušit): ";
    
    $confirmation = trim(fgets(STDIN));
    
    if ($confirmation !== 'ODESLAT') {
        echo "\n❌ Odesílání zrušeno.\n";
        exit(0);
    }
    
    echo "\n📤 Odesílám email...\n";
    
    // ODESLÁNÍ EMAILU
    Mail::to($user->email)->send(new WelcomeAfterMigration($user, $subscription));
    
    echo "\n✅ Email úspěšně odeslán!\n\n";
    
    echo "📊 SHRNUTÍ:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "✓ Email odeslán na: {$user->email}\n";
    echo "✓ User ID: {$user->id}\n";
    echo "✓ Subscription ID: {$subscription->id}\n";
    echo "✓ Čas odeslání: " . now()->format('d.m.Y H:i:s') . "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    echo "💡 TIP: Zkontroluj si inbox zákazníka a ujisti se, že email dorazil správně.\n";
    echo "💡 TIP: Password reset link je platný 60 minut.\n\n";
    
} catch (\Exception $e) {
    echo "\n❌ CHYBA při odesílání emailu:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Error: {$e->getMessage()}\n";
    echo "File: {$e->getFile()}:{$e->getLine()}\n\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString() . "\n\n";
    exit(1);
}

echo "✅ Hotovo!\n\n";

