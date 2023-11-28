<?php

namespace App\Mail\Subscriptions;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Enums\NotificationGroup;
use App\Models\SubscriptionCycle;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class EndTomorrow extends Mailable
{
    use Queueable, SerializesModels;

    public $plan;
    public $url;
    public $isCanceled;

    /**
     * Create a new message instance.
     */
    public function __construct(SubscriptionCycle $cycle, $type)
    {
        $this->plan = $cycle->plan->title;
        $this->url = route('profile.subscription');
        $this->isCanceled = $type == NotificationGroup::SUB_END_TOMORROW;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $title = $this->isCanceled ? 'Subscription ends tomorrow' : 'Subscription will be renewed tomorrow';
        return new Envelope(
            subject: $title . ' | rigmangers.com',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.subscriptions.end-tomorrow',
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
