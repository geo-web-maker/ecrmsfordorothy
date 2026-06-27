<?php

namespace App\Mail;

use App\Models\Stuff;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeUser extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Stuff $user) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address'),
                config('mail.from.name'),
            ),
            subject: 'Welcome to NEMA eCRMS',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
            with: ['user' => $this->user],
        );
    }
}
