<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorApiTest extends TestCase
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

    public function test_can_get_authors_list(): void
    {
        Author::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->getJson('/api/authors');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    public function test_can_create_author(): void
    {
        $authorData = [
            'name' => 'Test Author',
            'email' => 'test@example.com',
            'bio' => 'Test bio'
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->postJson('/api/authors', $authorData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Test Author']);

        $this->assertDatabaseHas('authors', ['email' => 'test@example.com']);
    }

    public function test_can_update_author(): void
    {
        $author = Author::factory()->create();

        $updateData = ['name' => 'Updated Name'];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->putJson("/api/authors/{$author->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Name']);
    }

    public function test_can_delete_author(): void
    {
        $author = Author::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->deleteJson("/api/authors/{$author->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    }

    public function test_email_must_be_unique(): void
    {
        $author = Author::factory()->create(['email' => 'existing@example.com']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->postJson('/api/authors', [
                             'name' => 'Another Author',
                             'email' => 'existing@example.com'
                         ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }
}