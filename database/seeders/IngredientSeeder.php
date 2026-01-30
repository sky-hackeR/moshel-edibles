<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\Ingredient;
use App\Models\Inventory;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public function run()
    {
        $categories = [

            // --- BAKERY & PASTRY STATION ---
            'bakery' => [
                ['name' => 'All-Purpose Flour', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Bread Flour', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Cake Flour', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Cornstarch', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Granulated Sugar', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Brown Sugar', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Icing Sugar', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Unsalted Butter', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Heavy Cream', 'unit' => 'ml', 'type' => 'volume'],
                ['name' => 'Yeast (Instant)', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Baking Powder', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Baking Soda', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Vanilla Bean Paste', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Dark Chocolate Couverture', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Milk Chocolate Chips', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Honey', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Maple Syrup', 'unit' => 'ml', 'type' => 'volume'],
            ],

            // --- SHAWARMA & MIDDLE EASTERN STATION ---
            'shawarma' => [
                ['name' => 'Chicken Thighs (Boneless)', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Beef Sirloin', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Lamb Shoulder', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Tahini Paste', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Greek Yogurt', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Garlic Cloves', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Sumac', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Cumin Powder', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Coriander Powder', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Paprika', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Cardamom Powder', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'White Pepper', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Pickled Turnips', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Pita Bread (Large)', 'unit' => 'pcs', 'type' => 'count'],
            ],

            // --- BURGER & FAST FOOD STATION ---
            'burger' => [
                ['name' => 'Ground Beef (80/20)', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Brioche Burger Buns', 'unit' => 'pcs', 'type' => 'count'],
                ['name' => 'Cheddar Cheese Slices', 'unit' => 'pcs', 'type' => 'count'],
                ['name' => 'American Cheese Slices', 'unit' => 'pcs', 'type' => 'count'],
                ['name' => 'Iceberg Lettuce', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Beefsteak Tomatoes', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Red Onions', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Dill Pickle Slices', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Mayonnaise', 'unit' => 'ml', 'type' => 'volume'],
                ['name' => 'Yellow Mustard', 'unit' => 'ml', 'type' => 'volume'],
                ['name' => 'Tomato Ketchup', 'unit' => 'ml', 'type' => 'volume'],
                ['name' => 'Liquid Smoke', 'unit' => 'ml', 'type' => 'volume'],
                ['name' => 'Beef Bacon Slices', 'unit' => 'pcs', 'type' => 'count'],
            ],

            // --- PANCAKE & BREAKFAST STATION ---
            'breakfast' => [
                ['name' => 'Buttermilk', 'unit' => 'ml', 'type' => 'volume'],
                ['name' => 'Large Eggs', 'unit' => 'pcs', 'type' => 'count'],
                ['name' => 'Blueberries (Fresh)', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Strawberries', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Nutella', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Peanut Butter', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Canola Oil', 'unit' => 'ml', 'type' => 'volume'],
            ],

            // --- GENERAL PANTRY ---
            'pantry' => [
                ['name' => 'Kosher Salt', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Black Peppercorns', 'unit' => 'g', 'type' => 'mass'],
                ['name' => 'Extra Virgin Olive Oil', 'unit' => 'ml', 'type' => 'volume'],
                ['name' => 'Lemon Juice', 'unit' => 'ml', 'type' => 'volume'],
                ['name' => 'White Vinegar', 'unit' => 'ml', 'type' => 'volume'],
            ],
        ];

        foreach ($categories as $station => $ingredients) {
            foreach ($ingredients as $item) {

                $unit = Unit::where('symbol', $item['unit'])
                            ->where('unit_type', $item['type'])
                            ->first();

                if (!$unit) {
                    continue;
                }

                $ingredient = Ingredient::updateOrCreate(
                    ['slug' => Str::slug($item['name'])],
                    [
                        'name' => $item['name'],
                        'base_unit_id' => $unit->id,
                        'is_active' => true,
                    ]
                );

                // 🔥 ALWAYS ENSURE INVENTORY EXISTS
                Inventory::firstOrCreate(
                    ['ingredient_id' => $ingredient->id],
                    ['quantity' => 0, 'average_cost' => 0],
                    
                );
            }
        }
    }
}
