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

    public $pTitle;
    public $pDescription;
    public $created;
    public $mTitle;
    public $mId;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Mailer $mailer, Post $post, $created)
    {
        $this->pTitle = $post->title;
        $desc = substr($post->description, 0, 190);
        $this->pDescription = strlen($post->description) > 190 ? ($desc . '...') : $desc;
        $this->created = $created;
        $this->mTitle = $mailer->title;
        $this->mId = $mailer->id;
        $this->url = route('posts.show', $post);
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
