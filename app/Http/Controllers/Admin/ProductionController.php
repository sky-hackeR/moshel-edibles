<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

// Mailables
use App\Mail\Production\BatchCompleted;
use App\Mail\Production\InsufficientStock;

use App\Services\UnitConversion\UnitConverter;
use App\Models\SiteInfo as Setting;
use App\Models\Admin;
use App\Models\Staff;
use App\Models\Unit;
use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\StockIn;
use App\Models\StockInItem;
use App\Models\Recipe;
use App\Models\RecipeItem;
use App\Models\Product;
use App\Models\Production;
use App\Models\ProductionItem;

use SweetAlert;
use Alert;
use Log;
use Carbon\Carbon;

class ProductionController extends Controller
{
    public function production() {
        $products = Product::with('recipe')->where('is_active', true)->get();
        $stats = [
            'today_production' => \App\Models\Production::whereDate('created_at', today())->count()
        ];
        return view('admin.production', [
            'products' => $products,
            'stats' => $stats,
        ]);
    }

    public function productionHistory() {
        $history = Production::with(['product', 'items.ingredient'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.productionHistory', [
            'history' => $history,
        ]);
    }

    public function recordProduction(Request $request) {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|numeric|min:0.01',
            'notes'      => 'nullable|string',
            'produced_at'=> 'nullable|date',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $product = Product::with(['recipe.items.ingredient', 'recipe.items.unit'])
            ->where('is_active', true)
            ->findOrFail($request->product_id);

        if (!$product->recipe || $product->recipe->items->isEmpty()) {
            alert()->error('Invalid Operation', 'Product has no recipe or recipe is empty')->persistent('Close');
            return redirect()->back();
        }

        DB::beginTransaction();

        try {
            $totalCost = 0;
            $itemsData = [];
            $finalProducedAt = $request->produced_at ? Carbon::parse($request->produced_at) : now();

            foreach ($product->recipe->items as $item) {
                // Convert recipe quantity to Base Units (g/ml) using your Model helper
                $baseQtyPerUnit = $item->unit->toBase($item->quantity); 
                $totalRequiredBaseQty = $baseQtyPerUnit * $request->quantity;

                $inventory = Inventory::where('ingredient_id', $item->ingredient_id)
                    ->lockForUpdate()
                    ->first();

                if (!$inventory || $inventory->quantity < $totalRequiredBaseQty) {
                    $neededAmount = $totalRequiredBaseQty;
                    $ingredientName = $item->ingredient->name;

                    // Trigger the Insufficient Stock Mail
                    $this->notifyStockFailure($product, $ingredientName, $neededAmount);

                    throw new \Exception(
                        "Insufficient stock for {$ingredientName}. Needed: " . 
                        number_format($neededAmount, 2) . " " . ($item->unit->base_unit ?? 'units')
                    );
                }

                $unitCost = $inventory->average_cost;
                $itemCost = $totalRequiredBaseQty * $unitCost;

                // Deduct from Raw Inventory
                $inventory->decrement('quantity', $totalRequiredBaseQty);

                $itemsData[] = [
                    'ingredient_id' => $item->ingredient_id,
                    'quantity_used' => $totalRequiredBaseQty,
                    'unit_cost'     => $unitCost,
                    'total_cost'    => $itemCost,
                ];

                $totalCost += $itemCost;
            }

            // Calculations
            $unitCost = $totalCost / $request->quantity;
            $sellingPrice = $product->selling_price ?? 0;
            $expectedRevenue = $sellingPrice * $request->quantity;
            $profit = $expectedRevenue - $totalCost;

            // 1. Save the production log
            $production = Production::create([
                'product_id'       => $product->id,
                'quantity'         => $request->quantity,
                'unit_cost'        => $unitCost,
                'total_cost'       => $totalCost,
                'selling_price'    => $sellingPrice,
                'expected_revenue' => $expectedRevenue,
                'profit'           => $profit,
                'notes'            => $request->notes,
                'produced_at'      => $finalProducedAt,
                'created_at'       => $finalProducedAt,
            ]);

            foreach ($itemsData as $data) {
                $production->items()->create($data);
            }

            // 2. Increment Finished Goods Inventory for POS
            $product->increment('stock_on_hand', $request->quantity);

            DB::commit();

            // Trigger the Batch Success Mail
            $this->notifyProductionSuccess($production);

            alert()->success('Success', "Produced {$request->quantity} {$product->sales_unit}(s). Inventory updated.")->persistent('Close');
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error('Production Failed', $e->getMessage())->persistent('Close');
            return redirect()->back();
        }
    }

    /**
     * Notify Admins of successful production completion
     */
    private function notifyProductionSuccess($production) {
        try {
            $admins = Admin::all();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new BatchCompleted($production));
            }
        } catch (\Exception $e) {
            Log::error("Production Success Mail failed: " . $e->getMessage());
        }
    }

    /**
     * Notify Admins when production is blocked by raw material shortage
     */
    private function notifyStockFailure($product, $ingredientName, $needed) {
        try {
            $admins = Admin::all();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new InsufficientStock($product, $ingredientName, $needed));
            }
        } catch (\Exception $e) {
            Log::error("Production Failure Mail failed: " . $e->getMessage());
        }
    }
}