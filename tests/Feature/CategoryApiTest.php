<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryApiTest extends TestCase
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

    public function test_can_get_categories_list(): void
    {
        Category::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->getJson('/api/categories');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    public function test_can_create_category(): void
    {
        $categoryData = [
            'name' => 'Test Category',
            'description' => 'Test description'
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->postJson('/api/categories', $categoryData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Test Category']);

        $this->assertDatabaseHas('categories', ['name' => 'Test Category']);
    }

    public function test_can_create_category_without_description(): void
    {
        $categoryData = ['name' => 'Category Without Description'];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->postJson('/api/categories', $categoryData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('categories', [
            'name' => 'Category Without Description',
            'description' => null
        ]);
    }

    public function test_can_update_category(): void
    {
        $category = Category::factory()->create();

        $updateData = ['name' => 'Updated Category'];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->putJson("/api/categories/{$category->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Category']);
    }

    public function test_can_delete_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_name_is_required(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->postJson('/api/categories', [
                             'description' => 'Only description'
                         ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);
    }
}