<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendTemporaryPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $password;
    public $userName;

    public function __construct($userName, $password)
    {
        $this->userName = $userName;
        $this->password = $password;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your PDA Chapter Portal Temporary Password',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.temporary-password', // We will create this small view next
        );
    }
}