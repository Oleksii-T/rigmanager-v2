<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TmpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $url;

    /**
     * Create a new message instance.
     *
     * @param  Order  $order
     * @param  string  $unsubscribeUrl
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Build the message.
     *resources/lang/en/email-password-reset.php
     * @return $this
     */
    public function build()
    {
        return $this->subject('Reset Link')->markdown('emails.verify');
    }
}
