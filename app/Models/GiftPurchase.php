<?php

namespace App\Models;

use App\Helpers\BoxPricing;
use App\Helpers\DateFormat;
use App\Helpers\ShipmentCycles;
use App\Services\EmailService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

/**
 * Prodaný dárek: dárkové předplatné na N boxů, nebo poukaz na e-shop.
 *
 * Vzniká jako "pending" při vytvoření objednávky. Po zaplacení se vystaví kód
 * (kupon), PDF certifikát a e-maily (GiftFulfilmentService, GiftDeliveryService).
 * Uplatněním dárkového předplatného vznikne předplatné se statusem complimentary.
 */
class GiftPurchase extends Model
{
    public const TYPE_SUBSCRIPTION = 'subscription';
    public const TYPE_VOUCHER = 'voucher';

    public const STATUS_PENDING = 'pending';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_ISSUING = 'issuing';
    public const STATUS_ISSUED = 'issued';
    public const STATUS_REDEEMED = 'redeemed';
    public const STATUS_CANCELLED = 'cancelled';

    public const DELIVERY_BUYER = 'buyer';
    public const DELIVERY_RECIPIENT = 'recipient';

    protected $fillable = [
        'order_id',
        'order_item_id',
        'coupon_id',
        'code',
        'type',
        'status',
        'bags',
        'months',
        'amount',
        'currency',
        'vat_rate',
        'locale',
        'recipient_name',
        'giver_name',
        'note',
        'delivery',
        'recipient_email',
        'send_at',
        'buyer_name',
        'buyer_email',
        'valid_until',
        'issued_at',
        'issuing_started_at',
        'pdf_path',
        'buyer_email_sent_at',
        'recipient_email_sent_at',
        'delivery_attempts',
        'last_delivery_error',
        'start_month',
        'redeemed_at',
        'subscription_id',
        'redeemed_by_user_id',
        'ending_reminder_sent_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'vat_rate' => 'decimal:2',
        'bags' => 'integer',
        'months' => 'integer',
        'send_at' => 'datetime',
        'valid_until' => 'datetime',
        'issued_at' => 'datetime',
        'issuing_started_at' => 'datetime',
        'buyer_email_sent_at' => 'datetime',
        'recipient_email_sent_at' => 'datetime',
        'redeemed_at' => 'datetime',
        'ending_reminder_sent_at' => 'datetime',
        'delivery_attempts' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function redeemedBy()
    {
        return $this->belongsTo(User::class, 'redeemed_by_user_id');
    }

    public function isSubscription(): bool
    {
        return $this->type === self::TYPE_SUBSCRIPTION;
    }

    public function isVoucher(): bool
    {
        return $this->type === self::TYPE_VOUCHER;
    }

    public function isIssued(): bool
    {
        return in_array($this->status, [self::STATUS_ISSUED, self::STATUS_REDEEMED], true);
    }

    public function isExpired(): bool
    {
        return $this->valid_until !== null && $this->valid_until->isPast();
    }

    /**
     * Kód lze ještě uplatnit (vystavený, neuplatněný, v platnosti, kupon aktivní)
     */
    public function isRedeemable(): bool
    {
        return $this->status === self::STATUS_ISSUED
            && ! $this->isExpired()
            && $this->coupon
            && $this->coupon->is_active;
    }

    /**
     * Velikost boxu: "L"
     */
    public function boxCode(): ?string
    {
        return $this->bags ? (BoxPricing::SIZES[$this->bags] ?? null) : null;
    }

    /**
     * Datum rozesílky prvního a posledního boxu uplatněného předplatného (z rozvrhu)
     *
     * @return array{first: \Carbon\Carbon, last: \Carbon\Carbon}|null
     */
    public function boxDates(): ?array
    {
        if (! $this->isSubscription() || ! $this->start_month) {
            return null;
        }

        [$year, $month] = array_map('intval', explode('-', $this->start_month));
        $start = \Carbon\Carbon::create($year, $month, 1)->startOfDay();
        $end = $start->copy()->addMonthsNoOverflow(max(1, (int) $this->months) - 1);

        return [
            'first' => ShipmentCycles::forMonth($start->year, $start->month)->shipmentDate,
            'last' => ShipmentCycles::forMonth($end->year, $end->month)->shipmentDate,
        ];
    }

    /**
     * "L Box · 3 balíčky měsíčně"
     */
    public function boxLabel(?string $locale = null): ?string
    {
        if (! $this->isSubscription() || ! $this->bags) {
            return null;
        }

        return __('gifts.box_label', ['box' => $this->boxCode(), 'count' => $this->bags], $locale ?? $this->locale);
    }

    /**
     * Název položky v objednávce a na faktuře
     */
    public function itemName(?string $locale = null): string
    {
        $locale = $locale ?? $this->locale;

        if ($this->isSubscription()) {
            return __('gifts.item_name.subscription', [
                'box' => $this->boxCode(),
                'months' => trans_choice('gifts.months', $this->months, ['count' => $this->months], $locale),
            ], $locale);
        }

        return __('gifts.item_name.voucher', [
            'amount' => \App\Helpers\CurrencyHelper::formatByCurrency((float) $this->amount, $this->currency, 0),
        ], $locale);
    }

    /**
     * Adresa uplatnění s kódem pro QR (vždy doména podle jazyka dárku, ne podle requestu)
     */
    public function redeemUrl(): string
    {
        $param = $this->locale === 'en' ? 'code' : 'kod';

        return self::redeemBaseUrl($this->locale) . ($this->code ? '?' . $param . '=' . urlencode($this->code) : '');
    }

    /**
     * Adresa stránky uplatnění bez kódu
     */
    public static function redeemBaseUrl(string $locale): string
    {
        $name = $locale === 'en' && Route::has('gifts.redeem.en') ? 'gifts.redeem.en' : 'gifts.redeem';

        return EmailService::getSiteUrl($locale) . route($name, [], false);
    }

    /**
     * Krátký tvar adresy na certifikát: "kavi.cz/darek"
     */
    public function redeemDisplay(): string
    {
        return self::redeemDisplayFor($this->locale);
    }

    public static function redeemDisplayFor(string $locale): string
    {
        return preg_replace('#^https?://(www\.)?#', '', self::redeemBaseUrl($locale));
    }

    /**
     * Krátký tvar domény e-shopu: "kavi.cz"
     */
    public static function siteDisplayFor(string $locale): string
    {
        return preg_replace('#^https?://(www\.)?#', '', EmailService::getSiteUrl($locale));
    }

    /**
     * Data pro šablonu certifikátu (web náhled i PDF používají stejný partial)
     */
    public function certificateData(): array
    {
        return [
            'type' => $this->type,
            'locale' => $this->locale,
            'months' => $this->months,
            'box' => $this->boxLabel(),
            'amount' => (float) $this->amount,
            'currency' => $this->currency,
            'recipient' => $this->recipient_name,
            'giver' => $this->giver_name,
            'note' => $this->note,
            'code' => $this->code ?? 'KAVI-XXXX-XXXX',
            'valid_until' => $this->valid_until ? DateFormat::full($this->valid_until, $this->locale) : null,
            'redeem_display' => $this->redeemDisplay(),
            'redeem_url' => $this->redeemUrl(),
            'site_display' => self::siteDisplayFor($this->locale),
        ];
    }

    /**
     * Dárky čekající na odeslání obdarovanému
     */
    public function scopeDueForRecipient(Builder $query): Builder
    {
        return $query->where('delivery', self::DELIVERY_RECIPIENT)
            ->whereIn('status', [self::STATUS_ISSUED, self::STATUS_REDEEMED])
            ->whereNull('recipient_email_sent_at')
            ->whereNotNull('recipient_email')
            ->where('send_at', '<=', now());
    }
}
