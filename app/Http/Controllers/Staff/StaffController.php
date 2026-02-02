<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
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
use App\Models\Sale;
use App\Models\SaleItem;

use SweetAlert;
use Alert;
use Log;
use Carbon\Carbon;

class StaffController extends Controller
{
    //
    public function index(){
        $today = Carbon::today();

        // 1. Today's Production Count (Batches)
        $todayProductionCount = Production::whereDate('produced_at', $today)->count();

        // 2. Today's Sales Count (Number of Transactions)
        $todaySalesCount = Sale::whereDate('created_at', $today)->count();

        // 3. Low Stock Count (Using your Base Truth Logic)
        $thresholds = ['gram' => 1000, 'ml' => 1000, 'pcs' => 10];
        $lowStockCount = Inventory::with(['ingredient.baseUnit'])
            ->get()
            ->filter(function($inventory) use ($thresholds) {
                $ingredient = $inventory->ingredient;
                if (!$ingredient) return false;
                
                if ($ingredient->reorder_level > 0) {
                    return $inventory->quantity <= $ingredient->reorder_level;
                }

                $unitName = strtolower($ingredient->baseUnit->name ?? '');
                return $inventory->quantity <= ($thresholds[$unitName] ?? 0);
            })->count();

        // 4. Recent Production Activity (Last 5 batches)
        $recentProductions = Production::with('product')
            ->whereDate('produced_at', $today)
            ->latest()
            ->take(5)
            ->get();

        return view('staff.home', [
            'todayProductionCount' => $todayProductionCount,
            'todaySalesCount'      => $todaySalesCount,
            'lowStockCount'        => $lowStockCount,
            'recentProductions'    => $recentProductions,
        ]);
    }
}
