<?php

namespace App\Mail;

use App\Models\UserInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserInvitationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public readonly UserInvitation $invitation) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You have been invited to ' . $this->invitation->site->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.user-invitation',
            with: [
                'siteName' => $this->invitation->site->name,
                'role' => $this->invitation->role,
                'acceptUrl' => route('invitation.accept', $this->invitation->token),
                'expiresAt' => $this->invitation->expires_at->format('M j, Y'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
