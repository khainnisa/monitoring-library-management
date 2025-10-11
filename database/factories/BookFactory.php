<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'author_id' => Author::factory(),
            'category_id' => Category::factory(),
            'description' => fake()->paragraph(),
            'stock' => fake()->numberBetween(0, 50),
        ];
    }
}