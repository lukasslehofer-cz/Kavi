<?php

namespace App\Console\Commands;

use App\Mail\OrderReviewRequestMail;
use App\Mail\ReviewNudgeMail;
use App\Mail\SubscriptionReviewRequestMail;
use App\Models\Order;
use App\Models\ReviewRequest;
use App\Models\SubscriptionShipment;
use App\Services\GoogleReviewsService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
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
     * Kliknuté žádosti načtené jednou pro párování i výzvy. V dry-runu se
     * spárování nikam neukládá, takže musí platit aspoň na těchhle modelech.
     *
     * @var Collection<int, ReviewRequest>
     */
    protected Collection $clickedRequests;

    /**
     * Identity, které už recenzi na Googlu mají (klíče u:{id} i e:{email}).
     *
     * @var array<string, true>
     */
    protected array $reviewedIdentities = [];

    /**
     * Profily, pro které je čerstvé stažení recenzí. Bez něj nevíme, kdo
     * recenzi dopsal, a výzva naslepo by šla i těm, kdo ji mají hotovou.
     *
     * @var array<string, bool>
     */
    protected array $profileKnown = [];

    /**
     * Execute the console command.
     */
    public function handle(GoogleReviewsService $googleReviews)
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

        // Nejdřív zjistit, kdo recenzi dopsal - řídí se tím žádosti i výzvy
        $this->matchGoogleReviews($googleReviews);

        $ordersSent = 0;
        $subscriptionsSent = 0;

        if ($type === 'all' || $type === 'order') {
            $ordersSent = $this->sendOrderReviewRequests();
        }

        if ($type === 'all' || $type === 'subscription') {
            $subscriptionsSent = $this->sendSubscriptionReviewRequests();
        }

        $remindersSent = $this->sendReminders();
        $nudgesSent = $this->sendNudges();

        $this->newLine();
        $this->table(
            ['Typ', 'Odesláno'],
            [
                ['Objednávky', $ordersSent],
                ['Předplatné', $subscriptionsSent],
                ['Připomínky', $remindersSent],
                ['Výzvy k dokončení', $nudgesSent],
                ['Celkem', $ordersSent + $subscriptionsSent + $remindersSent + $nudgesSent],
            ]
        );

        return Command::SUCCESS;
    }

    /**
     * Spáruje recenze z Googlu s kliknutími z e-mailu.
     *
     * Hvězdička z e-mailu se na Google nepřenese, takže jediné, co kliknutí
     * a recenzi spojuje, je čas: recenze vzniká pár minut po kliku. Jméno
     * autora ani text se s nikým neporovnávají a u žádosti se uloží jen
     * okamžik zjištění.
     *
     * Prochází se pokaždé všechny recenze proti všem kliknutím, i těm už
     * spárovaným. Když nejbližší klik recenzi má, recenze se přeskočí - kdyby
     * si místo toho hledala další klik v okně, označila by cizího člověka.
     */
    protected function matchGoogleReviews(GoogleReviewsService $googleReviews): void
    {
        $this->info('Recenze na Googlu');

        $window = (int) config('reviews.match_window_hours');
        $maxAge = (int) config('reviews.google_max_age_hours');

        $this->clickedRequests = ReviewRequest::real()
            ->whereNotNull('clicked_at')
            ->with(['user', 'order.user', 'subscription.user'])
            ->orderBy('clicked_at')
            ->get();

        $byProfile = $this->clickedRequests->groupBy(
            fn (ReviewRequest $reviewRequest) => $reviewRequest->profileLocale()
        );

        $paired = 0;

        foreach (array_keys(config('services.review_links', [])) as $locale) {
            $times = $googleReviews->reviewTimes($locale, $maxAge);

            if ($times === null) {
                $this->profileKnown[$locale] = false;
                $this->warn("  Profil {$locale}: chybí čerstvé stažení recenzí, nepáruji a výzvy pro něj přeskočím");

                continue;
            }

            $this->profileKnown[$locale] = true;

            $candidates = $byProfile->get($locale, collect());

            // Od nejstarší, ať je výsledek stejný při každém běhu
            usort($times, fn ($a, $b) => $a->getTimestamp() <=> $b->getTimestamp());

            foreach ($times as $reviewTime) {
                $best = null;
                $bestGap = null;

                foreach ($candidates as $candidate) {
                    $gap = $candidate->clickGapTo($reviewTime, $window);

                    if ($gap !== null && ($bestGap === null || $gap < $bestGap)) {
                        $best = $candidate;
                        $bestGap = $gap;
                    }
                }

                if (! $best || $best->google_review_found_at) {
                    continue;
                }

                $this->line("  ✓ žádost #{$best->id}: recenze nalezena (klik {$best->clicked_at->format('d.m.Y H:i')})");

                $best->google_review_found_at = now();

                if (! $this->dryRun) {
                    $best->save();
                }

                $paired++;
            }
        }

        foreach ($this->clickedRequests as $reviewRequest) {
            if ($reviewRequest->google_review_found_at) {
                foreach ($this->identityKeys($reviewRequest->user_id, $reviewRequest->email) as $key) {
                    $this->reviewedIdentities[$key] = true;
                }
            }
        }

        $this->info("  Nově spárováno: {$paired}");
        $this->newLine();
    }

    /**
     * Žádosti o hodnocení pro doručené objednávky.
     */
    protected function sendOrderReviewRequests(): int
    {
        $this->info('Objednávky');

        $sent = 0;

        $orders = Order::where('status', 'delivered')
            // Objednávka jen s dárky (certifikát e-mailem) nemá co hodnotit
            ->whereHas('items', fn ($q) => $q->where('is_gift', false))
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

            $email = $this->resolveRecipient($reviewRequest);

            if (! $email) {
                continue;
            }

            // Kdo mezitím kliknul jinou žádostí, připomínku nedostane.
            $cooldown = now()->subMonths((int) config('reviews.click_cooldown_months'));

            if (ReviewRequest::hasClickedSince($reviewRequest->user_id, $email, $cooldown)) {
                continue;
            }

            $mailable = $reviewRequest->review_type === 'order'
                ? ($reviewRequest->order ? new OrderReviewRequestMail($reviewRequest->order, $reviewRequest, reminder: true) : null)
                : ($reviewRequest->subscription ? new SubscriptionReviewRequestMail($reviewRequest->subscription, $reviewRequest, reminder: true) : null);

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
        $this->newLine();

        return $sent;
    }

    /**
     * Jedna výzva pro toho, kdo v e-mailu kliknul, ale recenzi na Googlu
     * nedopsal. Hvězdičku kliknutou v e-mailu snadno považuje za hotové
     * hodnocení, takže bez výzvy by o chybějícím kroku nevěděl.
     *
     * Jde všem bez ohledu na počet hvězdiček. Vyzývat jen spokojené by bylo
     * filtrování recenzí - viz GoogleReviewsService.
     */
    protected function sendNudges(): int
    {
        $after = (int) config('reviews.nudge_after_days');

        if ($after <= 0) {
            return 0;
        }

        $this->info('Výzvy k dokončení');

        $sent = 0;
        $maxAge = (int) config('reviews.nudge_max_age_days');
        $cooldown = now()->subMonths((int) config('reviews.click_cooldown_months'));

        $pending = $this->clickedRequests->filter(
            fn (ReviewRequest $reviewRequest) => ! $reviewRequest->google_review_found_at
                && ! $reviewRequest->nudged_at
                && ($reviewRequest->last_clicked_at ?? $reviewRequest->clicked_at)->lte(now()->subDays($after))
                && $reviewRequest->clicked_at->gte(now()->subDays($maxAge))
        );

        $this->line("  Ve výběru: {$pending->count()} kliknutí bez recenze");

        $nudgedThisRun = [];

        foreach ($pending as $reviewRequest) {
            if ($this->exhausted()) {
                break;
            }

            if (! ($this->profileKnown[$reviewRequest->profileLocale()] ?? false)) {
                $this->line("  - žádost #{$reviewRequest->id}: bez čerstvých recenzí z Googlu, přeskakuji");

                continue;
            }

            $email = $this->resolveRecipient($reviewRequest);

            if (! $email) {
                continue;
            }

            $keys = $this->identityKeys($reviewRequest->user_id, $email);

            // Recenzi má pod jinou žádostí, nebo už výzvu dostal
            if (array_intersect_key(array_flip($keys), $this->reviewedIdentities + $nudgedThisRun)) {
                continue;
            }

            if (ReviewRequest::wasNudgedSince($reviewRequest->user_id, $email, $cooldown)) {
                continue;
            }

            $subject = $reviewRequest->review_type === 'order'
                ? $reviewRequest->order
                : $reviewRequest->subscription;

            if (! $subject) {
                continue;
            }

            $this->line("  ✓ výzva → {$email} (žádost #{$reviewRequest->id})");

            if (! $this->dryRun) {
                // Fronta je sync, takže chyba SMTP by jinak shodila celý běh.
                // nudged_at až po úspěchu, ať se nezdařená výzva zkusí zítra.
                try {
                    Mail::to($email)->send(new ReviewNudgeMail($reviewRequest, $subject));
                    $reviewRequest->update(['nudged_at' => now()]);
                } catch (\Throwable $e) {
                    $this->error("    odeslání selhalo: {$e->getMessage()}");

                    Log::warning('Výzva k dokončení hodnocení se neodeslala', [
                        'review_request_id' => $reviewRequest->id,
                        'error' => $e->getMessage(),
                    ]);

                    continue;
                }
            }

            foreach ($keys as $key) {
                $nudgedThisRun[$key] = true;
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

        // Google dovolí jednu recenzi na účet a místo. Kdo ji napsal, už se
        // o další neprosí - na rozdíl od kliknutí bez časového omezení.
        if (array_intersect_key(array_flip($this->identityKeys($userId, $email)), $this->reviewedIdentities)) {
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

    /**
     * Komu připomínka nebo výzva půjde. Žádosti založené před přidáním sloupce
     * 'email' ho mají prázdný, příjemce se u nich dohledá z objednávky nebo účtu.
     *
     * Smazaný účet nedostane nic: AccountDeletionService adresu u žádosti
     * maže, tohle je pojistka pro řádky anonymizované dřív.
     */
    protected function resolveRecipient(ReviewRequest $reviewRequest): ?string
    {
        $user = $reviewRequest->user
            ?? $reviewRequest->order?->user
            ?? $reviewRequest->subscription?->user;

        if ($user?->anonymized_at) {
            return null;
        }

        return $reviewRequest->email
            ?? $reviewRequest->order?->shipping_address['email']
            ?? $user?->email;
    }

    protected function identityKey(?int $userId, ?string $email): string
    {
        return $userId ? "u:{$userId}" : 'e:'.strtolower((string) $email);
    }

    /**
     * Oba klíče naráz. ReviewRequest::forIdentity() páruje přes účet NEBO
     * e-mail, takže jeden klíč z identityKey() by hosta s účtem minul.
     *
     * @return array<int, string>
     */
    protected function identityKeys(?int $userId, ?string $email): array
    {
        return array_values(array_filter([
            $userId ? "u:{$userId}" : null,
            $email ? 'e:'.strtolower($email) : null,
        ]));
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
