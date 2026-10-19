<?php

namespace App\Services;

use App\Helpers\GiftCode;
use App\Models\Coupon;
use App\Models\GiftPurchase;
use App\Models\Order;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Vystavení dárku po zaplacení objednávky: kód + kupon.
 *
 * Volá se z OrderObserver při změně payment_status na "paid", což pokryje všechny
 * tři cesty (návrat ze Stripe, webhook, ruční změna v adminu). Vystavení je
 * idempotentní: dárek se převezme atomickým UPDATE ... WHERE status IN (...),
 * takže ani souběh návratu a webhooku nevytvoří dva kódy.
 *
 * PDF a e-maily neběží tady (ve webhooku by zdržovaly odpověď Stripe), ale až po
 * odeslání odpovědi (GiftDeliveryService přes dispatchAfterResponse), s pojistkou
 * v příkazu gifts:deliver.
 */
class GiftFulfilmentService
{
    /** Dárek "issuing" starší než tohle se považuje za spadlý a vystaví se znovu */
    public const STALE_ISSUING_MINUTES = 10;

    public function onOrderPaid(Order $order): void
    {
        $this->restoreReleasedVoucherUsage($order);

        $gifts = $order->giftPurchases()
            ->whereIn('status', [GiftPurchase::STATUS_PENDING, GiftPurchase::STATUS_EXPIRED])
            ->pluck('id');

        if ($gifts->isEmpty()) {
            return;
        }

        foreach ($gifts as $giftId) {
            $this->issue($giftId);
        }

        // Certifikát a e-maily až po odeslání odpovědi (Stripe ani zákazník nečeká na PDF)
        $orderId = $order->id;
        dispatch(function () use ($orderId) {
            app(GiftDeliveryService::class)->deliverOrder($orderId);
        })->afterResponse();
    }

    /**
     * Pozdní platba objednávky, které orders:cancel-expired mezitím uvolnil dárkový
     * poukaz: použití se zapíše znovu, aby kód nešel uplatnit podruhé. Kód mohl být
     * mezitím použitý jinde, proto kritický log k ruční kontrole.
     */
    protected function restoreReleasedVoucherUsage(Order $order): void
    {
        $coupon = $order->coupon;

        if (! $coupon || ! $coupon->isGiftCode()) {
            return;
        }

        $hasUsage = \App\Models\CouponUsage::where('coupon_id', $coupon->id)->where('order_id', $order->id)->exists();

        if (! $hasUsage) {
            app(CouponService::class)->recordUsage($coupon, $order->user, 'order', (float) $order->discount_amount, $order);
            Log::critical('Gift voucher usage re-recorded after late payment, check the code was not used twice', [
                'order_id' => $order->id,
                'coupon_id' => $coupon->id,
                'times_used' => $coupon->fresh()->times_used,
            ]);
        }
    }

    /**
     * Vygeneruje kód a kupon. Vrací true, pokud dárek vystavilo tohle volání.
     */
    public function issue(int $giftId): bool
    {
        $claimed = GiftPurchase::where('id', $giftId)
            ->where(function ($q) {
                $q->whereIn('status', [GiftPurchase::STATUS_PENDING, GiftPurchase::STATUS_EXPIRED])
                    ->orWhere(function ($q) {
                        $q->where('status', GiftPurchase::STATUS_ISSUING)
                            ->where('issuing_started_at', '<', now()->subMinutes(self::STALE_ISSUING_MINUTES));
                    });
            })
            ->update(['status' => GiftPurchase::STATUS_ISSUING, 'issuing_started_at' => now()]);

        if ($claimed !== 1) {
            return false;
        }

        $gift = GiftPurchase::with('order')->findOrFail($giftId);

        try {
            for ($attempt = 1; $attempt <= 5; $attempt++) {
                try {
                    DB::transaction(function () use ($gift) {
                        $code = GiftCode::generate();
                        $paidAt = $gift->order?->paid_at ?? now();
                        $validUntil = $paidAt->copy()->addMonths((int) config('gifts.validity_months', 12))->endOfDay();

                        $coupon = Coupon::create($this->couponAttributesFor($gift, $code, $validUntil));

                        $gift->update([
                            'code' => $code,
                            'coupon_id' => $coupon->id,
                            'status' => GiftPurchase::STATUS_ISSUED,
                            'issued_at' => now(),
                            'valid_until' => $validUntil,
                        ]);
                    });

                    Log::info('Gift issued', ['gift_id' => $gift->id, 'order_id' => $gift->order_id, 'code' => $gift->code]);

                    return true;
                } catch (QueryException $e) {
                    // Kolize kódu (unique) - zkusit jiný
                    if ($attempt === 5 || ! str_contains($e->getMessage(), 'Duplicate')) {
                        throw $e;
                    }
                }
            }
        } catch (\Throwable $e) {
            GiftPurchase::where('id', $giftId)->update(['status' => GiftPurchase::STATUS_PENDING, 'issuing_started_at' => null]);
            Log::error('Gift issue failed', ['gift_id' => $giftId, 'error' => $e->getMessage()]);

            throw $e;
        }

        return false;
    }

    /**
     * Kupon dárku. Kód dárkového předplatného se uplatní jen přes stránku uplatnění
     * (CouponService ho jinde odmítne), poukaz jako pevná sleva v měně nákupu.
     */
    protected function couponAttributesFor(GiftPurchase $gift, string $code, $validUntil): array
    {
        $isEur = $gift->currency === 'EUR';
        $common = [
            'code' => $code,
            'name' => $gift->itemName('cs'),
            'description' => 'Dárek z objednávky ' . ($gift->order?->order_number ?? '#' . $gift->order_id) . ', pro ' . $gift->recipient_name . ' od ' . $gift->giver_name,
            'gift_type' => $gift->type,
            'valid_from' => now(),
            'valid_until' => $validUntil,
            'usage_limit_total' => 1,
            'usage_limit_per_user' => 1,
            'times_used' => 0,
            'is_active' => true,
            // Dárek může uplatnit i stávající zákazník, který už kdysi kupon na předplatné použil
            'allow_repeated_subscription_usage' => true,
            'free_shipping' => false,
        ];

        if ($gift->isSubscription()) {
            return $common + [
                'discount_type_order' => 'none',
                'discount_type_subscription' => 'percentage',
                'discount_value_subscription' => 100,
                'discount_value_subscription_eur' => 100,
                'subscription_discount_months' => $gift->months,
                'is_gift_voucher' => false,
            ];
        }

        $amount = (float) $gift->amount;

        return $common + [
            'discount_type_order' => 'fixed',
            'discount_value_order' => $isEur ? null : $amount,
            'discount_value_order_eur' => $isEur ? $amount : null,
            'discount_type_subscription' => 'fixed',
            'discount_value_subscription' => $isEur ? null : $amount,
            'discount_value_subscription_eur' => $isEur ? $amount : null,
            'subscription_discount_months' => 1,
            // Víceúčelový poukaz: DPH se počítá z plné ceny při uplatnění
            'is_gift_voucher' => true,
        ];
    }
}
