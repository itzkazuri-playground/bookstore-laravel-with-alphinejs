<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AuthorStatistics>
 */
class AuthorStatisticsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_id' => null, // nanti assign random saat seeding
            'total_voters' => fake()->numberBetween(0, 5000),
            'voters_above_5' => fake()->numberBetween(0, 5000),
            'average_rating' => round(fake()->randomFloat(2, 1, 9.99), 2),
            'last_30_days_avg' => round(fake()->randomFloat(2, 1, 9.99), 2),
            'previous_30_days_avg' => round(fake()->randomFloat(2, 1, 9.99), 2),
            'trending_score' => round(fake()->randomFloat(2, -100, 100), 2),
            'best_rated_book_id' => null, // nanti assign random saat seeding
            'worst_rated_book_id' => null, // nanti assign random saat seeding
            'last_calculated_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
