<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Mailables
use App\Mail\Stock\StockInRecorded;
use App\Mail\Stock\LowStockAlert;

// Models
use App\Models\Admin;
use App\Models\Unit;
use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\StockIn;
use App\Models\StockInItem;

class StockController extends Controller
{
    /**
     * Display current inventory levels
     */
    public function inventory() {
        $inventories = Inventory::with(['ingredient.baseUnit'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('admin.inventory', [
            'inventories' => $inventories,
        ]);
    }

    /**
     * Display Stock In history and stats
     */
    public function stockIn() {
        $stockIns = StockIn::with([
                'items',
                'items.ingredient',
                'items.unit',
            ])
            ->latest()
            ->get();

        $stats = [
            'today' => StockIn::whereDate('created_at', today())->count(),
            'month' => StockInItem::whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->sum('total_price'),
            'items' => StockInItem::whereDate('created_at', today())->count(),
            'last'  => StockIn::latest()->value('purchase_date'),
        ];

        return view('admin.stockIn', [
            'ingredients' => Ingredient::where('is_active', true)->get(),
            'units'       => Unit::where('use_for_purchase', true)->where('is_active', true)->get(),
            'stockIns'    => $stockIns,
            'stats'       => $stats,
        ]);
    }

    /**
     * Record a new stock purchase and update inventory
     */
    public function newStockIn(Request $request) {
        $validator = Validator::make($request->all(), [
            'purchase_date'          => 'required|date',
            'items'                  => 'required|array|min:1',
            'items.*.ingredient_id'  => 'required|exists:ingredients,id',
            'items.*.unit_id'        => 'required|exists:units,id',
            'items.*.quantity'       => 'required|numeric|min:0.001',
            'items.*.total_price'    => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first());
            return back()->withInput();
        }

        DB::beginTransaction();

        try {
            $totalSpent = 0;

            $stockIn = StockIn::create([
                'reference'     => 'STK-' . strtoupper(Str::random(8)),
                'purchase_date' => $request->purchase_date,
                'supplier'      => $request->supplier,
                'note'          => $request->note,
                'created_by'    => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                $ingredient = Ingredient::findOrFail($item['ingredient_id']);
                $unit       = Unit::findOrFail($item['unit_id']);

                // Normalize to Base Truth
                $baseQty = $unit->toBase($item['quantity']);
                $unitPrice = $item['total_price'] / $item['quantity'];

                StockInItem::create([
                    'stock_in_id'   => $stockIn->id,
                    'ingredient_id' => $ingredient->id,
                    'unit_id'       => $unit->id,
                    'quantity'      => $item['quantity'],
                    'unit_price'    => $unitPrice,
                    'total_price'   => $item['total_price'],
                    'base_quantity' => $baseQty,
                ]);

                $totalSpent += $item['total_price'];

                $inventory = Inventory::lockForUpdate()->firstOrCreate(
                    ['ingredient_id' => $ingredient->id],
                    ['quantity' => 0, 'average_cost' => 0]
                );

                // Weighted Average Cost Calculation
                $newUnitCost = $item['total_price'] / $baseQty;
                $existingQty  = $inventory->quantity;
                $existingCost = $inventory->average_cost;

                $totalQty = $existingQty + $baseQty;

                $weightedCost = $totalQty > 0
                    ? (($existingQty * $existingCost) + ($baseQty * $newUnitCost)) / $totalQty
                    : $newUnitCost;

                $inventory->update([
                    'quantity'     => $totalQty,
                    'average_cost' => $weightedCost,
                ]);
            }

            // 1. Notify Admins of the new purchase
            $this->notifyPurchase($stockIn, $totalSpent);

            // 2. Perform low stock check across all ingredients
            $this->checkLowStock();

            DB::commit();
            alert()->success('Success', 'Stock added successfully');
            return back();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Stock In failed: " . $e->getMessage());
            alert()->error('Error', 'Transaction failed: ' . $e->getMessage());
            return back();
        }
    }

    /**
     * Send email notification for a completed Stock In purchase
     */
    private function notifyPurchase($stockIn, $totalSpent) {
        try {
            $adminEmails = Admin::pluck('email')->toArray();
            if (!empty($adminEmails)) {
                Mail::to($adminEmails)->send(new StockInRecorded($stockIn, $totalSpent, Auth::user()));
            }
        } catch (\Exception $e) {
            Log::error("Stock In Mail notification failed: " . $e->getMessage());
        }
    }

    /**
     * Scan inventory for items below reorder levels (Base Truth aware)
     */
    private function checkLowStock() {
        try {
            // Define thresholds based on normalized units (Base Truth)
            $thresholds = [
                'gram' => 1000, // Warn at 1kg
                'ml'   => 1000, // Warn at 1 Litre
                'pcs'  => 10,   // Warn at 10 units
            ];

            $lowStockItems = Inventory::with(['ingredient.baseUnit'])
                ->get()
                ->filter(function($inventory) use ($thresholds) {
                    $ingredient = $inventory->ingredient;
                    if (!$ingredient) return false;
                    
                    // Priority 1: Specific reorder level in DB
                    if ($ingredient->reorder_level > 0) {
                        return $inventory->quantity <= $ingredient->reorder_level;
                    }

                    // Priority 2: Base Truth Global Fallback
                    $unitName = strtolower($ingredient->baseUnit->name ?? '');
                    if (array_key_exists($unitName, $thresholds)) {
                        return $inventory->quantity <= $thresholds[$unitName];
                    }

                    // Priority 3: Final safety fallback
                    return $inventory->quantity <= 0;
                });

            if ($lowStockItems->isNotEmpty()) {
                $adminEmails = Admin::pluck('email')->toArray();
                if (!empty($adminEmails)) {
                    Mail::to($adminEmails)->send(new LowStockAlert($lowStockItems));
                }
            }
        } catch (\Exception $e) {
            Log::error("Low Stock Check Error: " . $e->getMessage());
        }
    }
}