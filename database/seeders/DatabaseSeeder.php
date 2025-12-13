<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Author;
use App\Models\Category;
use App\Models\Book;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@library.com',
            'password' => Hash::make('password123')
        ]);

        $author1 = Author::create([
            'name' => 'J.K. Rowling',
            'email' => 'jk@example.com',
            'bio' => 'British author'
        ]);

        $author2 = Author::create([
            'name' => 'George R.R. Martin',
            'email' => 'george@example.com',
            'bio' => 'American novelist'
        ]);

        $category1 = Category::create([
            'name' => 'Fantasy',
            'description' => 'Fantasy books'
        ]);

        $category2 = Category::create([
            'name' => 'Horror',
            'description' => 'Horror books'
        ]);

        Book::create([
            'title' => 'Harry Potter',
            'author_id' => $author1->id,
            'category_id' => $category1->id,
            'description' => 'Magic story',
            'stock' => 10
        ]);

        Book::create([
            'title' => 'Game of Thrones',
            'author_id' => $author2->id,
            'category_id' => $category1->id,
            'description' => 'Epic fantasy',
            'stock' => 8
        ]);

        // Seeder baru
        $this->call([
            MemberSeeder::class,
            BorrowingSeeder::class,
        ]);
    }
}