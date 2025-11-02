<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPaymentFailed extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public ?string $failureReason = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Problém s platbou objednávky - ' . ($this->order->order_number ?? 'KAVI.cz'),
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
