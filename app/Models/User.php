<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'locale',
        'password',
        'password_set_by_user',
        'phone',
        'address',
        'city',
        'postal_code',
        'country',
        'invoice_override',
        'invoice_company',
        'invoice_registration_no',
        'invoice_vat_no',
        'invoice_name',
        'invoice_street',
        'invoice_city',
        'invoice_zip',
        'invoice_country',
        // 'is_admin' a 'is_affiliate_partner' záměrně NEJSOU fillable kvůli ochraně
        // proti mass assignmentu (eskalace oprávnění). Nastavují se explicitně přes forceFill().
        'affiliate_activated_at',
        'affiliate_payout_threshold',
        'stripe_customer_id',
        'fakturoid_subject_id',
        'packeta_point_id',
        'packeta_point_name',
        'packeta_point_address',
        'carrier_id',
        'carrier_pickup_point',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'password_set_by_user' => 'boolean',
        'invoice_override' => 'boolean',
        'is_admin' => 'boolean',
        'is_affiliate_partner' => 'boolean',
        'affiliate_activated_at' => 'datetime',
        'affiliate_payout_threshold' => 'decimal:2',
        'affiliate_threshold_notified_at' => 'datetime',
        'deleted_at' => 'datetime',
        'anonymized_at' => 'datetime',
    ];

    /**
     * Přepis odběratele pro Fakturoid, pokud má zákazník nastavené vlastní
     * fakturační údaje. Vrací null, když se má použít adresa z objednávky
     * nebo z předplatného.
     *
     * Klíče odpovídají polím subjektu ve Fakturoid API. E-mail ani telefon
     * se záměrně nepřepisují – doklady mají chodit dál na kontakt zákazníka.
     */
    public function fakturoidSubjectOverride(): ?array
    {
        if (! $this->invoice_override) {
            return null;
        }

        return [
            // Ve Fakturoidu se jako hlavní řádek odběratele tiskne "name",
            // proto tam patří firma; kontaktní osoba jde do "full_name".
            'name' => $this->invoice_company ?: ($this->invoice_name ?: $this->name),
            'full_name' => $this->invoice_company ? (string) $this->invoice_name : '',
            'street' => (string) $this->invoice_street,
            'city' => (string) $this->invoice_city,
            'zip' => (string) $this->invoice_zip,
            'country' => strtoupper($this->invoice_country ?: 'CZ'),
            'registration_no' => (string) $this->invoice_registration_no,
            'vat_no' => (string) $this->invoice_vat_no,
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->latest();
    }

    public function activeSubscriptions()
    {
        return $this->hasMany(Subscription::class)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Affiliate vztahy
     */
    public function affiliateLinks()
    {
        return $this->hasMany(AffiliateLink::class, 'affiliate_partner_id');
    }

    public function affiliateRewards()
    {
        return $this->hasMany(AffiliateReward::class, 'affiliate_partner_id');
    }

    public function affiliateCoupons()
    {
        return $this->hasMany(Coupon::class, 'affiliate_partner_id');
    }

    /**
     * Zkontroluje, zda je uživatel affiliate partner
     */
    public function isAffiliatePartner(): bool
    {
        return $this->is_affiliate_partner === true;
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }
}

