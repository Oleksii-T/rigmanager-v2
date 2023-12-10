<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\URL;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class DailyContactViewsForNonReg extends Mailable
{
    use Queueable, SerializesModels;

    public $regUrl;
    public $user;
    public $count;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $count)
    {
        $this->user = $user;
        $this->count = $count;
        $this->regUrl = URL::temporarySignedRoute('profile.register-simple-form', now()->addDays(2), ['email' => $user->email]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Daily Contact Views Report | Rigmangers.com',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.daily-contact-views-for-non-reg',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
