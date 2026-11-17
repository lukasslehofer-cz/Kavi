<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\ReviewRequest;
use App\Services\EmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class OrderReviewRequestMail extends LocalizedMailable implements ShouldQueue
{
    public Order $order;
    public ReviewRequest $reviewRequest;

    /**
     * Připomínka nesmí vypadat jako omylem odeslaná kopie: má vlastní předmět,
     * nadpis a žádnou nabídku obchodu. Odkazy a token zůstávají stejné.
     */
    public bool $reminder = false;

    public function __construct(Order $order, ReviewRequest $reviewRequest, ?string $locale = null, bool $reminder = false)
    {
        $this->order = $order;
        $this->reviewRequest = $reviewRequest;
        $this->reminder = $reminder;
        $this->setLocale($locale ?? EmailService::getLocaleFromOrder($order));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans($this->reminder
                ? 'emails.review_request.reminder_subject'
                : 'emails.order_review.subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-review-request',
            with: [
                'order' => $this->order,
                'heading' => $this->trans($this->reminder
                    ? 'emails.review_request.reminder_title'
                    : 'emails.review_request.title'),
                'subheading' => $this->trans($this->reminder
                    ? 'emails.review_request.reminder_subtitle'
                    : 'emails.review_request.subtitle'),
                'reviewLink' => $this->reviewLink(),
                'starLinks' => $this->starLinks(),
                'dashboardUrl' => $this->mailUrl('dashboard.index'),
                'locale' => $this->emailLocale,
                'siteName' => $this->siteName,
                'contactEmail' => $this->contactEmail,
            ],
        );
    }

    /**
     * Odkaz bez hvězdičky - pro ty, kdo kliknou jinam než na hodnocení.
     */
    protected function reviewLink(): string
    {
        return $this->localizedRouteFor('review.track', [
            'token' => $this->reviewRequest->tracking_token,
        ]);
    }

    /**
     * Každá hvězdička je samostatný odkaz. Klik ji uloží u nás a otevře Google,
     * kde zákazník hodnotí znovu - hvězdička se tam nepřenese.
     *
     * @return array<int, string>
     */
    protected function starLinks(): array
    {
        $links = [];

        foreach (range(1, 5) as $rating) {
            $links[$rating] = $this->localizedRouteFor('review.track.rating', [
                'token' => $this->reviewRequest->tracking_token,
                'rating' => $rating,
            ]);
        }

        return $links;
    }

    public function attachments(): array
    {
        return [];
    }
}
