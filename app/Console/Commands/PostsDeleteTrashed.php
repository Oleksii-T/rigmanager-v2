<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use Log;

class PostsDeleteTrashed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:delete-trashed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete trashed posts';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $posts = Post::query()
                ->where('is_trashed', true)
                ->where('updated_at', '<', now()->subWeek())
                ->get();

            foreach ($posts as $post) {
                $post->delete();
            }
        } catch (\Throwable $th) {
            Log::channel('commands')->error("[$this->signature] " . $th->getMessage());
        }

        return true;
    }
}
