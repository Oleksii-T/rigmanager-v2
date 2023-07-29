<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attachment>
 */
class AttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => 'other.svg',
            'original_name' => 'other.svg',
            'type' => 'image',
            'size' => '1179'
        ];
    }

    public function group($group)
    {
        return $this->state(function (array $attributes) use ($group){
            return [
                'group' => $group
            ];
        });
    }

    public function parent($model)
    {
        return $this->state(function (array $attributes) use ($model){
            return [
                'translatable_id' => $model->id,
                'translatable_type' => get_class($model),
            ];
        });
    }
}
