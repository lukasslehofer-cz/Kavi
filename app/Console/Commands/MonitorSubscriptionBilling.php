<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class MonitorSubscriptionBilling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:monitor-billing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor subscription billing system health and send alerts if needed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Monitoring subscription billing system...');
        $this->newLine();
        
        $alerts = [];
        
        // Check 1: Has cron run today?
        $lastRun = Cache::get('subscription_billing_cron_last_run');
        if (!$lastRun || $lastRun->isYesterday()) {
            $alert = '⚠️ Billing cron has NOT run today!';
            $this->error($alert);
            $alerts[] = $alert;
        } else {
            $this->info('✓ Billing cron last run: ' . $lastRun->diffForHumans());
        }
        
        // Check 2: Any overdue payments?
        $overdue = Subscription::where('status', 'active')
            ->whereDate('next_billing_date', '<', today())
            ->count();
            
        if ($overdue > 0) {
            $alert = "⚠️ {$overdue} subscription(s) with overdue payments!";
            $this->warn($alert);
            $alerts[] = $alert;
        } else {
            $this->info('✓ No overdue payments');
        }
        
        // Check 3: Any unpaid subscriptions?
        $unpaid = Subscription::where('status', 'unpaid')->count();
        if ($unpaid > 0) {
            $this->warn("⚠️ {$unpaid} subscription(s) in unpaid status");
            $alerts[] = "⚠️ {$unpaid} unpaid subscriptions";
        } else {
            $this->info('✓ No unpaid subscriptions');
        }
        
        // Check 4: Multiple payment failures today?
        $lastSummary = Cache::get('subscription_billing_cron_last_summary');
        if ($lastSummary && $lastSummary['failed'] > 3) {
            $alert = "⚠️ High failure rate: {$lastSummary['failed']} failures out of {$lastSummary['total']} attempts";
            $this->error($alert);
            $alerts[] = $alert;
        }
        
        // Check 5: Subscriptions expiring cards soon?
        $expiringCards = $this->checkExpiringCards();
        if ($expiringCards > 0) {
            $this->warn("⚠️ {$expiringCards} subscription(s) with cards expiring within 30 days");
        }
        
        $this->newLine();
        
        // Show last billing summary if available
        if ($lastSummary) {
            $this->info('📊 Last Billing Run Summary:');
            $this->table(
                ['Metric', 'Value'],
                [
                    ['Timestamp', $lastSummary['timestamp']],
                    ['Total', $lastSummary['total']],
                    ['Successful', $lastSummary['successful']],
                    ['Failed', $lastSummary['failed']],
                    ['Skipped', $lastSummary['skipped']],
                ]
            );
        }
        
        // Send alerts if critical issues found
        if (!empty($alerts)) {
            $this->newLine();
            $this->error('🚨 CRITICAL ALERTS DETECTED');
            $this->sendCriticalAlerts($alerts);
            return 1; // Exit with error code
        }
        
        $this->newLine();
        $this->info('✅ All systems operational');
        return 0;
    }
    
    /**
     * Check for cards expiring soon
     */
    private function checkExpiringCards(): int
    {
        // This would require Stripe API calls to check expiry dates
        // For now, return 0 - can be implemented later
        return 0;
    }
    
    /**
     * Send critical alerts to admin
     */
    private function sendCriticalAlerts(array $alerts): void
    {
        try {
            $adminEmail = config('mail.from.address');
            
            if ($adminEmail) {
                \Mail::raw(
                    "🚨 SUBSCRIPTION BILLING SYSTEM ALERT\n\n" .
                    "Critical issues detected:\n\n" .
                    implode("\n", $alerts) . "\n\n" .
                    "Time: " . now()->toDateTimeString() . "\n" .
                    "Server: " . config('app.url'),
                    function ($message) use ($adminEmail) {
                        $message->to($adminEmail)
                            ->subject('🚨 Critical: Subscription Billing Issues - ' . now()->toDateString());
                    }
                );
                
                $this->warn("📧 Critical alert email sent to {$adminEmail}");
            }
        } catch (\Exception $e) {
            $this->error("Failed to send alert email: " . $e->getMessage());
        }
    }
}

