<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AuthorsTableSeeder::class,
            CategoriesTableSeeder::class,
            BooksTableSeeder::class,
            AdminUserSeeder::class,  // Admin user first
            UsersTableSeeder::class, // Regular users next
            RatingsTableSeeder::class, // Ratings last (after users are created)
            AuthorStatisticsTableSeeder::class,
            BookStatisticsTableSeeder::class,
        ]);
    }
}