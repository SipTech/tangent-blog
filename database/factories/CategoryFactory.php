<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unique_word = fake()->word()
        . "_"
        . str(fake()->unique()->randomDigit());
        return [
            'title' => $unique_word ,
            'slug' => $unique_word,
            'created_at' => now(),
        ];
    }
}
