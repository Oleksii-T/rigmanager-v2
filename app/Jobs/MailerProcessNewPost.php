<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailerPostFound;
use App\Models\Post;
use App\Models\Mailer;

class MailerProcessNewPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $post;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mailersByUser = Mailer::where('is_active', true)->get()->groupBy('user_id');

        foreach ($mailersByUser as $userId => $mailers) {
            $send = false;
            foreach ($mailers as $mailer) {
                if ($send) {
                    break;
                }

                $post = Post::where('posts.id', $this->post->id)->filter($mailer->filters)->first();

                if (!$post) {
                    continue;
                }

                $toSend = $mailer->to_send??[];
                $toSend[] = $this->post->id;
                $mailer->update([
                    'to_mail' => $toSend
                ]);

                $send = true;
            }
        }
    }
}
