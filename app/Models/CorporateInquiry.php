<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Poptávka firemních dárků ze stránky Pro firmy (CorporateGiftController).
 */
class CorporateInquiry extends Model
{
    public const STATUS_NEW = 'new';
    public const STATUS_OFFER_SENT = 'offer_sent';
    public const STATUS_WON = 'won';
    public const STATUS_LOST = 'lost';

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
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'utm' => 'array',
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
}
