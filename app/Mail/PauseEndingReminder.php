<?php

namespace App\Mail;

use App\Helpers\DateFormat;
use Carbon\Carbon;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Připomínka tři dny před koncem pauzy: co přijde v prvním boxu po pauze, kdy se
 * za něj strhne platba a odkazy na prodloužení pauzy a změnu boxu.
 */
class PauseEndingReminder extends UpcomingBoxMailable
{
    public function envelope(): Envelope
    {
        $box = $this->box();
        $month = $this->boxMonth();
        $roasteries = $this->roasteryNames();

        $subject = match (true) {
            $month && $roasteries !== '' => $this->trans('emails.pause_ending_reminder.subject', ['month' => $month, 'roasteries' => $roasteries]),
            (bool) $month => $this->trans('emails.pause_ending_reminder.subject_month', [
                'date' => DateFormat::dayMonth($box['shipment_date'], $this->emailLocale),
            ]),
            default => $this->trans('emails.pause_ending_reminder.subject_generic'),
        };

        return new Envelope(
            from: $this->getFromAddress(),
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pause-ending-reminder',
            with: $this->boxViewData() + [
                'pauseEnd' => $this->subscription->paused_until_date
                    ? DateFormat::dayMonth($this->subscription->paused_until_date, $this->emailLocale)
                    : null,
            ],
        );
    }

    /**
     * Během pauzy next_billing_date neplatí (pauza ho neposouvá, nastaví ho až
     * obnovení), platba je billing date měsíce prvního boxu po pauze
     */
    protected function paymentDate(): ?Carbon
    {
        return $this->box()['billing_date'] ?? null;
    }
}
