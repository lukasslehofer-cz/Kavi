<?php

namespace App\Http\Controllers;

use App\Helpers\BoxPricing;
use App\Helpers\CurrencyHelper;
use App\Helpers\DateFormat;
use App\Helpers\GiftCode;
use App\Helpers\ShipmentCycles;
use App\Models\Coupon;
use App\Models\GiftPurchase;
use App\Services\EmailService;
use Illuminate\Http\Request;

/**
 * Uplatnění dárku na kavi.cz/darek (kavibox.com/redeem).
 *
 * 1. show:    stránka; ?kod= / ?code= z QR kódu se uloží do session a přesměruje
 *             na čistou adresu, aby kód neodešel v URL do analytiky
 * 2. verify:  AJAX ověření kódu (limit pokusů, RouteServiceProvider "gift-verify")
 * 3. prepare: u předplatného typ kávy a start, pak standardní pokladna předplatného
 *             v režimu dárku (SubscriptionController); u poukazu se kód uloží do
 *             session a uplatní se v pokladně jako slevový kód
 */
class GiftRedeemController extends Controller
{
    public const SESSION_VERIFIED = 'gift_redeem_verified';

    public const SESSION_REDEMPTION = 'gift_redemption';

    public function show(Request $request)
    {
        abort_unless(config('gifts.enabled'), 404);

        $code = $request->query('kod') ?? $request->query('code');
        if (is_string($code) && $code !== '') {
            return redirect()->to(localizedRoute('gifts.redeem'))->with('gift_prefill', GiftCode::normalize($code));
        }

        $locale = app()->getLocale();
        $email = EmailService::getContactEmail($locale);
        $cycle = ShipmentCycles::current();

        $faq = [];
        foreach (array_keys(__('gifts.redeem.faq')) as $i) {
            $faq[] = ['q' => __("gifts.redeem.faq.$i.q"), 'a' => __("gifts.redeem.faq.$i.a", ['email' => $email])];
        }

        return view('gifts.redeem', [
            // Kód z QR, nebo už ověřený v této session (návrat z pokladny / chyba volby)
            'prefill' => session('gift_prefill') ?? (session(self::SESSION_VERIFIED)['code'] ?? null),
            'email' => $email,
            'cycle' => $cycle,
            'faq' => $faq,
            'cheapest' => BoxPricing::cheapest(),
            'jsConfig' => [
                'verifyUrl' => localizedRoute('gifts.redeem.verify'),
                'locale' => $locale,
                'errors' => [
                    'throttled' => __('gifts.redeem.error_throttled'),
                    'generic' => __('gifts.redeem.error_generic'),
                ],
                'types' => __('gifts.redeem.types'),
            ],
        ]);
    }

    public function verify(Request $request)
    {
        abort_unless(config('gifts.enabled'), 404);

        $request->validate(['code' => 'required|string|max:40']);

        $locale = app()->getLocale();
        $email = EmailService::getContactEmail($locale);
        $code = GiftCode::normalize($request->input('code'));

        $coupon = Coupon::where('code', $code)->whereNotNull('gift_type')->with('giftPurchase')->first();
        $gift = $coupon?->giftPurchase;

        // Neznámý, nevystavený nebo zneplatněný kód: jedna obecná hláška (nic neprozradí)
        if (! $gift || ! in_array($gift->status, [GiftPurchase::STATUS_ISSUED, GiftPurchase::STATUS_REDEEMED], true)
            || (! $coupon->is_active && $gift->status !== GiftPurchase::STATUS_REDEEMED)) {
            session()->forget(self::SESSION_VERIFIED);

            return response()->json(['valid' => false, 'message' => __('gifts.redeem.error_unknown', ['email' => $email])], 422);
        }

        if ($gift->status === GiftPurchase::STATUS_REDEEMED || ($gift->isVoucher() && $coupon->hasReachedTotalLimit())) {
            $date = $gift->redeemed_at ?? $coupon->usages()->latest()->value('created_at');

            return response()->json(['valid' => false, 'message' => __('gifts.redeem.error_redeemed', [
                'date' => $date ? DateFormat::full(\Carbon\Carbon::parse($date)) : '',
                'email' => $email,
            ])], 422);
        }

        if ($gift->isExpired()) {
            return response()->json(['valid' => false, 'message' => __('gifts.redeem.error_expired', [
                'date' => DateFormat::full($gift->valid_until),
                'email' => $email,
            ])], 422);
        }

        // Dárek z druhé domény (jiná měna) se uplatní tam
        if ($gift->currency !== CurrencyHelper::code()) {
            $url = GiftPurchase::redeemBaseUrl($gift->locale);

            return response()->json(['valid' => false, 'message' => __('gifts.redeem.error_other_domain', ['url' => preg_replace('#^https?://#', '', $url)]), 'url' => $url], 422);
        }

        session([self::SESSION_VERIFIED => ['gift_id' => $gift->id, 'code' => $gift->code]]);

        if ($gift->isVoucher()) {
            // Poukaz se uplatní v pokladně jako slevový kód (stejně jako CouponController)
            session(['coupon_code' => $gift->code]);
            session()->forget('affiliate_code');
        }

        return response()->json(['valid' => true] + $this->giftPayload($gift));
    }

    public function prepare(Request $request)
    {
        abort_unless(config('gifts.enabled'), 404);

        $gift = $this->verifiedGift();

        if (! $gift || ! $gift->isSubscription()) {
            return redirect()->to(localizedRoute('gifts.redeem'))->with('error', __('gifts.redeem.error_not_verified'));
        }

        $validated = $request->validate([
            'type' => 'required|in:espresso,filter,mix',
            'mix_espresso' => 'nullable|required_if:type,mix|integer|min:1',
            'mix_filter' => 'nullable|required_if:type,mix|integer|min:1',
            'start' => 'required|integer|in:0,1,2',
        ]);

        $bags = (int) $gift->bags;

        if ($validated['type'] === 'mix' && ((int) $validated['mix_espresso'] + (int) $validated['mix_filter']) !== $bags) {
            return back()->withInput()->with('error', __('gifts.redeem.error_generic'));
        }

        $cycles = ShipmentCycles::upcoming(3);
        $start = $cycles[(int) $validated['start']] ?? $cycles->first();

        // Stejný tvar konfigurace jako z konfigurátoru, velikost a frekvence z dárku
        $configuration = [
            'amount' => $bags,
            'type' => $validated['type'],
            'isDecaf' => false,
            'frequency' => 1,
            'gift' => true,
        ];
        if ($validated['type'] === 'mix') {
            $configuration['mix'] = ['espresso' => (int) $validated['mix_espresso'], 'filter' => (int) $validated['mix_filter']];
        }

        // Dostupnost kávy jde ověřit jen u měsíce, který už má nastavené kávy
        $schedule = $start->schedule;
        if ($schedule && $schedule->hasCoffeeSlotsConfigured()) {
            $availability = app(\App\Services\StockReservationService::class)->checkAvailability($configuration, $schedule);
            if (! $availability['available']) {
                return back()->withInput()->with('error', __('gifts.redeem.error_sold_out'));
            }
        }

        session([self::SESSION_REDEMPTION => [
            'gift_id' => $gift->id,
            'code' => $gift->code,
            'configuration' => $configuration,
            'start' => $start->key(),
        ]]);

        return redirect()->to(localizedRoute('subscriptions.checkout'));
    }

    /**
     * Dárek ověřený v této session, jen pokud je stále uplatnitelný
     */
    public static function verifiedGift(): ?GiftPurchase
    {
        $verified = session(self::SESSION_VERIFIED);

        if (! is_array($verified) || empty($verified['gift_id'])) {
            return null;
        }

        $gift = GiftPurchase::with('coupon')->find($verified['gift_id']);

        if (! $gift || $gift->code !== ($verified['code'] ?? null) || ! $gift->isRedeemable() || $gift->currency !== CurrencyHelper::code()) {
            return null;
        }

        return $gift;
    }

    /**
     * Data dárku pro levý sloupec a kroky na stránce uplatnění
     */
    private function giftPayload(GiftPurchase $gift): array
    {
        $locale = app()->getLocale();
        $payload = [
            'type' => $gift->type,
            'code' => $gift->code,
            'recipient' => $gift->recipient_name,
            'giver' => $gift->giver_name,
            'note' => $gift->note,
            'valid_until' => $gift->valid_until ? DateFormat::full($gift->valid_until, $locale) : null,
        ];

        if ($gift->isVoucher()) {
            $amount = CurrencyHelper::formatByCurrency((float) $gift->amount, $gift->currency, 0);

            return $payload + [
                'title' => __('gifts.redeem.title_voucher', ['amount' => $amount]),
                'amount' => $amount,
                'rows' => [
                    ['l' => __('gifts.redeem.row_for'), 'v' => $gift->recipient_name],
                    ['l' => __('gifts.redeem.row_valid_on'), 'v' => __('gifts.redeem.row_valid_on_value', ['site' => GiftPurchase::siteDisplayFor($locale)])],
                    ['l' => __('gifts.redeem.row_valid_until'), 'v' => $payload['valid_until']],
                ],
                'ok' => __('gifts.redeem.ok_voucher'),
                'rules' => __('gifts.redeem.voucher_rules', ['amount' => $amount, 'date' => $payload['valid_until']]),
            ];
        }

        $months = (int) $gift->months;
        $starts = [];
        $cycles = ShipmentCycles::upcoming(3 + $months);
        foreach (array_slice(__('gifts.redeem.starts'), 0, 3) as $i => $option) {
            $first = $cycles[$i] ?? null;
            $last = $cycles[$i + $months - 1] ?? null;
            if (! $first) {
                continue;
            }
            $starts[] = [
                'index' => $i,
                'name' => $option['name'],
                'desc' => __("gifts.redeem.starts.$i.desc", ['cutoff' => $first->cutoffShort()]),
                'date' => $first->shipmentShort(),
                'first' => DateFormat::full($first->shipmentDate, $locale),
                'last' => $last ? DateFormat::full($last->shipmentDate, $locale) : null,
            ];
        }

        return $payload + [
            'title' => __('gifts.redeem.title_subscription', ['months' => trans_choice('gifts.months', $months, ['count' => $months])]),
            'bags' => (int) $gift->bags,
            'months' => $months,
            'box' => $gift->boxLabel($locale),
            'rows' => [
                ['l' => __('gifts.redeem.row_for'), 'v' => $gift->recipient_name],
                ['l' => __('gifts.redeem.row_box'), 'v' => $gift->boxLabel($locale)],
                ['l' => __('gifts.redeem.row_count'), 'v' => __('gifts.redeem.row_count_value', ['count' => $months])],
            ],
            'ok' => __('gifts.redeem.ok_subscription'),
            'starts' => $starts,
        ];
    }
}
