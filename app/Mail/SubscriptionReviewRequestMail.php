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

    /**
     * Připomínka nesmí vypadat jako omylem odeslaná kopie: má vlastní předmět,
     * nadpis a žádnou nabídku obchodu. Odkazy a token zůstávají stejné.
     */
    public bool $reminder = false;

    public function __construct(Subscription $subscription, ReviewRequest $reviewRequest, ?string $locale = null, bool $reminder = false)
    {
        $this->subscription = $subscription;
        $this->reviewRequest = $reviewRequest;
        $this->reminder = $reminder;
        $this->setLocale($locale ?? EmailService::getLocaleFromSubscription($subscription));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans($this->reminder
                ? 'emails.review_request.reminder_subject'
                : 'emails.subscription_review.subject'),
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
                'heading' => $this->trans($this->reminder
                    ? 'emails.review_request.reminder_title'
                    : 'emails.subscription_review.title'),
                'subheading' => $this->trans($this->reminder
                    ? 'emails.review_request.reminder_subtitle'
                    : 'emails.subscription_review.subtitle'),
                'reviewLink' => $this->reviewLink(),
                'starLinks' => $this->starLinks(),
                'deliveredOrdersCount' => $deliveredOrdersCount,
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
