<?php

namespace App\Console\Commands;

use App\Mail\OrderReviewRequestMail;
use App\Mail\SubscriptionReviewRequestMail;
use App\Models\Order;
use App\Models\ReviewRequest;
use App\Models\SubscriptionShipment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendReviewRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reviews:send
                            {--type= : Type of review requests to send (order|subscription|all)}
                            {--max= : Maximum number of emails to send in this run}
                            {--dry-run : Run without actually sending emails}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send review requests to customers based on their delivery milestones';

    protected bool $dryRun = false;

    protected ?int $remaining = null;

    /**
     * Identity oslovené v tomhle běhu. V ostrém běhu je zachytí odstup mezi
     * žádostmi, v dry-runu se ale nic neukládá - bez tohohle by výpis nadhodnotil.
     *
     * @var array<string, true>
     */
    protected array $askedThisRun = [];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type') ?? 'all';
        $this->dryRun = (bool) $this->option('dry-run');
        $this->remaining = $this->option('max') !== null ? (int) $this->option('max') : null;

        if ($this->dryRun) {
            $this->warn('DRY RUN - nic se neodešle ani neuloží');
        }

        if (! config('reviews.enabled')) {
            $this->warn('REVIEWS_ENABLED je false - naplánovaný běh se přeskakuje, ruční spuštění pokračuje');
        }

        $this->info('Milníky: '.implode(', ', config('reviews.milestones')));
        $this->info(sprintf(
            'Okno: doručeno před %d až %d dny',
            config('reviews.delay_days'),
            config('reviews.max_age_days')
        ));
        $this->newLine();

        $ordersSent = 0;
        $subscriptionsSent = 0;

        if ($type === 'all' || $type === 'order') {
            $ordersSent = $this->sendOrderReviewRequests();
        }

        if ($type === 'all' || $type === 'subscription') {
            $subscriptionsSent = $this->sendSubscriptionReviewRequests();
        }

        $remindersSent = $this->sendReminders();

        $this->newLine();
        $this->table(
            ['Typ', 'Odesláno'],
            [
                ['Objednávky', $ordersSent],
                ['Předplatné', $subscriptionsSent],
                ['Připomínky', $remindersSent],
                ['Celkem', $ordersSent + $subscriptionsSent + $remindersSent],
            ]
        );

        return Command::SUCCESS;
    }

    /**
     * Žádosti o hodnocení pro doručené objednávky.
     */
    protected function sendOrderReviewRequests(): int
    {
        $this->info('Objednávky');

        $sent = 0;

        $orders = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [
                now()->subDays(config('reviews.max_age_days'))->startOfDay(),
                now()->subDays(config('reviews.delay_days'))->endOfDay(),
            ])
            ->with('user')
            ->orderBy('delivered_at')
            ->get();

        $this->line("  Ve výběru: {$orders->count()} doručených objednávek");

        foreach ($orders as $order) {
            if ($this->exhausted()) {
                break;
            }

            $email = $this->resolveOrderEmail($order);

            if (! $email) {
                continue;
            }

            if ($this->looksLikeBackfill($order->shipped_at, $order->delivered_at)) {
                $this->line("  - #{$order->order_number}: vypadá jako backfill, přeskakuji");

                Log::info('Review request přeskočen kvůli backfillu', [
                    'order_id' => $order->id,
                    'shipped_at' => $order->shipped_at,
                    'delivered_at' => $order->delivered_at,
                ]);

                continue;
            }

            if (! $this->canAsk($order->user_id, $email)) {
                continue;
            }

            $milestone = $this->countDeliveredOrders($order->user_id, $email);

            if (! in_array($milestone, config('reviews.milestones'), true)) {
                continue;
            }

            if (ReviewRequest::existsForMilestone($order->user_id, $email, 'order', $milestone)) {
                continue;
            }

            $this->line("  ✓ #{$order->order_number} → {$email} (milník {$milestone})");

            if (! $this->dryRun) {
                $reviewRequest = ReviewRequest::create([
                    'user_id' => $order->user_id,
                    'email' => $email,
                    'order_id' => $order->id,
                    'review_type' => 'order',
                    'milestone' => $milestone,
                    'tracking_token' => ReviewRequest::generateTrackingToken(),
                    'email_sent_at' => now(),
                ]);

                Mail::to($email)->send(new OrderReviewRequestMail($order, $reviewRequest));
            }

            $this->markAsked($order->user_id, $email);
            $this->consume();
            $sent++;
        }

        $this->info("  Odesláno: {$sent}");
        $this->newLine();

        return $sent;
    }

    /**
     * Žádosti o hodnocení pro doručené zásilky předplatného.
     */
    protected function sendSubscriptionReviewRequests(): int
    {
        $this->info('Předplatné');

        $sent = 0;

        $shipments = SubscriptionShipment::whereBetween('delivered_at', [
            now()->subDays(config('reviews.max_age_days'))->startOfDay(),
            now()->subDays(config('reviews.delay_days'))->endOfDay(),
        ])
            ->with(['subscription.user'])
            ->orderBy('delivered_at')
            ->get();

        $this->line("  Ve výběru: {$shipments->count()} doručených zásilek");

        foreach ($shipments as $shipment) {
            if ($this->exhausted()) {
                break;
            }

            $subscription = $shipment->subscription;

            if (! $subscription || ! $subscription->user) {
                continue;
            }

            $email = $subscription->user->email;

            if ($this->looksLikeBackfill($shipment->sent_at, $shipment->delivered_at)) {
                $this->line("  - #{$subscription->subscription_number}: vypadá jako backfill, přeskakuji");

                Log::info('Review request přeskočen kvůli backfillu', [
                    'shipment_id' => $shipment->id,
                    'sent_at' => $shipment->sent_at,
                    'delivered_at' => $shipment->delivered_at,
                ]);

                continue;
            }

            if (! $this->canAsk($subscription->user_id, $email)) {
                continue;
            }

            $milestone = SubscriptionShipment::where('subscription_id', $subscription->id)
                ->whereNotNull('delivered_at')
                ->where('delivered_at', '<=', $shipment->delivered_at)
                ->count();

            if (! in_array($milestone, config('reviews.milestones'), true)) {
                continue;
            }

            if (ReviewRequest::existsForMilestone(
                $subscription->user_id,
                $email,
                'subscription',
                $milestone,
                $subscription->id
            )) {
                continue;
            }

            $this->line("  ✓ #{$subscription->subscription_number} → {$email} (milník {$milestone})");

            if (! $this->dryRun) {
                $reviewRequest = ReviewRequest::create([
                    'user_id' => $subscription->user_id,
                    'email' => $email,
                    'subscription_id' => $subscription->id,
                    'review_type' => 'subscription',
                    'milestone' => $milestone,
                    'tracking_token' => ReviewRequest::generateTrackingToken(),
                    'email_sent_at' => now(),
                ]);

                Mail::to($email)->send(new SubscriptionReviewRequestMail($subscription, $reviewRequest));
            }

            $this->markAsked($subscription->user_id, $email);
            $this->consume();
            $sent++;
        }

        $this->info("  Odesláno: {$sent}");
        $this->newLine();

        return $sent;
    }

    /**
     * Jedna připomínka pro toho, kdo na žádost vůbec nereagoval.
     */
    protected function sendReminders(): int
    {
        $after = (int) config('reviews.reminder_after_days');

        if ($after <= 0) {
            return 0;
        }

        $this->info('Připomínky');

        $sent = 0;

        $pending = ReviewRequest::real()
            ->whereNotNull('email_sent_at')
            ->whereNull('clicked_at')
            ->whereNull('reminded_at')
            ->where('email_sent_at', '<=', now()->subDays($after))
            ->where('email_sent_at', '>=', now()->subDays($after + config('reviews.max_age_days')))
            ->with(['user', 'order.user', 'subscription.user'])
            ->get();

        $this->line("  Ve výběru: {$pending->count()} nezodpovězených žádostí");

        foreach ($pending as $reviewRequest) {
            if ($this->exhausted()) {
                break;
            }

            // Žádosti založené před přidáním sloupce 'email' ho mají prázdný,
            // příjemce se u nich dohledá z objednávky nebo účtu.
            $email = $reviewRequest->email
                ?? $reviewRequest->order?->shipping_address['email']
                ?? $reviewRequest->user?->email;

            if (! $email) {
                continue;
            }

            // Kdo mezitím kliknul jinou žádostí, připomínku nedostane.
            $cooldown = now()->subMonths((int) config('reviews.click_cooldown_months'));

            if (ReviewRequest::hasClickedSince($reviewRequest->user_id, $email, $cooldown)) {
                continue;
            }

            $mailable = $reviewRequest->review_type === 'order'
                ? ($reviewRequest->order ? new OrderReviewRequestMail($reviewRequest->order, $reviewRequest) : null)
                : ($reviewRequest->subscription ? new SubscriptionReviewRequestMail($reviewRequest->subscription, $reviewRequest) : null);

            if (! $mailable) {
                continue;
            }

            $this->line("  ✓ připomínka → {$email}");

            if (! $this->dryRun) {
                Mail::to($email)->send($mailable);
                $reviewRequest->update(['reminded_at' => now()]);
            }

            $this->consume();
            $sent++;
        }

        $this->info("  Odesláno: {$sent}");

        return $sent;
    }

    /**
     * Společná pravidla potlačení pro jednu identitu.
     */
    protected function canAsk(?int $userId, ?string $email): bool
    {
        if (isset($this->askedThisRun[$this->identityKey($userId, $email)])) {
            return false;
        }

        $cooldown = now()->subMonths((int) config('reviews.click_cooldown_months'));

        if (ReviewRequest::hasClickedSince($userId, $email, $cooldown)) {
            return false;
        }

        $lastSent = ReviewRequest::lastSentAt($userId, $email);

        if ($lastSent && now()->diffInDays($lastSent) < (int) config('reviews.min_days_between_requests')) {
            return false;
        }

        return true;
    }

    /**
     * Kolikátá doručená objednávka to je. Hosté nemají účet, takže se počítá
     * podle e-mailu v doručovací adrese.
     */
    protected function countDeliveredOrders(?int $userId, string $email): int
    {
        $query = Order::where('status', 'delivered');

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->whereNull('user_id')->where('shipping_address->email', $email);
        }

        return $query->count();
    }

    /**
     * E-mail příjemce - stejné pořadí jako v OrderObserver.
     */
    protected function resolveOrderEmail(Order $order): ?string
    {
        return $order->shipping_address['email'] ?? $order->user?->email;
    }

    /**
     * Doručení zapsané dlouho po odeslání znamená skoro vždy zpětný import,
     * ne čerstvý balík. Bez téhle pojistky by první běh oslovil celou historii.
     */
    protected function looksLikeBackfill($sentAt, $deliveredAt): bool
    {
        if (! $sentAt || ! $deliveredAt) {
            return false;
        }

        return $sentAt->diffInDays($deliveredAt) > 30;
    }

    protected function identityKey(?int $userId, ?string $email): string
    {
        return $userId ? "u:{$userId}" : 'e:'.strtolower((string) $email);
    }

    protected function markAsked(?int $userId, ?string $email): void
    {
        $this->askedThisRun[$this->identityKey($userId, $email)] = true;
    }

    protected function exhausted(): bool
    {
        return $this->remaining !== null && $this->remaining <= 0;
    }

    protected function consume(): void
    {
        if ($this->remaining !== null) {
            $this->remaining--;
        }
    }
}
