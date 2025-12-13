<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Author;
use App\Models\Category;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $authors = Author::all();
        $categories = Category::all();

        if ($authors->isEmpty() || $categories->isEmpty()) {
            $this->command->warn('Please seed authors and categories first!');
            return;
        }

        for ($i = 1; $i <= 30; $i++) {
            Book::create([
                'title' => fake()->sentence(3),
                'author_id' => $authors->random()->id,
                'category_id' => $categories->random()->id,
                'description' => fake()->paragraph(),
                'stock' => rand(0, 20),
            ]);
        }
    }
}
