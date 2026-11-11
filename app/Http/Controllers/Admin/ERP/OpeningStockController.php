<?php

namespace App\Http\Controllers\Admin\ERP;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Admin;
use App\Models\Product;
use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\OpeningStock;

class OpeningStockController extends Controller
{
    /**
     * Display Opening Stock page
     */
    public function openingStock()
    {
        $products = Product::where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();

        $ingredients = Ingredient::with('baseUnit')
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();

        $openingStocks = OpeningStock::with([
                'product',
                'ingredient.baseUnit',
                'admin'
            ])
            ->latest()
            ->get();

        return view('admin.erp.openingStock', [
            'products'      => $products,
            'ingredients'   => $ingredients,
            'openingStocks' => $openingStocks,
        ]);
    }


    /**
     * Add existing finished product stock
     */
    public function addProductStock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|numeric|min:0.001',
            'reason'     => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            alert()->error(
                'Validation Error',
                $validator->messages()->first()
            )->persistent('Close');

            return redirect()->back()->withInput();
        }

        DB::beginTransaction();

        try {

            $product = Product::where('is_active', true)
                ->findOrFail($request->product_id);

            $openingQuantity = (float) $request->quantity;


            /*
             * Record the opening stock transaction.
             */
            OpeningStock::create([
                'item_type'  => 'product',
                'product_id' => $product->id,
                'quantity'   => $openingQuantity,
                'reason'     => $request->reason,
                'admin_id'   => Auth::guard('admin')->id(),
            ]);


            /*
             * Update actual product stock.
             */
            $product->increment(
                'stock_on_hand',
                $openingQuantity
            );


            DB::commit();

            alert()->success(
                'Success',
                "{$openingQuantity} {$product->sales_unit}(s) added to {$product->name} stock."
            )->persistent('Close');

            return redirect()->back();

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error(
                'Opening Product Stock Failed: ' .
                $e->getMessage()
            );

            alert()->error(
                'Error',
                'Failed to add product opening stock.'
            )->persistent('Close');

            return redirect()->back()->withInput();
        }
    }


    /**
     * Add existing ingredient stock
     */
    public function addIngredientStock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ingredient_id' => 'required|exists:ingredients,id',
            'quantity'     => 'required|numeric|min:0.001',
            'total_value'  => 'required|numeric|min:0',
            'reason'       => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            alert()->error(
                'Validation Error',
                $validator->messages()->first()
            )->persistent('Close');

            return redirect()->back()->withInput();
        }

        DB::beginTransaction();

        try {

            $ingredient = Ingredient::where('is_active', true)
                ->with('baseUnit')
                ->findOrFail($request->ingredient_id);


            /*
             * Get existing inventory or create it.
             */
            $inventory = Inventory::lockForUpdate()
                ->firstOrCreate(
                    [
                        'ingredient_id' => $ingredient->id,
                    ],
                    [
                        'quantity'     => 0,
                        'average_cost' => 0,
                    ]
                );


            /*
             * Opening stock is entered using the ingredient's
             * base unit.
             */
            $openingQuantity = (float) $request->quantity;

            /*
             * This is the total estimated value of the
             * existing opening stock.
             */
            $totalValue = (float) $request->total_value;


            /*
             * Calculate the cost of one base unit automatically.
             *
             * Example:
             *
             * 25,000g of flour
             * Total value = ₦37,500
             *
             * Opening cost per gram:
             *
             * 37,500 / 25,000 = ₦1.50
             */
            $openingCost = $openingQuantity > 0
                ? $totalValue / $openingQuantity
                : 0;


            /*
             * Existing inventory values.
             */
            $existingQuantity = (float) $inventory->quantity;
            $existingCost     = (float) $inventory->average_cost;


            /*
             * Calculate the new total quantity.
             */
            $totalQuantity =
                $existingQuantity +
                $openingQuantity;


            /*
             * Maintain the same weighted-average
             * costing method used by StockController.
             *
             * This means opening stock does not create
             * a separate costing system.
             */
            $weightedCost = $totalQuantity > 0
                ? (
                    ($existingQuantity * $existingCost) +
                    ($openingQuantity * $openingCost)
                ) / $totalQuantity
                : $openingCost;


            /*
             * Record the opening stock transaction.
             *
             * average_cost stores the cost per base unit
             * calculated by the system.
             */
            OpeningStock::create([
                'item_type'     => 'ingredient',
                'ingredient_id' => $ingredient->id,
                'quantity'      => $openingQuantity,
                'average_cost'  => $openingCost,
                'reason'        => $request->reason,
                'admin_id'      => Auth::guard('admin')->id(),
            ]);


            /*
             * Update actual inventory.
             */
            $inventory->update([
                'quantity'     => $totalQuantity,
                'average_cost' => $weightedCost,
            ]);


            DB::commit();

            $baseUnit = optional($ingredient->baseUnit)->symbol
                ?? 'base units';

            alert()->success(
                'Success',
                "{$openingQuantity} {$baseUnit} of {$ingredient->name} added to inventory."
            )->persistent('Close');

            return redirect()->back();

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error(
                'Opening Ingredient Stock Failed: ' .
                $e->getMessage()
            );

            alert()->error(
                'Error',
                'Failed to add ingredient opening stock.'
            )->persistent('Close');

            return redirect()->back()->withInput();
        }
    }
}