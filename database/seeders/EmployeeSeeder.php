<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Classification;
use App\Models\Employee;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

final class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Product categories
        collect([
            ['name' => 'Juice'],
            ['name' => 'Meal'],
            ['name' => 'Snack'],
            ['name' => 'Coffee'],
        ])->each(fn ($data) => ProductCategory::create($data));

        // Classifications
        $regularClassification = Classification::create([
            'name' => 'Regular employee',
            'daily_points_limit' => 300,
        ]);

        $managerClassification = Classification::create([
            'name' => 'Manager',
            'daily_points_limit' => 500,
        ]);

        // Classification limits
        $regularClassification->productCategories()->attach([
            1 => ['daily_limit' => 1], // Juice
            2 => ['daily_limit' => 1], // Meal
            3 => ['daily_limit' => 1], // Snack
            4 => ['daily_limit' => 2], // Coffee
        ]);

        $managerClassification->productCategories()->attach([
            1 => ['daily_limit' => 3], // Juice
            2 => ['daily_limit' => 2], // Meal
            3 => ['daily_limit' => 2], // Snack
            4 => ['daily_limit' => 2], // Coffee
        ]);

        // Regular employees
        Employee::factory(50)
            ->count(50)
            ->for($regularClassification)
            ->has(Card::factory())
            ->create(['current_points' => 300]);

        // Manager
        Employee::factory()
            ->count(5)
            ->for($managerClassification)
            ->has(Card::factory())
            ->create(['current_points' => 500]);
    }
}
