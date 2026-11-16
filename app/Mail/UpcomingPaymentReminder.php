<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Upomínka tři dny před stržením platby: co přijde v boxu, pod tím platba
 * a odkazy na přeskočení a změnu boxu.
 */
class UpcomingPaymentReminder extends UpcomingBoxMailable
{
    public function envelope(): Envelope
    {
        $month = $this->boxMonth();
        $roasteries = $this->roasteryNames();

        $subject = match (true) {
            $month && $roasteries !== '' => $this->trans('emails.upcoming_payment.subject', ['month' => $month, 'roasteries' => $roasteries]),
            (bool) $month => $this->trans('emails.upcoming_payment.subject_month', ['month' => $month]),
            default => $this->trans('emails.upcoming_payment.subject_generic'),
        };

        return new Envelope(
            from: $this->getFromAddress(),
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.upcoming-payment-reminder',
            with: $this->boxViewData(),
        );
    }

    /**
     * Cron strhává, až když nastane next_billing_date i billing date měsíce boxu,
     * rozhoduje tedy to pozdější z obou
     */
    protected function paymentDate(): ?Carbon
    {
        $billing = $this->subscription->next_billing_date?->copy()->startOfDay();
        $boxBilling = $this->box()['billing_date'] ?? null;

        if ($billing && $boxBilling) {
            return $billing->max($boxBilling);
        }

        return $billing ?? $boxBilling;
    }
}
