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
        <div class="card border-0 shadow-sm h-100 stockin-kpi-card" style="border-left: 4px solid #059669 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Purchases Today</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ $stats['today'] }}</h4>
                        <small class="text-success font-size-11 fw-semibold">
                            <i class="bx bx-package me-1"></i>{{ $stats['items'] }} {{ Str::plural('item', $stats['items']) }} restocked
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-success-subtle text-success">
                            <i class="bx bx-cart"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Monthly Procurement Spend --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 stockin-kpi-card" style="border-left: 4px solid #4f46e5 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div style="min-width: 0;">
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Monthly Spend</p>
                        <h4 class="mb-0 fw-bold text-dark text-truncate" title="₦{{ number_format($stats['month'], 2) }}">
                            ₦{{ number_format($stats['month'], 2) }}
                        </h4>
                        <small class="text-primary font-size-11 fw-medium">
                            <i class="bx bx-calendar-event me-1"></i>Recorded this month
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0 ms-2">
                        <span class="avatar-title rounded-circle font-size-20 bg-primary-subtle text-primary">
                            <i class="bx bx-wallet"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 3: Total Recorded Purchases --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 stockin-kpi-card" style="border-left: 4px solid #0284c7 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Purchase Batches</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ $stockIns->count() }}</h4>
                        <small class="text-info font-size-11 fw-semibold">
                            <i class="bx bx-receipt me-1"></i>Cumulative procurement logs
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-info-subtle text-info">
                            <i class="bx bx-receipt"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 4: Latest Purchase Date --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 stockin-kpi-card" style="border-left: 4px solid #f59e0b !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Latest Purchase</p>
                        <h5 class="mb-0 fw-bold text-dark">
                            {{ $stats['last'] ? \Carbon\Carbon::parse($stats['last'])->format('M d, Y') : 'None Recorded' }}
                        </h5>
                        <small class="text-warning font-size-11 fw-medium">
                            <i class="bx bx-time-five me-1"></i>{{ $stockIns->first() ? ($stockIns->first()->supplier ?: 'Vendor purchase') : 'Awaiting entries' }}
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-warning-subtle text-warning">
                            <i class="bx bx-calendar-check"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
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
@foreach($stockIns as $stock)
<div class="modal fade" id="viewStock{{ $stock->id }}" tabindex="-1" aria-labelledby="viewStockTitle{{ $stock->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">

            {{-- Modal Header --}}
            <div class="modal-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="modal-title fw-bold text-dark mb-0" id="viewStockTitle{{ $stock->id }}">
                        <i class="bx bx-receipt me-1 text-primary"></i> Purchase Invoice Details
                    </h5>
                    <span class="badge bg-primary-subtle text-primary font-monospace font-size-11 mt-1">
                        {{ $stock->reference }}
                    </span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                {{-- Quick Summary Info Grid --}}
                <div class="row g-2 mb-4">
                    <div class="col-sm-6 col-md-3">
                        <div class="p-2.5 bg-light rounded-3 border h-100 text-center">
                            <small class="text-muted font-size-11 text-uppercase fw-semibold d-block">Purchase Date</small>
                            <span class="fw-bold text-dark font-size-13">
                                {{ \Carbon\Carbon::parse($stock->purchase_date)->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="p-2.5 bg-light rounded-3 border h-100 text-center">
                            <small class="text-muted font-size-11 text-uppercase fw-semibold d-block">Supplier / Vendor</small>
                            <span class="fw-bold text-dark font-size-13 text-truncate d-block">
                                {{ $stock->supplier ?: 'General Vendor' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="p-2.5 bg-light rounded-3 border h-100 text-center">
                            <small class="text-muted font-size-11 text-uppercase fw-semibold d-block">Total Line Items</small>
                            <span class="fw-bold text-primary font-size-13">
                                {{ $stock->items->count() }} {{ Str::plural('Item', $stock->items->count()) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="p-2.5 bg-success-subtle rounded-3 border border-success-subtle h-100 text-center">
                            <small class="text-success font-size-11 text-uppercase fw-bold d-block">Grand Total</small>
                            <span class="fw-bold text-success font-size-14">
                                ₦{{ number_format($stock->items->sum('total_price'), 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Optional Note --}}
                @if(!empty($stock->note))
                    <div class="alert alert-light border mb-4 p-2.5 font-size-12">
                        <i class="bx bx-info-circle me-1 text-primary"></i><strong>Purchase Note:</strong> {{ $stock->note }}
                    </div>
                @endif

                {{-- Purchased Items Breakdown Table --}}
                <div class="table-responsive rounded border mb-2">
                    <table class="table table-hover align-middle font-size-12 mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="min-width: 160px;">Ingredient</th>
                                <th class="text-center" style="min-width: 110px;">Purchased Qty</th>
                                <th class="text-center" style="min-width: 120px;">Stored Base Qty</th>
                                <th class="text-end" style="min-width: 110px;">Purchase Rate</th>
                                <th class="text-end" style="min-width: 120px;">Total Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stock->items as $item)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $item->ingredient->name ?? 'Raw Material' }}</div>
                                    <small class="text-muted font-monospace font-size-11">
                                        {{ $item->ingredient->slug ?? '' }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-dark">{{ number_format($item->quantity, 3) }}</span>
                                    <span class="badge bg-light text-dark border ms-1">{{ $item->unit->symbol ?? '' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="text-primary fw-semibold">{{ number_format($item->base_quantity, 2) }}</span>
                                    <span class="text-muted font-size-11">{{ $item->unit->base_unit ?? '' }}</span>
                                </td>
                                <td class="text-end text-muted font-size-12">
                                    ₦{{ number_format($item->unit_price, 2) }}
                                    <small class="d-block font-size-10">/ {{ $item->unit->symbol ?? 'unit' }}</small>
                                </td>
                                <td class="text-end fw-bold text-dark font-size-13">
                                    ₦{{ number_format($item->total_price, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="4" class="text-end fw-bold font-size-12">Grand Total:</th>
                                <th class="text-end fw-bold text-success font-size-14">
                                    ₦{{ number_format($stock->items->sum('total_price'), 2) }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer bg-light border-top px-4 py-2.5 d-flex justify-content-between">
                <div class="font-size-11 text-muted">
                    <i class="mdi mdi-account-circle-outline me-1"></i> Recorded by: 
                    <strong>{{ $stock->admin->name ?? 'System Admin' }}</strong> on {{ $stock->created_at->format('M d, Y h:i A') }}
                </div>
                <button type="button" class="btn btn-secondary rounded-pill px-4 font-size-12" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
@endforeach

{{-- =====================================================
     CREATE NEW STOCK PURCHASE MODAL
     ===================================================== --}}
<div class="modal fade" id="addStock" tabindex="-1" aria-labelledby="addStockTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <form method="POST" action="{{ route('newStockIn') }}" style="width: 100%" id="newStockInForm">
            @csrf
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">

                {{-- Modal Header --}}
                <div class="modal-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="addStockTitle">
                            <i class="bx bx-plus-circle me-1 text-primary"></i> Record Stock Purchase
                        </h5>
                        <small class="text-muted">Enter vendor invoice details and restock raw materials into inventory with automatic weighted costing.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    {{-- Invoice Metadata --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold font-size-12 text-dark mb-1">Purchase Date <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-calendar"></i></span>
                                <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}" class="form-control border-start-0 font-size-13" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold font-size-12 text-dark mb-1">Supplier / Vendor Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-store"></i></span>
                                <input type="text" name="supplier" class="form-control border-start-0 font-size-13" placeholder="e.g. Flour Mills Ltd, Golden Penny">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold font-size-12 text-dark mb-1">Purchase Note / Invoice Reference</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-file"></i></span>
                                <input type="text" name="note" class="form-control border-start-0 font-size-13" placeholder="e.g. Invoice #4892, Paid via Transfer">
                            </div>
                        </div>
                    </div>

                    {{-- Dynamic Line Items Table --}}
                    <div class="table-responsive rounded border mb-3">
                        <table class="table table-bordered table-hover align-middle mb-0 font-size-13" id="purchaseItemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width: 280px;">Ingredient / Raw Material <span class="text-danger">*</span></th>
                                    <th style="min-width: 140px;">Quantity <span class="text-danger">*</span></th>
                                    <th style="min-width: 180px;">Purchase Unit <span class="text-danger">*</span></th>
                                    <th style="min-width: 160px;">Total Line Cost (₦) <span class="text-danger">*</span></th>
                                    <th class="text-center" style="width: 60px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <tr class="table-line-item-row" id="row-0">
                                    <td>
                                        <select name="items[0][ingredient_id]" class="form-select font-size-13" required>
                                            <option value="">-- Choose Raw Material --</option>
                                            @foreach($ingredients as $ingredient)
                                                <option value="{{ $ingredient->id }}">{{ $ingredient->name }} (Base: {{ $ingredient->baseUnit->symbol ?? 'unit' }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="0.001" min="0.001" name="items[0][quantity]" class="form-control font-size-13 item-qty-input" required placeholder="0.000">
                                    </td>
                                    <td>
                                        <select name="items[0][unit_id]" class="form-select font-size-13" required>
                                            <option value="">-- Select Unit --</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->symbol }} × {{ (float)$unit->base_multiplier }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light font-size-12">₦</span>
                                            <input type="number" step="0.01" min="0" name="items[0][total_price]" class="form-control font-size-13 item-cost-input" required placeholder="0.00" oninput="calculatePurchaseGrandTotal()">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" disabled title="First line item cannot be deleted">
                                            <i class="mdi mdi-trash-can-outline"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Actions & Grand Total Counter --}}
                    <div class="d-flex flex-wrap align-items-center justify-content-between p-3 bg-light rounded-3 border">
                        <div>
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" onclick="addRow()">
                                <i class="bx bx-plus me-1"></i> Add Another Line Item
                            </button>
                        </div>
                        <div class="text-end">
                            <span class="text-muted font-size-12 me-2">Estimated Total Spend:</span>
                            <span class="fw-bold text-success font-size-16" id="grandTotalDisplay">₦0.00</span>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="modal-footer bg-light border-top px-4 py-3 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 font-size-12" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 font-size-12 fw-medium shadow-sm">
                        <i class="bx bx-check-double me-1 font-size-14"></i> Save Stock Purchase
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- Dynamic Row Addition & Cost Calculation Script --}}
<script>
let rowCount = 1;

function addRow() {
    const tbody = document.getElementById('itemsBody');
    const row = document.createElement('tr');
    row.className = 'table-line-item-row';
    row.id = `row-${rowCount}`;

    row.innerHTML = `
        <td>
            <select name="items[${rowCount}][ingredient_id]" class="form-select font-size-13" required>
                <option value="">-- Choose Raw Material --</option>
                @foreach($ingredients as $ingredient)
                    <option value="{{ $ingredient->id }}">{{ $ingredient->name }} (Base: {{ $ingredient->baseUnit->symbol ?? 'unit' }})</option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" step="0.001" min="0.001" name="items[${rowCount}][quantity]" class="form-control font-size-13 item-qty-input" required placeholder="0.000">
        </td>
        <td>
            <select name="items[${rowCount}][unit_id]" class="form-select font-size-13" required>
                <option value="">-- Select Unit --</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->symbol }} × {{ (float)$unit->base_multiplier }})</option>
                @endforeach
            </select>
        </td>
        <td>
            <div class="input-group">
                <span class="input-group-text bg-light font-size-12">₦</span>
                <input type="number" step="0.01" min="0" name="items[${rowCount}][total_price]" class="form-control font-size-13 item-cost-input" required placeholder="0.00" oninput="calculatePurchaseGrandTotal()">
            </div>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeRow(this)" title="Remove item">
                <i class="mdi mdi-trash-can-outline"></i>
            </button>
        </td>
    `;

    tbody.appendChild(row);
    rowCount++;
    calculatePurchaseGrandTotal();
}

function removeRow(button) {
    const row = button.closest('tr');
    if (row) {
        row.remove();
        calculatePurchaseGrandTotal();
    }
}

function calculatePurchaseGrandTotal() {
    let total = 0;
    const costInputs = document.querySelectorAll('.item-cost-input');
    costInputs.forEach(input => {
        const val = parseFloat(input.value) || 0;
        total += val;
    });

    const display = document.getElementById('grandTotalDisplay');
    if (display) {
        display.textContent = '₦' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
}
</script>

@endsection
