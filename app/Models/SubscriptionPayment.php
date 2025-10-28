<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'stripe_invoice_id',
        'stripe_payment_intent_id',
        'amount',
        'currency',
        'status',
        'paid_at',
        'period_start',
        'period_end',
        'fakturoid_invoice_id',
        'invoice_pdf_path',
        'invoice_number',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function hasFaktura(): bool
    {
        return !empty($this->fakturoid_invoice_id) && !empty($this->invoice_pdf_path);
    }
}

