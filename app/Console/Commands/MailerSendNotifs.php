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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $mailers = Mailer::where('is_active', true)->whereNotNull('to_send')->get();

            foreach ($mailers as $mailer) {

                $posts = Post::query()
                    ->visible()
                    ->whereIn('id', $mailer->to_send)
                    ->filter($mailer->filters)
                    ->get();

                if ($posts->isEmpty()) {
                    $mailer->update([
                        'to_send' => null
                    ]);
                    continue;
                }

                Mail::to($mailer->user)->send(new MailerPostFound($mailer, $posts));

                $mailer->update([
                    'last_at' => now(),
                    'posts' => array_merge($mailer->posts??[], $posts->pluck('id')->toArray()),
                    'to_send' => null
                ]);

            }
        } catch (\Throwable $th) {
            Log::channel('commands')->error('[rates.update] Rates updating fails. '.$th->getMessage(), [
                'mailer' => $mailer??null,
                'trace' => substr($th->getTraceAsString(), 0, 600)
            ]);
        }
    }
}
