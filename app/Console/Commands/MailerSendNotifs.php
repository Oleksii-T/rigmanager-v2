<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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
            $mailers = Mailer::where('is_active', true)->whereNotNull('to_mail')->get();

            foreach ($mailers as $mailer) {

                $posts = Post::query()
                    ->visible()
                    ->whereIn('id', $mailer->to_mail)
                    ->filter($mailer->filters)
                    ->get();

                if ($posts->isEmpty()) {
                    $mailer->update([
                        'to_mail' => null
                    ]);
                    continue;
                }

                Mail::to($mailer->user)->send(new MailerPostFound($mailer, $posts));

                $postsIds = $posts->pluck('id')->toArray();

                $mailer->logs()->create([
                    'posts' => $postsIds,
                    'filters' => $mailer->filters
                ]);

                $mailer->update([
                    'last_at' => now(),
                    'posts' => array_merge($mailer->posts??[], $postsIds),
                    'to_mail' => null
                ]);

            }
        } catch (\Throwable $th) {
            Log::channel('commands')->error("[$this->signature] " . $th->getMessage(), [
                'mailer' => $mailer??null,
                'trace' => substr($th->getTraceAsString(), 0, 600)
            ]);
        }
    }
}
