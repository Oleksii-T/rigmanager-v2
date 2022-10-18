<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Post;
use App\Models\Mailer;

class MailerPostFound extends Mailable
{
    use Queueable, SerializesModels;

    public $posts;
    public $mTitle;
    public $mId;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Mailer $mailer, $posts)
    {
        $this->posts = $posts;

        $this->mTitle = $mailer->title;
        $this->mId = $mailer->id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->mTitle)->markdown('emails.mailer-post-found');
    }
}
