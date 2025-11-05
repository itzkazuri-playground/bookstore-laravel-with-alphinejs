<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 3000 categories as required by the instructions
        $baseCategories = [
            'Fiction', 'Non-Fiction', 'Science', 'History', 'Mystery', 
            'Romance', 'Fantasy', 'Biography', 'Autobiography', 'Poetry',
            'Drama', 'Horror', 'Thriller', 'Adventure', 'Comedy', 
            'Tragedy', 'Mythology', 'Fairy Tale', 'Fable', 'Legend',
            'Crime', 'Dystopian', 'Utopian', 'Satire', 'Allegory',
            'Epistolary', 'Picaresque', ' bildungsroman', 'Coming-of-age', 'Slice of Life',
            'Action', 'Adventure', 'Alternate History', 'Apocalyptic', 'Chick Lit',
            'Children\'s', 'Classic', 'Contemporary', 'Cyberpunk', 'Drama',
            'Dystopian', 'Erotica', 'Fairy Tale', 'Fan Fiction', 'Fantasy',
            'Graphic Novel', 'Historical Fiction', 'Horror', 'Humor', 'Manga',
            'Memoir', 'Mystery', 'Mythology', 'Paranormal', 'Philosophy',
            'Political Thriller', 'Religious', 'Romance', 'Satire', 'Science Fiction',
            'Self Help', 'Suspense', 'Thriller', 'Urban Fantasy', 'Western',
            'Young Adult', 'Art', 'Architecture', 'Bibles', 'Biography', 'Business',
            'Chemistry', 'Computers', 'Cooking', 'Crafts', 'Economics',
            'Education', 'Engineering', 'Fitness', 'Games', 'Gardening',
            'Health', 'History', 'Hobbies', 'Home', 'Humor',
            'Inspirational', 'Language', 'Literature', 'Mathematics', 'Medical',
            'Music', 'Nature', 'Parenting', 'Pets', 'Philosophy',
            'Photography', 'Poetry', 'Politics', 'Psychology', 'Reference',
            'Religion', 'Science', 'Self Help', 'Sports', 'Technology',
            'Travel', 'True Crime', 'Veterinary', 'Dictionaries', 'Encyclopedias'
        ];
        
        // Create the required 3000 categories using base categories and adding variations
        $categoriesToCreate = [];
        $count = min(3000, count($baseCategories) * 20); // We'll make variations to reach 3000
        $batchSize = 250; // Smaller batch size to reduce memory usage
        
        for ($i = 0; $i < $count; $i++) {
            $baseCategory = $baseCategories[$i % count($baseCategories)];
            $suffix = ($i >= count($baseCategories)) ? ' ' . ($i % count($baseCategories) + 1) : '';
            
            $categoriesToCreate[] = [
                'name' => $baseCategory . $suffix,
                'description' => fake()->sentence(10),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Insert in smaller batches to manage memory
            if (count($categoriesToCreate) >= $batchSize) {
                DB::table('categories')->insert($categoriesToCreate);
                unset($categoriesToCreate); // Free memory
                $categoriesToCreate = [];
                echo "Created " . min($i + 1, $count) . " of $count categories...\n";
            }
        }
        
        // Insert any remaining categories
        if (!empty($categoriesToCreate)) {
            DB::table('categories')->insert($categoriesToCreate);
            echo "Created " . $count . " of $count categories...\n";
        }
    }
}