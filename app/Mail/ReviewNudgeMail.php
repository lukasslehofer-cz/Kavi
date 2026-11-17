<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\ReviewRequest;
use App\Models\Subscription;
use App\Services\EmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Výzva k dokončení hodnocení na Googlu.
 *
 * Jde tomu, kdo v žádosti o hodnocení kliknul na hvězdičku, ale recenze se
 * na Googlu neobjevila. Hvězdička z e-mailu se tam nepřenese a kdo to neví,
 * má po kliknutí pocit, že je hotovo. Posílá ji reviews:send, jednou.
 *
 * ShouldQueue je tu kvůli maileru: jen přes frontu (i sync) se použije SMTP
 * podle jazyka, takže anglická výzva odejde z kavibox.com.
 */
class ReviewNudgeMail extends LocalizedMailable implements ShouldQueue
{
    public ReviewRequest $reviewRequest;

    /**
     * Vyplněná je vždy jen jedna z dvojice. LocalizedMailable::build() z nich
     * plní hlavičky, podle kterých se e-mail v email_logs přiřadí.
     */
    public ?Order $order = null;

    public ?Subscription $subscription = null;

    /**
     * Dva povinné argumenty jsou záměr: "Znovu odeslat" v administraci umí
     * jen mailables s jedním a u ostatních vrátí srozumitelnou hlášku.
     */
    public function __construct(ReviewRequest $reviewRequest, Order|Subscription $subject, ?string $locale = null)
    {
        $this->reviewRequest = $reviewRequest;

        if ($subject instanceof Order) {
            $this->order = $subject;
        } else {
            $this->subscription = $subject;
        }

        $this->setLocale($locale ?? EmailService::getLocaleFromCurrency($subject->currency));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('emails.review_nudge.subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.review-nudge',
            with: [
                'reviewLink' => $this->reviewLink(),
            ],
        );
    }

    /**
     * Přes trackovací odkaz bez hvězdičky: klik se zaznamená, dřív kliknutá
     * hvězdička zůstane a zákazník pokračuje rovnou na Google.
     */
    protected function reviewLink(): string
    {
        return $this->localizedRouteFor('review.track', [
            'token' => $this->reviewRequest->tracking_token,
        ]);
    }

    public function attachments(): array
    {
        return [];
    }
}
