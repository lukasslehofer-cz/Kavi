<?php

/**
 * Testovací skript pro nastavení předplatného jako neuhrazené
 * 
 * Použití:
 * php artisan tinker < test_unpaid_subscription.php
 * 
 * Nebo přímo v Tinkeru:
 * require 'test_unpaid_subscription.php';
 */

use App\Models\Subscription;
use App\Models\User;

echo "🔍 Hledání aktivního předplatného...\n";

// Najít první aktivní předplatné
$subscription = Subscription::where('status', 'active')->first();

if (!$subscription) {
    echo "❌ Žádné aktivní předplatné nenalezeno!\n";
    echo "💡 Vytvořte nejdřív předplatné nebo změňte filtr.\n";
    exit;
}

echo "✅ Nalezeno předplatné #" . $subscription->id . "\n";
echo "👤 Uživatel: " . ($subscription->user ? $subscription->user->name : 'Host') . "\n";
echo "💰 Cena: " . $subscription->configured_price . " Kč\n";
echo "\n";

echo "⚠️  Nastavuji status na 'unpaid'...\n";

$subscription->update([
    'status' => 'unpaid',
    'pending_invoice_id' => 'in_test_' . time(),
    'pending_invoice_amount' => $subscription->configured_price ?? 500.00,
    'payment_failure_count' => 1,
    'last_payment_failure_at' => now(),
    'last_payment_failure_reason' => 'Test - Nedostatek prostředků na účtu. Karta byla zamítnuta.'
]);

$subscription->fresh();

echo "✅ Hotovo!\n\n";
echo "📊 Aktuální stav předplatného:\n";
echo "   - Status: " . $subscription->status . "\n";
echo "   - Pending Invoice ID: " . $subscription->pending_invoice_id . "\n";
echo "   - Částka k úhradě: " . $subscription->pending_invoice_amount . " Kč\n";
echo "   - Počet pokusů: " . $subscription->payment_failure_count . "\n";
echo "   - Důvod: " . $subscription->last_payment_failure_reason . "\n";
echo "\n";
echo "🌐 Zobrazit v dashboardu:\n";
echo "   User: http://localhost:8000/dashboard/predplatne\n";
echo "   Admin: http://localhost:8000/admin/subscriptions/" . $subscription->id . "\n";
echo "\n";
echo "💳 Pro test platby použijte button 'Zaplatit nyní' v user dashboardu.\n";
echo "\n";
echo "🔄 Pro obnovení zpět na active:\n";
echo "   \$subscription = Subscription::find(" . $subscription->id . ");\n";
echo "   \$subscription->update(['status' => 'active', 'payment_failure_count' => 0, 'pending_invoice_id' => null]);\n";

