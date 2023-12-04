<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Enums\NotificationGroup;
use App\Models\Notification;
use App\Models\Mailer;
use App\Models\Post;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailerPostFound;
use Log;

class MailerSendNotifs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailers:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email notifications of mailer found posts';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            // get active mailers
            $mailers = Mailer::where('is_active', true)->whereNotNull('to_mail')->get();

            // check each mailer for need of sending
            foreach ($mailers as $mailer) {

                // get posts found by mailer
                $posts = Post::query()
                    ->visible()
                    ->whereIn('id', $mailer->to_mail)
                    ->filter($mailer->filters)
                    ->get();

                // skip if not posts found
                if ($posts->isEmpty()) {
                    $mailer->update([
                        'to_mail' => null
                    ]);
                    continue;
                }

                // send the mail with found posts
                Mail::to($mailer->user)->send(new MailerPostFound($mailer, $posts));

                // make notification about mailer been send
                Notification::make($mailer->user->id, NotificationGroup::MAILER_SEND, [
                    'vars' => [
                        'name' => $mailer->title,
                        'posts' => $posts->count()
                    ]
                ], $mailer);

                // clear found posts
                $postsIds = $posts->pluck('id')->toArray();
                $mailer->update([
                    'last_at' => now(),
                    'posts' => array_merge($mailer->posts??[], $postsIds),
                    'to_mail' => null
                ]);

                activity('mailers')
                    ->event('email-send')
                    ->withProperties(infoForActivityLog() + [
                        'posts' => $postsIds
                    ])
                    ->on($mailer)
                    ->log('');
            }
        } catch (\Throwable $th) {
            Log::channel('commands')->error("[$this->signature] " . $th->getMessage(), [
                'mailer' => $mailer??null,
                'trace' => substr($th->getTraceAsString(), 0, 600)
            ]);
        }

        return 0;
    }
}
