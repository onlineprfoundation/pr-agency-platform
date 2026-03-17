<?php

namespace App\Mail;

use App\Models\PublicationOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PublicationOrderStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PublicationOrder $order
    ) {}

    public function envelope(): Envelope
    {
        $status = ucfirst(str_replace('_', ' ', $this->order->status));
        return new Envelope(
            subject: "Order Update: {$this->order->publication->name} – {$status}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.publication-order-status-updated',
        );
    }
}
