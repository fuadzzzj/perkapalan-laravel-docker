<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MemberActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly string $activationUrl) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Aktifkan akun Perkapalan');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.member-activation');
    }
}
