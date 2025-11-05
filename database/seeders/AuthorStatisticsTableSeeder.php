<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\AuthorStatistics;
use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorStatisticsTableSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure books exist before creating author statistics
        $bookCount = Book::count();
        if ($bookCount === 0) {
            $this->command->info('No books found. Skipping AuthorStatistics seeding until books are created.');
            return;
        }
        
        Author::chunkById(100, function ($authors) {
            $statisticsToCreate = [];
            foreach ($authors as $author) {
                $authorBookIds = Book::where('author_id', $author->id)->pluck('id')->toArray();
                
                $bestRatedBookId = null;
                $worstRatedBookId = null;
                
                if (!empty($authorBookIds)) {
                    $bestRatedBookId = $authorBookIds[array_rand($authorBookIds)];
                    $worstRatedBookId = $authorBookIds[array_rand($authorBookIds)];
                }
                
                $authorStats = AuthorStatistics::factory()->make([
                    'author_id' => $author->id,
                    'best_rated_book_id' => $bestRatedBookId,
                    'worst_rated_book_id' => $worstRatedBookId
                ]);
                
                // Convert attributes to array and ensure proper datetime format
                $attributes = $authorStats->getAttributes();
                
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
                    AuthorStatistics::insert($statisticsToCreate);
                } catch (\Exception $e) {
                    $this->command->error("Error inserting author statistics: " . $e->getMessage());
                }
            }
        });
    }
}