<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    public function test_can_get_books_list(): void
    {
        Book::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->getJson('/api/books');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'title', 'author_id', 'category_id', 'description', 'stock']
                     ]
                 ])
                 ->assertJsonCount(3, 'data');
    }

    public function test_can_create_book(): void
    {
        $author = Author::factory()->create();
        $category = Category::factory()->create();

        $bookData = [
            'title' => 'Test Book',
            'author_id' => $author->id,
            'category_id' => $category->id,
            'description' => 'Test Description',
            'stock' => 10
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->postJson('/api/books', $bookData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'Test Book']);

        $this->assertDatabaseHas('books', ['title' => 'Test Book']);
    }

    public function test_can_get_single_book(): void
    {
        $book = Book::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->getJson("/api/books/{$book->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $book->id]);
    }

    public function test_can_update_book(): void
    {
        $book = Book::factory()->create();

        $updateData = [
            'title' => 'Updated Title',
            'stock' => 20
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->putJson("/api/books/{$book->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Updated Title']);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Updated Title',
            'stock' => 20
        ]);
    }

    public function test_can_delete_book(): void
    {
        $book = Book::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->deleteJson("/api/books/{$book->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Book deleted successfully']);

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_unauthorized_access_denied(): void
    {
        $response = $this->getJson('/api/books');
        $response->assertStatus(401);
    }

    public function test_validation_error_on_create(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->postJson('/api/books', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title', 'author_id', 'category_id', 'description', 'stock']);
    }
}