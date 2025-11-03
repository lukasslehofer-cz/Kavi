<?php

namespace App\Mail;

use App\Models\Subscription;
use App\Models\ReviewRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionReviewRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Subscription $subscription,
        public ReviewRequest $reviewRequest
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Jak se vám líbí naše služby? ⭐',
        );
    }

    /**
     * Get the message content definition.
     */
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
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
