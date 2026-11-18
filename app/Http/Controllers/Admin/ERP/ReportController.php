<?php

namespace App\Http\Controllers\Admin\ERP;

use App\Http\Controllers\Controller;
use App\Mail\Finance\DailyPerformance;
use App\Mail\Stock\LowStockAlert;
use App\Models\Product;
use App\Models\Production;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    /**
     * Manual trigger for Daily Financial Report
     * Can be linked to a button: /admin/send-report
     */
    public function sendDailyReport()
    {
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
        if ($lowStock->count() > 0) {
            Mail::to(Auth::user()->email)->send(new LowStockAlert($lowStock));
        }

        alert()->success('Mailed!', 'Report and alerts sent to your inbox.')->persistent('Close');
        return redirect()->back();
    }
}
