<?php

namespace App\Mail;

use App\Models\Subscription;
use App\Models\ReviewRequest;
use App\Services\EmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class SubscriptionReviewRequestMail extends LocalizedMailable implements ShouldQueue
{
    public Subscription $subscription;
    public ReviewRequest $reviewRequest;

    public function __construct(Subscription $subscription, ReviewRequest $reviewRequest, ?string $locale = null)
    {
        $this->subscription = $subscription;
        $this->reviewRequest = $reviewRequest;
        $this->setLocale($locale ?? EmailService::getLocaleFromSubscription($subscription));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('emails.subscription_review.subject'),
        );
    }

    public function content(): Content
    {
        // Kolik boxů z tohohle předplatného už dorazilo. Milník na žádosti je
        // spolehlivější než dopočítávání ze stavů objednávek.
        $deliveredOrdersCount = $this->reviewRequest->milestone
            ?: $this->subscription->shipments()->whereNotNull('delivered_at')->count();

        return new Content(
            view: 'emails.subscription-review-request',
            with: [
                'subscription' => $this->subscription,
                'reviewLink' => $this->reviewLink(),
                'starLinks' => $this->starLinks(),
                'deliveredOrdersCount' => $deliveredOrdersCount,
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
