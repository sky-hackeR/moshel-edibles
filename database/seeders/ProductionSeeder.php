<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Production;
use App\Models\Inventory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Get products that have recipes defined
        $products = Product::has('recipe.items')->with('recipe.items.ingredient')->get();

        if ($products->isEmpty()) {
            $this->command->warn('No products with recipes found. Production skipped.');
            return;
        }

        foreach ($products as $product) {
            // Create 2 production batches for each product
            for ($i = 0; $i < 2; $i++) {
                DB::transaction(function () use ($product) {
                    $quantityToProduce = rand(10, 30);
                    $totalCost = 0;
                    $itemsData = [];

                    foreach ($product->recipe->items as $item) {
                        $inventory = Inventory::where('ingredient_id', $item->ingredient_id)->first();
                        
                        // Calculate base quantity needed (using your base_quantity from recipe)
                        $totalNeeded = $item->base_quantity * $quantityToProduce;

                        // Check if we have enough stock to actually "produce" this
                        if (!$inventory || $inventory->quantity < $totalNeeded) {
                            $this->command->warn("Skipping production for {$product->name} due to low {$item->ingredient->name} stock.");
                            return; 
                        }

                        $costForThisItem = $totalNeeded * $inventory->average_cost;
                        
                        // Deduct from raw material inventory
                        $inventory->decrement('quantity', $totalNeeded);

                        $itemsData[] = [
                            'ingredient_id' => $item->ingredient_id,
                            'quantity_used' => $totalNeeded,
                            'unit_cost'     => $inventory->average_cost,
                            'total_cost'    => $costForThisItem,
                        ];

                        $totalCost += $costForThisItem;
                    }

                    // 2. Record the Production entry
                    $production = Production::create([
                        'product_id'       => $product->id,
                        'quantity'         => $quantityToProduce,
                        'unit_cost'        => $totalCost / $quantityToProduce,
                        'total_cost'       => $totalCost,
                        'selling_price'    => $product->selling_price,
                        'expected_revenue' => $product->selling_price * $quantityToProduce,
                        'profit'           => ($product->selling_price * $quantityToProduce) - $totalCost,
                        'notes'            => 'Initial seeded production run.',
                        'produced_at'      => Carbon::now()->subDays(rand(0, 5)),
                    ]);

                    // 3. Save the Production Items (Ingredients used)
                    foreach ($itemsData as $data) {
                        $production->items()->create($data);
                    }
                    
                    // 4. Update the finished goods stock (Product stock_on_hand)
                    $product->increment('stock_on_hand', $quantityToProduce);
                });
            }
        }
        $this->command->info('Production Seeder completed successfully!');
    }
}