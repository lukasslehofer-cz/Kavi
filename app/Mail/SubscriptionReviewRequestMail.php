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
        // Generate tracking link to Trustpilot via our controller
        $trustpilotLink = route('review.track', ['token' => $this->reviewRequest->tracking_token]);
        
        // Count delivered orders from this subscription
        $deliveredOrdersCount = $this->subscription->orders()
            ->whereIn('status', ['delivered', 'shipped'])
            ->count();
        
        return new Content(
            view: 'emails.subscription-review-request',
            with: [
                'subscription' => $this->subscription,
                'trustpilotLink' => $trustpilotLink,
                'deliveredOrdersCount' => $deliveredOrdersCount,
                'locale' => $this->emailLocale,
                'siteName' => $this->siteName,
                'contactEmail' => $this->contactEmail,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
