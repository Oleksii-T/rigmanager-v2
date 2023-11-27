<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;

class DevFillPostsMeta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:fill-posts-meta';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dev';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $posts = Post::all();
        $bar = $this->output->createProgressBar(count($posts));

        foreach ($posts as $post) {
            $d = $post->description;
            $metaTitle = $post->title . ' - ' . $post->category->name . ' ' . __('meta.title.post.show');

            $post->translations()->create([
                'field' => 'meta_title',
                'locale' => 'en',
                'value' => $metaTitle
            ]);

            $d = $post->description;
            $metaDesc = strlen($d)>90 ? (substr($d, 0, 90) . '...') : $d;

            $post->translations()->create([
                'field' => 'meta_description',
                'locale' => 'en',
                'value' => $metaDesc
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->line('');

        return 0;
    }
}



