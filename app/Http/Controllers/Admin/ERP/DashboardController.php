<?php

namespace App\Http\Controllers\Admin\ERP;

use App\Http\Controllers\Controller;
use App\Services\Admin\Dashboard\DashboardService;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Display the ERP Admin Dashboard.
     *
     * @param DashboardService $dashboardService
     * @return View
     */
    public function index(DashboardService $dashboardService): View
    {
        $dashboardData = $dashboardService->getDashboardData();

        return view('admin.erp.home', $dashboardData);
    }
}
