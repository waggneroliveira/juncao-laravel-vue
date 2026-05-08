<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
    public $fullName;

    public function __construct($code, $fullName)
    {
        $this->code = $code;
        $this->fullName = $fullName;
    }

    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Código de Verificação - Seu Pedido',
    //     );
    // }

    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'emails.verification-code',
    //     );
    // }

    public function build()
    {
        return $this->from('waggner.447@gmail.com', 'WHI')
            ->subject('Código de Verificação - Seu Pedido')
            ->view('emails.verification-code'); 
    }
}