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
        $staffId = Auth::guard('staff')->id();

        $myProductionCount = Production::where('staff_id', $staffId)
            ->whereDate('produced_at', $today)
            ->count();

        $mySalesCount = Sale::where('user_id', $staffId)
            ->where('user_type', 'staff')
            ->whereDate('created_at', $today)
            ->count();

        $mySalesTotal = Sale::where('user_id', $staffId)
            ->where('user_type', 'staff')
            ->whereDate('created_at', $today)
            ->sum('payable_amount');

        $overallProductionCount = Production::whereDate('produced_at', $today)->count();
        $overallSalesCount = Sale::whereDate('created_at', $today)->count();
        $overallSalesTotal = Sale::whereDate('created_at', $today)->sum('payable_amount');

        $lowStockCount = Inventory::with(['ingredient.baseUnit'])
            ->get()
            ->filter(function($inventory) {
                $ingredient = $inventory->ingredient;
                if (!$ingredient) return false;

                return $inventory->quantity <= $ingredient->effective_reorder_level;
            })->count();

        $recentProductions = Production::with('product')
            ->where('staff_id', $staffId)
            ->whereDate('produced_at', $today)
            ->latest()
            ->take(5)
            ->get();

        $myUnitsProducedToday = (float) Production::where('staff_id', $staffId)
            ->whereDate('produced_at', $today)
            ->sum('quantity');

        $myRecentSales = Sale::where('user_id', $staffId)
            ->where('user_type', 'staff')
            ->whereDate('created_at', $today)
            ->with(['items.product'])
            ->latest()
            ->take(6)
            ->get();

        $myPaymentBreakdown = Sale::where('user_id', $staffId)
            ->where('user_type', 'staff')
            ->whereDate('created_at', $today)
            ->select('payment_method', DB::raw('SUM(payable_amount) as total'), DB::raw('count(*) as count'))
            ->groupBy('payment_method')
            ->get();

        $lowStockIngredients = Inventory::with(['ingredient.baseUnit'])
            ->get()
            ->filter(function($inventory) {
                $ingredient = $inventory->ingredient;
                return $ingredient && $inventory->quantity <= $ingredient->effective_reorder_level;
            })
            ->take(4)
            ->values();

        $availableProducts = Product::where('is_active', true)
            ->where('stock_on_hand', '>', 0)
            ->orderBy('stock_on_hand', 'desc')
            ->take(5)
            ->get();

        $outOfStockProductsCount = Product::where('is_active', true)
            ->where('stock_on_hand', '<=', 0)
            ->count();

        return view('staff.dashboard.index', [
            'myProductionCount'       => $myProductionCount,
            'myUnitsProducedToday'    => $myUnitsProducedToday,
            'mySalesCount'            => $mySalesCount,
            'mySalesTotal'            => $mySalesTotal,
            'overallProductionCount'  => $overallProductionCount,
            'overallSalesCount'       => $overallSalesCount,
            'overallSalesTotal'       => $overallSalesTotal,
            'lowStockCount'           => $lowStockCount,
            'recentProductions'       => $recentProductions,
            'myRecentSales'           => $myRecentSales,
            'myPaymentBreakdown'      => $myPaymentBreakdown,
            'lowStockIngredients'     => $lowStockIngredients,
            'availableProducts'       => $availableProducts,
            'outOfStockProductsCount' => $outOfStockProductsCount,
        ]);
    }
    
    

    public function profile()
    {
        $staff = Auth::guard('staff')->user() ?? Auth::user();
        return view('staff.users.profile', [
            'staff' => $staff
        ]);
    }

    public function updateProfile(Request $request)
    {
        $staff = Auth::guard('staff')->user() ?? Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,' . $staff->id,
        ]);

        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $staff = Auth::guard('staff')->user() ?? Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $staff->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        $staff->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

}
