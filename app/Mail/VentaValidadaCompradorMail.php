<?php

namespace App\Mail;

use App\Models\Venta;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VentaValidadaCompradorMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Venta $venta)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu compra ha sido validada',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.venta-validada-comprador',
        );
    }
}
