<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\Admin;
use App\Models\StockIn;
use App\Models\Ingredient;
use App\Models\StockInItem;
use App\Models\Inventory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StockInSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Get an Admin
        $admin = Admin::first() ?? Admin::factory()->create(['name' => 'Store Manager']);

        $ingredients = Ingredient::all();
        
        if ($ingredients->isEmpty()) {
            $this->command->warn("No ingredients found. Run IngredientSeeder first!");
            return;
        }

        // 2. GUARANTEE INITIAL STOCK: Ensure every ingredient has a base quantity
        // This prevents the ProductionSeeder from skipping due to "low stock"
        foreach ($ingredients as $ingredient) {
            Inventory::updateOrCreate(
                ['ingredient_id' => $ingredient->id],
                [
                    'quantity' => 100000, // Start with 100kg / 100L / 100pcs base
                    'average_cost' => rand(1, 10), // Base cost per gram/ml/unit
                ]
            );
        }

        // 3. Define bulk units for random purchases
        $kg = Unit::where('symbol', 'kg')->first();
        $ltr = Unit::where('symbol', 'l')->first();
        $pcs = Unit::where('symbol', 'pcs')->first();

        // 4. Create 5 random bulk purchase batches
        for ($i = 1; $i <= 5; $i++) {
            $date = Carbon::now()->subDays(rand(1, 20));
            
            $stockIn = StockIn::create([
                'reference'     => 'STK-' . strtoupper(Str::random(6)),
                'purchase_date' => $date,
                'supplier'      => 'Global Food Supplies Ltd',
                'note'          => 'Random bulk restocking batch.',
                'created_by'    => $admin->id,
            ]);

            // Pick 4 random ingredients for this specific invoice
            $randomIngredients = $ingredients->random(min(4, $ingredients->count()));

            foreach ($randomIngredients as $ingredient) {
                $purchaseUnit = $kg;
                $conversionFactor = 1000;

                if ($ingredient->base_unit_id == $pcs?->id) {
                    $purchaseUnit = $pcs;
                    $conversionFactor = 1;
                } elseif ($ingredient->base_unit_id == Unit::where('symbol', 'ml')->first()?->id) {
                    $purchaseUnit = $ltr;
                    $conversionFactor = 1000;
                }

                $qtyPurchased = rand(10, 50);
                $totalPrice = $qtyPurchased * rand(500, 2000);

                StockInItem::create([
                    'stock_in_id'   => $stockIn->id,
                    'ingredient_id' => $ingredient->id,
                    'unit_id'       => $purchaseUnit->id ?? $ingredient->base_unit_id,
                    'quantity'      => $qtyPurchased,
                    'base_quantity' => $qtyPurchased * $conversionFactor,
                    'unit_price'    => $totalPrice / $qtyPurchased,
                    'total_price'   => $totalPrice,
                ]);

                // Update Weighted Average Cost in Inventory
                $inventory = Inventory::where('ingredient_id', $ingredient->id)->first();
                $newBaseQty = $qtyPurchased * $conversionFactor;
                
                $currentTotalValue = $inventory->quantity * $inventory->average_cost;
                $newTotalQty = $inventory->quantity + $newBaseQty;
                $newAverageCost = ($currentTotalValue + $totalPrice) / $newTotalQty;

                $inventory->update([
                    'quantity'     => $newTotalQty,
                    'average_cost' => $newAverageCost
                ]);
            }
        }

        $this->command->info('StockIn Seeder completed successfully with guaranteed base stock!');
    }
}