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
        // Generate tracking link to Trustpilot via our controller
        $trustpilotLink = route('review.track', ['token' => $this->reviewRequest->tracking_token]);
        
        return new Content(
            view: 'emails.order-review-request',
            with: [
                'order' => $this->order,
                'trustpilotLink' => $trustpilotLink,
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
