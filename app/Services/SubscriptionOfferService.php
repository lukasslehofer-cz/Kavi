<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Nabídka předplatného v e-mailu o doručení objednávky z e-shopu: komu se ukáže,
 * záznam o odeslání a přehled, kolik kupců do 60 dnů na předplatné přešlo.
 *
 * Kupec = zákazník objednávky podle účtu, u objednávky bez účtu podle e-mailu.
 * Přechod = pravidelné (ne dárkové, ne bezplatné) předplatné založené a zaplacené
 * do CONVERSION_DAYS dnů od první nabídky.
 */
class SubscriptionOfferService
{
    public const CONVERSION_DAYS = 60;

    /** Stavy, ve kterých zákazník předplatné má a nabídka by šla mimo */
    private const LIVE_STATUSES = ['active', 'paused', 'unpaid', 'complimentary'];

    /**
     * Má e-mail o doručení této objednávky nabídnout předplatné?
     */
    public function shouldOffer(Order $order): bool
    {
        // Doplňková objednávka přibalená k boxu = zákazník předplatné má
        if ($order->shipped_with_subscription || $order->subscription_id) {
            return false;
        }

        $email = $this->buyerEmail($order);

        if (! $order->user_id && ! $email) {
            return false;
        }

        return ! Subscription::query()
            ->whereIn('status', self::LIVE_STATUSES)
            ->where(function ($query) use ($order, $email) {
                if ($order->user_id) {
                    $query->orWhere('user_id', $order->user_id);
                }

                if ($email) {
                    $query->orWhereHas('user', fn ($user) => $user->whereRaw('LOWER(email) = ?', [$email]))
                        ->orWhereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(shipping_address, "$.email"))) = ?', [$email]);
                }
            })
            ->exists();
    }

    /**
     * Zapíše, že kupec nabídku dostal. Přímý UPDATE: volá se z observeru uvnitř
     * ukládání objednávky a nesmí spustit další kolo událostí modelu.
     */
    public function markOffered(Order $order): void
    {
        Order::whereKey($order->id)
            ->whereNull('subscription_offer_sent_at')
            ->update(['subscription_offer_sent_at' => now()]);
    }

    /**
     * Přehled pro administraci: kupci s nabídkou a jejich přechod na předplatné,
     * pro srovnání totéž u objednávek doručených bez nabídky.
     */
    public function stats(): array
    {
        $days = self::CONVERSION_DAYS;
        $subscriptions = $this->subscriptionsByBuyerKey();

        $orders = Order::with('user:id,email')
            ->whereNotNull('delivered_at')
            ->where('shipped_with_subscription', false)
            ->whereNull('subscription_id')
            ->orderBy('delivered_at')
            ->get(['id', 'order_number', 'user_id', 'shipping_address', 'delivered_at', 'subscription_offer_sent_at']);

        // Každý kupec jednou, od své první nabídky
        $offered = $orders->whereNotNull('subscription_offer_sent_at')
            ->sortBy('subscription_offer_sent_at')
            ->unique(fn (Order $order) => $this->orderKeys($order)[0] ?? 'o:'.$order->id)
            ->map(function (Order $order) use ($subscriptions, $days) {
                $from = $order->subscription_offer_sent_at;
                $subscription = $this->firstSubscriptionBetween($subscriptions, $this->orderKeys($order), $from, $days);

                return [
                    'order' => $order,
                    'email' => $this->buyerEmail($order),
                    'offered_at' => $from,
                    'open' => $from->copy()->addDays($days)->isFuture(),
                    'subscription' => $subscription,
                    'days_to_convert' => $subscription ? (int) $from->diffInDays($subscription->created_at) : null,
                ];
            })
            ->values();

        $closed = $offered->where('open', false);
        $open = $offered->where('open', true);

        // Srovnání: kupci, jejichž první doručená objednávka přišla bez nabídky,
        // v tu chvíli předplatné neměli a jejich 60 dnů už uběhlo
        $baseline = $orders->whereNull('subscription_offer_sent_at')
            ->unique(fn (Order $order) => $this->orderKeys($order)[0] ?? 'o:'.$order->id)
            ->filter(fn (Order $order) => ! $order->delivered_at->copy()->addDays($days)->isFuture()
                && ! $this->hadSubscriptionAt($subscriptions, $this->orderKeys($order), $order->delivered_at))
            ->map(fn (Order $order) => $this->firstSubscriptionBetween($subscriptions, $this->orderKeys($order), $order->delivered_at, $days) !== null);

        return [
            'days' => $days,
            'offered' => [
                'buyers' => $offered->count(),
                'closed' => $closed->count(),
                'closed_converted' => $closed->whereNotNull('subscription')->count(),
                'rate' => $this->rate($closed->whereNotNull('subscription')->count(), $closed->count()),
                'open' => $open->count(),
                'open_converted' => $open->whereNotNull('subscription')->count(),
            ],
            'baseline' => [
                'buyers' => $baseline->count(),
                'converted' => $baseline->filter()->count(),
                'rate' => $this->rate($baseline->filter()->count(), $baseline->count()),
            ],
            'months' => $offered
                ->groupBy(fn (array $row) => $row['offered_at']->format('Y-m'))
                ->map(fn (Collection $rows, string $month) => [
                    'month' => $month,
                    'buyers' => $rows->count(),
                    'converted' => $rows->whereNotNull('subscription')->count(),
                    'rate' => $this->rate($rows->whereNotNull('subscription')->count(), $rows->count()),
                    'open' => $rows->contains('open', true),
                ])
                ->sortKeysDesc()
                ->values(),
            'recent' => $offered->sortByDesc('offered_at')->take(30)->values(),
        ];
    }

    /**
     * E-mail kupce malými písmeny: adresa z objednávky, jinak z účtu
     */
    public function buyerEmail(Order $order): ?string
    {
        $email = $order->shipping_address['email'] ?? $order->user?->email ?? null;

        return $email ? mb_strtolower(trim($email)) : null;
    }

    /**
     * Klíče, podle kterých se kupec páruje s předplatným. První je hlavní (podle něj
     * se kupec počítá jednou): účet, u objednávky bez účtu e-mail.
     *
     * @return array<int, string>
     */
    private function orderKeys(Order $order): array
    {
        $email = $this->buyerEmail($order);

        return array_values(array_filter([
            $order->user_id ? 'u:'.$order->user_id : null,
            $email ? 'e:'.$email : null,
        ]));
    }

    /**
     * Pravidelná placená předplatná podle klíče kupce (účet, e-mail účtu, e-mail doručení)
     *
     * @return array<string, array<int, Subscription>>
     */
    private function subscriptionsByBuyerKey(): array
    {
        $byKey = [];

        Subscription::with('user:id,email')
            ->where('frequency_months', '>', 0)
            ->whereNull('gift_purchase_id')
            // pending = nedokončená objednávka předplatného, complimentary = bezplatné
            ->whereNotIn('status', ['pending', 'complimentary'])
            ->orderBy('created_at')
            ->get(['id', 'subscription_number', 'user_id', 'status', 'shipping_address', 'created_at', 'ends_at'])
            ->each(function (Subscription $subscription) use (&$byKey) {
                $emails = array_filter([
                    $subscription->user?->email,
                    $subscription->shipping_address['email'] ?? null,
                ]);

                $keys = array_unique(array_merge(
                    $subscription->user_id ? ['u:'.$subscription->user_id] : [],
                    array_map(fn ($email) => 'e:'.mb_strtolower(trim($email)), $emails),
                ));

                foreach ($keys as $key) {
                    $byKey[$key][] = $subscription;
                }
            });

        return $byKey;
    }

    private function subscriptionsFor(array $byKey, array $keys): Collection
    {
        return collect($keys)
            ->flatMap(fn (string $key) => $byKey[$key] ?? [])
            ->unique('id')
            ->sortBy('created_at')
            ->values();
    }

    /**
     * První předplatné kupce založené do N dnů od daného okamžiku
     */
    private function firstSubscriptionBetween(array $byKey, array $keys, Carbon $from, int $days): ?Subscription
    {
        $until = $from->copy()->addDays($days);

        return $this->subscriptionsFor($byKey, $keys)
            ->first(fn (Subscription $subscription) => $subscription->created_at->betweenIncluded($from, $until));
    }

    /**
     * Měl kupec v daný okamžik běžící předplatné? Zrušené se počítá do svého ends_at
     * (datum zrušení se jinde neukládá).
     */
    private function hadSubscriptionAt(array $byKey, array $keys, Carbon $at): bool
    {
        return $this->subscriptionsFor($byKey, $keys)->contains(function (Subscription $subscription) use ($at) {
            if ($subscription->created_at->gte($at)) {
                return false;
            }

            return in_array($subscription->status, self::LIVE_STATUSES, true)
                || ($subscription->ends_at && $subscription->ends_at->gt($at));
        });
    }

    private function rate(int $converted, int $total): ?float
    {
        return $total > 0 ? round($converted / $total * 100, 1) : null;
    }
}
