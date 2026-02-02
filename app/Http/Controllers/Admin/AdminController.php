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

// Import your new Mailables
use App\Mail\Admin\AccountCreated as AdminAccountMail;
use App\Mail\Admin\AdminCreated as SecurityAlertMail;
use App\Mail\Staff\Created as StaffWelcomeMail;
use App\Mail\Finance\DailyPerformance;
use App\Mail\Finance\LowStockAlert;

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
            try {
                // Mail 1: To the new Admin with their credentials
                Mail::to($admin->email)->send(new AdminAccountMail($admin, $request->password));
                
                // Mail 2: Security Alert to the Admin who performed the action
                Mail::to(Auth::user()->email)->send(new SecurityAlertMail($admin, Auth::user()));
                
                alert()->success('Success', 'Admin created and notification emails sent')->persistent('Close');
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
            try {
                // Mail the new staff member
                Mail::to($staff->email)->send(new StaffWelcomeMail($staff, $request->password));
                alert()->success('Success', 'Staff created and welcome email sent')->persistent('Close');
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

    public function profile()
    {
        $admin = Auth::user();
        return view('admin.profile', [
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
}


