<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

// Models
use App\Models\Admin;
use App\Models\Product;
use App\Models\Production;
use App\Models\Inventory;

// Mailables
use App\Mail\Production\BatchCompleted;
use App\Mail\Production\InsufficientStock;

class ProductionController extends Controller
{
    /**
     * Show the staff production form
     */
    public function production() {
        $products = Product::with([
            'recipe.items.ingredient.baseUnit',
            'recipe.items.unit',
        ])->where('is_active', true)->get();

        $inventories = Inventory::with('ingredient.baseUnit')->get()->keyBy('ingredient_id');

        $productionMatrix = $products->mapWithKeys(function ($product) use ($inventories) {
            $recipe = $product->recipe;
            $items = [];
            $hasRecipe = $recipe && $recipe->items->isNotEmpty();

            if ($hasRecipe) {
                foreach ($recipe->items as $item) {
                    $baseQtyPerUnit = $item->unit ? $item->unit->toBase($item->quantity) : (float) $item->quantity;
                    $inv = $inventories->get($item->ingredient_id);
                    $stockAvailable = $inv ? (float) $inv->quantity : 0.0;
                    $avgCost = $inv ? (float) $inv->average_cost : 0.0;
                    $baseSymbol = $item->ingredient->baseUnit->symbol ?? ($item->unit->symbol ?? 'units');

                    $items[] = [
                        'ingredient_id' => $item->ingredient_id,
                        'name' => $item->ingredient->name ?? 'Unknown Ingredient',
                        'recipe_qty' => (float) $item->quantity,
                        'recipe_unit' => $item->unit->symbol ?? '',
                        'base_qty_per_unit' => (float) $baseQtyPerUnit,
                        'base_unit' => $baseSymbol,
                        'stock_available' => (float) $stockAvailable,
                        'average_cost' => (float) $avgCost,
                    ];
                }
            }

            return [$product->id => [
                'id' => $product->id,
                'name' => $product->name,
                'sales_unit' => $product->sales_unit ?? 'Units',
                'selling_price' => (float) ($product->selling_price ?? 0),
                'stock_on_hand' => (float) ($product->stock_on_hand ?? 0),
                'has_recipe' => $hasRecipe,
                'items' => $items,
            ]];
        });

        $stats = [
            'today_production' => Production::where('staff_id', Auth::guard('staff')->id())
                                        ->whereDate('created_at', today())
                                        ->count()
        ];

        return view('staff.production', [
            'products' => $products,
            'stats' => $stats,
            'productionMatrix' => $productionMatrix,
        ]);
    }

    public function liveInventoryStatus() {
        $inventories = Inventory::select('ingredient_id', 'quantity', 'average_cost')->get()->keyBy('ingredient_id');
        return response()->json([
            'status' => 'success',
            'timestamp' => now()->format('h:i:s A'),
            'inventories' => $inventories,
        ]);
    }

    /**
     * Show production history to staff
     */
    public function productionHistory() {
        $history = Production::with(['product', 'items.ingredient'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('staff.productionHistory', [
            'history' => $history,
        ]);
    }

    /**
     * Record new production batch (Staff Version)
     */
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
                $baseQtyPerUnit = $item->unit->toBase($item->quantity); 
                $totalRequiredBaseQty = $baseQtyPerUnit * $request->quantity;

                $inventory = Inventory::where('ingredient_id', $item->ingredient_id)
                    ->lockForUpdate()
                    ->first();

                if (!$inventory || $inventory->quantity < $totalRequiredBaseQty) {
                    $neededAmount = $totalRequiredBaseQty;
                    $ingredientName = $item->ingredient->name;
                    $this->notifyStockFailure($product, $ingredientName, $neededAmount);

                    throw new \Exception(
                        "Insufficient stock for {$ingredientName}. Needed: " . 
                        number_format($neededAmount, 2) . " " . ($item->unit->base_unit ?? 'units')
                    );
                }

                $unitCost = $inventory->average_cost;
                $itemCost = $totalRequiredBaseQty * $unitCost;

                $inventory->decrement('quantity', $totalRequiredBaseQty);

                $itemsData[] = [
                    'ingredient_id' => $item->ingredient_id,
                    'quantity_used' => $totalRequiredBaseQty,
                    'unit_cost'     => $unitCost,
                    'total_cost'    => $itemCost,
                ];

                $totalCost += $itemCost;
            }

            $unitCost = $totalCost / $request->quantity;
            $sellingPrice = $product->selling_price ?? 0;
            $expectedRevenue = $sellingPrice * $request->quantity;
            $profit = $expectedRevenue - $totalCost;

            // 1. Save the production log (Capturing Staff ID)
            $production = Production::create([
                'product_id'       => $product->id,
                'staff_id'         => Auth::guard('staff')->id(), 
                'admin_id'         => null,
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

            $product->increment('stock_on_hand', $request->quantity);

            DB::commit();

            $this->notifyProductionSuccess($production);

            alert()->success('Success', "Produced {$request->quantity} {$product->sales_unit}(s). Stock updated.")->persistent('Close');
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error('Production Failed', $e->getMessage())->persistent('Close');
            return redirect()->back();
        }
    }

    /**
     * Notify Admins of successful production completion via single SMTP envelope
     */
    private function notifyProductionSuccess($production) {
        try {
            $adminEmails = Admin::pluck('email')->filter()->unique()->values()->all();
            if (empty($adminEmails)) return;

            $mailable = new BatchCompleted($production);
            $primary = array_shift($adminEmails);

            $mail = Mail::to($primary);
            if (!empty($adminEmails)) {
                $mail->bcc($adminEmails);
            }
            $mail->send($mailable);
        } catch (\Throwable $e) {
            Log::error("Staff Production Success Mail failed: " . $e->getMessage());
        }
    }

    /**
     * Notify Admins when production is blocked by raw material shortage
     */
    private function notifyStockFailure($product, $ingredientName, $needed) {
        try {
            $adminEmails = Admin::pluck('email')->filter()->unique()->values()->all();
            if (empty($adminEmails)) return;

            $mailable = new InsufficientStock($product, $ingredientName, $needed);
            $primary = array_shift($adminEmails);

            $mail = Mail::to($primary);
            if (!empty($adminEmails)) {
                $mail->bcc($adminEmails);
            }
            $mail->send($mailable);
        } catch (\Throwable $e) {
            Log::error("Staff Production Failure Mail failed: " . $e->getMessage());
        }
    }
}