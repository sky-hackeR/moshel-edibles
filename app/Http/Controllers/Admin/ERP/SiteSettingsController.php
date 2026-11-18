<?php

namespace App\Http\Controllers\Admin\ERP;

use App\Http\Controllers\Controller;
use App\Models\SiteInfo as Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SiteSettingsController extends Controller
{
    public function siteSettings()
    {
        $setting = Setting::first();
        return view('admin.erp.siteSettings', [
            'setting' => $setting,
        ]);
    }

    public function updateSiteInfo(Request $request)
    {
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
    
        if ($siteInfo->save()) {
            alert()->success('Changes Saved', 'Site information changes saved successfully')->persistent('Close');
            return redirect()->back();
        }
    
        alert()->error('Oops!', 'Something went wrong')->persistent('Close');
        return redirect()->back();
    }
}
