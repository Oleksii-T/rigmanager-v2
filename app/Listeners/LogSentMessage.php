<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSentMessage
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserUpdated $event
     * @return void
     * @throws \Exception
     */
    public function handle(MessageSent $event)
    {
        if (!config('mail.log_sent_emails')) {
            return;
        }

        // Symfony\Component\Mime\Email or Swift_Message obj - depends on version
        $message = $event->message;

        try {
            try {
                \Log::channel('emails')->info($message);
            } catch (\Throwable $th) {}
            \Log::channel('emails')->info($message->toString());
        } catch (\Throwable $th) {
            \Log::channel('emails')->info('Error when logging: ' . $th->getMessage());
        }
    }
}
