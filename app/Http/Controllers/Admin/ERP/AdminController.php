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

use SweetAlert;
use Alert;
use Log;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index(){
        $today = Carbon::today();
        
        // 1. Core Stats
        $todayRevenue = Sale::whereDate('created_at', $today)->sum('payable_amount');
        $todayPurchases = StockInItem::whereDate('created_at', $today)->sum('total_price');
        $todayProductionCost = Production::whereDate('produced_at', $today)->sum('total_cost');
        
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

        return view('admin.erp.home', [
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
            ->paginate(25);

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


