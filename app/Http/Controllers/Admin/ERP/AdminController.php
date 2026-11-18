<?php

namespace App\Http\Controllers\Admin\ERP;

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

use App\Mail\Admin\AdminCreated as SecurityAlertMail;
use App\Mail\Finance\DailyPerformance;
use App\Mail\Stock\LowStockAlert;

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
use App\Models\Customer;
use App\Models\StoreProduct;
use App\Models\AuditLog;

use SweetAlert;
use Alert;
use Log;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index(){
        $today = Carbon::today();
        $weekStart = $today->copy()->subDays(6);
        $previousWeekStart = $weekStart->copy()->subDays(7);
        $previousWeekEnd = $weekStart->copy()->subSecond();
        $endOfToday = $today->copy()->endOfDay();

        $revenueByDate = Sale::where('payment_status', 'paid')
            ->whereBetween('created_at', [$previousWeekStart, $endOfToday])
            ->selectRaw('DATE(created_at) as sale_date, SUM(payable_amount) as revenue')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->pluck('revenue', 'sale_date');

        $chartDays = [];
        $chartRevenues = [];
        $previousWeekRevenues = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $weekStart->copy()->addDays($i);
            $previousDate = $date->copy()->subDays(7);
            $chartDays[] = $date->format('D, d M');
            $chartRevenues[] = (float) $revenueByDate->get($date->toDateString(), 0);
            $previousWeekRevenues[] = (float) $revenueByDate->get($previousDate->toDateString(), 0);
        }

        $todayRevenue = end($chartRevenues) ?: 0;
        $yesterdayRevenue = (float) $revenueByDate->get($today->copy()->subDay()->toDateString(), 0);
        $weeklyRevenue = array_sum($chartRevenues);
        $previousWeeklyRevenue = array_sum($previousWeekRevenues);
        $weeklyProductionCost = Production::whereBetween('produced_at', [$weekStart, $endOfToday])->sum('total_cost');
        $previousWeeklyProductionCost = Production::whereBetween('produced_at', [$previousWeekStart, $previousWeekEnd])->sum('total_cost');
        $weeklyOperatingBalance = $weeklyRevenue - $weeklyProductionCost;
        $previousWeeklyOperatingBalance = $previousWeeklyRevenue - $previousWeeklyProductionCost;
        $weeklyOperatingChange = $weeklyOperatingBalance - $previousWeeklyOperatingBalance;
        $weeklyRevenueChange = $previousWeeklyRevenue > 0
            ? (($weeklyRevenue - $previousWeeklyRevenue) / $previousWeeklyRevenue) * 100
            : null;
        $weeklyOrdersCount = Sale::whereBetween('created_at', [$weekStart, $endOfToday])->count();
        $weeklyPaidOrdersCount = Sale::whereBetween('created_at', [$weekStart, $endOfToday])
            ->where('payment_status', 'paid')
            ->count();
        $paymentSuccessRate = $weeklyOrdersCount > 0
            ? ($weeklyPaidOrdersCount / $weeklyOrdersCount) * 100
            : 0;
        $averagePaidOrderValue = $weeklyPaidOrdersCount > 0
            ? $weeklyRevenue / $weeklyPaidOrdersCount
            : 0;
        $lowStockCount = Product::where('stock_on_hand', '<=', 10)->where('is_active', true)->count();
        $criticalStockCount = Product::where('stock_on_hand', '<=', 3)->where('is_active', true)->count();
        
        // 1. Core Stats
        $todayPurchases = StockInItem::whereDate('created_at', $today)->sum('total_price');
        $todayProductionCost = Production::whereDate('produced_at', $today)->sum('total_cost');
        
        $todaySpent = $todayProductionCost; 
        $todayProfit = $todayRevenue - $todaySpent;
        $todaySalesCount = Sale::whereDate('created_at', $today)->count();

        // 3. Tables Data
        $topProducts = SaleItem::with('product')
            ->whereHas('sale', function ($query) {
                $query->where('payment_status', 'paid');
            })
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

        // 5. Channel Intelligence (POS vs Online Storefront - Last 7 Days)
        $posPaidRevenue = (float) Sale::whereBetween('created_at', [$weekStart, $endOfToday])
            ->where('payment_status', 'paid')
            ->whereIn('user_type', ['staff', 'admin'])
            ->sum('payable_amount');
        $posPaidOrdersCount = Sale::whereBetween('created_at', [$weekStart, $endOfToday])
            ->where('payment_status', 'paid')
            ->whereIn('user_type', ['staff', 'admin'])
            ->count();

        $onlinePaidRevenue = (float) Sale::whereBetween('created_at', [$weekStart, $endOfToday])
            ->where('payment_status', 'paid')
            ->where(function ($q) {
                $q->whereNull('user_type')->orWhere('user_type', 'customer');
            })
            ->sum('payable_amount');
        $onlinePaidOrdersCount = Sale::whereBetween('created_at', [$weekStart, $endOfToday])
            ->where('payment_status', 'paid')
            ->where(function ($q) {
                $q->whereNull('user_type')->orWhere('user_type', 'customer');
            })
            ->count();

        $totalChannelRevenue = $posPaidRevenue + $onlinePaidRevenue;
        $posRevenueShare = $totalChannelRevenue > 0 ? round(($posPaidRevenue / $totalChannelRevenue) * 100, 1) : 0;
        $onlineRevenueShare = $totalChannelRevenue > 0 ? round(($onlinePaidRevenue / $totalChannelRevenue) * 100, 1) : 0;

        // 6. Order Fulfillment & Pipeline Status
        $pendingOrdersCount = Sale::where(function ($q) {
            $q->where('order_status', 'pending')
              ->orWhere('payment_status', 'pending');
        })->count();
        $processingOrdersCount = Sale::where('order_status', 'processing')->count();
        $completedOrdersCount = Sale::whereIn('order_status', ['completed', 'delivered'])->count();
        $failedOrdersCount = Sale::where('payment_status', 'failed')
            ->whereBetween('created_at', [$weekStart, $endOfToday])
            ->count();

        // 7. Kitchen & Production Operations
        $todayProductionBatches = Production::whereDate('produced_at', $today)->count();
        $todayProducedUnits = (float) Production::whereDate('produced_at', $today)->sum('quantity');
        $weeklyProductionBatches = Production::whereBetween('produced_at', [$weekStart, $endOfToday])->count();
        $weeklyProducedUnits = (float) Production::whereBetween('produced_at', [$weekStart, $endOfToday])->sum('quantity');
        $weeklyExpectedRevenue = (float) Production::whereBetween('produced_at', [$weekStart, $endOfToday])->sum('expected_revenue');
        $weeklyProductionProfit = $weeklyExpectedRevenue - $weeklyProductionCost;
        $productionMarginPercent = $weeklyExpectedRevenue > 0 ? round(($weeklyProductionProfit / $weeklyExpectedRevenue) * 100, 1) : 0;
        $totalRecipesCount = Recipe::count();

        // 8. Warehouse Asset Valuation & Procurement
        $rawMaterialsValuation = (float) Inventory::sum(DB::raw('quantity * average_cost'));
        $finishedGoodsValuation = (float) Product::where('is_active', true)->sum(DB::raw('stock_on_hand * selling_price'));
        $totalInventoryValuation = $rawMaterialsValuation + $finishedGoodsValuation;
        $weeklyPurchases = (float) StockInItem::whereBetween('created_at', [$weekStart, $endOfToday])->sum('total_price');

        // Raw Ingredients stock attention (items at or below reorder level)
        $lowStockIngredients = Inventory::with(['ingredient.baseUnit'])
            ->get()
            ->filter(function ($inventory) {
                $ingredient = $inventory->ingredient;
                return $ingredient && $inventory->quantity <= $ingredient->effective_reorder_level;
            })
            ->values();
        $lowStockIngredientsCount = $lowStockIngredients->count();
        $topLowIngredients = $lowStockIngredients->take(5);

        // 9. Customers & Retention
        $totalCustomersCount = Customer::count();
        $weeklyNewCustomersCount = Customer::whereBetween('created_at', [$weekStart, $endOfToday])->count();
        $verifiedCustomersCount = Customer::whereNotNull('email_verified_at')->count();
        $customerVerificationRate = $totalCustomersCount > 0 ? round(($verifiedCustomersCount / $totalCustomersCount) * 100, 1) : 0;
        $topCustomers = Customer::withCount(['sales as paid_orders_count' => function ($q) {
                $q->where('payment_status', 'paid');
            }])
            ->withSum(['sales as total_spent' => function ($q) {
                $q->where('payment_status', 'paid');
            }], 'payable_amount')
            ->orderByDesc('total_spent')
            ->take(4)
            ->get();

        // 10. Storefront Catalog Health
        $totalStoreProducts = StoreProduct::count();
        $publishedStoreProducts = StoreProduct::where('is_published', true)->count();
        $draftStoreProducts = StoreProduct::where('is_published', false)->count();
        $featuredStoreProducts = StoreProduct::where('is_featured', true)->count();
        $outOfStockStoreProducts = StoreProduct::whereHas('product', function ($q) {
            $q->where('stock_on_hand', '<=', 0);
        })->count();

        // 11. Team & Activity
        $adminsCount = Admin::count();
        $staffCount = Staff::count();
        $todayAuditLogsCount = AuditLog::whereDate('created_at', $today)->count();

        return view('admin.erp.home', [
            'todayRevenue'        => $todayRevenue,
            'todayPurchases'      => $todayPurchases,
            'todayProductionCost' => $todayProductionCost,
            'todaySpent'          => $todaySpent,
            'todayProfit'         => $todayProfit,
            'todaySalesCount'     => $todaySalesCount,
            'yesterdayRevenue'    => $yesterdayRevenue,
            'weeklyRevenue'       => $weeklyRevenue,
            'previousWeeklyRevenue' => $previousWeeklyRevenue,
            'weeklyProductionCost' => $weeklyProductionCost,
            'previousWeeklyProductionCost' => $previousWeeklyProductionCost,
            'weeklyOperatingBalance' => $weeklyOperatingBalance,
            'previousWeeklyOperatingBalance' => $previousWeeklyOperatingBalance,
            'weeklyOperatingChange' => $weeklyOperatingChange,
            'weeklyRevenueChange' => $weeklyRevenueChange,
            'weeklyOrdersCount'   => $weeklyOrdersCount,
            'weeklyPaidOrdersCount' => $weeklyPaidOrdersCount,
            'paymentSuccessRate'  => $paymentSuccessRate,
            'averagePaidOrderValue' => $averagePaidOrderValue,
            'lowStockCount'       => $lowStockCount,
            'criticalStockCount'  => $criticalStockCount,
            'chartDays'           => $chartDays,
            'chartRevenues'       => $chartRevenues,
            'previousWeekRevenues' => $previousWeekRevenues,
            'topProducts'         => $topProducts,
            'lowStockProducts'    => $lowStockProducts,
            'paymentLabels'       => $paymentData->pluck('payment_method')->toArray(),
            'paymentCounts'       => $paymentData->pluck('count')->toArray(),

            // Multi-Channel & Pipeline
            'posPaidRevenue'            => $posPaidRevenue,
            'posPaidOrdersCount'        => $posPaidOrdersCount,
            'posRevenueShare'           => $posRevenueShare,
            'onlinePaidRevenue'         => $onlinePaidRevenue,
            'onlinePaidOrdersCount'     => $onlinePaidOrdersCount,
            'onlineRevenueShare'        => $onlineRevenueShare,
            'pendingOrdersCount'        => $pendingOrdersCount,
            'processingOrdersCount'     => $processingOrdersCount,
            'completedOrdersCount'      => $completedOrdersCount,
            'failedOrdersCount'         => $failedOrdersCount,

            // Kitchen & Production
            'todayProductionBatches'    => $todayProductionBatches,
            'todayProducedUnits'        => $todayProducedUnits,
            'weeklyProductionBatches'   => $weeklyProductionBatches,
            'weeklyProducedUnits'       => $weeklyProducedUnits,
            'weeklyExpectedRevenue'     => $weeklyExpectedRevenue,
            'weeklyProductionProfit'    => $weeklyProductionProfit,
            'productionMarginPercent'   => $productionMarginPercent,
            'totalRecipesCount'         => $totalRecipesCount,

            // Warehouse & Valuation
            'rawMaterialsValuation'     => $rawMaterialsValuation,
            'finishedGoodsValuation'    => $finishedGoodsValuation,
            'totalInventoryValuation'   => $totalInventoryValuation,
            'weeklyPurchases'           => $weeklyPurchases,
            'lowStockIngredientsCount'  => $lowStockIngredientsCount,
            'topLowIngredients'         => $topLowIngredients,

            // Customer Intelligence
            'totalCustomersCount'       => $totalCustomersCount,
            'weeklyNewCustomersCount'   => $weeklyNewCustomersCount,
            'verifiedCustomersCount'    => $verifiedCustomersCount,
            'customerVerificationRate'  => $customerVerificationRate,
            'topCustomers'              => $topCustomers,

            // Storefront Catalog
            'totalStoreProducts'        => $totalStoreProducts,
            'publishedStoreProducts'    => $publishedStoreProducts,
            'draftStoreProducts'        => $draftStoreProducts,
            'featuredStoreProducts'     => $featuredStoreProducts,
            'outOfStockStoreProducts'   => $outOfStockStoreProducts,

            // Team & Logs
            'adminsCount'               => $adminsCount,
            'staffCount'                => $staffCount,
            'todayAuditLogsCount'       => $todayAuditLogsCount,
        ]);
    }

    public function newAdmin(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:admins,email',
        ]);

        if ($validator->fails()) {
            alert()->error('Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $admin = new Admin();
        $admin->fill([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make(Str::random(64)),
        ]);

        if ($admin->save()) {
            try {
                $resetStatus = Password::broker('admins')->sendResetLink(['email' => $admin->email]);
                if ($resetStatus !== Password::RESET_LINK_SENT) {
                    throw new \RuntimeException('Admin password setup email could not be queued.');
                }
                
                Mail::to(Auth::user()->email)->send(new SecurityAlertMail($admin, Auth::user()));
                
                alert()->success('Success', 'Admin created and a secure password setup link was sent')->persistent('Close');
            } catch (\Exception $e) {
                Log::error("Email failed: " . $e->getMessage());
                alert()->success('Success', 'Admin created, but email notifications failed. Check logs.')->persistent('Close');
            }
        } else {
            alert()->error('Oops!', 'Something went wrong while creating the admin')->persistent('Close');
        }

        return redirect()->back();
    }

    public function newStaff(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:staff,email',
        ]);

        if ($validator->fails()) {
            alert()->error('Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $staff = new Staff();
        $staff->fill([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make(Str::random(64)),
        ]);

        if ($staff->save()) {
            try {
                $resetStatus = Password::broker('staff')->sendResetLink(['email' => $staff->email]);
                if ($resetStatus !== Password::RESET_LINK_SENT) {
                    throw new \RuntimeException('Staff password setup email could not be queued.');
                }
                alert()->success('Success', 'Staff account created and a secure password setup link was sent')->persistent('Close');
            } catch (\Exception $e) {
                Log::error("Staff Email failed: " . $e->getMessage());
                alert()->success('Success', 'Staff created, but welcome email failed.')->persistent('Close');
            }
        } else {
            alert()->error('Oops!', 'Something went wrong while creating the staff')->persistent('Close');
        }

        return redirect()->back();
    }

    /**
     * Manual trigger for Daily Financial Report
     * Can be linked to a button: /admin/send-report
     */
    public function sendDailyReport() {
        $today = Carbon::today();
        
        $stats = [
            'revenue' => Sale::whereDate('created_at', $today)->sum('payable_amount'),
            'cost'    => Production::whereDate('produced_at', $today)->sum('total_cost'),
        ];
        $stats['profit'] = $stats['revenue'] - $stats['cost'];

        // Send Report
        Mail::to(Auth::user()->email)->send(new DailyPerformance($stats));

        // Check for Low Stock and include in alert if any exist
        $lowStock = Product::where('stock_on_hand', '<=', 10)->where('is_active', true)->get();
        if($lowStock->count() > 0) {
            Mail::to(Auth::user()->email)->send(new LowStockAlert($lowStock));
        }

        alert()->success('Mailed!', 'Report and alerts sent to your inbox.')->persistent('Close');
        return redirect()->back();
    }

    //GLOBAL SITE SETTINGS LOGIC
    public function siteSettings(){
        $setting = Setting::first();
        return view('admin.erp.siteSettings', [
            'setting' => $setting,
        ]);
    }

    public function adminList() {
        $admins = Admin::orderBy('name', 'asc')->get();

        return view('admin.erp.admins', [
            'admins' => $admins,
        ]);
    }

    public function staffList() {
        $staffs = Staff::orderBy('name', 'asc')->get();

        return view('admin.erp.staffs', [
            'staffs' => $staffs,
        ]);
    }


    public function updateSiteInfo(Request $request){
        $validator = Validator::make($request->all(), [
            'logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'favicon' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,ico,webp|max:2048',
            'description' => 'nullable|string',
            'site_name' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            alert()->error('Error', $validator->messages()->all()[0])->persistent('Close');
            return redirect()->back();
        }
    
        $siteInfo = null;
        if (!empty($request->site_info_id)) {
            $siteInfo = Setting::find($request->site_info_id);
        }
        if (!$siteInfo) {
            $siteInfo = Setting::first() ?? new Setting;
        }
    
        if (!empty($request->site_name)) {
            $siteInfo->site_name = $request->site_name;
        }
    
        if (!empty($request->description)) {
            $siteInfo->description = $request->description;
        }
    
        // Ensure upload directory exists (isolate testing uploads so real assets are never overwritten)
        $isTesting = app()->environment('testing');
        $relativeDir = $isTesting ? 'uploads/siteInfo/testing' : 'uploads/siteInfo';
        $targetDir = public_path($relativeDir);
        if (!file_exists($targetDir)) {
            @mkdir($targetDir, 0755, true);
        }

        // Save logo with cache-busting unique name and cleanup of previous custom asset
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $ext = strtolower($request->file('logo')->getClientOriginalExtension() ?: 'png');
            $filename = 'logo_' . time() . '_' . Str::random(8) . '.' . $ext;
            $dest = $targetDir . DIRECTORY_SEPARATOR . $filename;

            // Delete prior uploaded logo if distinct and not a template default
            if (!empty($siteInfo->logo) && !str_contains($siteInfo->logo, 'frontAssets')) {
                $oldPath = public_path($siteInfo->logo);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            if (@copy($request->file('logo')->getRealPath(), $dest)) {
                $siteInfo->logo = $relativeDir . '/' . $filename;
            }
        }

        // Save favicon with cache-busting unique name and cleanup of previous custom asset
        if ($request->hasFile('favicon') && $request->file('favicon')->isValid()) {
            $ext = strtolower($request->file('favicon')->getClientOriginalExtension() ?: 'png');
            $filename = 'favicon_' . time() . '_' . Str::random(8) . '.' . $ext;
            $dest = $targetDir . DIRECTORY_SEPARATOR . $filename;

            // Delete prior uploaded favicon if distinct and not a template default
            if (!empty($siteInfo->favicon) && !str_contains($siteInfo->favicon, 'frontAssets')) {
                $oldFavPath = public_path($siteInfo->favicon);
                if (file_exists($oldFavPath)) {
                    @unlink($oldFavPath);
                }
            }

            if (@copy($request->file('favicon')->getRealPath(), $dest)) {
                $siteInfo->favicon = $relativeDir . '/' . $filename;
            }
        }
    
        if($siteInfo->save()){
            alert()->success('Changes Saved', 'Site information changes saved successfully')->persistent('Close');
            return redirect()->back();
        }
    
        alert()->error('Oops!', 'Something went wrong')->persistent('Close');
        return redirect()->back();
    }

    public function profile()
    {
        $admin = Auth::user();
        return view('admin.erp.profile', [
            'admin' => $admin
        ]);
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
        ]);

        $admin->update([
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

    /**
     * Delete an Administrator
     */
    public function deleteAdmin(Request $request) {
        $validator = Validator::make($request->all(), [
            'admin_id' => 'required|exists:admins,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        // Prevent self-deletion
        if ((int)$request->admin_id === (int)Auth::user()->id) {
            alert()->error('Action Denied', 'You cannot delete your own administrator account.')->persistent('Close');
            return redirect()->back();
        }

        $admin = Admin::findOrFail($request->admin_id);

        DB::beginTransaction();
        try {
            $admin->delete();
            DB::commit();

            alert()->success('Deleted', 'Administrator account removed successfully')->persistent('Close');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Admin Deletion Failed: " . $e->getMessage());
            alert()->error('Error', 'Failed to remove administrator account.')->persistent('Close');
        }

        return redirect()->back();
    }

    /**
     * Delete a Staff
     */
    public function deleteStaff(Request $request) {
        $validator = Validator::make($request->all(), [
            'staff_id' => 'required|exists:staff,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        // // Prevent self-deletion
        // if ((int)$request->admin_id === (int)Auth::user()->id) {
        //     alert()->error('Action Denied', 'You cannot delete your own administrator account.')->persistent('Close');
        //     return redirect()->back();
        // }

        $staff = Staff::findOrFail($request->staff_id);

        DB::beginTransaction();
        try {
            $staff->delete();
            DB::commit();

            alert()->success('Deleted', 'Staff account removed successfully')->persistent('Close');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Staff Deletion Failed: " . $e->getMessage());
            alert()->error('Error', 'Failed to remove staff account.')->persistent('Close');
        }

        return redirect()->back();
    }


    public function customers() {
        $latestSale = function ($column) {
            return Sale::select($column)
                ->whereColumn('customer_id', 'customers.id')
                ->latest('created_at')
                ->latest('id')
                ->limit(1);
        };

        $customers = Customer::withCount([
            'sales',
            'sales as paid_orders_count' => function ($query) {
                $query->where('payment_status', 'paid');
            },
            'sales as pending_orders_count' => function ($query) {
                $query->where('payment_status', 'pending');
            },
            'sales as attention_orders_count' => function ($query) {
                $query->whereIn('payment_status', ['failed', 'review']);
            },
        ])
            ->addSelect([
                'confirmed_order_total' => Sale::selectRaw('COALESCE(SUM(payable_amount), 0)')
                    ->whereColumn('customer_id', 'customers.id')
                    ->where('payment_status', 'paid'),
                'latest_order_reference' => $latestSale('reference_no'),
                'latest_order_payment_status' => $latestSale('payment_status'),
                'latest_order_status' => $latestSale('order_status'),
                'latest_order_total' => $latestSale('payable_amount'),
                'latest_order_date' => $latestSale('created_at'),
                'latest_delivery_address' => $latestSale('delivery_address'),
                'latest_delivery_phone' => $latestSale('delivery_phone'),
            ])
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.erp.customers', [
            'customers' => $customers,
        ]);
    }

    public function customerOrders(Customer $customer)
    {
        $orders = $customer->sales()
            ->with('items.product.storeProduct')
            ->latest('created_at')
            ->latest('id')
            ->get();

        return view('admin.erp.customerOrders', compact('customer', 'orders'));
    }

    public function deleteCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        $customer = Customer::findOrFail($request->customer_id);
        try {
            $customer->delete();
            alert()->success('Deleted', 'Customer account removed successfully.')->persistent('Close');
        } catch (\Throwable $exception) {
            Log::error('Customer deletion failed: ' . $exception->getMessage());
            alert()->error('Error', 'Customer account could not be removed.')->persistent('Close');
        }

        return redirect()->back();
    }

    /**
     * Manual trigger for Daily Financial Report
     * Can be linked to a button: /admin/send-report
     */
    // public function sendDailyReport()
    // {
    //     $today = Carbon::today();

    //     $stats = [
    //         'revenue' => Sale::whereDate('created_at', $today)->sum('payable_amount'),
    //         'cost'    => Production::whereDate('produced_at', $today)->sum('total_cost'),
    //     ];
    //     $stats['profit'] = $stats['revenue'] - $stats['cost'];

    //     // Send Report
    //     Mail::to(Auth::user()->email)->send(new DailyPerformance($stats));

    //     // Check for Low Stock and include in alert if any exist
    //     $lowStock = Product::where('stock_on_hand', '<=', 10)->where('is_active', true)->get();
    //     if ($lowStock->count() > 0) {
    //         Mail::to(Auth::user()->email)->send(new LowStockAlert($lowStock));
    //     }

    //     alert()->success('Mailed!', 'Report and alerts sent to your inbox.')->persistent('Close');
    //     return redirect()->back();
    // }
}


