<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book_id' => null, // Will be set when creating
            'rating' => fake()->numberBetween(1, 10),
            'voter_identifier' => null, // Will be set to user ID when creating for authenticated users
            'rated_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}