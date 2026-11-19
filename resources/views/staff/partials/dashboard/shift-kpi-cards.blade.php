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
