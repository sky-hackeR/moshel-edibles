<?php

namespace App\Http\Controllers\Admin;

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

use App\Models\Sale;
use App\Models\SaleItem;

use SweetAlert;
use Alert;
use Log;
use Carbon\Carbon;

class AdminController extends Controller
{
    // public function index(){
    //     $today = Carbon::today();
    //     $yesterday = Carbon::yesterday();

    //     // 1. Core Stats
    //     $todayRevenue = Sale::whereDate('created_at', $today)->sum('payable_amount');
    //     $yesterdayRevenue = Sale::whereDate('created_at', $yesterday)->sum('payable_amount');
        
    //     $todayPurchases = StockInItem::whereDate('created_at', $today)->sum('total_price');
    //     $todayProductionCost = Production::whereDate('produced_at', $today)->sum('total_cost');
    //     $todaySpent = $todayPurchases + $todayProductionCost;
    //     $todayProfit = $todayRevenue - $todaySpent;
    //     $todaySalesCount = Sale::whereDate('created_at', $today)->count();

    //     // 2. Smart Insights Logic
    //     $revenueChange = 0;
    //     if ($yesterdayRevenue > 0) {
    //         $revenueChange = (($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100;
    //     }

    //     $lowStockCount = Product::where('stock_on_hand', '<=', 10)->count();

    //     // 3. Payment Method Distribution
    //     $paymentData = Sale::select('payment_method', DB::raw('count(*) as count'))
    //         ->groupBy('payment_method')
    //         ->orderBy('count', 'desc')
    //         ->get();
        
    //     $topPaymentMethod = $paymentData->first()->payment_method ?? 'N/A';

    //     // 4. Forecast Logic
    //     $lastThreeDaysAvg = Sale::whereDate('created_at', '>=', Carbon::now()->subDays(3))
    //         ->select(DB::raw('SUM(payable_amount) as total'))
    //         ->groupBy(DB::raw('Date(created_at)'))
    //         ->get()
    //         ->avg('total');
    //     $forecastRevenue = $lastThreeDaysAvg ?? 0;

    //     // 5. Charts
    //     $chartDays = [];
    //     $chartRevenues = [];
    //     for ($i = 6; $i >= 0; $i--) {
    //         $date = Carbon::now()->subDays($i);
    //         $chartDays[] = $date->format('D, d M');
    //         $chartRevenues[] = (float) Sale::whereDate('created_at', $date->toDateString())->sum('payable_amount');
    //     }

    //     $topProducts = SaleItem::with('product')
    //         ->select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(quantity * unit_price) as total_revenue'))
    //         ->groupBy('product_id')
    //         ->orderBy('total_qty', 'desc')
    //         ->take(5)
    //         ->get();

    //     $lowStockProducts = Product::where('stock_on_hand', '<=', 10)
    //         ->where('is_active', true)
    //         ->orderBy('stock_on_hand', 'asc')
    //         ->take(5)
    //         ->get();

    //     return view('admin.home', [
    //         'todayRevenue'     => $todayRevenue,
    //         'todaySpent'       => $todaySpent,
    //         'todayProfit'      => $todayProfit,
    //         'todaySalesCount'  => $todaySalesCount,
    //         'revenueChange'    => round($revenueChange, 1),
    //         'lowStockCount'    => $lowStockCount,
    //         'topPaymentMethod' => $topPaymentMethod,
    //         'forecastRevenue'  => $forecastRevenue,
    //         'chartDays'        => $chartDays,
    //         'chartRevenues'    => $chartRevenues,
    //         'topProducts'      => $topProducts,
    //         'lowStockProducts' => $lowStockProducts,
    //         'paymentLabels'    => $paymentData->pluck('payment_method')->toArray(),
    //         'paymentCounts'    => $paymentData->pluck('count')->toArray(),
    //     ]);
    // }
    public function index(){
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // 1. Core Stats
        $todayRevenue = Sale::whereDate('created_at', $today)->sum('payable_amount');
        
        // Split the logic: Purchases is stock investment, Production is the actual expense
        $todayPurchases = StockInItem::whereDate('created_at', $today)->sum('total_price');
        $todayProductionCost = Production::whereDate('produced_at', $today)->sum('total_cost');
        
        // FIX: Only count Production Cost as the expense for profit calculation
        $todaySpent = $todayProductionCost; 
        $todayProfit = $todayRevenue - $todaySpent;
        
        $todaySalesCount = Sale::whereDate('created_at', $today)->count();

        // 2. Charts Data (Last 7 Days)
        $chartDays = [];
        $chartRevenues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartDays[] = $date->format('D, d M');
            $chartRevenues[] = (float) Sale::whereDate('created_at', $date->toDateString())->sum('payable_amount');
        }

        // 3. Tables Data
        $topProducts = SaleItem::with('product')
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(quantity * unit_price) as total_revenue'))
            ->groupBy('product_id')
            ->orderBy('total_qty', 'desc')
            ->take(5)
            ->get();

        $lowStockProducts = Product::where('stock_on_hand', '<=', 10)
            ->where('is_active', true)
            ->orderBy('stock_on_hand', 'asc')
            ->take(5)
            ->get();

        // 4. Payment Method Distribution
        $paymentData = Sale::select('payment_method', DB::raw('count(*) as count'))
            ->groupBy('payment_method')
            ->get();

        return view('admin.home', [
            'todayRevenue'        => $todayRevenue,
            'todayPurchases'      => $todayPurchases,
            'todayProductionCost' => $todayProductionCost,
            'todaySpent'          => $todaySpent,
            'todayProfit'         => $todayProfit,
            'todaySalesCount'     => $todaySalesCount,
            'chartDays'           => $chartDays,
            'chartRevenues'       => $chartRevenues,
            'topProducts'         => $topProducts,
            'lowStockProducts'    => $lowStockProducts,
            'paymentLabels'       => $paymentData->pluck('payment_method')->toArray(),
            'paymentCounts'       => $paymentData->pluck('count')->toArray(),
        ]);
    }

    //GLOBAL SITE SETTINGS LOGIC
    public function siteSettings(){
        $setting = Setting::first();
        return view('admin.siteSettings', [
            'setting' => $setting,
        ]);
    }

    public function adminList() {
        $admins = Admin::orderBy('name', 'asc')->get();

        return view('admin.admins', [
            'admins' => $admins,
        ]);
    }

    public function staffList() {
        $staffs = Staff::orderBy('name', 'asc')->get();

        return view('admin.staffs', [
            'staffs' => $staffs,
        ]);
    }


    public function updateSiteInfo(Request $request){
        $validator = Validator::make($request->all(), [
            'logo' => 'nullable|image',
            'favicon' => 'nullable|image',
            'description' => 'nullable|string',
            'site_name' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            alert()->error('Error', $validator->messages()->all()[0])->persistent('Close');
            return redirect()->back();
        }
    
        $siteInfo = new Setting;
        if(!empty($request->site_info_id) && !$siteInfo = Setting::find($request->site_info_id)){
            alert()->error('Oops', 'Invalid Site Information')->persistent('Close');
            return redirect()->back();
        }
    
        if (!empty($request->site_name)) {
            $siteInfo->site_name = $request->site_name;
        }
    
        if (!empty($request->description)) {
            $siteInfo->description = $request->description;
        }
    
        // Save logo
        $logoUrl = null;
        if ($request->hasFile('logo')) {
            $logoUrl = 'uploads/siteInfo/' .'logo'.'.'.$request->file('logo')->getClientOriginalExtension();
            $logo = $request->file('logo')->move('uploads/siteInfo', $logoUrl);
            $siteInfo->logo = $logoUrl;
        }
    
        // Save favicon
        $faviconUrl = null;
        if ($request->hasFile('favicon')) {
            $faviconUrl = 'uploads/siteInfo/' .'favicon'.'.'.$request->file('favicon')->getClientOriginalExtension();
            $favicon = $request->file('favicon')->move('uploads/siteInfo', $faviconUrl);
            $siteInfo->favicon = $faviconUrl;
        }
    
        if($siteInfo->save()){
            alert()->success('Changes Saved', 'Site information changes saved successfully')->persistent('Close');
            return redirect()->back();
        }
    
        alert()->error('Oops!', 'Something went wrong')->persistent('Close');
        return redirect()->back();
    }  

    public function newAdmin(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:admins,email',
            'password' => 'required|string|min:6|confirmed', 
        ]);

        if ($validator->fails()) {
            alert()->error('Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $admin = new Admin();
        $admin->fill([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if ($admin->save()) {
            alert()->success('Success', 'Admin created successfully')->persistent('Close');
        } else {
            alert()->error('Oops!', 'Something went wrong while creating the admin')->persistent('Close');
        }

        return redirect()->back();
    }

    public function newStaff(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:staff,email',
            'password' => 'required|string|min:6|confirmed', 
        ]);

        if ($validator->fails()) {
            alert()->error('Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $staff = new Staff();
        $staff->fill([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if ($staff->save()) {
            alert()->success('Success', 'Staff created successfully')->persistent('Close');
        } else {
            alert()->error('Oops!', 'Something went wrong while creating the staff')->persistent('Close');
        }

        return redirect()->back();
    }
   
}