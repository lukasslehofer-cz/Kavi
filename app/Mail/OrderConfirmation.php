<?php

namespace App\Mail;

use App\Helpers\CurrencyHelper;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = __('emails.order_confirmation.subject', ['order_number' => $this->order->order_number]);
        
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        
        // Attach invoice PDF if available
        if ($this->order->invoice_pdf_path && \Storage::exists($this->order->invoice_pdf_path)) {
            $invoicePrefix = CurrencyHelper::isCzk() ? 'Faktura-' : 'Invoice-';
            $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromStorage($this->order->invoice_pdf_path)
                ->as($invoicePrefix . $this->order->order_number . '.pdf')
                ->withMime('application/pdf');
        }
        
        return $attachments;
    }
}

