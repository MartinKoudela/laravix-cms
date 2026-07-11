<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Laravix\Cms\Models\UserInvitation;

class UserInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly UserInvitation $invitation) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You have been invited to '.$this->invitation->site->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'laravix::emails.user-invitation',
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
