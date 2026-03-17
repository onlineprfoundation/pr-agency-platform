<?php

namespace App\Mail;

use App\Models\PackageOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PackageOrderStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PackageOrder $order
    ) {}

    public function envelope(): Envelope
    {
        $status = ucfirst(str_replace('_', ' ', $this->order->status));
        return new Envelope(
            subject: "Order Update: {$this->order->package->name} – {$status}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.package-order-status-updated',
        );
    }
}
