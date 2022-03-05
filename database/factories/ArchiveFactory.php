<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Archive>
 */
class ArchiveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->text(14),
            'description' => $this->faker->text(40),
            'year' => $this->faker->year,
            'tome' => $this->faker->randomNumber,
            'document_path' => $this->faker->filePath,
            'preview_small_image_path' => $this->faker->text,
            'preview_big_image_path' => $this->faker->text,
        ];
    }
}
