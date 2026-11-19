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
    {{-- 1. Today's Revenue --}}
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

    {{-- 2. 7-Day Operating Position (Net Profit / Operating Balance) --}}
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

    {{-- 3. Today's Expenses --}}
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

    {{-- 4. Total Transactions --}}
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

{{-- 1. OMNICHANNEL, FULFILLMENT & INVENTORY VALUATION GRID --}}
@include('admin.erp.partials.dashboard.dashboard-domain-metrics')

{{-- 2. PAID REVENUE TREND & PAYMENT METHODS CHARTS --}}
@include('admin.erp.partials.dashboard.dashboard-charts')

{{-- 3. BEST SELLERS & STOCK ATTENTION INSIGHTS ROW --}}
@include('admin.erp.partials.dashboard.dashboard-product-insights')

{{-- 4. SUPPLY CHAIN, STOREFRONT CATALOG & CUSTOMER HEALTH ROW --}}
@include('admin.erp.partials.dashboard.dashboard-supply-and-customers')

{{-- 5. OPERATING POSITION / EXPENSE BREAKDOWN MODAL --}}
@include('admin.erp.partials.modals.dashboard.dashboard-expense-modal')

{{-- 6. DASHBOARD STYLES & APEXCHARTS JAVASCRIPT --}}
@include('admin.erp.partials.scripts.dashboard-scripts')
</div>
@endsection
