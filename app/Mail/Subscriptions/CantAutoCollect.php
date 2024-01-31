<?php

namespace App\Mail\Subscriptions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\SubscriptionCycle;
use App\Enums\NotificationGroup;

class CantAutoCollect extends Mailable
{
    use Queueable, SerializesModels;

    public $plan;
    public $sub;

    /**
     * Create a new message instance.
     */
    public function __construct(Subscription $sub)
    {
        $this->plan = $sub->plan->title;
        $this->url = route('profile.subscription');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Subscription payment can not be automatically collected | rigmangers.com',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.subscriptions.cant-auto-collect',
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
