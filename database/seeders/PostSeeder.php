<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Translation;
use App\Models\Attachment;
use App\Models\ExchangeRate;

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
            ->count(200)
            ->create();

        $countriesCurrencies = [
            'ua' => 'uah',
            'cn' => 'cny',
            'ru' => 'rub',
            'us' => 'usd',
        ];

        foreach ($posts as $post) {

            $baseCurrency = $countriesCurrencies[$post->country];
            $cost = fake()->randomFloat(2, 100, 9999);

            foreach ($countriesCurrencies as $country => $currency) {
                $post->costs()->create([
                    'currency' => $currency,
                    'cost' => ExchangeRate::convert($baseCurrency, $currency, $cost),
                    'is_default' => $currency == $baseCurrency,
                ]);
            }

            Translation::create([
                'field' => 'slug',
                'locale' => 'en',
                'value' => makeSlug($post->title, Post::allSlugs()),
                'translatable_id' => $post->id,
                'translatable_type' => Post::class,
            ]);
        }
    }
}
