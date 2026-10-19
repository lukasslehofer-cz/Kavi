<?php

namespace App\Services;

use App\Mail\GiftCertificateForBuyer;
use App\Mail\GiftCertificateForRecipient;
use App\Models\GiftPurchase;
use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Doručení vystaveného dárku: PDF certifikát, e-mail kupujícímu a v zadaný den
 * obdarovanému. Každý e-mail se odešle nejvýš jednou (zámek + časové značky
 * *_email_sent_at), chyby se zapíší k dárku a zkusí znovu příkaz gifts:deliver.
 */
class GiftDeliveryService
{
    public const MAX_ATTEMPTS = 5;

    public function __construct(private GiftCertificateService $certificates) {}

    public function deliverOrder(int $orderId): void
    {
        $gifts = GiftPurchase::where('order_id', $orderId)
            ->whereIn('status', [GiftPurchase::STATUS_ISSUED, GiftPurchase::STATUS_REDEEMED])
            ->get();

        foreach ($gifts as $gift) {
            $this->deliver($gift);
        }

        $this->markOrderDeliveredIfDone($orderId);
    }

    public function deliver(GiftPurchase $gift): void
    {
        Cache::lock('gift-deliver-' . $gift->id, 120)->get(function () use ($gift) {
            $gift->refresh();

            if (! $gift->isIssued()) {
                return;
            }

            try {
                $pdfPath = $this->certificates->ensurePdf($gift);

                if (! $gift->buyer_email_sent_at && $gift->buyer_email) {
                    Mail::to($gift->buyer_email)->send(new GiftCertificateForBuyer($gift, $pdfPath));
                    $gift->forceFill(['buyer_email_sent_at' => now()])->save();
                    Log::info('Gift certificate sent to buyer', ['gift_id' => $gift->id]);
                }

                if ($this->isDueForRecipient($gift)) {
                    // Obdarovaný už dárek uplatnil (kupující mu dal PDF dřív) - e-mail by mátl
                    if ($gift->status === GiftPurchase::STATUS_REDEEMED) {
                        $gift->forceFill(['recipient_email_sent_at' => now()])->save();
                        Log::info('Gift recipient email skipped, already redeemed', ['gift_id' => $gift->id]);
                    } else {
                        Mail::to($gift->recipient_email)->send(new GiftCertificateForRecipient($gift, $pdfPath));
                        $gift->forceFill(['recipient_email_sent_at' => now()])->save();
                        Log::info('Gift certificate sent to recipient', ['gift_id' => $gift->id]);
                    }
                }

                if ($gift->last_delivery_error) {
                    $gift->forceFill(['last_delivery_error' => null])->save();
                }
            } catch (\Throwable $e) {
                $gift->forceFill([
                    'delivery_attempts' => min(255, $gift->delivery_attempts + 1),
                    'last_delivery_error' => mb_substr($e->getMessage(), 0, 1000),
                ])->save();

                Log::error('Gift delivery failed', ['gift_id' => $gift->id, 'error' => $e->getMessage()]);
            }
        });
    }

    /**
     * Pojistka z cronu: dovystavit zaseklé dárky, doručit kupujícím a obdarovaným
     *
     * @return array{issued: int, delivered: int}
     */
    public function runDue(GiftFulfilmentService $fulfilment): array
    {
        $issued = 0;
        $delivered = 0;

        // Zaplacené objednávky, jejichž dárky se nevystavily (výpadek, chyba)
        $stuck = GiftPurchase::whereHas('order', fn ($q) => $q->where('payment_status', 'paid'))
            ->where(function ($q) {
                $q->whereIn('status', [GiftPurchase::STATUS_PENDING, GiftPurchase::STATUS_EXPIRED])
                    ->orWhere(fn ($q) => $q->where('status', GiftPurchase::STATUS_ISSUING)
                        ->where('issuing_started_at', '<', now()->subMinutes(GiftFulfilmentService::STALE_ISSUING_MINUTES)));
            })
            ->pluck('id');

        foreach ($stuck as $giftId) {
            try {
                if ($fulfilment->issue($giftId)) {
                    $issued++;
                }
            } catch (\Throwable $e) {
                // zalogováno v issue(), zkusí se příště
            }
        }

        $due = GiftPurchase::whereIn('status', [GiftPurchase::STATUS_ISSUED, GiftPurchase::STATUS_REDEEMED])
            ->where('delivery_attempts', '<', self::MAX_ATTEMPTS)
            ->where(function ($q) {
                $q->whereNull('buyer_email_sent_at')
                    ->orWhere(fn ($q) => $q->dueForRecipient());
            })
            ->get();

        foreach ($due as $gift) {
            $this->deliver($gift);
            $delivered++;
            $this->markOrderDeliveredIfDone($gift->order_id);
        }

        return ['issued' => $issued, 'delivered' => $delivered];
    }

    private function isDueForRecipient(GiftPurchase $gift): bool
    {
        return $gift->delivery === GiftPurchase::DELIVERY_RECIPIENT
            && $gift->recipient_email
            && ! $gift->recipient_email_sent_at
            && $gift->send_at
            && $gift->send_at->lte(now());
    }

    /**
     * Objednávka jen s dárky je po doručení certifikátů kupujícímu vyřízená.
     * updateQuietly: bez e-mailu "objednávka doručena" z OrderObserver.
     */
    private function markOrderDeliveredIfDone(?int $orderId): void
    {
        if (! $orderId) {
            return;
        }

        $order = Order::with(['items', 'giftPurchases'])->find($orderId);

        if (! $order || $order->payment_status !== 'paid' || in_array($order->status, ['delivered', 'cancelled'], true)) {
            return;
        }

        $onlyGifts = $order->items->isNotEmpty() && $order->items->every(fn ($item) => $item->is_gift);
        $allSent = $order->giftPurchases->every(fn (GiftPurchase $gift) => $gift->buyer_email_sent_at !== null);

        if ($onlyGifts && $allSent) {
            $order->forceFill(['status' => 'delivered', 'delivered_at' => now()])->saveQuietly();
        }
    }
}
