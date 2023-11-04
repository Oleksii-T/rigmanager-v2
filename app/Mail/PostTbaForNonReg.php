<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\URL;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class PostTbaForNonReg extends Mailable
{
    use Queueable, SerializesModels;

    public $post;
    public $user;
    public $currentU;
    public $regUrl;
    public $messageText;

    /**
     * Create a new message instance.
     */
    public function __construct($post, $user, $message)
    {
        $this->post = $post;
        $this->currentU = $post->user;
        $this->user = $user;
        $this->regUrl = URL::temporarySignedRoute('profile.register-simple-form', now()->addDays(2), ['email' => $this->user->email]);
        $message = str_replace("\n", '<br>', $message);
        $this->messageText = $message;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your have recieved Price Request | Rigmangers.com',
            replyTo: $this->user->getEmails(0)
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.posts.price-quotation-for-non-reg',
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
