<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Machine;
use App\Models\ProductCategory;
use App\Models\Slot;
use Exception;
use Illuminate\Database\Seeder;

final class MachineSeeder extends Seeder
{
    private const JUICES = ['Orange Juice', 'Apple Juice', 'Pineapple Juice', 'Mango Juice', 'Grape Juice'];

    private const MEALS = ['Grilled Chicken', 'Beef Burger', 'Vegetable Pasta', 'Caesar Salad', 'Fish and Chips'];

    private const SNACKS = ['Potato Chips', 'Chocolate Bar', 'Mixed Nuts', 'Granola Bar', 'Popcorn'];

    private const COFFEES = ['Espresso', 'Cappuccino', 'Latte', 'Americano', 'Mocha'];

    private const PRODUCT_CATEGORIES_SLUGS = ['juice', 'meal', 'snack', 'coffee'];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get product categories
        $productCategories = ProductCategory::query()
            ->get()
            ->keyBy('slug');

        // Check product categories existance
        if ($productCategories->count() !== count(self::PRODUCT_CATEGORIES_SLUGS)) {
            throw new Exception('At least one category is missing.');
        }

        Machine::factory()
            ->count(20)
            ->create()
            ->each(function (Machine $machine) use ($productCategories) {
                // Insert slots
                $slotsData = [];

                for ($slotNumber = 1; $slotNumber <= 40; $slotNumber++) {
                    if ($slotNumber >= 1 && $slotNumber <= 10) {
                        $category = $productCategories['juice'];
                        $products = self::JUICES;
                    } elseif ($slotNumber >= 11 && $slotNumber <= 20) {
                        $category = $productCategories['meal'];
                        $products = self::MEALS;
                    } elseif ($slotNumber >= 21 && $slotNumber <= 30) {
                        $category = $productCategories['snack'];
                        $products = self::SNACKS;
                    } else { // 31-40
                        $category = $productCategories['coffee'];
                        $products = self::COFFEES;
                    }

                    $productName = $products[array_rand($products)];

                    $slotsData[] = [
                        'product_name' => $productName,
                        'number' => $slotNumber,
                        'price' => rand(50, 150),
                        'is_available' => true,
                        'machine_id' => $machine->id,
                        'product_category_id' => $category->id,
                    ];
                }

                Slot::insert($slotsData);
            });
    }
}
