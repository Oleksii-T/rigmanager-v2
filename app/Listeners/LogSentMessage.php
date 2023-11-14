<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Activitylog\Contracts\Activity;
use Illuminate\Contracts\Queue\ShouldQueue;

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
     * @param  MessageSent $event
     * @return void
     * @throws \Exception
     */
    public function handle(MessageSent $event)
    {
        if (!config('mail.log_sent_emails')) {
            return;
        }

        $message = $event->message;

        try {
            $this->doLog($message);
        } catch (\Throwable $th) {
            \Log::error('Error when logging email send: ' . $th->getMessage());
        }
    }

    private function doLog($message)
    {
        $type = 'emails';
        try {
            // log for \Symfony\Component\Mime\Email

            $headers = $message->getPreparedHeaders(); // \Symfony\Component\Mime\Header\Headers
            $body = $message->getBody(); // \Symfony\Component\Mime\Part\Multipart\AlternativePart
            $body = $body->toString();
            $from = $headers->get('from'); // \Symfony\Component\Mime\Header\MailboxListHeader
            $to = $headers->get('to'); // \Symfony\Component\Mime\Header\MailboxListHeader
            $subject = $headers->get('subject'); // \Symfony\Component\Mime\Header\UnstructuredHeader
            $headers = [
                'from' => $from->getAddressStrings(),
                'to' => $to->getAddressStrings(),
                'subject' => $subject->getValue()
            ];

            activity($type)
                ->event('send')
                ->tap(function(Activity $activity) use($headers) {
                    $activity->properties = $headers;
                })
                ->log($body);

            return;

        } catch (\Throwable $th) {}

        try {

            // back up log for \Symfony\Component\Mime\Email

            activity($type)->event('send')->log($message->toString());

            return;

        } catch (\Throwable $th) {}

        // back up log for Swift_Message

        activity($type)->event('send')->log($message);
    }
}
