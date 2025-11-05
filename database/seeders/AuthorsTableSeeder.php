<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthorsTableSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 1000 fake authors in smaller batches to manage memory
        $totalAuthors = 1000;
        $batchSize = 250;
        
        for ($i = 0; $i < $totalAuthors; $i += $batchSize) {
            $currentBatchSize = min($batchSize, $totalAuthors - $i);
            $authorsToCreate = [];
            
            for ($j = 0; $j < $currentBatchSize; $j++) {
                $authorsToCreate[] = [
                    'name' => fake()->unique()->name(),
                    'bio' => fake()->sentence(15),
                    'country' => fake()->country(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            DB::table('authors')->insert($authorsToCreate);
            unset($authorsToCreate); // Free memory
            echo "Created " . min($i + $batchSize, $totalAuthors) . " of $totalAuthors authors...\n";
        }
    }
}