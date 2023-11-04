<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PostTba extends Mailable
{
    use Queueable, SerializesModels;

    public $post;
    public $user;
    public $messageText;

    /**
     * Create a new message instance.
     */
    public function __construct($post, $user, $message)
    {
        $this->post = $post;
        $this->user = $user;
        $this->messageText = $message;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Post recieved Price Request! | Rigmangers.com',
            replyTo: $this->user->getEmails(0)
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.posts.price-quotation',
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
