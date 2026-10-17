<?php

namespace App\Services;

use App\Helpers\BoxPricing;
use App\Helpers\CurrencyHelper;
use App\Helpers\VatHelper;
use App\Models\GiftPurchase;
use App\Models\Order;
use Illuminate\Support\Str;

/**
 * Dárky v košíku. Drží se v samostatném klíči session "gift_cart", aby se nemusel
 * měnit tvar běžného košíku ([product_id => množství]), na kterém stojí pokladna.
 *
 * Cena řádku se vždy přepočítá z aktuálních cen (BoxPricing, config/gifts.php),
 * hodnota z formuláře se nikdy nebere jako cena.
 */
class GiftCartService
{
    private const SESSION_KEY = 'gift_cart';

    public const MAX_LINES = 10;

    /**
     * @param  array<string, mixed>  $input  validovaná data z GiftController::addToCart
     */
    public function add(array $input): string
    {
        $lines = session(self::SESSION_KEY, []);
        $id = (string) Str::uuid();

        $lines[$id] = $this->normalizeLine($input + [
            'currency' => CurrencyHelper::code(),
            'locale' => app()->getLocale(),
        ]);

        session()->put(self::SESSION_KEY, $lines);

        return $id;
    }

    public function remove(string $id): void
    {
        $lines = session(self::SESSION_KEY, []);
        unset($lines[$id]);
        session()->put(self::SESSION_KEY, $lines);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /**
     * Řádky v aktuální měně, přeceněné podle aktuálních cen
     *
     * @return array<string, array<string, mixed>>
     */
    public function lines(): array
    {
        $currency = CurrencyHelper::code();
        $result = [];

        foreach (session(self::SESSION_KEY, []) as $id => $line) {
            // Dárek koupený v jiné měně (přepnutí domény) do košíku nepatří
            if (($line['currency'] ?? null) !== $currency) {
                continue;
            }
            $result[$id] = $this->normalizeLine($line);
        }

        return $result;
    }

    public function count(): int
    {
        return count($this->lines());
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    public function isFull(): bool
    {
        return count(session(self::SESSION_KEY, [])) >= self::MAX_LINES;
    }

    /**
     * Součty dárkových řádků s DPH podle sazby dárku
     *
     * @return array{total: float, vat: float, net: float}
     */
    public function totals(): array
    {
        $total = 0.0;
        $vat = 0.0;

        foreach ($this->lines() as $line) {
            $total += $line['price'];
            $vat += VatHelper::calculateVat($line['price'], $line['vat_rate']);
        }

        return ['total' => round($total, 2), 'vat' => round($vat, 2), 'net' => round($total - $vat, 2)];
    }

    /**
     * Otisk obsahu dárkového košíku (null = prázdný). Pokladna podle něj pozná,
     * že čekající objednávka patří k jinému obsahu a nesmí se znovu použít.
     */
    public function hash(): ?string
    {
        $lines = $this->lines();

        if (empty($lines)) {
            return null;
        }

        ksort($lines);

        return sha1(json_encode(array_values($lines)));
    }

    /**
     * Po zrušené platbě vrátí do košíku dárky z čekající objednávky
     */
    public function restoreFromOrder(Order $order): void
    {
        $gifts = $order->giftPurchases()
            ->whereIn('status', [GiftPurchase::STATUS_PENDING, GiftPurchase::STATUS_EXPIRED])
            ->get();

        if ($gifts->isEmpty()) {
            return;
        }

        $lines = [];
        foreach ($gifts as $gift) {
            $lines[(string) Str::uuid()] = $this->normalizeLine([
                'type' => $gift->type,
                'bags' => $gift->bags,
                'months' => $gift->months,
                'voucher_amount' => $gift->isVoucher() ? (float) $gift->amount : null,
                'recipient_name' => $gift->recipient_name,
                'giver_name' => $gift->giver_name,
                'note' => $gift->note,
                'delivery' => $gift->delivery,
                'recipient_email' => $gift->recipient_email,
                'send_at' => $gift->send_at?->format('Y-m-d'),
                'currency' => $gift->currency,
                'locale' => $gift->locale,
            ]);
        }

        session()->put(self::SESSION_KEY, $lines);
    }

    /**
     * Název položky do objednávky a na fakturu
     */
    public function itemName(array $line): string
    {
        return $this->asModel($line)->itemName();
    }

    /**
     * Neuložený GiftPurchase z řádku košíku (pro názvy a náhled certifikátu)
     */
    public function asModel(array $line): GiftPurchase
    {
        return new GiftPurchase([
            'type' => $line['type'],
            'bags' => $line['bags'],
            'months' => $line['months'],
            'amount' => $line['type'] === GiftPurchase::TYPE_VOUCHER ? $line['voucher_amount'] : $line['price'],
            'currency' => $line['currency'],
            'vat_rate' => $line['vat_rate'],
            'locale' => $line['locale'],
            'recipient_name' => $line['recipient_name'],
            'giver_name' => $line['giver_name'],
            'note' => $line['note'],
            'delivery' => $line['delivery'],
            'recipient_email' => $line['recipient_email'],
        ]);
    }

    /**
     * Cena dárku v dané měně
     */
    public static function priceFor(string $type, ?int $bags, ?int $months, ?float $voucherAmount, string $currency): float
    {
        if ($type === GiftPurchase::TYPE_SUBSCRIPTION) {
            return BoxPricing::giftSubscriptionPrice((int) $bags, (int) $months, $currency);
        }

        return (float) $voucherAmount;
    }

    /**
     * @param  array<string, mixed>  $line
     * @return array<string, mixed>
     */
    private function normalizeLine(array $line): array
    {
        $type = $line['type'] === GiftPurchase::TYPE_VOUCHER ? GiftPurchase::TYPE_VOUCHER : GiftPurchase::TYPE_SUBSCRIPTION;
        $isSub = $type === GiftPurchase::TYPE_SUBSCRIPTION;
        $bags = $isSub ? (int) $line['bags'] : null;
        $months = $isSub ? (int) $line['months'] : null;
        $voucherAmount = $isSub ? null : (float) $line['voucher_amount'];
        $currency = $line['currency'];
        $delivery = ($line['delivery'] ?? null) === GiftPurchase::DELIVERY_RECIPIENT ? GiftPurchase::DELIVERY_RECIPIENT : GiftPurchase::DELIVERY_BUYER;

        return [
            'type' => $type,
            'bags' => $bags,
            'months' => $months,
            'voucher_amount' => $voucherAmount,
            'price' => self::priceFor($type, $bags, $months, $voucherAmount, $currency),
            'currency' => $currency,
            'vat_rate' => (float) config('gifts.vat_rates.' . $type, $isSub ? 12 : 0),
            'locale' => $line['locale'] ?? 'cs',
            'recipient_name' => $line['recipient_name'],
            'giver_name' => $line['giver_name'],
            'note' => filled($line['note'] ?? null) ? $line['note'] : null,
            'delivery' => $delivery,
            'recipient_email' => $delivery === GiftPurchase::DELIVERY_RECIPIENT ? ($line['recipient_email'] ?? null) : null,
            'send_at' => $delivery === GiftPurchase::DELIVERY_RECIPIENT ? ($line['send_at'] ?? null) : null,
        ];
    }
}
