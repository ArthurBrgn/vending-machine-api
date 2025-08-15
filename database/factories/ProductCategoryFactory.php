<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductCategory>
 */
final class ProductCategoryFactory extends Factory
{
    private array $productCategories = ['Juice', 'Meal', 'Snack', 'Coffee'];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement($this->productCategories),
            'description' => fake()->optional()->text(100),
        ];
    }
}
