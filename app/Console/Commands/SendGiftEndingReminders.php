<?php

namespace App\Console\Commands;

use App\Mail\GiftSubscriptionEnding;
use App\Models\Subscription;
use App\Services\SubscriptionShipmentService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

/**
 * Dárkové předplatné: jakmile je naplánovaný poslední box, pošle obdarovanému
 * e-mail s nabídkou pokračování. Nic se neobnovuje ani nestrhává automaticky.
 */
class SendGiftEndingReminders extends Command
{
    protected $signature = 'gifts:send-ending-reminders {--dry-run : Jen vypsat, neposílat}';

    protected $description = 'Pošle e-mail s nabídkou pokračování před posledním boxem dárkového předplatného';

    public function handle(SubscriptionShipmentService $shipments): int
    {
        $subscriptions = Subscription::where('status', 'complimentary')
            ->whereNotNull('gift_purchase_id')
            ->whereHas('giftPurchase', fn ($q) => $q->whereNull('ending_reminder_sent_at'))
            ->with(['giftPurchase', 'user'])
            ->get();

        $sent = 0;

        foreach ($subscriptions as $subscription) {
            $plan = $shipments->giftPlan($subscription);
            $pending = $subscription->shipments()->where('status', 'pending')->reorder('shipment_date', 'asc')->get();

            // Poslední box = zbývá 0 dalších a čeká jediný naplánovaný řádek
            if (! $plan || $plan['remaining'] > 0 || $pending->count() !== 1) {
                continue;
            }

            $email = $subscription->shipping_address['email'] ?? $subscription->user?->email;
            if (! $email) {
                continue;
            }

            if ($this->option('dry-run')) {
                $this->line("#{$subscription->id} {$email} poslední box {$pending->first()->shipment_date->toDateString()}");
                continue;
            }

            try {
                Mail::to($email)->send(new GiftSubscriptionEnding($subscription, $pending->first()->shipment_date));
                $subscription->giftPurchase->forceFill(['ending_reminder_sent_at' => now()])->save();
                $sent++;
            } catch (\Throwable $e) {
                \Log::error('Gift ending reminder failed', ['subscription_id' => $subscription->id, 'error' => $e->getMessage()]);
            }
        }

        if ($sent) {
            $this->info("Odesláno: {$sent}");
        }

        return self::SUCCESS;
    }
}
