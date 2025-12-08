<?php

namespace App\Mail;

use App\Models\Order;
use App\Services\EmailService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class OrderPaymentFailed extends LocalizedMailable
{
    public Order $order;
    public string $failureReason;

    public function __construct(Order $order, string $failureReason = '', ?string $locale = null)
    {
        $this->order = $order;
        $this->failureReason = $failureReason;
        $this->setLocale($locale ?? EmailService::getLocaleFromOrder($order));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('emails.order_payment_failed.subject', [
                'order_number' => $this->order->order_number ?? $this->siteName
            ]),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-payment-failed',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
