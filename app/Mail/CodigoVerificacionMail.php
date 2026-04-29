<?php

namespace App\Mail;

use App\Models\CodigoVerificacion;
use App\Models\Usuario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CodigoVerificacionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Usuario $usuario,
        public CodigoVerificacion $codigoVerificacion
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Codigo de verificacion para tu inicio de sesion',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.codigo-verificacion',
        );
    }
}
