<?php

namespace App\Http\Controllers;

use App\Helpers\BoxPricing;
use App\Helpers\CurrencyHelper;
use App\Helpers\DateFormat;
use App\Helpers\ShipmentCycles;
use App\Models\GiftPurchase;
use App\Services\EmailService;
use App\Services\GiftCartService;
use App\Services\GiftCertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Nákup dárků: dárkové předplatné (3/6 měsíců) a poukaz na e-shop.
 *
 * Dárek se nezaplatí tady. "Koupit dárek" ho vloží do dárkového košíku
 * (GiftCartService) a pošle zákazníka do standardní pokladny e-shopu.
 */
class GiftController extends Controller
{
    public function __construct(private GiftCartService $giftCart) {}

    public function index(Request $request)
    {
        abort_unless(config('gifts.enabled'), 404);

        $currency = CurrencyHelper::code();
        $locale = app()->getLocale();
        $tiers = BoxPricing::tiers($currency);
        $months = config('gifts.months', [3, 6]);
        $amounts = config('gifts.voucher_amounts.' . $currency, []);
        $cycle = ShipmentCycles::current();

        // Předvolba z odkazu na homepage: ?typ=3m|6m|poukaz (EN ?type=3m|6m|voucher)
        $preset = (string) ($request->query('typ') ?? $request->query('type') ?? '');
        $initial = [
            'type' => in_array($preset, ['poukaz', 'voucher'], true) ? GiftPurchase::TYPE_VOUCHER : GiftPurchase::TYPE_SUBSCRIPTION,
            'months' => $preset === '6m' && in_array(6, $months) ? 6 : (int) $months[0],
            'bags' => BoxPricing::POPULAR,
            'voucher_amount' => $amounts[(int) floor(count($amounts) / 2)] ?? ($amounts[0] ?? 0),
        ];

        // Popisy hodnot poukazu podle toho, na které boxy stačí (poukaz se uplatní
        // jen na první platbu dražší, než je sám, viz CouponService)
        $amountOptions = array_map(fn ($amount) => [
            'value' => $amount,
            'label' => CurrencyHelper::formatByCurrency($amount, $currency, 0),
            'desc' => $this->voucherDescription($amount, $tiers),
        ], $amounts);

        $sample = [
            'locale' => $locale,
            'recipient' => __('gifts.certificate.sample.recipient'),
            'giver' => __('gifts.certificate.sample.giver'),
            'note' => __('gifts.certificate.sample.note'),
            'code' => 'XXXX-XXXX',
            'valid_until' => DateFormat::full(now()->addMonths((int) config('gifts.validity_months', 12)), $locale),
            'redeem_display' => GiftPurchase::redeemDisplayFor($locale),
            'redeem_url' => GiftPurchase::redeemBaseUrl($locale),
            'site_display' => GiftPurchase::siteDisplayFor($locale),
            'currency' => $currency,
        ];
        $previewSubscription = $sample + [
            'type' => GiftPurchase::TYPE_SUBSCRIPTION,
            'months' => $initial['months'],
            'box' => __('gifts.box_label', ['box' => BoxPricing::SIZES[$initial['bags']], 'count' => $initial['bags']]),
        ];
        $previewVoucher = $sample + [
            'type' => GiftPurchase::TYPE_VOUCHER,
            'amount' => $initial['voucher_amount'],
        ];

        // Konfigurace pro JS (náhled a ceny se počítají v prohlížeči jen pro zobrazení)
        $jsConfig = [
            'currency' => $currency,
            'locale' => $locale,
            'prices' => BoxPricing::pricingMap($currency),
            'sizes' => BoxPricing::SIZES,
            'boxLabels' => collect(BoxPricing::SIZES)->mapWithKeys(fn ($code, $bags) => [$bags => __('gifts.box_label', ['box' => $code, 'count' => $bags])]),
            'monthsWords' => collect($months)->mapWithKeys(fn ($m) => [$m => __('gifts.months_word.' . $m)]),
            'monthsLabels' => collect($months)->mapWithKeys(fn ($m) => [$m => trans_choice('gifts.months', $m, ['count' => $m])]),
            'sample' => $sample,
            'noteSubscription' => __('gifts.page.total_note_subscription'),
            'noteVoucher' => __('gifts.page.total_note_voucher'),
        ];

        $faq = [];
        foreach (array_keys(__('gifts.page.faq')) as $i) {
            $faq[] = ['q' => __("gifts.page.faq.$i.q"), 'a' => __("gifts.page.faq.$i.a")];
        }

        return view('gifts.index', [
            'tiers' => $tiers,
            'months' => $months,
            'amountOptions' => $amountOptions,
            'initial' => $initial,
            'cycle' => $cycle,
            'currency' => $currency,
            'previewSubscription' => $previewSubscription,
            'previewVoucher' => $previewVoucher,
            'jsConfig' => $jsConfig,
            'faq' => $faq,
            'defaultSendDate' => $this->defaultSendDate(),
            'contactEmail' => EmailService::getContactEmail($locale),
            'cheapest' => BoxPricing::cheapest($currency),
        ]);
    }

    public function addToCart(Request $request)
    {
        abort_unless(config('gifts.enabled'), 404);

        $currency = CurrencyHelper::code();
        $months = config('gifts.months', [3, 6]);
        $amounts = config('gifts.voucher_amounts.' . $currency, []);

        $validated = $request->validate([
            'type' => 'required|in:subscription,voucher',
            'months' => 'nullable|required_if:type,subscription|integer|in:' . implode(',', $months),
            'bags' => 'nullable|required_if:type,subscription|integer|in:' . implode(',', array_keys(BoxPricing::SIZES)),
            'voucher_amount' => 'nullable|required_if:type,voucher|numeric|in:' . implode(',', $amounts),
            'recipient_name' => 'required|string|max:' . config('gifts.name_max_length', 60),
            'giver_name' => 'required|string|max:' . config('gifts.name_max_length', 60),
            'note' => 'nullable|string|max:' . config('gifts.note_max_length', 200),
            'delivery' => 'required|in:buyer,recipient',
            'recipient_email' => 'nullable|required_if:delivery,recipient|email:rfc|max:255',
            'send_at' => 'nullable|required_if:delivery,recipient|date|after_or_equal:today|before_or_equal:' . now()->addYear()->toDateString(),
        ]);

        if ($this->giftCart->isFull()) {
            return back()->withInput()->with('error', __('gifts.cart.too_many', ['max' => GiftCartService::MAX_LINES]));
        }

        $this->giftCart->add($validated);

        return redirect()->to(localizedRoute('checkout.index'))->with('success', __('gifts.cart.added'));
    }

    public function removeFromCart(string $line)
    {
        $this->giftCart->remove($line);

        return back()->with('success', __('gifts.cart.removed'));
    }

    /**
     * Stažení PDF certifikátu: podepsaný odkaz z e-mailu, nebo přihlášený kupující
     */
    public function certificate(Request $request, GiftPurchase $gift, GiftCertificateService $certificates)
    {
        $owner = $request->user() && $gift->order && $gift->order->user_id === $request->user()->id;
        // Odkaz z e-mailu je podepsaný relativně (doména se liší podle jazyka dárku)
        abort_unless($request->hasValidRelativeSignature() || $owner || $request->user()?->is_admin, 403);
        abort_unless($gift->isIssued(), 404);

        $path = $certificates->ensurePdf($gift);

        return Storage::disk('local')->download($path, $certificates->fileName($gift), ['Content-Type' => 'application/pdf']);
    }

    /**
     * Popis hodnoty poukazu podle toho, na které boxy stačí
     */
    private function voucherDescription(float $amount, array $tiers): string
    {
        $boxes = array_values(array_filter($tiers, fn ($tier) => $tier['price'] > $amount));

        if (count($boxes) === count($tiers)) {
            return __('gifts.page.amount_desc_all');
        }

        if (empty($boxes)) {
            return __('gifts.page.amount_desc_none');
        }

        return __('gifts.page.amount_desc_some', [
            'boxes' => implode(__('gifts.page.boxes_or'), array_column($boxes, 'code')),
        ]);
    }

    /**
     * Výchozí datum odeslání obdarovanému: 24. 12. letošního roku, pokud ještě nebyl, jinak dnes
     */
    private function defaultSendDate(): string
    {
        [$month, $day] = array_map('intval', explode('-', config('gifts.default_send_date', '12-24')));
        $date = now()->setDate(now()->year, $month, $day)->startOfDay();

        return ($date->isPast() && ! $date->isToday() ? now() : $date)->toDateString();
    }
}
