<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\Recipe;
use App\Models\Product;
use App\Models\Ingredient;
use App\Models\RecipeItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Get products and ingredients
        $products = Product::all();
        $ingredients = Ingredient::all();

        if ($products->isEmpty() || $ingredients->isEmpty()) {
            $this->command->warn("Ensure ProductSeeder and IngredientSeeder have been run first!");
            return;
        }

        // 2. Define recipe structures for specific products
        // Format: Product Name => [Ingredient Slug => Quantity in specific unit]
        $recipeTemplates = [
            'Bread' => [
                'all-purpose-flour' => ['qty' => 500, 'unit' => 'g'],
                'yeast-instant'     => ['qty' => 10,  'unit' => 'g'],
                'kosher-salt'       => ['qty' => 5,   'unit' => 'g'],
                'canola-oil'        => ['qty' => 20,  'unit' => 'ml'],
            ],
            'Cake' => [
                'cake-flour'        => ['qty' => 400, 'unit' => 'g'],
                'granulated-sugar'  => ['qty' => 200, 'unit' => 'g'],
                'unsalted-butter'   => ['qty' => 250, 'unit' => 'g'],
                'large-eggs'        => ['qty' => 4,   'unit' => 'pcs'],
                'baking-powder'     => ['qty' => 15,  'unit' => 'g'],
            ],
            'Meat Pie' => [
                'all-purpose-flour' => ['qty' => 300, 'unit' => 'g'],
                'ground-beef-8020'  => ['qty' => 150, 'unit' => 'g'],
                'unsalted-butter'   => ['qty' => 100, 'unit' => 'g'],
                'garlic-cloves'     => ['qty' => 10,  'unit' => 'g'],
            ],
        ];

        foreach ($recipeTemplates as $productName => $items) {
            $product = $products->where('name', $productName)->first();

            if (!$product) continue;

            // Create the Recipe
            $recipe = Recipe::create([
                'product_id' => $product->id,
                'name'       => $product->name . ' Standard Recipe',
                'note'       => 'Default production recipe for ' . $product->name,
                'is_active'  => true,
            ]);

            foreach ($items as $slug => $data) {
                $ingredient = $ingredients->where('slug', $slug)->first();
                $unit = Unit::where('symbol', $data['unit'])->first();

                if ($ingredient && $unit) {
                    // Calculate base_quantity
                    // If unit is KG and base is G, multiply by 1000. 
                    // Based on your controller, we use a multiplier or converter.
                    $multiplier = $this->getManualMultiplier($data['unit']);
                    
                    RecipeItem::create([
                        'recipe_id'     => $recipe->id,
                        'ingredient_id' => $ingredient->id,
                        'unit_id'       => $unit->id,
                        'quantity'      => $data['qty'],
                        'base_quantity' => $data['qty'] * $multiplier,
                    ]);
                }
            }
        }
    }

    /**
     * Helper to simulate your UnitConverter logic
     */
    private function getManualMultiplier($symbol)
    {
        return match (strtolower($symbol)) {
            'kg', 'l'   => 1000,
            'g', 'ml', 'pcs' => 1,
            default     => 1,
        };
    }
}