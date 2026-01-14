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

    /**
     * Get the shipment associated with this payment
     */
    public function shipment()
    {
        return $this->hasOne(SubscriptionShipment::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Get the expected shipment date for this payment
     * Returns the shipment date from subscription_shipments if linked,
     * otherwise calculates based on period_end (billing date determines shipment month)
     * 
     * Logic: Payment on 15.01. covers January shipment (20.01.)
     *        Payment on 15.02. covers February shipment (20.02.)
     * 
     * Note: period_end IS the billing date (e.g., 2026-01-15), and the shipment
     * happens in the same month (e.g., 2026-01-20). period_start is the previous
     * billing date, which would incorrectly point to the previous month's shipment.
     */
    public function getExpectedShipmentDateAttribute(): ?\Carbon\Carbon
    {
        // If we have a linked shipment, use its date
        if ($this->shipment) {
            return $this->shipment->shipment_date;
        }

        // Calculate from period_end - billing date determines the shipment month
        // Payment on 15.01. → shipment on 20.01. (same month)
        if ($this->period_end) {
            $schedule = \App\Models\ShipmentSchedule::getForMonth(
                $this->period_end->year,
                $this->period_end->month
            );
            
            if ($schedule) {
                return $schedule->shipment_date;
            }
            
            // Fallback to 20th of the same month
            return $this->period_end->copy()->day(20)->startOfDay();
        }

        // Fallback based on paid_at
        if ($this->paid_at) {
            // If paid after 15th, it's for next month's shipment
            $month = $this->paid_at->day > 15 
                ? $this->paid_at->copy()->addMonthNoOverflow() 
                : $this->paid_at;
            
            $schedule = \App\Models\ShipmentSchedule::getForMonth($month->year, $month->month);
            
            if ($schedule) {
                return $schedule->shipment_date;
            }
            
            return $month->copy()->day(20)->startOfDay();
        }

        return null;
    }

    public function hasFaktura(): bool
    {
        return !empty($this->fakturoid_invoice_id) && !empty($this->invoice_pdf_path);
    }
}

