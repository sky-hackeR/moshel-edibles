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

class StockController extends Controller
{
    //

     public function inventory(){
        $inventories = Inventory::with(['ingredient.baseUnit'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('admin.inventory', [
            'inventories' => $inventories,
        ]);
    }

    public function stockIn() {
        $stockIns = StockIn::with([
                'items',
                'items.ingredient',
                'items.unit',
            ])
            ->latest()
            ->get();

        $stats = [
            // Number of purchases today
            'today' => StockIn::whereDate('created_at', today())->count(),

            // Total money spent this month
            'month' => StockInItem::whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->sum('total_price'),

            // Items restocked today
            'items' => StockInItem::whereDate('created_at', today())->count(),

            // Last purchase date
            'last'  => StockIn::latest()->value('purchase_date'),
        ];

        return view('admin.stockIn', [
            'ingredients' => Ingredient::where('is_active', true)->get(),
            'units'       => Unit::where('use_for_purchase', true)->where('is_active', true)->get(),
            'stockIns'    => $stockIns,
            'stats'       => $stats,
        ]);
    }

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

        DB::transaction(function () use ($request) {

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

                $inventory = Inventory::lockForUpdate()->firstOrCreate(
                    ['ingredient_id' => $ingredient->id],
                    ['quantity' => 0, 'average_cost' => 0]
                );

                $newUnitCost = $item['total_price'] / $baseQty;

                $existingQty  = $inventory->quantity;
                $existingCost = $inventory->average_cost;

                $totalQty = $existingQty + $baseQty;

                // Weighted Average Cost Formula: (Old Total Value + New Total Value) / Total Qty
                $weightedCost = $totalQty > 0
                    ? (
                        ($existingQty * $existingCost) +
                        ($baseQty * $newUnitCost)
                    ) / $totalQty
                    : $newUnitCost;

                $inventory->update([
                    'quantity'     => $totalQty,
                    'average_cost' => $weightedCost,
                ]);
            }
        });

        alert()->success('Success', 'Stock added successfully');
        return back();
    }
}
