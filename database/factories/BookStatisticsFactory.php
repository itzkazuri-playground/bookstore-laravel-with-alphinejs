<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookStatistics>
 */
class BookStatisticsFactory extends Factory
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
            'average_rating' => fake()->randomFloat(2, 1, 9.99),
            'total_voters' => fake()->numberBetween(0, 1000),
            'last_7_days_avg' => fake()->randomFloat(2, 1, 9.99),
            'last_30_days_avg' => fake()->randomFloat(2, 1, 9.99),
            'previous_30_days_avg' => fake()->randomFloat(2, 1, 9.99),
            'last_calculated_at' => fake()->dateTime(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}