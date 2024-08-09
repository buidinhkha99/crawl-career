<?php

namespace Database\Factories;

use App\Enums\PostStatus;
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
            'title' => fake()->words(3, true),
            'author' => fake()->name(),
            'description' => fake()->words(20, true),
            'content' => fake()->paragraph(3, true),
            'status' => fake()->randomElement([
                PostStatus::Draft,
                PostStatus::Published,
            ]),
        ];
    }
}
