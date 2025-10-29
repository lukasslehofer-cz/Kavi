<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\StripeService;
use Illuminate\Console\Command;

class ResumeExpiredPausedSubscriptions extends Command
{
    protected $signature = 'subscriptions:resume-paused';

    protected $description = 'Resume subscriptions whose pause period has ended';

    public function handle(StripeService $stripeService): int
    {
        $now = now();
        $paused = Subscription::where('status', 'paused')
            ->whereNotNull('paused_until_date')
            ->whereDate('paused_until_date', '<=', $now->toDateString())
            ->get();

        $this->info('Found ' . $paused->count() . ' subscriptions to resume.');

        foreach ($paused as $subscription) {
            try {
                $stripeService->resumeSubscription($subscription);
            } catch (\Exception $e) {
                $this->error('Failed to resume Stripe for subscription ID ' . $subscription->id . ': ' . $e->getMessage());
            }

            $subscription->resume();

            $this->info('Resumed subscription ID ' . $subscription->id);
        }

        return Command::SUCCESS;
    }
}


