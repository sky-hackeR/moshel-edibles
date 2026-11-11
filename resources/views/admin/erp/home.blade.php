@extends('admin.layout.dashboard')

@section('content')
@php
    // Theme logic
    $isProfit = $weeklyOperatingBalance >= 0;
    $profitTheme = $isProfit ? 'theme-profit' : 'theme-loss';
    $trendIcon = $isProfit ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt';
    $operatingChangeText = number_format(abs($weeklyOperatingChange), 2) . ' ' . ($weeklyOperatingChange >= 0 ? 'stronger' : 'softer') . ' vs prior 7 days';
    $dailyPositionExplanation = $todayProductionCost > $todayRevenue
        ? 'Production use recorded today exceeds paid revenue by ₦' . number_format($todayProductionCost - $todayRevenue, 2) . '. Payments and production can land on different days, so this daily estimate is timing-sensitive, not a final accounting profit.'
        : ($todayRevenue > $todayProductionCost
            ? 'Paid revenue recorded today is ahead of production use by ₦' . number_format($todayRevenue - $todayProductionCost, 2) . '.'
            : ($todayRevenue > 0
                ? 'Paid revenue and production use recorded today are level.'
                : 'No paid revenue or production use has been recorded today.'));
    $topProductMaxQty = max(1, (int) $topProducts->max('total_qty'));
    $weeklyChangeText = $weeklyRevenueChange === null
        ? ($weeklyRevenue > 0 ? 'New revenue this period' : 'No prior-period revenue')
        : number_format(abs($weeklyRevenueChange), 1) . '% ' . ($weeklyRevenueChange >= 0 ? 'up' : 'down') . ' vs prior 7 days';
    $weeklyChangeClass = $weeklyRevenueChange === null || $weeklyRevenueChange >= 0 ? 'text-success' : 'text-danger';
    $dailyRevenueChange = $yesterdayRevenue > 0
        ? (($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100
        : null;
    $dailyRevenueInsight = $dailyRevenueChange === null
        ? 'No paid revenue yesterday'
        : number_format(abs($dailyRevenueChange), 1) . '% ' . ($dailyRevenueChange >= 0 ? 'up' : 'down') . ' vs yesterday';
@endphp

<div class="dashboard-analytics">
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <div>
                <h4 class="mb-1 font-size-18">Dashboard Analytics</h4>
                <small class="text-muted">{{ now()->format('l, d F Y') }} <span class="mx-1">·</span> Live operating snapshot</small>
            </div>
            <div>
                <a href="{{ route('send.report') }}" class="btn btn-light btn-sm" title="Email today's financial report and low-stock alerts">
                    <i class="bx bx-send me-1"></i> Send Daily Report
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 align-items-stretch kpi-grid">
    <div class="col-6 col-xl-3" data-reveal>
        <div class="card mini-stats-wid shadow-sm stat-card theme-revenue h-100">
            <div class="card-body d-flex align-items-center justify-content-between gap-2">
                <div class="stat-copy">
                    <p class="stat-label">Today's Revenue <button type="button" class="metric-help text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Revenue from orders marked paid today. Pending and failed orders are excluded."><i class="bx bx-info-circle"></i><span class="visually-hidden">Revenue metric details</span></button></p>
                    <h4 class="stat-value">₦<span class="counter" data-target="{{ $todayRevenue }}">0</span></h4>
                    <small class="stat-detail">{{ $dailyRevenueInsight }}</small>
                </div>
                <span class="stat-icon"><i class="bx bx-money"></i></span>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3" data-reveal>
        <div class="card mini-stats-wid shadow-sm stat-card theme-expenses h-100" data-bs-toggle="modal" data-bs-target="#expenseBreakdownModal" role="button" tabindex="0" aria-label="Open today's expense breakdown">
            <div class="card-body d-flex align-items-center justify-content-between gap-2">
                <div class="stat-copy">
                    <p class="stat-label">Today's Expenses <button type="button" class="metric-help text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Production costs recorded today. Stock purchases are shown separately in the breakdown."><i class="bx bx-info-circle"></i><span class="visually-hidden">Expenses metric details</span></button></p>
                    <h4 class="stat-value">₦<span class="counter" data-target="{{ $todaySpent }}">0</span></h4>
                    <small class="stat-detail">Production usage</small>
                </div>
                <span class="stat-icon"><i class="bx bx-credit-card"></i></span>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3" data-reveal>
        <div class="card mini-stats-wid shadow-sm stat-card {{ $profitTheme }} h-100" data-bs-toggle="modal" data-bs-target="#expenseBreakdownModal" role="button" tabindex="0" aria-label="Open today's profit and loss breakdown">
            <div class="card-body d-flex align-items-center justify-content-between gap-2">
                <div class="stat-copy">
                    <p class="stat-label">7-Day Operating Position <button type="button" class="metric-help text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Paid revenue from the last seven days minus production costs recorded over the same period. The longer window reduces day-to-day timing swings."><i class="bx bx-info-circle"></i><span class="visually-hidden">Seven-day operating position details</span></button></p>
                    <h4 class="stat-value">₦<span class="counter" data-target="{{ $weeklyOperatingBalance }}">0</span> <i class="bx {{ $trendIcon }}"></i></h4>
                    <small class="stat-detail">{{ $operatingChangeText }}</small>
                </div>
                <span class="stat-icon"><i class="bx bx-line-chart"></i></span>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3" data-reveal>
        <div class="card mini-stats-wid shadow-sm stat-card theme-orders h-100">
            <div class="card-body d-flex align-items-center justify-content-between gap-2">
                <div class="stat-copy">
                    <p class="stat-label">Total Transactions <button type="button" class="metric-help text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="All orders created today, including pending and failed payments."><i class="bx bx-info-circle"></i><span class="visually-hidden">Transaction metric details</span></button></p>
                    <h4 class="stat-value"><span class="counter" data-target="{{ $todaySalesCount }}">0</span></h4>
                    <small class="stat-detail">Orders created today</small>
                </div>
                <span class="stat-icon"><i class="bx bx-receipt"></i></span>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3" data-reveal>
    <div class="col-12">
        <div class="card shadow-sm insight-strip">
            <div class="card-body py-3">
                <div class="row g-3 align-items-center">
                    <div class="col-6 col-lg">
                        <small class="text-muted d-block">7-day paid revenue <button type="button" class="metric-help text-muted" data-bs-toggle="tooltip" title="Paid order revenue over the last seven calendar days."><i class="bx bx-info-circle"></i><span class="visually-hidden">Seven-day revenue details</span></button></small>
                        <strong>₦{{ number_format($weeklyRevenue, 2) }}</strong>
                    </div>
                    <div class="col-6 col-lg">
                        <small class="text-muted d-block">vs previous 7 days</small>
                        <strong class="{{ $weeklyChangeClass }}">{{ $weeklyChangeText }}</strong>
                    </div>
                    <div class="col-6 col-lg">
                        <small class="text-muted d-block">Average paid order <button type="button" class="metric-help text-muted" data-bs-toggle="tooltip" title="Seven-day paid revenue divided by the number of paid orders in that period."><i class="bx bx-info-circle"></i><span class="visually-hidden">Average order value details</span></button></small>
                        <strong>₦{{ number_format($averagePaidOrderValue, 2) }}</strong>
                    </div>
                    <div class="col-6 col-lg">
                        <small class="text-muted d-block">Payment completion <button type="button" class="metric-help text-muted" data-bs-toggle="tooltip" title="Paid orders divided by all orders created over the last seven days."><i class="bx bx-info-circle"></i><span class="visually-hidden">Payment completion details</span></button></small>
                        <strong>{{ number_format($paymentSuccessRate, 1) }}%</strong>
                        <small class="text-muted">{{ $weeklyPaidOrdersCount }} of {{ $weeklyOrdersCount }} orders</small>
                    </div>
                    <div class="col-6 col-lg">
                        <small class="text-muted d-block">Stock attention <button type="button" class="metric-help text-muted" data-bs-toggle="tooltip" title="Active products with 10 or fewer units; critical stock is 3 units or fewer."><i class="bx bx-info-circle"></i><span class="visually-hidden">Stock attention details</span></button></small>
                        <strong>{{ $lowStockCount }} low</strong>
                        <small class="text-danger">{{ $criticalStockCount }} critical</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4 g-3 align-items-stretch">
    <div class="col-xl-8 d-flex" data-reveal>
        <div class="card shadow-sm analytics-panel chart-panel flex-fill">
            <div class="card-body">
                <h4 class="card-title mb-1">Paid Revenue Trend <button type="button" class="metric-help text-muted" data-bs-toggle="tooltip" title="Daily paid revenue this week compared with the matching days in the previous week."><i class="bx bx-info-circle"></i><span class="visually-hidden">Revenue trend details</span></button></h4>
                <p class="text-muted small mb-3">This 7 days compared with the previous 7 days</p>
                <div id="revenue_chart"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 d-flex" data-reveal>
        <div class="card shadow-sm analytics-panel chart-panel flex-fill">
            <div class="card-body">
            <h4 class="card-title mb-4">Payment Methods <button type="button" class="metric-help text-muted" data-bs-toggle="tooltip" title="Distribution of all recorded orders by selected payment method."><i class="bx bx-info-circle"></i><span class="visually-hidden">Payment methods details</span></button></h4>
                <div id="payment_chart"></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1 align-items-stretch">
    <div class="col-xl-6 d-flex" data-reveal>
        <div class="card shadow-sm analytics-panel bottom-panel best-sellers-panel flex-fill">
            <div class="card-body">
                <div class="section-heading d-flex align-items-start justify-content-between gap-3 mb-3">
                    <div>
                        <span class="section-eyebrow">Sales leaders</span>
                        <h4 class="card-title mb-1">Top 5 Best Sellers</h4>
                        <small class="text-muted">Ranked by units sold across paid orders</small>
                    </div>
                    <span class="section-icon sales-icon"><i class="bx bx-trophy"></i></span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle insight-table">
                        <thead>
                            <tr><th class="rank-column">#</th><th>Product</th><th>Units sold</th><th class="text-end">Revenue</th></tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $item)
                            <tr>
                                <td class="rank-column"><span class="product-rank rank-{{ $loop->iteration }}">{{ sprintf('%02d', $loop->iteration) }}</span></td>
                                <td class="product-name">{{ $item->product->name ?? 'Deleted Product' }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-semibold">{{ number_format($item->total_qty, 0) }}</span>
                                        <span class="sales-meter" aria-hidden="true"><span style="width: {{ min(100, ((float) $item->total_qty / $topProductMaxQty) * 100) }}%"></span></span>
                                    </div>
                                </td>
                                <td class="text-end fw-semibold">₦{{ number_format($item->total_revenue, 0) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4"><div class="empty-insight"><i class="bx bx-bar-chart-alt-2"></i><span>No paid product sales yet</span><small>Product rankings will appear after the first completed payment.</small></div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 d-flex" data-reveal>
        <div class="card shadow-sm analytics-panel bottom-panel inventory-panel flex-fill">
            <div class="card-body">
                <div class="section-heading d-flex align-items-start justify-content-between gap-3 mb-3">
                    <div>
                        <span class="section-eyebrow">Inventory watch</span>
                        <h4 class="card-title mb-1">Stock needing attention</h4>
                        <small class="text-muted">{{ $lowStockCount }} products at or below reorder level</small>
                    </div>
                    <span class="stock-summary {{ $criticalStockCount > 0 ? 'stock-summary-alert' : '' }}" title="{{ $criticalStockCount }} products have 3 or fewer units remaining">
                        <strong>{{ $criticalStockCount }}</strong><small>critical</small>
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle insight-table stock-table">
                        <thead>
                            <tr><th>Product</th><th>On hand</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts as $product)
                            <tr>
                                <td class="product-name">{{ $product->name }}</td>
                                <td>
                                    <strong class="{{ $product->stock_on_hand <= 3 ? 'text-danger' : 'text-warning' }}">{{ number_format($product->stock_on_hand, 0) }}</strong>
                                    <span class="stock-meter d-block mt-1"><span class="{{ $product->stock_on_hand <= 3 ? 'meter-critical' : 'meter-low' }}" style="width: {{ min(100, max(4, ((float) $product->stock_on_hand / 10) * 100)) }}%"></span></span>
                                </td>
                                <td><span class="stock-badge {{ $product->stock_on_hand <= 3 ? 'critical' : 'low' }}">{{ $product->stock_on_hand <= 3 ? 'Critical' : 'Reorder' }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="3"><div class="empty-insight stock-healthy"><i class="bx bx-check-shield"></i><span>Stock levels are healthy</span><small>No active products are at or below the 10-unit reorder threshold.</small></div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="expenseBreakdownModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title font-size-16">Operating Position Breakdown</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p class="text-muted mb-0">Today's paid revenue</p>
                    <h5 class="text-dark mb-0">₦{{ number_format($todayRevenue, 2) }}</h5>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p class="text-muted mb-0">Today's production use</p>
                    <h5 class="text-danger mb-0">- ₦{{ number_format($todayProductionCost, 2) }}</h5>
                </div>
                <hr>
                <div class="p-3 rounded {{ $todayProfit >= 0 ? 'bg-soft-success' : 'bg-soft-danger' }} mb-3">
                    <p class="mb-1 small text-uppercase fw-bold">Today's operating estimate</p>
                    <h3 class="{{ $todayProfit >= 0 ? 'text-success' : 'text-danger' }} mb-0">
                        ₦{{ number_format($todayProfit, 2) }}
                    </h3>
                </div>

                <p class="text-muted small mb-4">{{ $dailyPositionExplanation }}</p>

                <div class="border rounded p-3 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Last 7 days</strong>
                        <span class="fw-bold {{ $weeklyOperatingBalance >= 0 ? 'text-success' : 'text-danger' }}">₦{{ number_format($weeklyOperatingBalance, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between small text-muted"><span>Paid revenue</span><span>₦{{ number_format($weeklyRevenue, 2) }}</span></div>
                    <div class="d-flex justify-content-between small text-muted"><span>Production use</span><span>− ₦{{ number_format($weeklyProductionCost, 2) }}</span></div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center small">
                        <span class="text-muted">Previous 7-day position</span>
                        <span class="fw-semibold">₦{{ number_format($previousWeeklyOperatingBalance, 2) }}</span>
                    </div>
                    <small class="d-block mt-2 text-muted">This rolling view helps account for production and payment activity landing on different days.</small>
                </div>
                
                <div class="border-top pt-3 mt-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Stock Purchases</h6>
                            <small class="text-muted">Total inventory bought today</small>
                        </div>
                        <span class="text-primary fw-bold">₦{{ number_format($todayPurchases, 2) }}</span>
                    </div>
                    <p class="mt-2 text-muted small italic" style="font-size: 0.75rem;">
                        * Purchases are considered inventory assets and are only deducted from profit as they are <b>produced/used</b>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .dashboard-analytics { --analytics-green: #19775f; --analytics-red: #bd4b42; --analytics-ink: #293b49; --analytics-muted: #71808a; --analytics-line: #e7ecef; }
    .stat-card { min-height: 174px; border: 0; border-radius: 8px; transition: transform 0.25s ease, box-shadow 0.25s ease; overflow: hidden; position: relative; }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 16px 34px rgba(27, 45, 57, 0.18) !important; }
    .stat-card .card-body { min-height: 174px; padding: 1.25rem; }
    .stat-copy { min-width: 0; }
    .stat-label { color: rgba(255, 255, 255, 0.9); font-size: 0.875rem; font-weight: 600; margin: 0 0 0.55rem; }
    .stat-value { color: #fff; font-size: 1.55rem; font-weight: 700; line-height: 1.15; margin: 0; white-space: nowrap; }
    .stat-detail { display: block; min-height: 1.1rem; margin-top: 0.55rem; color: rgba(255, 255, 255, 0.72); font-size: 0.75rem; }
    .stat-icon { display: grid; width: 46px; height: 46px; flex: 0 0 46px; place-items: center; border: 1px solid rgba(255, 255, 255, 0.25); border-radius: 13px; background: rgba(255, 255, 255, 0.14); color: #fff; font-size: 1.45rem; }
    .theme-revenue { background: linear-gradient(135deg, #176b59, #238c71); }
    .theme-expenses { background: linear-gradient(135deg, #9b552d, #c8753a); }
    .theme-orders { background: linear-gradient(135deg, #355d79, #497f9b); }
    .theme-profit { background: linear-gradient(135deg, #19775f, #2b9b78); }
    .theme-loss { background: linear-gradient(135deg, #a93f3a, #ca5b50); }
    .bg-soft-success { background-color: rgba(52, 195, 143, 0.1); }
    .bg-soft-danger { background-color: rgba(244, 106, 106, 0.1); }
    .text-success { color: #1f9d55 !important; }
    .text-danger { color: #d9534f !important; }
    .insight-strip { border: 1px solid var(--analytics-line); border-left: 4px solid var(--analytics-green); border-radius: 8px; }
    .analytics-panel { border: 1px solid var(--analytics-line); border-radius: 8px; }
    .chart-panel .card-body { min-height: 442px; }
    .bottom-panel { min-height: 370px; border: 1px solid var(--analytics-line); border-radius: 8px; }
    .best-sellers-panel { border-top: 3px solid #4776a8; }
    .inventory-panel { border-top: 3px solid #d97706; }
    .section-eyebrow { display: block; margin-bottom: 0.25rem; color: var(--analytics-muted); font-size: 0.68rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; }
    .section-icon { display: grid; width: 42px; height: 42px; flex: 0 0 42px; place-items: center; border-radius: 12px; font-size: 1.25rem; }
    .sales-icon { background: #eaf1f7; color: #4776a8; }
    .stock-summary { display: flex; min-width: 58px; flex-direction: column; align-items: center; justify-content: center; padding: 0.35rem 0.55rem; border-radius: 9px; background: #eef3f5; color: #48606b; line-height: 1.1; }
    .stock-summary strong { font-size: 1.05rem; }
    .stock-summary small { font-size: 0.62rem; text-transform: uppercase; }
    .stock-summary-alert { background: #fff0e9; color: #b04b32; }
    .insight-table { border-collapse: separate; border-spacing: 0; }
    .insight-table thead th { padding: 0.7rem 0.55rem; border-bottom: 1px solid var(--analytics-line); color: #788791; font-size: 0.67rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; white-space: nowrap; }
    .insight-table tbody td { padding: 0.75rem 0.55rem; border-bottom: 1px solid #f0f3f5; color: var(--analytics-ink); font-size: 0.84rem; }
    .insight-table tbody tr:last-child td { border-bottom: 0; }
    .rank-column { width: 42px; }
    .product-rank { display: inline-grid; width: 28px; height: 28px; place-items: center; border-radius: 9px; background: #eef2f4; color: #65747e; font-size: 0.68rem; font-weight: 700; }
    .rank-1 { background: #fff4dc; color: #a96b00; }
    .rank-2 { background: #edf2f5; color: #526572; }
    .rank-3 { background: #f9eee8; color: #9b6045; }
    .product-name { max-width: 210px; font-weight: 600; }
    .sales-meter, .stock-meter { display: block; width: 66px; height: 5px; overflow: hidden; border-radius: 99px; background: #edf1f3; }
    .sales-meter span, .stock-meter span { display: block; height: 100%; border-radius: inherit; background: linear-gradient(90deg, #4776a8, #4f9a9a); }
    .stock-meter { width: 76px; }
    .stock-meter .meter-low { background: #d97706; }
    .stock-meter .meter-critical { background: #c74747; }
    .stock-badge { display: inline-block; padding: 0.32rem 0.48rem; border-radius: 6px; font-size: 0.66rem; font-weight: 700; }
    .stock-badge.low { background: #fff3df; color: #9d6200; }
    .stock-badge.critical { background: #ffebe8; color: #b13d35; }
    .empty-insight { display: flex; min-height: 210px; flex-direction: column; align-items: center; justify-content: center; gap: 0.35rem; color: #526573; text-align: center; }
    .empty-insight i { margin-bottom: 0.4rem; color: #6e9a8d; font-size: 2rem; }
    .empty-insight span { font-weight: 700; }
    .empty-insight small { max-width: 270px; color: var(--analytics-muted); }
    .metric-help { color: inherit; }
    .metric-help { display: inline-flex; align-items: center; justify-content: center; padding: 0; border: 0; background: transparent; font-size: 1rem; line-height: 1; vertical-align: middle; cursor: help; }
    .metric-help:focus-visible { outline: 2px solid currentColor; outline-offset: 2px; }
    [data-reveal] { animation: dashboard-reveal 520ms ease-out both; }
    [data-reveal]:nth-child(1) { animation-delay: 40ms; }
    [data-reveal]:nth-child(2) { animation-delay: 100ms; }
    [data-reveal]:nth-child(3) { animation-delay: 160ms; }
    [data-reveal]:nth-child(4) { animation-delay: 220ms; }
    @keyframes dashboard-reveal { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
    @media (prefers-reduced-motion: reduce) { [data-reveal] { animation: none; } }
    @media (max-width: 575.98px) {
        .stat-card, .stat-card .card-body { min-height: 158px; }
        .stat-card .card-body { padding: 1rem 0.85rem; }
        .stat-value { font-size: 1.15rem; }
        .stat-icon { width: 38px; height: 38px; flex-basis: 38px; font-size: 1.2rem; }
        .chart-panel .card-body { min-height: 0; }
        .bottom-panel { min-height: 0; }
        .sales-meter { width: 40px; }
        .stock-meter { width: 54px; }
        .insight-table tbody td { padding: 0.65rem 0.35rem; font-size: 0.76rem; }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.bootstrap && bootstrap.Tooltip) {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(element => {
                new bootstrap.Tooltip(element);
                element.addEventListener('click', event => event.stopPropagation());
            });
        }

        document.querySelectorAll('.stat-card[role="button"]').forEach(card => {
            card.addEventListener('keydown', event => {
                if (event.target !== card) return;
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    bootstrap.Modal.getOrCreateInstance(document.querySelector('#expenseBreakdownModal')).show();
                }
            });
        });
    });

    document.querySelectorAll('.counter').forEach(counter => {
        const updateCount = () => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText.replace(/,/g, '');
            const increment = Math.max(target / 100, 1);
            if (count < target) {
                counter.innerText = Math.ceil(count + increment).toLocaleString();
                setTimeout(updateCount, 20);
            } else {
                counter.innerText = target.toLocaleString();
            }
        };
        updateCount();
    });

    new ApexCharts(document.querySelector("#revenue_chart"), {
        series: [
            { name: 'This 7 days', data: @json($chartRevenues) },
            { name: 'Previous 7 days', data: @json($previousWeekRevenues) }
        ],
        chart: { type: 'area', height: 350, toolbar: { show: false }, animations: { enabled: !window.matchMedia('(prefers-reduced-motion: reduce)').matches, easing: 'easeinout', speed: 700 } },
        colors: ['#19775f', '#4776a8'],
        stroke: { curve: 'smooth', width: [3, 2], dashArray: [0, 5] },
        fill: { opacity: [0.22, 0.04] },
        dataLabels: { enabled: false },
        yaxis: { labels: { formatter: value => '₦' + Number(value).toLocaleString() } },
        tooltip: { y: { formatter: value => '₦' + Number(value).toLocaleString() } },
        legend: { position: 'top', horizontalAlign: 'right' },
        xaxis: { categories: @json($chartDays) },
    }).render();

    new ApexCharts(document.querySelector("#payment_chart"), {
        series: @json($paymentCounts),
        labels: @json($paymentLabels),
        chart: { type: 'donut', height: 330 },
        colors: ['#18864b', '#d97706', '#386cb0', '#c74747', '#65727e'],
        plotOptions: { pie: { donut: { size: '70%', labels: { show: true } } } }
    }).render();
</script>
</div>
@endsection
