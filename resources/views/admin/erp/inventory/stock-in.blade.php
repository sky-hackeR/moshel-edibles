@extends('admin.layout.dashboard')

@section('content')
<style>
    /* ==========================================================================
       MOSHEL EXECUTIVE STOCK IN (PROCUREMENT) STYLES
       ========================================================================== */
    .stockin-kpi-card {
        border-radius: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stockin-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06) !important;
    }
    .vendor-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
        background: #eef2fe;
        color: #4338ca;
    }
    .table-line-item-row:hover {
        background-color: #f8fafc;
    }
</style>

{{-- =====================================================
     BREADCRUMB & PAGE HEADER
     ===================================================== --}}
<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between pb-2">
            <div>
                <h4 class="mb-sm-0 font-size-18 fw-bold">Stock In (Procurement)</h4>
                <ol class="breadcrumb m-0 mt-1">
                    <li class="breadcrumb-item active">Purchases & Restocking</li>
                </ol>
            </div>
            <div class="d-flex align-items-center flex-wrap gap-2">
                <button type="button" class="btn btn-primary rounded-pill px-3 py-1.5 font-size-12 fw-medium shadow-sm" data-bs-toggle="modal" data-bs-target="#addStock">
                    <i class="bx bx-plus me-1 font-size-14"></i> Record New Purchase
                </button>
                <a href="{{ route('inventory') }}" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 font-size-12 fw-medium">
                    <i class="bx bx-cube me-1"></i> View Inventory
                </a>
                <a href="{{ route('openingStock') }}" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 font-size-12 fw-medium">
                    <i class="bx bx-archive-in me-1"></i> Opening Stock
                </a>
            </div>
        </div>
    </div>
</div>

{{-- =====================================================
     EXECUTIVE KPI STATS ROW
     ===================================================== --}}
<div class="row g-3 mb-4">
    {{-- Card 1: Purchases Today --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Purchases Today"
            value="{{ $stats['today'] }}"
            subtitle="{{ $stats['items'] }} {{ Str::plural('item', $stats['items']) }} restocked"
            subicon="bx bx-package"
            color="success"
            borderColor="#059669"
            icon="bx bx-cart"
            cardClass="stockin-kpi-card"
        />
    </div>

    {{-- Card 2: Monthly Procurement Spend --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Monthly Spend"
            value="₦{{ number_format($stats['month'], 2) }}"
            subtitle="Recorded this month"
            subicon="bx bx-calendar-event"
            color="primary"
            borderColor="#4f46e5"
            icon="bx bx-wallet"
            cardClass="stockin-kpi-card"
        />
    </div>

    {{-- Card 3: Total Recorded Purchases --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Purchase Batches"
            value="{{ $stockIns->count() }}"
            subtitle="Cumulative procurement logs"
            subicon="bx bx-receipt"
            color="info"
            borderColor="#0284c7"
            icon="bx bx-receipt"
            cardClass="stockin-kpi-card"
        />
    </div>

    {{-- Card 4: Latest Purchase Date --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Latest Purchase"
            value="{{ $stats['last'] ? \Carbon\Carbon::parse($stats['last'])->format('M d, Y') : 'None Recorded' }}"
            subtitle="{{ $stockIns->first() ? ($stockIns->first()->supplier ?: 'Vendor purchase') : 'Awaiting entries' }}"
            subicon="bx bx-time-five"
            color="warning"
            borderColor="#f59e0b"
            icon="bx bx-calendar-check"
            cardClass="stockin-kpi-card"
        />
    </div>
</div>

{{-- =====================================================
     MAIN STOCK IN HISTORY DIRECTORY & TABLE
     ===================================================== --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-white border-bottom py-3 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h5 class="card-title mb-0 fw-bold text-dark">
                <i class="bx bx-cart-alt me-1 text-primary"></i> Stock In Purchase Ledger
            </h5>
            <small class="text-muted">Audit trail of raw material purchases, vendor receipts, and moving average cost adjustments.</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill bg-light text-dark border px-3 py-1 font-size-11">
                {{ $stockIns->count() }} {{ Str::plural('Purchase', $stockIns->count()) }} Total
            </span>
        </div>
    </div>

    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-hover align-middle dt-responsive nowrap w-100 font-size-13 mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">S/N</th>
                        <th style="min-width: 170px;">Reference & Date</th>
                        <th style="min-width: 200px;">Supplier / Vendor</th>
                        <th style="min-width: 140px;">Total Cost</th>
                        <th style="min-width: 200px;">Line Items Summary</th>
                        <th style="min-width: 150px;">Recorded By</th>
                        <th class="text-center" style="width: 110px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockIns as $stock)
                        @php
                            $totalCost = $stock->items->sum('total_price');
                            $itemsCount = $stock->items->count();
                            $topIngredients = $stock->items->take(2);
                        @endphp
                        <tr>
                            {{-- S/N --}}
                            <td class="text-center text-muted fw-semibold">
                                {{ $loop->iteration }}
                            </td>

                            {{-- REFERENCE & DATE --}}
                            <td>
                                <div>
                                    <span class="badge bg-primary-subtle text-primary font-monospace font-size-12 px-2 py-1">
                                        {{ $stock->reference }}
                                    </span>
                                </div>
                                <small class="text-muted font-size-11 d-block mt-1">
                                    <i class="mdi mdi-calendar-outline me-1"></i>{{ \Carbon\Carbon::parse($stock->purchase_date)->format('M d, Y') }}
                                </small>
                            </td>

                            {{-- SUPPLIER / VENDOR --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="vendor-avatar me-2 shadow-sm">
                                        {{ strtoupper(substr($stock->supplier ?: 'V', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark font-size-13">
                                            {{ $stock->supplier ?: 'General Vendor' }}
                                        </div>
                                        @if(!empty($stock->note))
                                            <small class="text-muted text-truncate d-block" style="max-width: 180px;" title="{{ $stock->note }}">
                                                <i class="bx bx-note me-1"></i>{{ $stock->note }}
                                            </small>
                                        @else
                                            <small class="text-muted font-size-11">Direct Purchase</small>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- TOTAL COST --}}
                            <td>
                                <div class="fw-bold font-size-14 text-dark">
                                    ₦{{ number_format($totalCost, 2) }}
                                </div>
                                <small class="text-muted font-size-11">
                                    {{ $itemsCount }} {{ Str::plural('line item', $itemsCount) }}
                                </small>
                            </td>

                            {{-- LINE ITEMS SUMMARY --}}
                            <td>
                                <div class="d-flex flex-wrap gap-1 align-items-center">
                                    @foreach($topIngredients as $item)
                                        <span class="badge rounded-pill bg-light text-dark border px-2 py-0.5 font-size-11">
                                            {{ $item->ingredient->name ?? 'Item' }}: {{ $item->quantity }} {{ $item->unit->symbol ?? '' }}
                                        </span>
                                    @endforeach
                                    @if($itemsCount > 2)
                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary font-size-10 px-2 py-0.5">
                                            +{{ $itemsCount - 2 }} more
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- RECORDED BY --}}
                            <td>
                                <div class="text-dark font-size-12 fw-medium">
                                    <i class="mdi mdi-account-circle-outline me-1 text-primary"></i>{{ $stock->admin->name ?? 'System Admin' }}
                                </div>
                                <small class="text-muted font-size-11" title="{{ $stock->created_at->format('M d, Y h:i A') }}">
                                    {{ $stock->created_at->diffForHumans() }}
                                </small>
                            </td>

                            {{-- ACTION --}}
                            <td class="text-center">
                                <button type="button"
                                        class="btn btn-sm btn-soft-primary rounded-pill px-3 py-1 font-size-11 fw-medium"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewStock{{ $stock->id }}">
                                    <i class="bx bx-show me-1"></i> Details
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bx bx-cart font-size-24 d-block mb-1 text-muted"></i>
                                No stock purchases recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- =====================================================
     VIEW PURCHASE DETAILS MODALS
     ===================================================== --}}
@include('admin.erp.partials.modals.stock-in.stock-in-details-modals')

{{-- =====================================================
     CREATE NEW STOCK PURCHASE MODAL
     ===================================================== --}}
@include('admin.erp.partials.modals.stock-in.record-stock-in-modal')

{{-- =====================================================
     DYNAMIC ROW ADDITION & COST CALCULATION JAVASCRIPT
     ===================================================== --}}
@include('admin.erp.partials.scripts.stock-in-scripts')

@endsection
