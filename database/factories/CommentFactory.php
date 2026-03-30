<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'content' => fake()->sentence(10),
            'status' => 'aprobado',
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => 'pendiente']);
    }

    public function rejected(): static
    {
        return $this->state(fn () => ['status' => 'rechazado']);
    }
}
