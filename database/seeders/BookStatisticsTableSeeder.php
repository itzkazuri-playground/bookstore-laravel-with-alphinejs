<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookStatistics;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookStatisticsTableSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Process books in smaller chunks to avoid memory issues
        $bookCount = Book::count();
        if ($bookCount === 0) {
            $this->command->info('No books found. Skipping BookStatistics seeding until books are created.');
            return;
        }
        
        Book::chunkById(200, function ($books) {  // Reduced from 1000 to 200
            $statisticsToCreate = [];
            foreach ($books as $book) {
                $bookStats = BookStatistics::factory()->make([
                    'book_id' => $book->id
                ]);
                
                // Convert attributes to array and ensure proper datetime format
                $attributes = $bookStats->getAttributes();
                
                // Convert Carbon instances to MySQL datetime format
                foreach (['created_at', 'updated_at', 'last_calculated_at'] as $dateField) {
                    if (isset($attributes[$dateField]) && $attributes[$dateField] instanceof \DateTime) {
                        $attributes[$dateField] = $attributes[$dateField]->format('Y-m-d H:i:s');
                    }
                }
                
                $statisticsToCreate[] = $attributes;
            }
            
            if (!empty($statisticsToCreate)) {
                try {
                    BookStatistics::insert($statisticsToCreate);
                } catch (\Exception $e) {
                    $this->command->error("Error inserting book statistics: " . $e->getMessage());
                }
            }
        });
    }
}