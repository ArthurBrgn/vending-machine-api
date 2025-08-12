<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Machine>
 */
final class MachineFactory extends Factory
{
    private array $locations = ['Ground Floor', 'Kitchen', 'Office', 'Workshop', 'Warehouse', 'First Floor'];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Machine '.fake()->numberBetween(1, 100),
            'location' => fake()->randomElement($this->locations),
            'ip_address' => fake()->ipv4(),
        ];
    }
}
