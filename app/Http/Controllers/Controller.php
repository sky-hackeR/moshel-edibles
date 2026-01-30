<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\SiteInfo as Setting;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(){
        $setting = Setting::first();
        $isIncomplete = !$setting || empty($setting->favicon) || empty($setting->site_name) || empty($setting->logo);

        $currentAction = request()->segment(2);

        $allowedActions = ['siteSettings', 'updateSiteInfo'];

        if ($isIncomplete && !in_array($currentAction, $allowedActions)) {
            redirect('/admin/siteSettings')->send();
            exit;
        }
    }
}