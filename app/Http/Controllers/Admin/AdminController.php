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

use SweetAlert;
use Alert;
use Log;
use Carbon\Carbon;

class AdminController extends Controller
{



    public function index(){    
        return view('admin.home');
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