<?php

namespace App\Mail;

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
        return new Envelope(
            subject: 'Potvrzení objednávky ' . $this->order->order_number . ' - Kavi Coffee',
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
            $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromStorage($this->order->invoice_pdf_path)
                ->as('Faktura-' . $this->order->order_number . '.pdf')
                ->withMime('application/pdf');
        }
        
        return $attachments;
    }
}

