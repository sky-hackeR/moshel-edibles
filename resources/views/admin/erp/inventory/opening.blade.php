@extends('admin.layout.dashboard')

@section('content')
<style>
    /* ==========================================================================
       MOSHEL EXECUTIVE OPENING STOCK & AUDIT LEDGER STYLES
       ========================================================================== */
    .opening-kpi-card {
        border-radius: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .opening-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06) !important;
    }
    .nav-segment-tabs {
        background: #f1f5f9;
        padding: 4px;
        border-radius: 30px;
        display: inline-flex;
        border: none;
    }
    .nav-segment-tabs .nav-link {
        border-radius: 24px;
        border: none;
        color: #475569;
        font-weight: 600;
        font-size: 13px;
        padding: 8px 20px;
        transition: all 0.2s ease;
    }
    .nav-segment-tabs .nav-link:hover {
        color: #1e293b;
    }
    .nav-segment-tabs .nav-link.active {
        background: #ffffff;
        color: #4338ca;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
    .preview-stat-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px;
    }
</style>

@php
    $totalEntries = $openingStocks->count();
    $productEntries = $openingStocks->where('item_type', 'product');
    $ingredientEntries = $openingStocks->where('item_type', 'ingredient');

    $totalProductQty = (float) $productEntries->sum('quantity');

    if (!function_exists('calculateOpeningValuation')) {
        function calculateOpeningValuation($stock) {
            if ($stock->item_type === 'product') {
                $price = (float) (optional($stock->product)->selling_price ?? 0);
                return (float) $stock->quantity * $price;
            }
            $rawVal = (float) $stock->quantity * (float) $stock->average_cost;
            // Fix fractional 6-decimal repeating division truncation (e.g. 25000/30000 = 0.833333 -> 24999.99 => 25000.00)
            if (abs($rawVal - round($rawVal)) <= 0.015) {
                return (float) round($rawVal);
            } elseif (abs($rawVal - round($rawVal, 2)) <= 0.005) {
                return (float) round($rawVal, 2);
            }
            return $rawVal;
        }
    }

    $totalProductValuation = $productEntries->sum(function($stock) {
        return calculateOpeningValuation($stock);
    });

    $totalIngredientValuation = $ingredientEntries->sum(function($stock) {
        return calculateOpeningValuation($stock);
    });

    $totalCombinedValuation = $totalProductValuation + $totalIngredientValuation;
@endphp

{{-- =====================================================
     BREADCRUMB & PAGE HEADER
     ===================================================== --}}
<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between pb-2">
            <div>
                <h4 class="mb-sm-0 font-size-18 fw-bold">Opening Stock Initialization</h4>
                <ol class="breadcrumb m-0 mt-1">
                    <li class="breadcrumb-item active">Opening Stock Ledger</li>
                </ol>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-dark border px-3 py-1.5 font-size-12 fw-medium">
                    <i class="bx bx-shield-quarter me-1 text-primary"></i> Audited Entry Protocol
                </span>
            </div>
        </div>
    </div>
</div>

{{-- =====================================================
     EXECUTIVE KPI STATS ROW
     ===================================================== --}}
<div class="row g-3 mb-4">
    {{-- Card 1: Total Initialized Records --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Total Entries Logged"
            value="{{ $totalEntries }}"
            subtitle="{{ $productEntries->count() }} Prod · {{ $ingredientEntries->count() }} Ing"
            subicon="bx bx-check-double"
            color="success"
            borderColor="#059669"
            icon="bx bx-list-check"
            cardClass="opening-kpi-card"
        />
    </div>

    {{-- Card 2: Finished Products Stock Injected --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Product Units Added"
            value="{{ number_format($totalProductQty, 0) }}"
            subtitle="Finished Retail Inventory"
            subicon="bx bx-package"
            color="primary"
            borderColor="#4f46e5"
            icon="bx bx-package"
            cardClass="opening-kpi-card"
        />
    </div>

    {{-- Card 3: Combined Opening Stock Valuation --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Total Stock Valuation"
            value="₦{{ number_format($totalCombinedValuation, 2) }}"
            color="info"
            borderColor="#0284c7"
            icon="bx bx-money"
            cardClass="opening-kpi-card"
        >
            <small class="text-info font-size-11 fw-semibold text-truncate d-block" title="₦{{ number_format($totalProductValuation, 2) }} Prod · ₦{{ number_format($totalIngredientValuation, 2) }} Mat">
                <i class="bx bx-money me-1"></i>₦{{ number_format($totalProductValuation, 2) }} Prod · ₦{{ number_format($totalIngredientValuation, 2) }} Mat
            </small>
        </x-kpi-card>
    </div>

    {{-- Card 4: Audit & Tracking Standard --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Ledger Integrity"
            value="Audited"
            subtitle="Officer Signed Logs"
            subicon="bx bx-user-check"
            color="warning"
            borderColor="#f59e0b"
            icon="bx bx-check-shield"
            cardClass="opening-kpi-card"
        />
    </div>
</div>

{{-- =====================================================
     OPENING STOCK ENTRY WORKBENCH
     ===================================================== --}}
@include('admin.erp.partials.workbenches.opening-stock-workbench')

{{-- =====================================================
     OPENING STOCK AUDIT LEDGER
     ===================================================== --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-white border-bottom py-3 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h5 class="card-title mb-0 fw-bold text-dark">
                <i class="bx bx-history me-1 text-primary"></i> Opening Stock Audit Ledger
            </h5>
            <small class="text-muted">Immutable historical record of all inventory balance initializations and starting stocks.</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill bg-light text-dark border px-3 py-1 font-size-11">
                {{ $totalEntries }} {{ Str::plural('Record', $totalEntries) }} Registered
            </span>
        </div>
    </div>

    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-hover align-middle dt-responsive w-100 font-size-13 mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 45px;">#</th>
                        <th style="width: 140px;">Date & Officer</th>
                        <th>Stock Item & Type</th>
                        <th style="width: 120px;">Quantity Added</th>
                        <th style="width: 160px;">Valuation (₦)</th>
                        <th style="width: 180px;">Audit Memo</th>
                        <th class="text-center" style="width: 75px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($openingStocks as $openingStock)
                        @php
                            $isProduct = $openingStock->item_type === 'product';
                            $itemName = $isProduct 
                                ? (optional($openingStock->product)->name ?? 'Deleted Product')
                                : (optional($openingStock->ingredient)->name ?? 'Deleted Ingredient');
                            $unitLabel = $isProduct 
                                ? (optional($openingStock->product)->sales_unit ?? 'Units')
                                : (optional(optional($openingStock->ingredient)->baseUnit)->symbol ?? 'base units');
                            $unitRate = $isProduct
                                ? (float) (optional($openingStock->product)->selling_price ?? 0)
                                : (float) $openingStock->average_cost;
                            $totalValuation = calculateOpeningValuation($openingStock);
                        @endphp
                        <tr>
                            {{-- S/N --}}
                            <td class="text-center text-muted fw-semibold">
                                {{ $loop->iteration }}
                            </td>

                            {{-- DATE & OFFICER --}}
                            <td>
                                <span class="d-block fw-semibold text-dark">{{ $openingStock->created_at->format('d M, Y') }}</span>
                                <small class="text-muted font-size-11">
                                    <i class="bx bx-user me-0.5 text-primary"></i>{{ optional($openingStock->admin)->name ?? 'Officer' }}
                                </small>
                            </td>

                            {{-- ITEM DETAILS & TYPE --}}
                            <td>
                                <div class="d-flex align-items-center gap-1 mb-0.5 flex-wrap">
                                    <strong class="text-dark font-size-13">{{ $itemName }}</strong>
                                    @if($isProduct)
                                        <span class="badge bg-primary-subtle text-primary font-size-10 px-1.5 py-0.5">Product</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success font-size-10 px-1.5 py-0.5">Ingredient</span>
                                    @endif
                                </div>
                                <small class="text-muted font-size-11 d-block">
                                    {{ $isProduct ? 'Finished Retail Catalog' : 'Raw Production Material' }}
                                </small>
                            </td>

                            {{-- OPENING QUANTITY --}}
                            <td>
                                <strong class="text-dark font-size-13">
                                    {{ number_format($openingStock->quantity, 3) }}
                                </strong>
                                <small class="text-muted font-size-11 ms-0.5">{{ $unitLabel }}</small>
                            </td>

                            {{-- VALUATION (TOTAL & RATE) --}}
                            <td>
                                <strong class="text-dark font-size-13 d-block">₦{{ number_format($totalValuation, 2) }}</strong>
                                <small class="text-muted font-size-11">
                                    @ ₦{{ number_format($unitRate, $isProduct ? 2 : 4) }} / {{ $unitLabel }}
                                </small>
                            </td>

                            {{-- REASON / MEMO --}}
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 180px;" title="{{ $openingStock->reason ?? 'Commencement initialization' }}">
                                    {{ $openingStock->reason ?: '—' }}
                                </span>
                            </td>

                            {{-- ACTIONS --}}
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-1 font-size-12" data-bs-toggle="modal" data-bs-target="#viewOpeningStockModal{{ $openingStock->id }}">
                                    <i class="bx bx-show me-0.5"></i> View
                                </button>
                            </td>
                        </tr>

                        {{-- VIEW OPENING STOCK DETAILS MODAL --}}
                        @include('admin.erp.partials.modals.opening-stock.opening-stock-view-modal')
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <div class="avatar-md mx-auto mb-3">
                                    <span class="avatar-title rounded-circle bg-light text-muted font-size-24">
                                        <i class="bx bx-package"></i>
                                    </span>
                                </div>
                                <h6 class="fw-bold text-dark">No Opening Stock Recorded</h6>
                                <p class="text-muted small mb-0">Use the workbench above to initialize pre-existing finished goods or raw materials.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- =====================================================
     DYNAMIC INTERACTIVE SIMULATORS JAVASCRIPT
     ===================================================== --}}
@include('admin.erp.partials.scripts.opening-stock-scripts')
@endsection
