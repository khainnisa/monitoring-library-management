<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Author>
 */
class AuthorFactory extends Factory
{
    protected $model = Author::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'bio' => fake()->optional(0.8)->paragraph(3), // 80% chance ada bio
        ];
    }

    /**
     * Indicate that the author has no bio.
     */
    public function withoutBio(): static
    {
        return $this->state(fn (array $attributes) => [
            'bio' => null,
        ]);
    }

    /**
     * Indicate that the author has a long bio.
     */
    public function withLongBio(): static
    {
        return $this->state(fn (array $attributes) => [
            'bio' => fake()->paragraphs(5, true),
        ]);
    }
}