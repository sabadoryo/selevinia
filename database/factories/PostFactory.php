<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'name' => $this->faker->text(14),
            'category_id' => $this->faker->numberBetween(1,10),
            'content' => $this->faker->text(),
            'preview_small_image_path' => $this->faker->imageUrl(),
            'preview_big_image_path' => $this->faker->imageUrl(),
        ];
    }
}
