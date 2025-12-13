<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Category::create([
                'name' => 'Category ' . $i,
                'description' => fake()->optional()->sentence(10),
            ]);
        }
    }
}
