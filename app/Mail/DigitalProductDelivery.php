<?php

namespace App\Mail;

use App\Models\Order;
use App\Services\EmailService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class DigitalProductDelivery extends LocalizedMailable
{
    public Order $order;
    protected string $pdfPath;

    public function __construct(Order $order, string $pdfPath, ?string $locale = null)
    {
        $this->order = $order;
        $this->pdfPath = $pdfPath;
        $this->setLocale($locale ?? EmailService::getLocaleFromOrder($order));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('emails.digital_delivery.subject', ['order_number' => $this->order->order_number]),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.digital-delivery',
            with: [
                'orderUrl' => $this->mailUrl('dashboard.order.detail', ['order' => $this->order->id]),
                'dashboardUrl' => $this->mailUrl('dashboard.index'),
            ],
        );
    }

    public function attachments(): array
    {
        $prefix = __('shop.files.voucher_prefix', [], $this->emailLocale);

        return [
            \Illuminate\Mail\Mailables\Attachment::fromStorage($this->pdfPath)
                ->as($prefix . $this->order->order_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
