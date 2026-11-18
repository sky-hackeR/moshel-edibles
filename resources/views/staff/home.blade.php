@extends('staff.layout.dashboard')

@section('content')

@php
    $staff = Auth::guard('staff')->user();
    $fullName = $staff->name ?? 'Staff Member';
    $firstName = explode(' ', trim($fullName))[0] ?? 'Staff';
    $initials = '';
    $nameParts = preg_split('/\s+/u', trim($fullName), -1, PREG_SPLIT_NO_EMPTY);
    if (!empty($nameParts)) {
        $initials = mb_substr($nameParts[0], 0, 1, 'UTF-8');
        if (count($nameParts) > 1) {
            $initials .= mb_substr(end($nameParts), 0, 1, 'UTF-8');
        }
    }
    $salesContribution = $overallSalesCount > 0 ? round(($mySalesCount / $overallSalesCount) * 100) : 0;
@endphp

<div class="container-fluid py-3">

    {{-- =====================================================
         1. SHIFT WELCOME & FAST ACTION BANNER
         ===================================================== --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden staff-hero-card"
         style="border: 1px solid rgba(226, 232, 240, 0.9) !important; border-top: 3px solid #059669 !important;">
        <div class="card-body p-4 bg-white">
            <div class="row align-items-center g-3">
                <div class="col-lg-7">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        {{-- STAFF AVATAR --}}
                        <div class="staff-hero-avatar flex-shrink-0">
                            {{ $initials ?: 'ST' }}
                        </div>

                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                <h3 class="mb-0 fw-bold text-dark font-size-20" style="letter-spacing: -0.02em;">
                                    Welcome back, {{ $firstName }}!
                                </h3>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 font-size-11 fw-semibold d-inline-flex align-items-center gap-1.5 shadow-xs">
                                    <span class="staff-pulse-dot bg-success"></span> Active Shift
                                </span>
                                <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1 font-size-11">
                                    Cashier &amp; Bakery Staff
                                </span>
                            </div>

                            <div class="d-flex align-items-center gap-2 text-muted font-size-12 flex-wrap">
                                <span><i class="bx bx-calendar me-1 text-primary"></i> {{ now()->format('l, d F Y') }}</span>
                                <span class="opacity-50">•</span>
                                <span><i class="bx bx-user me-1 text-muted"></i> {{ $fullName }}</span>
                                <span class="opacity-50">•</span>
                                <span class="text-success fw-medium">
                                    <i class="bx bx-check-shield me-0.5"></i> POS &amp; Kitchen Operational
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PRIMARY LAUNCHERS --}}
                <div class="col-lg-5 text-lg-end">
                    <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                        <a href="{{ url('staff/production') }}"
                           class="btn btn-outline-success px-3.5 py-2 rounded-pill shadow-xs fw-semibold d-inline-flex align-items-center gap-2 font-size-13">
                            <i class="bx bx-plus-circle font-size-16"></i>
                            <span>Record Batch</span>
                        </a>

                        <a href="{{ url('staff/pos') }}"
                           class="btn btn-primary px-4 py-2 rounded-pill shadow-sm fw-semibold d-inline-flex align-items-center gap-2 font-size-13"
                           style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); border: none;">
                            <i class="bx bx-shopping-bag font-size-16"></i>
                            <span>Open POS Terminal</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- =====================================================
         2. OPERATIONAL SHIFT KPI CARDS (4 TILES)
         ===================================================== --}}
    <div class="row g-3 mb-4 align-items-stretch">
        {{-- CARD 1: MY SALES TODAY --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm rounded-3 staff-kpi-card" style="border: 1px solid rgba(226, 232, 240, 0.9) !important;">
                <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-uppercase text-muted fw-bold font-size-11" style="letter-spacing: 0.5px;">My Sales Today</span>
                            <div class="staff-kpi-icon bg-emerald-subtle text-emerald">
                                <i class="bx bx-cart font-size-18"></i>
                            </div>
                        </div>
                        <h4 class="mb-1 fw-bold text-dark font-size-20">
                            ₦{{ number_format($mySalesTotal, 2) }}
                        </h4>
                        <small class="text-muted font-size-12 d-block">
                            {{ $mySalesCount }} {{ Str::plural('order', $mySalesCount) }} processed by you today
                        </small>
                    </div>
                    <div class="pt-2.5 mt-2.5 border-top border-light d-flex align-items-center justify-content-between font-size-11 text-muted">
                        <span>Store contribution:</span>
                        <strong class="text-success">{{ $salesContribution }}% of today's sales</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 2: MY PRODUCTION TODAY --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm rounded-3 staff-kpi-card" style="border: 1px solid rgba(226, 232, 240, 0.9) !important;">
                <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-uppercase text-muted fw-bold font-size-11" style="letter-spacing: 0.5px;">My Production Today</span>
                            <div class="staff-kpi-icon bg-indigo-subtle text-indigo">
                                <i class="bx bx-dish font-size-18"></i>
                            </div>
                        </div>
                        <h4 class="mb-1 fw-bold text-dark font-size-20">
                            {{ number_format($myUnitsProducedToday, 0) }}
                            <small class="text-muted font-size-13 fw-normal">Units</small>
                        </h4>
                        <small class="text-muted font-size-12 d-block">
                            {{ $myProductionCount }} {{ Str::plural('batch', $myProductionCount) }} logged by you today
                        </small>
                    </div>
                    <div class="pt-2.5 mt-2.5 border-top border-light d-flex align-items-center justify-content-between font-size-11 text-muted">
                        <span>Storewide bakery total:</span>
                        <strong class="text-dark">{{ $overallProductionCount }} batches</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 3: RAW STOCK ALERTS --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm rounded-3 staff-kpi-card" style="border: 1px solid rgba(226, 232, 240, 0.9) !important;">
                <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-uppercase text-muted fw-bold font-size-11" style="letter-spacing: 0.5px;">Raw Stock Watch</span>
                            <div class="staff-kpi-icon {{ $lowStockCount > 0 ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }}">
                                <i class="bx {{ $lowStockCount > 0 ? 'bx-error-circle' : 'bx-check-double' }} font-size-18"></i>
                            </div>
                        </div>
                        <h4 class="mb-1 fw-bold {{ $lowStockCount > 0 ? 'text-danger' : 'text-success' }} font-size-20">
                            {{ $lowStockCount }}
                            <small class="text-muted font-size-13 fw-normal">Low {{ Str::plural('Item', $lowStockCount) }}</small>
                        </h4>
                        <small class="text-muted font-size-12 d-block">
                            {{ $lowStockCount > 0 ? 'At or below minimum baking buffer' : 'All baking supplies healthy' }}
                        </small>
                    </div>
                    <div class="pt-2.5 mt-2.5 border-top border-light d-flex align-items-center justify-content-between font-size-11 text-muted">
                        <span>Before baking:</span>
                        <a href="{{ url('staff/inventory') }}" class="fw-semibold {{ $lowStockCount > 0 ? 'text-danger' : 'text-primary' }}">
                            Check Levels <i class="bx bx-right-arrow-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 4: STOREWIDE DAILY REGISTER --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm rounded-3 staff-kpi-card" style="border: 1px solid rgba(226, 232, 240, 0.9) !important;">
                <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-uppercase text-muted fw-bold font-size-11" style="letter-spacing: 0.5px;">Storewide Sales</span>
                            <div class="staff-kpi-icon bg-sky-subtle text-sky">
                                <i class="bx bx-store-alt font-size-18"></i>
                            </div>
                        </div>
                        <h4 class="mb-1 fw-bold text-dark font-size-20">
                            ₦{{ number_format($overallSalesTotal, 2) }}
                        </h4>
                        <small class="text-muted font-size-12 d-block">
                            {{ $overallSalesCount }} total {{ Str::plural('order', $overallSalesCount) }} across all registers today
                        </small>
                    </div>
                    <div class="pt-2.5 mt-2.5 border-top border-light d-flex align-items-center justify-content-between font-size-11 text-muted">
                        <span>Active terminal:</span>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-size-10 px-2 py-0.5">
                            Live POS Online
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- =====================================================
         3. FAST OPERATIONS DOCK (QUICK ACTION TILES)
         ===================================================== --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <a href="{{ url('staff/pos') }}" class="card border-0 shadow-sm rounded-3 text-decoration-none staff-action-tile h-100">
                <div class="card-body p-3 text-center">
                    <div class="avatar-sm rounded-3 bg-primary-subtle text-primary mx-auto mb-2 d-flex align-items-center justify-content-center">
                        <i class="bx bx-shopping-bag font-size-22"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0.5 font-size-14">POS Terminal</h6>
                    <small class="text-muted font-size-11 d-block">Process walk-in counter sales</small>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3">
            <a href="{{ url('staff/production') }}" class="card border-0 shadow-sm rounded-3 text-decoration-none staff-action-tile h-100">
                <div class="card-body p-3 text-center">
                    <div class="avatar-sm rounded-3 bg-success-subtle text-success mx-auto mb-2 d-flex align-items-center justify-content-center">
                        <i class="bx bx-plus font-size-22"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0.5 font-size-14">Record Production</h6>
                    <small class="text-muted font-size-11 d-block">Log bake batches &amp; usage</small>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3">
            <a href="{{ url('staff/inventory') }}" class="card border-0 shadow-sm rounded-3 text-decoration-none staff-action-tile h-100">
                <div class="card-body p-3 text-center">
                    <div class="avatar-sm rounded-3 bg-warning-subtle text-warning mx-auto mb-2 d-flex align-items-center justify-content-center">
                        <i class="bx bx-list-check font-size-22"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0.5 font-size-14">Raw Inventory</h6>
                    <small class="text-muted font-size-11 d-block">Check flour, sugar &amp; stock</small>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3">
            <a href="{{ url('staff/salesHistory') }}" class="card border-0 shadow-sm rounded-3 text-decoration-none staff-action-tile h-100">
                <div class="card-body p-3 text-center">
                    <div class="avatar-sm rounded-3 bg-info-subtle text-info mx-auto mb-2 d-flex align-items-center justify-content-center">
                        <i class="bx bx-receipt font-size-22"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0.5 font-size-14">Sales Receipts</h6>
                    <small class="text-muted font-size-11 d-block">Reprint &amp; verify past orders</small>
                </div>
            </a>
        </div>
    </div>


    {{-- =====================================================
         4. MASTER-DETAIL OPERATIONAL SECTION
         ===================================================== --}}
    <div class="row g-4">
        {{-- LEFT COLUMN: MY LIVE SHIFT ACTIVITY (col-xl-8) --}}
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden"
                 style="border: 1px solid rgba(226, 232, 240, 0.9) !important;">
                
                {{-- CARD HEADER WITH TABS --}}
                <div class="card-header bg-white border-bottom border-light py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-xs rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="bx bx-history font-size-18"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold text-dark font-size-15">My Shift Operations Log</h5>
                            <p class="text-muted mb-0 font-size-12">Live activity recorded under your staff account today.</p>
                        </div>
                    </div>

                    {{-- NAV PILL TABS --}}
                    <ul class="nav nav-pills staff-activity-pills" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active font-size-12 py-1.5 px-3 rounded-pill"
                               data-bs-toggle="tab"
                               href="#mySalesTab"
                               role="tab">
                                <i class="bx bx-shopping-bag me-1"></i> My Sales ({{ $mySalesCount }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-size-12 py-1.5 px-3 rounded-pill"
                               data-bs-toggle="tab"
                               href="#myProductionTab"
                               role="tab">
                                <i class="bx bx-dish me-1"></i> My Batches ({{ $myProductionCount }})
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- TAB CONTENT --}}
                <div class="card-body p-0">
                    <div class="tab-content">
                        {{-- TAB 1: MY SALES TODAY --}}
                        <div class="tab-pane fade show active" id="mySalesTab" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 staff-table">
                                    <thead class="table-light font-size-11 text-uppercase text-muted">
                                        <tr>
                                            <th>Receipt Ref</th>
                                            <th>Items Sold</th>
                                            <th>Method</th>
                                            <th>Amount</th>
                                            <th>Time</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($myRecentSales as $sale)
                                        <tr>
                                            <td>
                                                <span class="fw-bold font-monospace text-primary font-size-12">
                                                    {{ $sale->reference_no }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-dark font-size-13 fw-medium">
                                                    {{ $sale->items->count() }} {{ Str::plural('item', $sale->items->count()) }}
                                                </span>
                                                @if($sale->items->first())
                                                    <small class="text-muted d-block font-size-11">
                                                        {{ Str::limit($sale->items->first()->product->name ?? 'Product', 20) }}
                                                        @if($sale->items->count() > 1)
                                                            +{{ $sale->items->count() - 1 }} more
                                                        @endif
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $method = strtolower($sale->payment_method ?? 'cash');
                                                    $methodBadge = match($method) {
                                                        'cash' => 'bg-success-subtle text-success border-success-subtle',
                                                        'card', 'pos' => 'bg-primary-subtle text-primary border-primary-subtle',
                                                        'transfer' => 'bg-info-subtle text-info border-info-subtle',
                                                        default => 'bg-light text-muted border'
                                                    };
                                                @endphp
                                                <span class="badge {{ $methodBadge }} border font-size-11 px-2 py-0.5 text-uppercase fw-semibold">
                                                    {{ $sale->payment_method ?: 'Cash' }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong class="text-dark font-size-13">₦{{ number_format($sale->payable_amount, 2) }}</strong>
                                            </td>
                                            <td>
                                                <span class="text-muted font-size-12">
                                                    {{ $sale->created_at->format('h:i A') }}
                                                    <small class="text-muted d-block opacity-75">({{ $sale->created_at->diffForHumans() }})</small>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ url('staff/salesHistory') }}" class="btn btn-outline-secondary btn-sm rounded-pill font-size-11 px-2.5 py-1">
                                                    <i class="bx bx-receipt me-0.5"></i> Details
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="d-flex flex-column align-items-center justify-content-center text-muted">
                                                    <div class="avatar-md rounded-circle bg-light d-flex align-items-center justify-content-center mb-3">
                                                        <i class="bx bx-shopping-bag font-size-26 text-secondary"></i>
                                                    </div>
                                                    <h6 class="fw-bold text-dark mb-1">No Sales Processed by You Today</h6>
                                                    <p class="font-size-12 text-muted mb-3" style="max-width: 320px;">
                                                        Launch the POS terminal to start ringing up walk-in customers and collecting payments.
                                                    </p>
                                                    <a href="{{ url('staff/pos') }}" class="btn btn-primary btn-sm rounded-pill px-3 py-1.5 font-size-12">
                                                        <i class="bx bx-shopping-bag me-1"></i> Open POS Terminal
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- TAB 2: MY RECENT PRODUCTION --}}
                        <div class="tab-pane fade" id="myProductionTab" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 staff-table">
                                    <thead class="table-light font-size-11 text-uppercase text-muted">
                                        <tr>
                                            <th>Product Baked</th>
                                            <th>Batch Quantity</th>
                                            <th>Time Logged</th>
                                            <th class="text-end">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentProductions as $prod)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="avatar-xs rounded-2 bg-light border d-flex align-items-center justify-content-center text-primary font-size-14" style="width: 30px; height: 30px;">
                                                        <i class="bx bx-cookie"></i>
                                                    </div>
                                                    <div>
                                                        <strong class="text-dark font-size-13">{{ $prod->product->name ?? 'Commercial Product' }}</strong>
                                                        <small class="text-muted d-block font-size-11">Sales Unit: {{ $prod->product->sales_unit ?? 'Units' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <strong class="text-success font-size-13">{{ number_format($prod->quantity, 0) }} Units</strong>
                                            </td>
                                            <td>
                                                <span class="text-muted font-size-12">
                                                    {{ $prod->produced_at ? \Carbon\Carbon::parse($prod->produced_at)->format('h:i A') : $prod->created_at->format('h:i A') }}
                                                    <small class="text-muted d-block opacity-75">({{ $prod->created_at->diffForHumans() }})</small>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-size-11 px-2.5 py-1">
                                                    <i class="bx bx-check me-0.5"></i> Baked &amp; Stocked
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <div class="d-flex flex-column align-items-center justify-content-center text-muted">
                                                    <div class="avatar-md rounded-circle bg-light d-flex align-items-center justify-content-center mb-3">
                                                        <i class="bx bx-dish font-size-26 text-secondary"></i>
                                                    </div>
                                                    <h6 class="fw-bold text-dark mb-1">No Batches Logged Today</h6>
                                                    <p class="font-size-12 text-muted mb-3" style="max-width: 320px;">
                                                        When you complete a bake, record the batch so inventory deducts ingredients automatically.
                                                    </p>
                                                    <a href="{{ url('staff/production') }}" class="btn btn-success btn-sm rounded-pill px-3 py-1.5 font-size-12">
                                                        <i class="bx bx-plus me-1"></i> Record Batch Now
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD FOOTER --}}
                <div class="card-footer bg-light-subtle border-top border-light py-2.5 px-4 font-size-12 text-muted d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <span>
                        <i class="bx bx-info-circle text-primary me-1"></i> Showing activity recorded during today's shift.
                    </span>
                    <a href="{{ url('staff/salesHistory') }}" class="fw-medium text-primary text-decoration-none font-size-12">
                        View Complete Sales History <i class="bx bx-right-arrow-alt"></i>
                    </a>
                </div>
            </div>
        </div>


        {{-- RIGHT COLUMN: COUNTER READINESS & SHIFT INSIGHTS (col-xl-4) --}}
        <div class="col-xl-4">

            {{-- 1. COUNTER SELLING READINESS (READY FOR SALE IN POS) --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden"
                 style="border: 1px solid rgba(226, 232, 240, 0.9) !important; border-top: 3px solid #3b82f6 !important;">
                
                <div class="card-header bg-white border-bottom border-light py-3 px-3.5 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-xs rounded-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                            <i class="bx bx-package font-size-16"></i>
                        </div>
                        <h6 class="card-title mb-0 fw-bold text-dark font-size-14">Counter Stock Ready</h6>
                    </div>
                    <a href="{{ url('staff/pos') }}" class="btn btn-outline-primary btn-sm rounded-pill font-size-11 px-2.5 py-0.5">
                        Sell Now
                    </a>
                </div>

                <div class="card-body p-3">
                    <div class="d-flex flex-column gap-2 mb-2">
                        @forelse($availableProducts as $prod)
                        <div class="p-2.5 rounded-3 bg-light border d-flex align-items-center justify-content-between">
                            <div>
                                <strong class="text-dark font-size-13 d-block">{{ $prod->name }}</strong>
                                <span class="text-muted font-size-11">Price: ₦{{ number_format($prod->selling_price, 2) }}</span>
                            </div>
                            <div class="text-end">
                                <span class="badge {{ $prod->stock_on_hand > 5 ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} border rounded-pill font-size-11 px-2.5 py-1">
                                    {{ number_format($prod->stock_on_hand, 0) }} {{ $prod->sales_unit }} left
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-3 text-muted font-size-12">
                            No products currently recorded in stock.
                        </div>
                        @endforelse
                    </div>

                    @if($outOfStockProductsCount > 0)
                    <div class="p-2 rounded-2 bg-danger-subtle border border-danger-subtle d-flex align-items-center gap-2 font-size-11 text-danger mt-2">
                        <i class="bx bx-error-circle font-size-16"></i>
                        <span><strong>{{ $outOfStockProductsCount }} products</strong> currently out of stock. Notify the bakery team to produce!</span>
                    </div>
                    @endif
                </div>
            </div>


            {{-- 2. SHIFT PAYMENT BREAKDOWN (CASH DRAWER TALLY) --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden"
                 style="border: 1px solid rgba(226, 232, 240, 0.9) !important; border-top: 3px solid #10b981 !important;">
                
                <div class="card-header bg-white border-bottom border-light py-3 px-3.5 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-xs rounded-2 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                            <i class="bx bx-wallet font-size-16"></i>
                        </div>
                        <h6 class="card-title mb-0 fw-bold text-dark font-size-14">My Shift Register Tally</h6>
                    </div>
                    <span class="badge bg-light text-muted border font-size-10">Cashout Aid</span>
                </div>

                <div class="card-body p-3">
                    <div class="d-flex flex-column gap-2 mb-3">
                        @forelse($myPaymentBreakdown as $pay)
                        @php
                            $method = strtolower($pay->payment_method ?: 'cash');
                            $icon = match($method) {
                                'cash' => 'bx-money text-success',
                                'card', 'pos' => 'bx-credit-card text-primary',
                                'transfer' => 'bx-transfer text-info',
                                default => 'bx-wallet text-secondary'
                            };
                        @endphp
                        <div class="d-flex align-items-center justify-content-between p-2 rounded-2 bg-light border font-size-12">
                            <span class="d-flex align-items-center gap-2 text-dark fw-medium text-capitalize">
                                <i class="bx {{ $icon }} font-size-16"></i> {{ $pay->payment_method ?: 'Cash' }}
                                <small class="text-muted fw-normal">({{ $pay->count }} orders)</small>
                            </span>
                            <strong class="text-dark">₦{{ number_format($pay->total, 2) }}</strong>
                        </div>
                        @empty
                        <div class="text-center py-3 text-muted font-size-12">
                            No payments processed yet today.
                        </div>
                        @endforelse
                    </div>

                    <div class="p-2.5 rounded-3 bg-success-subtle border border-success-subtle d-flex align-items-center justify-content-between">
                        <span class="font-size-12 text-success fw-bold">My Total Sales Tally:</span>
                        <h5 class="mb-0 fw-bold text-success font-size-15">₦{{ number_format($mySalesTotal, 2) }}</h5>
                    </div>
                </div>
            </div>


            {{-- 3. RAW INGREDIENTS BUFFER ALERT (CHECK BEFORE YOU BAKE) --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden"
                 style="border: 1px solid rgba(226, 232, 240, 0.9) !important; border-top: 3px solid #f59e0b !important;">
                
                <div class="card-header bg-white border-bottom border-light py-3 px-3.5 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-xs rounded-2 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                            <i class="bx bx-layer font-size-16"></i>
                        </div>
                        <h6 class="card-title mb-0 fw-bold text-dark font-size-14">Kitchen Stock Buffer</h6>
                    </div>
                    <a href="{{ url('staff/inventory') }}" class="btn btn-outline-secondary btn-sm rounded-pill font-size-11 px-2 py-0.5">
                        Inventory
                    </a>
                </div>

                <div class="card-body p-3">
                    @if($lowStockIngredients->count() > 0)
                        <div class="d-flex flex-column gap-2 mb-2">
                            @foreach($lowStockIngredients as $inv)
                            @php
                                $ing = $inv->ingredient;
                                $unitSymbol = $ing->baseUnit->symbol ?? '';
                                $threshold = $ing ? (float) $ing->effective_reorder_level : 0;
                            @endphp
                            <div class="p-2 rounded-2 bg-light border d-flex align-items-center justify-content-between font-size-12">
                                <div>
                                    <strong class="text-dark d-block">{{ $ing->name ?? 'Ingredient' }}</strong>
                                    <small class="text-muted font-size-11">Buffer: {{ number_format($threshold, 1) }} {{ $unitSymbol }}</small>
                                </div>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle font-size-11 px-2 py-1">
                                    {{ number_format($inv->quantity, 1) }} {{ $unitSymbol }} left
                                </span>
                            </div>
                            @endforeach
                        </div>
                        <small class="text-muted font-size-11 d-block mt-2">
                            <i class="bx bx-info-circle text-warning me-0.5"></i> Verify recipe feasibility before starting batches.
                        </small>
                    @else
                        <div class="p-3 rounded-3 bg-success-subtle border border-success-subtle text-center text-success">
                            <i class="bx bx-check-circle font-size-22 mb-1 d-block"></i>
                            <strong class="d-block font-size-13">All Raw Supplies Healthy</strong>
                            <small class="text-muted font-size-11">Pantry ingredients are well-stocked for current baking plans.</small>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>

<style>
    /* Staff Dashboard Styles */
    .staff-hero-avatar {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        color: #ffffff;
        font-weight: 800;
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
    }
    .staff-pulse-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        display: inline-block;
        animation: staffPulse 1.8s infinite;
    }
    @keyframes staffPulse {
        0% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(1.3); }
        100% { opacity: 1; transform: scale(1); }
    }
    .staff-kpi-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .staff-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(27, 45, 57, 0.08) !important;
    }
    .staff-kpi-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .bg-emerald-subtle { background-color: #d1fae5; }
    .text-emerald { color: #059669; }
    .bg-indigo-subtle { background-color: #e0e7ff; }
    .text-indigo { color: #4338ca; }
    .bg-sky-subtle { background-color: #e0f2fe; }
    .text-sky { color: #0284c7; }
    .staff-action-tile {
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        border: 1px solid rgba(226, 232, 240, 0.9) !important;
    }
    .staff-action-tile:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.07) !important;
        border-color: #059669 !important;
    }
    .staff-activity-pills .nav-link {
        color: #64748b;
        background: #f1f5f9;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .staff-activity-pills .nav-link.active {
        color: #ffffff !important;
        background: #059669 !important;
        box-shadow: 0 2px 8px rgba(5, 150, 105, 0.35);
    }
    .staff-table thead th {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        padding: 0.75rem 1rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    .staff-table tbody td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #f1f5f9;
    }
</style>

@endsection
