<?php

namespace App\Services\Admin\Dashboard;

use App\Models\Admin;
use App\Models\AuditLog;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Production;
use App\Models\Recipe;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Staff;
use App\Models\StockInItem;
use App\Models\StoreProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Compile and return all dashboard statistics and view data.
     *
     * @return array<string, mixed>
     */
    public function getDashboardData(): array
    {
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

        return [
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
        ];
    }
}
