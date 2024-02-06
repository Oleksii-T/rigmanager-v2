<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use Log;

class PostsTruncateDeleted extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:truncate-deleted';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate deleted posts';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $posts = Post::onlyTrashed()
                ->where('deleted_at', '<', now()->subMonth())
                ->get();

            foreach ($posts as $post) {
                $post->forceDelete();
            }
        } catch (\Throwable $th) {
            Log::channel('commands')->error("[$this->signature] " . exceptionAsString($th));
        }

        return 0;
    }
}
