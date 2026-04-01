<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeamRemovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $member,
        public string $firmName,
        public string $removedByName,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'إشعار إزالة حسابك من مكتب ' . $this->firmName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.team-removal',
        );
    }
}