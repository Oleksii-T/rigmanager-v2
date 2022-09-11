<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Translation;
use App\Models\Attachment;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = Post::factory()
            ->has(
                Translation::factory()->locale('en')->field('title')->text(30)->state(
                    function (array $attributes, Post $post) {
                        return [
                            'translatable_id' => $post->id,
                            'translatable_type' => Post::class,
                        ];
                    }
                )
            )
            ->has(
                Translation::factory()->locale('en')->field('description')->text(1000)->state(
                    function (array $attributes, Post $post) {
                        return [
                            'translatable_id' => $post->id,
                            'translatable_type' => Post::class,
                        ];
                    }
                )
            )
            ->count(500)
            ->create();

        foreach ($posts as $post) {
            $post->update([
                'cost_usd' => $post->cost
            ]);
        }
    }
}
