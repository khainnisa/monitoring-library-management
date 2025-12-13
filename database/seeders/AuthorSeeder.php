<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;
use Illuminate\Support\Str;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            Author::create([
                'name' => 'Author ' . $i,
                'email' => 'author' . $i . '@example.com',
                'bio' => fake()->optional()->sentence(rand(8, 15)),
            ]);
        }
    }
}
