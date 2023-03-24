<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;

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
    public function definition(): array
    {
        return [
            'title' => fake()->word(),
            'author_id' => User::factory(),
            'category_id' => Category::factory(),
            'body' => fake()->paragraph(4),
            'image' => fake()->imageUrl(640, 480, 'animals', true),
            'created_at' => now(),
        ];
    }
}
