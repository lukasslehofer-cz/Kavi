<?php

namespace App\Helpers;

use App\Models\GiftPurchase;
use App\Models\Order;
use App\Models\Subscription;
use Carbon\CarbonInterface;

/**
 * Data události Purchase pro Meta – jeden zdroj pro Pixel v prohlížeči
 * (děkovací stránky) i pro Conversions API na serveru (MetaConversionsService).
 *
 * Obě strany musí poslat totéž: Meta páruje Pixel a CAPI podle event_name + event_id
 * a hodnoty, které se liší, by v reportech rozbily obsah i hodnotu nákupu.
 */
class MetaPurchaseData
{
    public const CATEGORY_SUBSCRIPTION = 'predplatne';
    public const CATEGORY_GIFT = 'darek';
    public const CATEGORY_VOUCHER = 'poukaz';
    public const CATEGORY_ESHOP = 'eshop';

    /**
     * Meta deduplikuje Pixel a CAPI jen v tomto okně. Pozdější otevření děkovací
     * stránky by se počítalo jako nový nákup, proto Pixel po něm už nestřílí.
     */
    public const BROWSER_WINDOW_HOURS = 48;

    // Při shodné hodnotě rozhoduje pořadí (smíšená objednávka zboží + dárek)
    private const CATEGORY_PRIORITY = [
        self::CATEGORY_ESHOP,
        self::CATEGORY_GIFT,
        self::CATEGORY_VOUCHER,
    ];

    /**
     * @return array{event_id: string, custom_data: array<string, mixed>}
     */
    public static function forOrder(Order $order): array
    {
        $order->loadMissing('items.giftPurchase');

        $contents = [];
        $valueByCategory = [];

        foreach ($order->items as $item) {
            [$id, $category] = self::orderItemIdentity($item);
            $quantity = max(1, (int) $item->quantity);
            $total = (float) $item->price * $quantity;

            // Dva poukazy v jedné objednávce jsou jedna položka s množstvím 2
            if (isset($contents[$id])) {
                $contents[$id]['quantity'] += $quantity;
                $contents[$id]['_total'] += $total;
            } else {
                $contents[$id] = ['id' => $id, 'quantity' => $quantity, '_total' => $total];
            }

            $valueByCategory[$category] = ($valueByCategory[$category] ?? 0) + (float) $item->total;
        }

        $contents = array_values(array_map(fn (array $content) => [
            'id' => $content['id'],
            'quantity' => $content['quantity'],
            'item_price' => round($content['_total'] / $content['quantity'], 2),
        ], $contents));

        return [
            'event_id' => (string) ($order->order_number ?: $order->meta_event_id ?: 'order-'.$order->id),
            'custom_data' => self::customData(
                value: (float) $order->total,
                currency: $order->currency,
                contents: $contents,
                category: self::dominantCategory($valueByCategory),
            ),
        ];
    }

    /**
     * První platba předplatného (i jednorázového boxu). Opakované platby se jako
     * Purchase neposílají.
     *
     * @return array{event_id: string, custom_data: array<string, mixed>}
     */
    public static function forSubscription(Subscription $subscription): array
    {
        $config = is_array($subscription->configuration)
            ? $subscription->configuration
            : (json_decode((string) $subscription->configuration, true) ?: []);
        $breakdown = SubscriptionPricing::forFirstPayment($subscription);

        $contents = [[
            // Stejné ID jako item_id v dataLayer na děkovací stránce
            'id' => 'subscription-'.($config['amount'] ?? 3),
            'quantity' => 1,
            'item_price' => round(max(0, $breakdown->box - $breakdown->discount), 2),
        ]];

        return [
            'event_id' => (string) ($subscription->subscription_number ?: $subscription->meta_event_id ?: 'subscription-'.$subscription->id),
            'custom_data' => self::customData(
                value: $breakdown->total,
                currency: $breakdown->currency,
                contents: $contents,
                category: self::CATEGORY_SUBSCRIPTION,
            ),
        ];
    }

    /**
     * Smí děkovací stránka ještě vystřelit Purchase v prohlížeči?
     */
    public static function withinBrowserWindow(?CarbonInterface $paidAt): bool
    {
        return $paidAt !== null && $paidAt->gt(now()->subHours(self::BROWSER_WINDOW_HOURS));
    }

    /**
     * @param  array<int, array{id: string, quantity: int, item_price: float}>  $contents
     * @return array<string, mixed>
     */
    private static function customData(float $value, ?string $currency, array $contents, string $category): array
    {
        return [
            'value' => round($value, 2),
            'currency' => strtoupper($currency ?: 'CZK'),
            'content_type' => 'product',
            'content_category' => $category,
            'content_ids' => array_column($contents, 'id'),
            'contents' => $contents,
            'num_items' => array_sum(array_column($contents, 'quantity')),
        ];
    }

    /**
     * @return array{0: string, 1: string} [content id, content_category]
     */
    private static function orderItemIdentity($item): array
    {
        if ($item->is_gift) {
            return $item->giftPurchase?->type === GiftPurchase::TYPE_VOUCHER
                ? ['gift-voucher', self::CATEGORY_VOUCHER]
                : ['gift-subscription', self::CATEGORY_GIFT];
        }

        // ID produktu = g:id v katalogovém feedu (FacebookCatalogFeedController)
        return [$item->product_id ? (string) $item->product_id : 'item-'.$item->id, self::CATEGORY_ESHOP];
    }

    /**
     * @param  array<string, float>  $valueByCategory
     */
    private static function dominantCategory(array $valueByCategory): string
    {
        $best = self::CATEGORY_ESHOP;
        $bestValue = null;

        foreach (self::CATEGORY_PRIORITY as $category) {
            if (! array_key_exists($category, $valueByCategory)) {
                continue;
            }
            if ($bestValue === null || $valueByCategory[$category] > $bestValue) {
                $best = $category;
                $bestValue = $valueByCategory[$category];
            }
        }

        return $best;
    }
}
