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

class Extended extends Mailable
{
    use Queueable, SerializesModels;

    public $plan;
    public $url;
    public $notPayed;

    /**
     * Create a new message instance.
     */
    public function __construct(SubscriptionCycle $cycle, $type)
    {
        $this->plan = $cycle->plan->title;
        $this->url = route('profile.subscription');
        $this->notPayed = $type == NotificationGroup::SUB_EXTENDED_INCOMPLETE;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Subscription been extended! | rigmangers.com',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.subscriptions.extended',
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
