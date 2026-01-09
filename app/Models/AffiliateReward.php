<?php

namespace App\Models;

use App\Helpers\CurrencyHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'affiliate_partner_id',
        'coupon_id',
        'order_id',
        'subscription_id',
        'subscription_payment_number',
        'reward_type',
        'reward_amount',
        'currency',
        'status',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'reward_amount' => 'decimal:2',
        'subscription_payment_number' => 'integer',
        'paid_at' => 'datetime',
    ];

    /**
     * Vztah k affiliate partnerovi
     */
    public function affiliatePartner()
    {
        return $this->belongsTo(User::class, 'affiliate_partner_id');
    }

    /**
     * Vztah ke kupónu
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Vztah k objednávce
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Vztah k předplatnému
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Scope pro čekající odměny
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope pro schválené odměny
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope pro vyplacené odměny
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope pro zrušené odměny
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope pro odměny z objednávek
     */
    public function scopeOrders($query)
    {
        return $query->where('reward_type', 'order');
    }

    /**
     * Scope pro odměny z předplatného
     */
    public function scopeSubscriptions($query)
    {
        return $query->where('reward_type', 'subscription');
    }

    /**
     * Formátovaná částka odměny
     */
    public function getFormattedAmount(): string
    {
        return CurrencyHelper::formatByCurrency($this->reward_amount, $this->currency);
    }

    /**
     * Získá název statusu
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Čeká na schválení',
            'approved' => 'Schváleno',
            'paid' => 'Vyplaceno',
            'cancelled' => 'Zrušeno',
            default => $this->status,
        };
    }

    /**
     * Schválí odměnu
     */
    public function approve(): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }

        return $this->update(['status' => 'approved']);
    }

    /**
     * Označí jako vyplacenou
     */
    public function markAsPaid(?string $notes = null): bool
    {
        $data = [
            'status' => 'paid',
            'paid_at' => now(),
        ];

        if ($notes) {
            $data['notes'] = $notes;
        }

        return $this->update($data);
    }

    /**
     * Zruší odměnu
     */
    public function cancel(?string $notes = null): bool
    {
        $data = ['status' => 'cancelled'];

        if ($notes) {
            $data['notes'] = $notes;
        }

        return $this->update($data);
    }
}
