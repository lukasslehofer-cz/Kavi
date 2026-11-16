<?php

namespace App\Mail;

use App\Models\Order;
use App\Services\EmailService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class OrderShipped extends LocalizedMailable
{
    public Order $order;

    public function __construct(Order $order, ?string $locale = null)
    {
        $this->order = $order;
        $this->setLocale($locale ?? EmailService::getLocaleFromOrder($order));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('emails.order_shipped.subject', ['order_number' => $this->order->order_number]),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-shipped',
            with: [
                'orderUrl' => $this->mailUrl('dashboard.order.detail', ['order' => $this->order->id]),
                'dashboardUrl' => $this->mailUrl('dashboard.index'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
