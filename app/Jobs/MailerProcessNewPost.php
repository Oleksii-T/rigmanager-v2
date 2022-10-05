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
    private $created;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post, $created=true)
    {
        $this->post = $post;
        $this->created = $created;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        sleep(1); // wait untill post translations saved as well.

        dlog("MailerProcessNewPost@handle. " . $this->post->id); //! LOG

        $mailersByUser = Mailer::where('is_active', true)->get()->groupBy('user_id');

        foreach ($mailersByUser as $userId => $mailers) {
            $send = false;
            dlog(" check mailers for $userId user"); //! LOG
            foreach ($mailers as $mailer) {
                if ($send) {
                    dlog("  already send"); //! LOG
                    break;
                }

                $query = Post::where('posts.id', $this->post->id);
                Post::applyFilters($query, $mailer->filters);
                $post = $query->first();

                if (!$post) {
                    dlog("  not meets mailer filters"); //! LOG
                    continue;
                }

                dlog("  send"); //! LOG
                Mail::to($post->user)->send(new MailerPostFound($mailer, $post, $this->created));
                $mailer->update([
                    'last_at' => now(),
                    'posts' => ($mailer->posts??[]) + [$this->post->id]
                ]);

                $send = true;
            }
        }
    }
}
