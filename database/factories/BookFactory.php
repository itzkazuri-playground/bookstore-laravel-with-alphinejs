<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3), // judul lebih pendek, ringan untuk seeding
            'author_id' => null,            // assign random saat seeding
            'isbn' => '978' . fake()->unique()->numerify('##########'),
            'publisher' => fake()->company(),
            'publication_year' => fake()->numberBetween(1950, 2024),
            'description' => fake()->sentence(15), // lebih ringan daripada paragraph panjang
            'availability_status' => fake()->randomElement(['available', 'rented', 'reserved']),
            'store_location' => fake()->word(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
