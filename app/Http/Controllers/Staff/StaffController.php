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

        $recentProductions = Production::with('product')
            ->where('staff_id', $staffId)
            ->whereDate('produced_at', $today)
            ->latest()
            ->take(5)
            ->get();

        return view('staff.home', [
            'myProductionCount'      => $myProductionCount,
            'mySalesCount'           => $mySalesCount,
            'mySalesTotal'           => $mySalesTotal,
            'overallProductionCount' => $overallProductionCount,
            'overallSalesCount'      => $overallSalesCount,
            'overallSalesTotal'      => $overallSalesTotal,
            'lowStockCount'          => $lowStockCount,
            'recentProductions'      => $recentProductions,
        ]);
    }
    
    

    public function profile()
    {
        $staff = Auth::user();
        return view('staff.profile', [
            'staff' => $staff
        ]);
    }

    public function updateProfile(Request $request)
    {
        $staff = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staffs,email,' . $staff->id,
        ]);

        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        Auth::user()->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password changed successfully!');
    }

}
