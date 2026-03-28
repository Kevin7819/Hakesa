<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $categories = ['sublimacion', 'laser', 'vinil', 'varios'];
        $serviceTypes = ['sublimacion', 'laser', 'vinil'];

        return [
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(10),
            'price' => fake()->randomFloat(2, 1000, 50000),
            'category' => fake()->randomElement($categories),
            'service_type' => fake()->randomElement($serviceTypes),
            'is_active' => true,
            'stock' => fake()->numberBetween(0, 100),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
