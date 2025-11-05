<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BooksTableSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if authors and categories exist, if not create some
        $authorCount = Author::count();
        $categoryCount = Category::count();
        
        if ($authorCount == 0) {
            $this->command->info('No authors found, creating 1000 authors...');
            \App\Models\Author::factory()->count(1000)->create();
        }
        
        if ($categoryCount == 0) {
            $this->command->info('No categories found, creating 3000 categories...');
            \App\Models\Category::factory()->count(3000)->create();
        }
        
        // Create books in smaller batches to manage memory usage
        $totalBooks = 100000; // 100,000 books as required
        $batchSize = 500; // Smaller batch size to reduce memory usage
        
        for ($i = 0; $i < $totalBooks; $i += $batchSize) {
            $currentBatchSize = min($batchSize, $totalBooks - $i);
            $booksToCreate = [];
            
            // Create books in a memory-efficient way
            for ($j = 0; $j < $currentBatchSize; $j++) {
                $bookData = [
                    'title' => fake()->sentence(3),
                    'author_id' => Author::inRandomOrder()->first()->id,
                    'isbn' => '978' . fake()->unique()->numerify('##########'),
                    'publisher' => fake()->company(),
                    'publication_year' => fake()->numberBetween(1950, 2024),
                    'description' => fake()->sentence(15),
                    'availability_status' => fake()->randomElement(['available', 'rented', 'reserved']),
                    'store_location' => fake()->word(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                $booksToCreate[] = $bookData;
            }
            
            // Insert books with error handling
            try {
                DB::table('books')->insert($booksToCreate);
                unset($booksToCreate); // Free memory
            } catch (\Exception $e) {
                $this->command->error("Error inserting books batch at position $i: " . $e->getMessage());
                continue;
            }
            
            // Output progress
            $progress = min($i + $batchSize, $totalBooks);
            echo "Created $progress of $totalBooks books...\n";
        }

        // Attach categories in smaller chunks to manage memory
        $bookCount = Book::count();
        if ($bookCount > 0) {
            $processedCount = 0;
            $batchNum = 0;
            $maxBatchSize = 100; // Much smaller batch size for category assignment
            
            // Process books in smaller batches
            $totalBooksForCategorization = Book::count();
            
            // Get all category IDs once to avoid repeated queries
            $allCategoryIds = Category::pluck('id')->toArray();
            
            for ($offset = 0; $offset < $totalBooksForCategorization; $offset += $maxBatchSize) {
                // Use raw query to get book IDs efficiently
                $bookIds = DB::table('books')
                    ->select('id')
                    ->offset($offset)
                    ->limit($maxBatchSize)
                    ->pluck('id')
                    ->toArray();
                
                $pivots = [];
                foreach ($bookIds as $bookId) {
                    $numCategories = rand(1, 5);
                    // Select random categories without loading the models
                    $randomCategoryIds = array_rand($allCategoryIds, $numCategories > count($allCategoryIds) ? count($allCategoryIds) : $numCategories);
                    if (!is_array($randomCategoryIds)) {
                        $randomCategoryIds = [$randomCategoryIds];
                    } else {
                        $randomCategoryIds = array_values($randomCategoryIds);
                    }
                    
                    $selectedCategoryIds = array_map(function($idx) use ($allCategoryIds) {
                        return $allCategoryIds[$idx];
                    }, $randomCategoryIds);

                    foreach ($selectedCategoryIds as $categoryId) {
                        $pivots[] = [
                            'book_id' => $bookId,
                            'category_id' => $categoryId,
                        ];
                    }
                }
                
                if (!empty($pivots)) {
                    try {
                        DB::table('book_category')->insertOrIgnore($pivots);
                        unset($pivots); // Free memory
                    } catch (\Exception $e) {
                        $this->command->error("Error inserting book-category relations: " . $e->getMessage());
                    }
                }
                
                $processedCount += count($bookIds);
                $batchNum++;
                echo "Processed $processedCount books with categories (batch $batchNum)...\n";
            }
        } else {
            $this->command->info('No books found to assign categories.');
        }
    }
}