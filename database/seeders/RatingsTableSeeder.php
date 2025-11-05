<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Rating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RatingsTableSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $minBookId = DB::table('books')->min('id');
        $maxBookId = DB::table('books')->max('id');

        if (!$minBookId || !$maxBookId) {
            $this->command->info('No books found, you need to run BooksTableSeeder first.');
            return;
        }
        
        // Get user range for voter_identifier
        $minUserId = DB::table('users')->min('id');
        $maxUserId = DB::table('users')->max('id');

        if (!$minUserId || !$maxUserId) {
            $this->command->info('No users found, you need to run UsersTableSeeder first.');
            return;
        }
        
        // Create ratings in smaller batches to manage memory usage
        $totalRatings = 500000; // 500,000 ratings as required
        $batchSize = 500; // Smaller batch size to reduce memory usage
        
        for ($i = 0; $i < $totalRatings; $i += $batchSize) {
            $currentBatchSize = min($batchSize, $totalRatings - $i);
            
            // Create a batch of ratings
            $ratingsToCreate = [];
            for ($j = 0; $j < $currentBatchSize; $j++) {
                $ratingsToCreate[] = [
                    'book_id' => rand($minBookId, $maxBookId),
                    'rating' => rand(1, 10),
                    'voter_identifier' => rand($minUserId, $maxUserId), // Use actual user ID
                    'rated_at' => fake()->dateTimeBetween('-1 year', 'now'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            // Insert with error handling
            try {
                DB::table('ratings')->insert($ratingsToCreate);
                unset($ratingsToCreate); // Free memory
            } catch (\Exception $e) {
                $this->command->error("Error inserting ratings batch at position $i: " . $e->getMessage());
                continue;
            }
            
            // Output progress
            $progress = min($i + $batchSize, $totalRatings);
            echo "Created $progress of $totalRatings ratings...\n";
        }
    }
}