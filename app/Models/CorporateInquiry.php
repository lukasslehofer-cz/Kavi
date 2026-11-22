<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Poptávka firemních dárků ze stránky Pro firmy (CorporateGiftController).
 * V adminu (Dárky › Firemní dárky) se k ní doplňuje stav a detaily dealu.
 */
class CorporateInquiry extends Model
{
    public const STATUS_NEW = 'new';
    public const STATUS_COMMUNICATION = 'communication';
    public const STATUS_OFFER_SENT = 'offer_sent';
    public const STATUS_REALIZATION = 'realization';
    public const STATUS_DONE = 'done';
    public const STATUS_LOST = 'lost';

    // Pořadí = průběh obchodního případu. Barvy odznaků: components/admin/corporate-status
    public const STATUSES = [
        self::STATUS_NEW => 'Čeká',
        self::STATUS_COMMUNICATION => 'Komunikace',
        self::STATUS_OFFER_SENT => 'Nabídka odeslaná',
        self::STATUS_REALIZATION => 'Realizace',
        self::STATUS_DONE => 'Hotovo',
        self::STATUS_LOST => 'Nerealizováno',
    ];

    // Hodnota z formuláře => text v e-mailu
    public const AUDIENCES = [
        'klienti' => 'Klienti',
        'zamestnanci' => 'Zaměstnanci',
        'oboji' => 'Obojí',
    ];

    protected $fillable = [
        'name',
        'company',
        'email',
        'phone',
        'audience',
        'quantity',
        'delivery_date',
        'note',
        'locale',
        'utm',
        'status',
        'box_count',
        'box_contents',
        'unit_price',
        'total_price',
        'agreed_delivery_date',
        'delivery_address',
        'billing_ico',
        'billing_dic',
        'billing_address',
        'invoice_number',
        'paid_at',
        'internal_note',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'utm' => 'array',
        'box_count' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'agreed_delivery_date' => 'date',
        'paid_at' => 'date',
        'status_history' => 'array',
        'status_changed_at' => 'datetime',
    ];

    public function audienceLabel(): string
    {
        return self::AUDIENCES[$this->audience] ?? $this->audience;
    }

    /**
     * "10–24", "50+" (bez " ks")
     */
    public function quantityLabel(): string
    {
        return str_replace('-', '–', $this->quantity);
    }

    /**
     * "1 250 Kč", "450,50 Kč" (haléře jen když nejsou nulové)
     */
    public static function formatCzk($amount): string
    {
        if ($amount === null || $amount === '') {
            return '–';
        }

        $amount = (float) $amount;

        return \App\Helpers\CurrencyHelper::formatByCurrency($amount, 'CZK', fmod($amount, 1.0) == 0.0 ? 0 : 2);
    }

    /**
     * Hodnota do textového pole: "1250", "450,50"
     */
    public static function priceInput($amount): string
    {
        if ($amount === null || $amount === '') {
            return '';
        }

        $amount = (float) $amount;

        return number_format($amount, fmod($amount, 1.0) == 0.0 ? 0 : 2, ',', '');
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Změní stav a zapíše do historie kdy a kdo. Neukládá, to udělá volající.
     */
    public function changeStatus(string $status, ?User $by = null): void
    {
        if ($status === $this->status) {
            return;
        }

        $history = $this->status_history ?? [];
        $history[] = [
            'from' => $this->status,
            'to' => $status,
            'at' => now()->toIso8601String(),
            'by' => $by?->name ?: $by?->email,
        ];

        $this->status = $status;
        $this->status_history = $history;
        $this->status_changed_at = now();
    }
}
