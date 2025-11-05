<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Author>
 */
class AuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->name(), // pakai unique biar nggak sama
            'bio' => fake()->sentence(10),      // lebih ringan daripada paragraph panjang
            'country' => fake()->country(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
