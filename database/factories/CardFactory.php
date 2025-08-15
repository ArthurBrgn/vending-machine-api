<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
final class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'expired_date' => fake()->optional(0.8)->dateTimeBetween('+1 month', '+2 years'),
            'notes' => fake()->optional()->text(100),
            'is_active' => true,
        ];
    }
}
