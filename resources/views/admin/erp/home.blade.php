@extends('admin.layout.dashboard')

@section('content')
@php
    // Theme logic
    $isProfit = $todayProfit >= 0;
    $themeClass = $isProfit ? 'theme-profit' : 'theme-loss';
    $trendIcon = $isProfit ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt';
@endphp

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Dashboard Analytics</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card mini-stats-wid shadow-lg stat-card {{ $themeClass }}">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <p class="fw-medium text-white mb-1">Today's Revenue</p>
                    <h4 class="mb-0 text-white">
                        ₦<span class="counter" data-target="{{ $todayRevenue }}">0</span>
                    </h4>
                </div>
                <i class="bx bx-money text-white display-5 opacity-75"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card mini-stats-wid shadow-lg stat-card theme-neutral" 
             data-bs-toggle="modal" data-bs-target="#expenseBreakdownModal" style="cursor: pointer;">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <p class="fw-medium text-white mb-1">Today's Expenses</p>
                    <h4 class="mb-0 text-white">
                        ₦<span class="counter" data-target="{{ $todaySpent }}">0</span>
                    </h4>
                </div>
                <i class="bx bx-credit-card text-white display-5 opacity-75"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card mini-stats-wid shadow-lg stat-card {{ $themeClass }}" 
             data-bs-toggle="modal" data-bs-target="#expenseBreakdownModal" style="cursor: pointer;">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <p class="fw-medium text-white mb-1">Daily Net Profit</p>
                    <h4 class="mb-0 text-white">
                        ₦<span class="counter" data-target="{{ $todayProfit }}">0</span>
                        <i class="bx {{ $trendIcon }} text-white"></i>
                    </h4>
                </div>
                <i class="bx bx-line-chart text-white display-5 opacity-75"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card mini-stats-wid shadow-lg stat-card theme-neutral">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <p class="fw-medium text-white mb-1">Total Transactions</p>
                    <h4 class="mb-0 text-white">
                        <span class="counter" data-target="{{ $todaySalesCount }}">0</span>
                    </h4>
                </div>
                <i class="bx bx-receipt text-white display-5 opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-xl-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title mb-4">7-Day Revenue Trend</h4>
                <div id="revenue_chart"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title mb-4">Payment Methods</h4>
                <div id="payment_chart"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title mb-4">Top 5 Best Sellers</h4>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr><th>Product</th><th>Sold</th><th>Revenue</th></tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $item)
                            <tr>
                                <td>{{ $item->product->name ?? 'Deleted Product' }}</td>
                                <td><span class="badge bg-success">{{ $item->total_qty }}</span></td>
                                <td>₦{{ number_format($item->total_revenue, 0) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card border-danger shadow-sm">
            <div class="card-body">
                <h4 class="card-title text-danger mb-4">Low Stock Alerts</h4>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr><th>Product</th><th>Stock on Hand</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td class="text-danger fw-bold">{{ $product->stock_on_hand }}</td>
                                <td><span class="badge bg-danger">Critical</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted">Stock levels are healthy.</td></tr>
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
                <h5 class="modal-title font-size-16">Daily Profit/Loss Breakdown</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p class="text-muted mb-0">Total Revenue</p>
                    <h5 class="text-dark mb-0">₦{{ number_format($todayRevenue, 2) }}</h5>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p class="text-muted mb-0">Production Cost (Used)</p>
                    <h5 class="text-danger mb-0">- ₦{{ number_format($todayProductionCost, 2) }}</h5>
                </div>
                <hr>
                <div class="p-3 rounded {{ $isProfit ? 'bg-soft-success' : 'bg-soft-danger' }} mb-4">
                    <p class="mb-1 small text-uppercase fw-bold">Daily Net Profit</p>
                    <h3 class="{{ $isProfit ? 'text-success' : 'text-danger' }} mb-0">
                        ₦{{ number_format($todayProfit, 2) }}
                    </h3>
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
    .stat-card { border-radius: 1rem; transition: all 0.3s ease; overflow: hidden; position: relative; }
    .stat-card:hover { transform: translateY(-6px) scale(1.01); box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
    .theme-profit { background: linear-gradient(135deg, #1f9d55, #34c38f); }
    .theme-loss { background: linear-gradient(135deg, #d9534f, #f46a6a); }
    .theme-neutral { background: linear-gradient(135deg, #495057, #6c757d); }
    .bg-soft-success { background-color: rgba(52, 195, 143, 0.1); }
    .bg-soft-danger { background-color: rgba(244, 106, 106, 0.1); }
    .text-success { color: #1f9d55 !important; }
    .text-danger { color: #d9534f !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
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

    const isProfit = {{ $todayProfit >= 0 ? 'true' : 'false' }};
    const primaryColor = isProfit ? '#34c38f' : '#f46a6a';

    new ApexCharts(document.querySelector("#revenue_chart"), {
        series: [{ name: 'Revenue', data: @json($chartRevenues) }],
        chart: { type: 'area', height: 350, toolbar: { show: false } },
        colors: [primaryColor],
        stroke: { curve: 'smooth', width: 3 },
        xaxis: { categories: @json($chartDays) },
    }).render();

    new ApexCharts(document.querySelector("#payment_chart"), {
        series: @json($paymentCounts),
        labels: @json($paymentLabels),
        chart: { type: 'donut', height: 330 },
        colors: [primaryColor, '#6c757d', '#adb5bd', '#ced4da'],
        plotOptions: { pie: { donut: { size: '70%', labels: { show: true } } } }
    }).render();
</script>
@endsection
