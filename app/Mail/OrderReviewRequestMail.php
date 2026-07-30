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

    public function __construct(Order $order, ReviewRequest $reviewRequest, ?string $locale = null)
    {
        $this->order = $order;
        $this->reviewRequest = $reviewRequest;
        $this->setLocale($locale ?? EmailService::getLocaleFromOrder($order));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('emails.order_review.subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-review-request',
            with: [
                'order' => $this->order,
                'reviewLink' => $this->reviewLink(),
                'starLinks' => $this->starLinks(),
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
     * Každá hvězdička je samostatný odkaz, takže hodnocení vznikne jedním klikem.
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
