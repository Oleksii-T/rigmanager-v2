<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\User;
use App\Models\Post;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $amounts[] = null;
        for ($i=0; $i < 10; $i++) {
            $amounts[] = $i*rand(1,5);
        }
        $partNumberFormats = [
            '??-###',
            '??-##-?-#-???',
            '????-?-###',
            '##-??-##-??',
            '##?#/#?##',
            '??? / ###-?',
        ];

        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'category_id' => Category::inRandomOrder()->value('id'),
            'status' => 'approved',
            'type' => Post::TYPES[array_rand(Post::TYPES)],
            'condition' => Post::CONDITIONS[array_rand(Post::CONDITIONS)],
            'legal_type' => Post::LEGAL_TYPES[array_rand(Post::LEGAL_TYPES)],
            'duration' => Post::DURATIONS[array_rand(Post::DURATIONS)],
            'is_active' => true,
            'is_urgent' => rand(0,1),
            'is_import' => rand(0,1),
            'amount' => $amounts[array_rand($amounts)],
            'manufacturer' => fake()->company(),
            'manufacture_date' => fake()->date(),
            'part_number' => fake()->bothify($partNumberFormats[array_rand($partNumberFormats)]),
            'cost' => fake()->randomFloat(2, 100, 9999),
        ];
    }
}
