<?php

namespace App\Services;

use App\Helpers\CurrencyHelper;
use App\Helpers\ShipmentCycle;
use App\Models\Coupon;
use App\Models\GiftPurchase;
use App\Models\ShipmentSchedule;
use App\Models\ShippingRate;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Uplatnění dárkového předplatného.
 *
 * Vznikne předplatné se statusem "complimentary" navázané na dárek (gift_purchase_id):
 * - billing cron ho nevybírá (bere jen active/unpaid/paused s next_billing_date),
 *   připomínky plateb ani Fakturoid se ho netýkají, nic se nestrhne ani neobnoví;
 * - zásilky vznikají postupně přes SubscriptionShipmentService, nejvýš N
 *   (počet měsíců dárku), po odeslání posledního boxu je předplatné "completed".
 */
class GiftRedemptionService
{
    public function __construct(
        private CouponService $couponService,
        private SubscriptionShipmentService $shipmentService,
    ) {}

    /**
     * @param  array<string, mixed>  $configuration  amount, type, mix, isDecaf=false, frequency=1
     * @param  array<string, mixed>  $contact  name, email, phone, billing_country, packeta_*, carrier_*, delivery_notes
     */
    public function redeem(GiftPurchase $gift, array $configuration, ShipmentCycle $start, array $contact, User $user, ?ShippingRate $shippingRate): Subscription
    {
        return DB::transaction(function () use ($gift, $configuration, $start, $contact, $user, $shippingRate) {
            /** @var GiftPurchase $gift */
            $gift = GiftPurchase::whereKey($gift->id)->lockForUpdate()->firstOrFail();
            $coupon = Coupon::whereKey($gift->coupon_id)->lockForUpdate()->first();

            if (! $gift->isSubscription()
                || $gift->status !== GiftPurchase::STATUS_ISSUED
                || $gift->redeemed_at !== null
                || $gift->isExpired()
                || $gift->currency !== CurrencyHelper::code()
                || ! $coupon || ! $coupon->isValid() || $coupon->hasReachedTotalLimit()) {
                throw new \RuntimeException('Gift is not redeemable');
            }

            // Velikost boxu a počet boxů vždy z dárku, ne z formuláře
            $configuration['amount'] = (int) $gift->bags;
            $configuration['frequency'] = 1;
            $configuration['isDecaf'] = false;
            $configuration['gift'] = true;

            // Začátek dárku musí být známý dřív, než vznikne předplatné: observer
            // přepočítává rezervace kávy už při vytvoření (SubscriptionStockObserver)
            $gift->update(['start_month' => $start->key()]);

            $subscription = Subscription::create([
                'subscription_number' => Subscription::generateSubscriptionNumber(),
                'user_id' => $user->id,
                'subscription_plan_id' => null,
                'coupon_id' => $coupon->id,
                'coupon_code' => $coupon->code,
                'gift_purchase_id' => $gift->id,
                'discount_amount' => 0,
                'discount_months_remaining' => null,
                'discount_months_total' => null,
                'configuration' => $configuration,
                'configured_price' => 0,
                'vat_rate' => $gift->vat_rate,
                'currency' => $gift->currency,
                'frequency_months' => 1,
                'status' => 'complimentary',
                'starts_at' => now(),
                'next_billing_date' => null,
                'shipping_address' => [
                    'name' => $contact['name'],
                    'email' => $contact['email'],
                    'phone' => $contact['phone'],
                    'country' => $contact['billing_country'],
                ],
                'payment_method' => 'gift',
                'packeta_point_id' => $contact['packeta_point_id'],
                'packeta_point_name' => $contact['packeta_point_name'],
                'packeta_point_address' => $contact['packeta_point_address'] ?? null,
                'carrier_id' => $contact['carrier_id'] ?? null,
                'carrier_pickup_point' => $contact['carrier_pickup_point'] ?? null,
                'delivery_notes' => $contact['delivery_notes'] ?? null,
                // Dopravu hradí dárek, předplatné nic neplatí
                'shipping_cost' => 0,
                'shipping_country' => $contact['billing_country'],
                'shipping_rate_id' => $shippingRate?->id,
                'meta_event_id' => (string) \Illuminate\Support\Str::uuid(),
            ]);

            // První box přes jediný povolený creator zásilek (ledger)
            $this->shipmentService->getOrCreateForSchedule(
                $subscription,
                ShipmentSchedule::getOrCreateForMonth($start->year, $start->month)
            );

            $this->couponService->recordUsage($coupon, $user, 'gift', (float) $gift->amount, null, $subscription);

            $gift->update([
                'status' => GiftPurchase::STATUS_REDEEMED,
                'redeemed_at' => now(),
                'subscription_id' => $subscription->id,
                'redeemed_by_user_id' => $user->id,
            ]);

            Log::info('Gift subscription redeemed', [
                'gift_id' => $gift->id,
                'subscription_id' => $subscription->id,
                'user_id' => $user->id,
                'start' => $start->key(),
                'months' => $gift->months,
            ]);

            return $subscription;
        });
    }
}
