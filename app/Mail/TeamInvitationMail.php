<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeamInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $member,
        public string $firmName,
        public string $setPasswordUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'دعوة لتفعيل حسابك في مكتب ' . $this->firmName . ' على قسطاس',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.team-invitation',
        );
    }
}
