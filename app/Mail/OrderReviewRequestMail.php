<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\ReviewRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderReviewRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Order $order,
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
            subject: 'Jak se vám líbila káva? ⭐',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Generate tracking link to Trustpilot via our controller
        $trustpilotLink = route('review.track', ['token' => $this->reviewRequest->tracking_token]);
        
        return new Content(
            view: 'emails.order-review-request',
            with: [
                'order' => $this->order,
                'trustpilotLink' => $trustpilotLink,
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
