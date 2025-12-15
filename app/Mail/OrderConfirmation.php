<?php

namespace App\Mail;

use App\Helpers\CurrencyHelper;
use App\Models\Order;
use App\Services\EmailService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class OrderConfirmation extends LocalizedMailable
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
            subject: $this->trans('emails.order_confirmation.subject', ['order_number' => $this->order->order_number]),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmation',
        );
    }

    public function attachments(): array
    {
        $attachments = [];
        
        // Attach invoice PDF if available
        if ($this->order->invoice_pdf_path && \Storage::exists($this->order->invoice_pdf_path)) {
            $invoicePrefix = $this->emailLocale === 'cs' ? 'Faktura-' : 'Invoice-';
            $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromStorage($this->order->invoice_pdf_path)
                ->as($invoicePrefix . $this->order->order_number . '.pdf')
                ->withMime('application/pdf');
        }
        
        return $attachments;
    }
}
