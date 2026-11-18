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
        <div class="card border-0 shadow-sm h-100 opening-kpi-card" style="border-left: 4px solid #059669 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Total Entries Logged</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ $totalEntries }}</h4>
                        <small class="text-success font-size-11 fw-semibold">
                            <i class="bx bx-check-double me-1"></i>{{ $productEntries->count() }} Prod · {{ $ingredientEntries->count() }} Ing
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-success-subtle text-success">
                            <i class="bx bx-list-check"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Finished Products Stock Injected --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 opening-kpi-card" style="border-left: 4px solid #4f46e5 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Product Units Added</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ number_format($totalProductQty, 0) }}</h4>
                        <small class="text-primary font-size-11 fw-medium">
                            <i class="bx bx-package me-1"></i>Finished Retail Inventory
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-primary-subtle text-primary">
                            <i class="bx bx-package"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 3: Combined Opening Stock Valuation --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 opening-kpi-card" style="border-left: 4px solid #0284c7 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div style="min-width: 0;">
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Total Stock Valuation</p>
                        <h4 class="mb-0 fw-bold text-dark text-truncate" title="₦{{ number_format($totalCombinedValuation, 2) }}">
                            ₦{{ number_format($totalCombinedValuation, 2) }}
                        </h4>
                        <small class="text-info font-size-11 fw-semibold text-truncate d-block" title="₦{{ number_format($totalProductValuation, 2) }} Prod · ₦{{ number_format($totalIngredientValuation, 2) }} Mat">
                            <i class="bx bx-money me-1"></i>₦{{ number_format($totalProductValuation, 2) }} Prod · ₦{{ number_format($totalIngredientValuation, 2) }} Mat
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0 ms-2">
                        <span class="avatar-title rounded-circle font-size-20 bg-info-subtle text-info">
                            <i class="bx bx-money"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 4: Audit & Tracking Standard --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 opening-kpi-card" style="border-left: 4px solid #f59e0b !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Ledger Integrity</p>
                        <h4 class="mb-0 fw-bold text-dark">Audited</h4>
                        <small class="text-warning font-size-11 fw-semibold">
                            <i class="bx bx-user-check me-1"></i>Officer Signed Logs
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-warning-subtle text-warning">
                            <i class="bx bx-check-shield"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- =====================================================
     OPENING STOCK ENTRY WORKBENCH
     ===================================================== --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-white border-bottom py-3 px-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <h5 class="card-title mb-0 fw-bold text-dark">
                <i class="bx bx-import me-1 text-primary"></i> Stock Initialization Workbench
            </h5>
            <small class="text-muted">Enter pre-existing physical inventory prior to automated system tracking.</small>
        </div>

        {{-- SEGMENTED TABS --}}
        <ul class="nav nav-segment-tabs" id="openingStockTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="product-tab" data-bs-toggle="tab" data-bs-target="#product-pane" type="button" role="tab" aria-controls="product-pane" aria-selected="true">
                    <i class="bx bx-package me-1"></i> Finished Products
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="ingredient-tab" data-bs-toggle="tab" data-bs-target="#ingredient-pane" type="button" role="tab" aria-controls="ingredient-pane" aria-selected="false">
                    <i class="bx bx-bowl-rice me-1"></i> Raw Ingredients
                </button>
            </li>
        </ul>
    </div>

    <div class="card-body p-4">
        <div class="tab-content" id="openingStockTabContent">
            {{-- ================================================= --}}
            {{-- TAB 1: PRODUCT OPENING STOCK --}}
            {{-- ================================================= --}}
            <div class="tab-pane fade show active" id="product-pane" role="tabpanel" aria-labelledby="product-tab">
                <form action="{{ route('addProductOpeningStock') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        {{-- Form Inputs --}}
                        <div class="col-lg-7">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark font-size-12 mb-1">Select Finished Product <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-package"></i></span>
                                        <select name="product_id" id="product_id" class="form-select border-start-0" required>
                                            <option value="" selected disabled>Choose a product from catalog...</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}"
                                                        data-name="{{ $product->name }}"
                                                        data-stock="{{ $product->stock_on_hand }}"
                                                        data-unit="{{ $product->sales_unit }}"
                                                        data-price="{{ $product->selling_price }}">
                                                    {{ $product->name }} (Current: {{ number_format($product->stock_on_hand, 0) }} {{ $product->sales_unit }} · ₦{{ number_format($product->selling_price, 2) }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <small class="text-muted font-size-11 mt-1 d-block">Only currently active finished catalog products appear in this list.</small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark font-size-12 mb-1">Opening Quantity <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-plus-circle"></i></span>
                                        <input type="number" name="quantity" id="product_quantity" class="form-control border-start-0" min="0.001" step="0.001" placeholder="e.g. 50" required>
                                        <span class="input-group-text bg-light text-muted font-size-12" id="product_unit_badge">Units</span>
                                    </div>
                                    <small class="text-muted font-size-11 mt-1 d-block">Physical count available at commencement.</small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark font-size-12 mb-1">Audit Reason / Origin</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-comment-detail"></i></span>
                                        <input type="text" name="reason" class="form-control border-start-0" value="Existing stock before system commencement" placeholder="Reason note...">
                                    </div>
                                    <small class="text-muted font-size-11 mt-1 d-block">Recorded in permanent audit trail.</small>
                                </div>
                            </div>
                        </div>

                        {{-- Live Summary Card --}}
                        <div class="col-lg-5">
                            <div class="preview-stat-box h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between pb-2 mb-2 border-bottom">
                                        <span class="fw-bold text-dark font-size-12 text-uppercase tracking-wider">
                                            <i class="bx bx-line-chart me-1 text-primary"></i> Live Impact Simulator
                                        </span>
                                        <span class="badge bg-primary-subtle text-primary font-size-10 px-2 py-0.5">Finished Goods</span>
                                    </div>

                                    <div class="mb-2">
                                        <span class="text-muted font-size-11 d-block">Selected Item:</span>
                                        <strong class="text-dark font-size-13 d-block text-truncate" id="preview_product_name">No product selected</strong>
                                    </div>

                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <div class="p-2 bg-white rounded border">
                                                <small class="text-muted font-size-10 d-block">Current Stock</small>
                                                <span class="fw-bold text-secondary font-size-13" id="preview_current_stock">—</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 bg-white rounded border">
                                                <small class="text-muted font-size-10 d-block">Adding Quantity</small>
                                                <span class="fw-bold text-primary font-size-13" id="preview_adding_stock">—</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <div class="p-2 bg-white rounded border">
                                                <small class="text-muted font-size-10 d-block">Retail Price</small>
                                                <span class="fw-bold text-dark font-size-13" id="preview_product_price">—</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 bg-white rounded border">
                                                <small class="text-muted font-size-10 d-block">Added Value</small>
                                                <span class="fw-bold text-success font-size-13" id="preview_product_valuation">—</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-2.5 rounded border border-primary-subtle" style="background: #eef2ff;">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <small class="fw-bold text-primary font-size-11">Projected Total In-Stock:</small>
                                            <strong class="font-size-14 text-primary" id="preview_projected_stock">—</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 font-size-12 fw-medium shadow-sm w-100">
                                        <i class="bx bx-check-circle me-1 font-size-14"></i> Post Product Opening Stock
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ================================================= --}}
            {{-- TAB 2: INGREDIENT OPENING STOCK --}}
            {{-- ================================================= --}}
            <div class="tab-pane fade" id="ingredient-pane" role="tabpanel" aria-labelledby="ingredient-tab">
                <form action="{{ route('addIngredientOpeningStock') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        {{-- Form Inputs --}}
                        <div class="col-lg-7">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark font-size-12 mb-1">Select Raw Material / Ingredient <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-bowl-rice"></i></span>
                                        <select name="ingredient_id" id="ingredient_id" class="form-select border-start-0" required>
                                            <option value="" selected disabled>Choose an ingredient from catalog...</option>
                                            @foreach($ingredients as $ingredient)
                                                @php
                                                    $uSymbol = optional($ingredient->baseUnit)->symbol ?? 'unit';
                                                    $uName = optional($ingredient->baseUnit)->name ?? 'Base Unit';
                                                @endphp
                                                <option value="{{ $ingredient->id }}"
                                                        data-name="{{ $ingredient->name }}"
                                                        data-unit="{{ $uSymbol }}"
                                                        data-unitname="{{ $uName }}">
                                                    {{ $ingredient->name }} — {{ $uName }} ({{ $uSymbol }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <small class="text-muted font-size-11 mt-1 d-block">
                                        All raw material inventory is stored and costed using the ingredient's <strong>base unit</strong>.
                                    </small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark font-size-12 mb-1">Opening Quantity <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-scale"></i></span>
                                        <input type="number" name="quantity" id="ingredient_quantity" class="form-control border-start-0" min="0.001" step="0.001" placeholder="e.g. 25000" required>
                                        <span class="input-group-text bg-light text-muted font-size-12" id="ingredient_unit_badge">Base Units</span>
                                    </div>
                                    <small class="text-muted font-size-11 mt-1 d-block" id="ingredient_unit_help">
                                        Enter in base units (e.g. 25,000 for 25 kg).
                                    </small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark font-size-12 mb-1">Estimated Total Stock Value (₦) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0">₦</span>
                                        <input type="number" name="total_value" id="ingredient_total_value" class="form-control border-start-0" min="0" step="0.01" placeholder="e.g. 37500" required>
                                    </div>
                                    <small class="text-muted font-size-11 mt-1 d-block">Total monetary worth of the batch.</small>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark font-size-12 mb-1">Audit Reason / Origin</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-comment-detail"></i></span>
                                        <input type="text" name="reason" class="form-control border-start-0" value="Existing stock before system commencement" placeholder="Reason note...">
                                    </div>
                                    <small class="text-muted font-size-11 mt-1 d-block">Recorded in permanent audit trail.</small>
                                </div>
                            </div>
                        </div>

                        {{-- Live Valuation & Costing Simulator --}}
                        <div class="col-lg-5">
                            <div class="preview-stat-box h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between pb-2 mb-2 border-bottom">
                                        <span class="fw-bold text-dark font-size-12 text-uppercase tracking-wider">
                                            <i class="bx bx-calculator me-1 text-success"></i> Auto Costing Engine
                                        </span>
                                        <span class="badge bg-success-subtle text-success font-size-10 px-2 py-0.5">Weighted Cost</span>
                                    </div>

                                    <div class="mb-2">
                                        <span class="text-muted font-size-11 d-block">Selected Material:</span>
                                        <strong class="text-dark font-size-13 d-block text-truncate" id="preview_ingredient_name">No ingredient selected</strong>
                                    </div>

                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <div class="p-2 bg-white rounded border">
                                                <small class="text-muted font-size-10 d-block">Quantity Logged</small>
                                                <span class="fw-bold text-dark font-size-13" id="preview_ing_quantity">—</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 bg-white rounded border">
                                                <small class="text-muted font-size-10 d-block">Total Value</small>
                                                <span class="fw-bold text-dark font-size-13" id="preview_ing_value">—</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-2.5 rounded border border-success-subtle" style="background: #f0fdf4;">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <small class="fw-bold text-success font-size-11 d-block">Calculated Unit Cost:</small>
                                                <span class="text-muted font-size-10">Used for recipe production formulas</span>
                                            </div>
                                            <strong class="font-size-14 text-success" id="preview_calculated_unit_cost">₦0.00 / unit</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-success rounded-pill px-4 py-2 font-size-12 fw-medium shadow-sm w-100">
                                        <i class="bx bx-check-circle me-1 font-size-14"></i> Post Ingredient Opening Stock
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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

                        {{-- =====================================================
                             VIEW OPENING STOCK DETAILS MODAL
                             ===================================================== --}}
                        <div class="modal fade" id="viewOpeningStockModal{{ $openingStock->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                                    <div class="modal-header border-bottom px-4 py-3 bg-light">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="avatar-xs">
                                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                    <i class="bx bx-file font-size-16"></i>
                                                </span>
                                            </span>
                                            <div>
                                                <h5 class="modal-title fw-bold text-dark mb-0">Opening Stock Details</h5>
                                                <small class="text-muted">Audit Record #{{ $openingStock->id }}</small>
                                            </div>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body p-4">
                                        {{-- TYPE BANNER --}}
                                        <div class="text-center mb-4">
                                            @if($isProduct)
                                                <span class="badge bg-primary-subtle text-primary font-size-12 px-3 py-1.5 rounded-pill">
                                                    <i class="bx bx-package me-1"></i> Finished Product Starting Stock
                                                </span>
                                            @else
                                                <span class="badge bg-success-subtle text-success font-size-12 px-3 py-1.5 rounded-pill">
                                                    <i class="bx bx-bowl-rice me-1"></i> Raw Material Starting Stock
                                                </span>
                                            @endif
                                        </div>

                                        {{-- AUDIT DETAILS LIST --}}
                                        <div class="p-3 bg-light rounded-3 border">
                                            <div class="d-flex justify-content-between py-1.5 border-bottom">
                                                <span class="text-muted font-size-12">Item Name</span>
                                                <strong class="text-dark font-size-12">{{ $itemName }}</strong>
                                            </div>

                                            <div class="d-flex justify-content-between py-1.5 border-bottom">
                                                <span class="text-muted font-size-12">Opening Quantity</span>
                                                <strong class="text-dark font-size-12">{{ number_format($openingStock->quantity, 3) }} {{ $unitLabel }}</strong>
                                            </div>

                                            <div class="d-flex justify-content-between py-1.5 border-bottom">
                                                <span class="text-muted font-size-12">{{ $isProduct ? 'Retail Price' : 'Opening Unit Cost' }}</span>
                                                <strong class="{{ $isProduct ? 'text-primary' : 'text-success' }} font-size-12">
                                                    ₦{{ number_format($unitRate, $isProduct ? 2 : 4) }} / {{ $unitLabel }}
                                                </strong>
                                            </div>

                                            <div class="d-flex justify-content-between py-1.5 border-bottom">
                                                <span class="text-muted font-size-12">Total Valuation</span>
                                                <strong class="text-dark font-size-12">₦{{ number_format($totalValuation, 2) }}</strong>
                                            </div>

                                            <div class="d-flex justify-content-between py-1.5 border-bottom">
                                                <span class="text-muted font-size-12">Audit Reason</span>
                                                <span class="text-dark font-size-12 text-end text-break" style="max-width: 250px;">{{ $openingStock->reason ?? 'Standard commencement initialization' }}</span>
                                            </div>

                                            <div class="d-flex justify-content-between py-1.5 border-bottom">
                                                <span class="text-muted font-size-12">Authorized Officer</span>
                                                <span class="text-dark font-size-12 fw-medium"><i class="bx bx-user me-1 text-primary"></i>{{ optional($openingStock->admin)->name ?? 'System Officer' }}</span>
                                            </div>

                                            <div class="d-flex justify-content-between py-1.5">
                                                <span class="text-muted font-size-12">Logged Timestamp</span>
                                                <span class="text-dark font-size-12">{{ $openingStock->created_at->format('d M, Y h:i A') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer border-top px-4 py-3 bg-white d-flex justify-content-end">
                                        <button type="button" class="btn btn-secondary px-3 font-size-12" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ----------------------------------------------------
    // Product Opening Stock Live Simulator
    // ----------------------------------------------------
    const productSelect = document.getElementById('product_id');
    const productQtyInput = document.getElementById('product_quantity');
    const productUnitBadge = document.getElementById('product_unit_badge');
    const previewProductName = document.getElementById('preview_product_name');
    const previewCurrentStock = document.getElementById('preview_current_stock');
    const previewAddingStock = document.getElementById('preview_adding_stock');
    const previewProductPrice = document.getElementById('preview_product_price');
    const previewProductValuation = document.getElementById('preview_product_valuation');
    const previewProjectedStock = document.getElementById('preview_projected_stock');

    function updateProductSimulator() {
        if (!productSelect || !productSelect.value) {
            previewProductName.textContent = 'No product selected';
            previewCurrentStock.textContent = '—';
            previewAddingStock.textContent = '—';
            if (previewProductPrice) previewProductPrice.textContent = '—';
            if (previewProductValuation) previewProductValuation.textContent = '—';
            previewProjectedStock.textContent = '—';
            productUnitBadge.textContent = 'Units';
            return;
        }

        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const name = selectedOption.getAttribute('data-name') || 'Selected Product';
        const currentStock = parseFloat(selectedOption.getAttribute('data-stock') || 0);
        const unit = selectedOption.getAttribute('data-unit') || 'Units';
        const price = parseFloat(selectedOption.getAttribute('data-price') || 0);
        const addingQty = parseFloat(productQtyInput.value || 0);
        const projected = currentStock + (isNaN(addingQty) ? 0 : addingQty);
        const addedVal = (isNaN(addingQty) ? 0 : addingQty) * price;

        productUnitBadge.textContent = unit;
        previewProductName.textContent = name;
        previewCurrentStock.textContent = currentStock.toLocaleString() + ' ' + unit;
        previewAddingStock.textContent = (isNaN(addingQty) ? 0 : addingQty).toLocaleString() + ' ' + unit;
        if (previewProductPrice) previewProductPrice.textContent = '₦' + price.toLocaleString(undefined, {minimumFractionDigits: 2});
        if (previewProductValuation) previewProductValuation.textContent = '₦' + addedVal.toLocaleString(undefined, {minimumFractionDigits: 2});
        previewProjectedStock.textContent = projected.toLocaleString() + ' ' + unit;
    }

    if (productSelect) productSelect.addEventListener('change', updateProductSimulator);
    if (productQtyInput) productQtyInput.addEventListener('input', updateProductSimulator);

    // ----------------------------------------------------
    // Ingredient Opening Stock Live Costing Simulator
    // ----------------------------------------------------
    const ingredientSelect = document.getElementById('ingredient_id');
    const ingredientQtyInput = document.getElementById('ingredient_quantity');
    const ingredientValInput = document.getElementById('ingredient_total_value');
    const ingredientUnitBadge = document.getElementById('ingredient_unit_badge');
    const ingredientUnitHelp = document.getElementById('ingredient_unit_help');
    const previewIngName = document.getElementById('preview_ingredient_name');
    const previewIngQty = document.getElementById('preview_ing_quantity');
    const previewIngVal = document.getElementById('preview_ing_value');
    const previewCalculatedCost = document.getElementById('preview_calculated_unit_cost');

    function updateIngredientSimulator() {
        if (!ingredientSelect || !ingredientSelect.value) {
            previewIngName.textContent = 'No ingredient selected';
            previewIngQty.textContent = '—';
            previewIngVal.textContent = '—';
            previewCalculatedCost.textContent = '₦0.00 / unit';
            ingredientUnitBadge.textContent = 'Base Units';
            ingredientUnitHelp.textContent = 'Select an ingredient first to see its base unit.';
            return;
        }

        const selectedOption = ingredientSelect.options[ingredientSelect.selectedIndex];
        const name = selectedOption.getAttribute('data-name') || 'Ingredient';
        const unit = selectedOption.getAttribute('data-unit') || 'unit';
        const unitName = selectedOption.getAttribute('data-unitname') || 'Base Unit';
        const qty = parseFloat(ingredientQtyInput.value || 0);
        const totalVal = parseFloat(ingredientValInput.value || 0);
        const unitCost = qty > 0 && totalVal > 0 ? (totalVal / qty) : 0;

        ingredientUnitBadge.textContent = unit;
        ingredientUnitHelp.innerHTML = 'Enter the quantity in <strong>' + unit + '</strong> (' + unitName + '), the base measurement standard.';
        previewIngName.textContent = name;
        previewIngQty.textContent = (isNaN(qty) ? 0 : qty).toLocaleString() + ' ' + unit;
        previewIngVal.textContent = '₦' + (isNaN(totalVal) ? 0 : totalVal).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        previewCalculatedCost.textContent = '₦' + unitCost.toLocaleString(undefined, {minimumFractionDigits: 4, maximumFractionDigits: 4}) + ' / ' + unit;
    }

    if (ingredientSelect) ingredientSelect.addEventListener('change', updateIngredientSimulator);
    if (ingredientQtyInput) ingredientQtyInput.addEventListener('input', updateIngredientSimulator);
    if (ingredientValInput) ingredientValInput.addEventListener('input', updateIngredientSimulator);
});
</script>
@endsection
