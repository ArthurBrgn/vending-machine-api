<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classification>
 */
final class ClassificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'daily_points_limit' => fake()->numberBetween(100, 500),
        ];
    }

    public function regularEmployee(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Regular employee',
                'daily_points_limit' => 300,
            ];
        });
    }

    public function manager(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Manager',
                'daily_points_limit' => 500,
            ];
        });
    }
}
