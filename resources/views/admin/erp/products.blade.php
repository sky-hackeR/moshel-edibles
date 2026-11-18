@extends('admin.layout.dashboard')

@section('content')
<style>
    /* ==========================================================================
       MOSHEL EXECUTIVE PRODUCT MANAGEMENT STYLES
       ========================================================================== */
    .product-kpi-card {
        border-radius: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .product-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06) !important;
    }
    .product-icon-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
        background: #eef2ff;
        color: #4338ca;
    }
</style>

@php
    $totalProducts = $products->count();
    $activeProducts = $products->where('is_active', true)->count();
    $inStockCount = $products->where('stock_on_hand', '>', 0)->count();
    $outOfStockCount = $totalProducts - $inStockCount;
    $totalStockUnits = $products->sum('stock_on_hand');

    $totalRetailValuation = $products->sum(function($prod) {
        return ((float)$prod->stock_on_hand) * ((float)$prod->selling_price);
    });

    $withRecipeCount = $products->filter(function($prod) {
        return !is_null($prod->recipe);
    })->count();
    $withoutRecipeCount = $totalProducts - $withRecipeCount;
@endphp

{{-- =====================================================
     BREADCRUMB & PAGE HEADER
     ===================================================== --}}
<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between pb-2">
            <div>
                <h4 class="mb-sm-0 font-size-18 fw-bold">Product Management</h4>
                <ol class="breadcrumb m-0 mt-1">
                    <li class="breadcrumb-item active">Commercial Products</li>
                </ol>
            </div>
            <div class="d-flex align-items-center flex-wrap gap-2">
                <button type="button" class="btn btn-primary rounded-pill px-3 py-1.5 font-size-12 fw-medium shadow-sm" data-bs-toggle="modal" data-bs-target="#addProduct">
                    <i class="bx bx-plus me-1 font-size-14"></i> Add Product
                </button>
                <a href="{{ route('admin.products.stock') }}" class="btn btn-outline-danger rounded-pill px-3 py-1.5 font-size-12 fw-medium">
                    <i class="bx bx-error-alt me-1"></i> Stock Danger Zone
                </a>
                <a href="{{ route('openingStock') }}" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 font-size-12 fw-medium">
                    <i class="bx bx-archive-in me-1"></i> Opening Stock
                </a>
                <a href="{{ route('recipes') }}" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 font-size-12 fw-medium">
                    <i class="bx bx-book-open me-1"></i> Recipes
                </a>
            </div>
        </div>
    </div>
</div>

{{-- =====================================================
     EXECUTIVE KPI STATS ROW
     ===================================================== --}}
<div class="row g-3 mb-4">
    {{-- Card 1: Cataloged Products --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 product-kpi-card" style="border-left: 4px solid #059669 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Cataloged Products</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ $totalProducts }}</h4>
                        <small class="text-success font-size-11 fw-semibold">
                            <i class="bx bx-check-circle me-1"></i>{{ $activeProducts }} Active Commercial SKUs
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-success-subtle text-success">
                            <i class="bx bx-package"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Finished Goods Stock --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 product-kpi-card" style="border-left: 4px solid #0284c7 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Stock on Hand</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ number_format($totalStockUnits, 0) }}</h4>
                        <small class="text-info font-size-11 fw-semibold">
                            <i class="bx bx-layer me-1"></i>{{ $inStockCount }} In Stock, {{ $outOfStockCount }} Depleted
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-info-subtle text-info">
                            <i class="bx bx-layer"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 3: Retail Inventory Valuation --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 product-kpi-card" style="border-left: 4px solid #4f46e5 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div style="min-width: 0;">
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Retail Valuation</p>
                        <h4 class="mb-0 fw-bold text-dark text-truncate" title="₦{{ number_format($totalRetailValuation, 2) }}">
                            ₦{{ number_format($totalRetailValuation, 2) }}
                        </h4>
                        <small class="text-primary font-size-11 fw-medium">
                            <i class="bx bx-wallet me-1"></i>Finished Goods Retail Value
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

    {{-- Card 4: Recipe Formulations --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 product-kpi-card" style="border-left: 4px solid #f59e0b !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Recipe Formulations</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ $withRecipeCount }} / {{ $totalProducts }}</h4>
                        @if($withoutRecipeCount > 0)
                            <small class="text-warning font-size-11 fw-semibold">
                                <i class="bx bx-error me-1"></i>{{ $withoutRecipeCount }} Missing Recipe
                            </small>
                        @else
                            <small class="text-success font-size-11 fw-medium">
                                <i class="bx bx-check me-1"></i>100% Production Ready
                            </small>
                        @endif
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-warning-subtle text-warning">
                            <i class="bx bx-book-open"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- =====================================================
     MAIN PRODUCTS DIRECTORY CARD & TABLE
     ===================================================== --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-white border-bottom py-3 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h5 class="card-title mb-0 fw-bold text-dark">
                <i class="bx bx-package me-1 text-primary"></i> Commercial Products Directory
            </h5>
            <small class="text-muted">Master catalog of finished bakery products, retail pricing, stock balances, and recipe configurations.</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill bg-light text-dark border px-3 py-1 font-size-11">
                {{ $totalProducts }} {{ Str::plural('Product', $totalProducts) }} Registered
            </span>
        </div>
    </div>

    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-hover align-middle dt-responsive nowrap w-100 font-size-13 mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">S/N</th>
                        <th style="min-width: 220px;">Product Name</th>
                        <th style="min-width: 100px;">Sales Unit</th>
                        <th style="min-width: 140px;">Stock on Hand</th>
                        <th style="min-width: 130px;">Selling Price</th>
                        <th style="min-width: 140px;">Stock Valuation</th>
                        <th style="min-width: 130px;">Recipe Status</th>
                        <th class="text-center" style="width: 110px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        @php
                            $qty = (float) $product->stock_on_hand;
                            $price = (float) $product->selling_price;
                            $valuation = $qty * $price;
                        @endphp
                        <tr>
                            {{-- S/N --}}
                            <td class="text-center text-muted fw-semibold">
                                {{ $loop->iteration }}
                            </td>

                            {{-- PRODUCT NAME --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="product-icon-avatar me-2 shadow-sm">
                                        {{ strtoupper(substr($product->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="javascript:void(0);"
                                           class="fw-bold text-dark text-decoration-none font-size-13 d-block"
                                           data-bs-toggle="modal"
                                           data-bs-target="#viewProduct{{ $product->id }}">
                                            {{ $product->name }}
                                        </a>
                                        <div class="d-flex align-items-center gap-1">
                                            <small class="text-muted font-monospace font-size-11">
                                                {{ $product->slug ?: Str::slug($product->name) }}
                                            </small>
                                            @if($product->is_active)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle font-size-10 px-1.5 py-0">Active</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle font-size-10 px-1.5 py-0">Disabled</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- SALES UNIT --}}
                            <td>
                                <span class="badge rounded-pill bg-light text-dark border px-2.5 py-1 font-size-11">
                                    {{ $product->sales_unit }}
                                </span>
                            </td>

                            {{-- STOCK ON HAND --}}
                            <td>
                                @if($qty > 0)
                                    <div class="fw-bold font-size-13 text-success">
                                        {{ number_format($qty, 0) }} <span class="font-size-11 text-muted fw-normal">{{ $product->sales_unit }}</span>
                                    </div>
                                    <small class="text-success font-size-11">
                                        <i class="bx bx-check-circle me-1"></i>In Stock
                                    </small>
                                @else
                                    <div class="fw-bold font-size-13 text-danger">
                                        0 <span class="font-size-11 text-muted fw-normal">{{ $product->sales_unit }}</span>
                                    </div>
                                    <small class="text-danger font-size-11">
                                        <i class="bx bx-error-circle me-1"></i>Out of Stock
                                    </small>
                                @endif
                            </td>

                            {{-- SELLING PRICE --}}
                            <td>
                                <div class="fw-bold font-size-13 text-dark">
                                    ₦{{ number_format($price, 2) }}
                                </div>
                                <small class="text-muted font-size-11">per {{ $product->sales_unit }}</small>
                            </td>

                            {{-- STOCK VALUATION --}}
                            <td>
                                <div class="fw-bold font-size-13 {{ $valuation > 0 ? 'text-primary' : 'text-muted' }}">
                                    ₦{{ number_format($valuation, 2) }}
                                </div>
                                <small class="text-muted font-size-11">
                                    {{ $qty > 0 ? 'Retail Value' : 'Zero balance' }}
                                </small>
                            </td>

                            {{-- RECIPE STATUS --}}
                            <td>
                                @if($product->recipe)
                                    <a href="{{ route('recipes') }}" class="badge rounded-pill bg-info-subtle text-info border border-info-subtle px-2.5 py-1 font-size-11 text-decoration-none" title="View recipe formulation">
                                        <i class="mdi mdi-book-open-page-variant me-1"></i> Configured
                                    </a>
                                @else
                                    <a href="{{ route('recipes') }}" class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 font-size-11 text-decoration-none" title="Configure recipe formulation">
                                        <i class="mdi mdi-alert-circle-outline me-1"></i> No Recipe
                                    </a>
                                @endif
                            </td>

                            {{-- ACTIONS --}}
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <button type="button"
                                            class="btn btn-sm btn-soft-primary rounded-circle p-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewProduct{{ $product->id }}"
                                            title="View Specifications">
                                        <i class="mdi mdi-eye-outline font-size-15"></i>
                                    </button>

                                    <button type="button"
                                            class="btn btn-sm btn-soft-info rounded-circle p-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editProduct{{ $product->id }}"
                                            title="Edit Product">
                                        <i class="mdi mdi-pencil-outline font-size-15"></i>
                                    </button>

                                    <button type="button"
                                            class="btn btn-sm btn-soft-danger rounded-circle p-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteProduct{{ $product->id }}"
                                            title="Delete Product">
                                        <i class="mdi mdi-delete-outline font-size-15"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bx bx-package font-size-24 d-block mb-1 text-muted"></i>
                                No commercial products registered yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- =====================================================
     VIEW PRODUCT SPECIFICATIONS MODALS
     ===================================================== --}}
@foreach($products as $product)
    @php
        $qty = (float) $product->stock_on_hand;
        $price = (float) $product->selling_price;
        $val = $qty * $price;
    @endphp
    <div class="modal fade" id="viewProduct{{ $product->id }}" tabindex="-1" aria-labelledby="viewProductTitle{{ $product->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                {{-- Header --}}
                <div class="modal-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="viewProductTitle{{ $product->id }}">
                            <i class="bx bx-package me-1 text-primary"></i> Product Specifications
                        </h5>
                        <small class="text-muted">System ID: #{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    {{-- Hero Card --}}
                    <div class="p-3 bg-light rounded-3 border mb-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="product-icon-avatar me-3" style="width: 48px; height: 48px; font-size: 18px;">
                                {{ strtoupper(substr($product->name, 0, 1)) }}
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0 font-size-16">{{ $product->name }}</h5>
                                <span class="text-muted font-monospace font-size-11">{{ $product->slug ?: Str::slug($product->name) }}</span>
                            </div>
                        </div>
                        <div>
                            @if($product->is_active)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 font-size-11">
                                    <i class="mdi mdi-check-circle-outline me-1"></i> Active Item
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 font-size-11">
                                    <i class="mdi mdi-block-helper me-1"></i> Disabled
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Metrics Grid --}}
                    <div class="row g-2 mb-4">
                        <div class="col-sm-6 col-md-3">
                            <div class="p-2.5 bg-light rounded-3 border h-100 text-center">
                                <small class="text-muted font-size-11 text-uppercase fw-semibold d-block">Stock On Hand</small>
                                <span class="fw-bold font-size-14 {{ $qty > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($qty, 0) }} {{ $product->sales_unit }}
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="p-2.5 bg-light rounded-3 border h-100 text-center">
                                <small class="text-muted font-size-11 text-uppercase fw-semibold d-block">Selling Price</small>
                                <span class="fw-bold text-dark font-size-14">
                                    ₦{{ number_format($price, 2) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="p-2.5 bg-light rounded-3 border h-100 text-center">
                                <small class="text-muted font-size-11 text-uppercase fw-semibold d-block">Retail Valuation</small>
                                <span class="fw-bold text-primary font-size-14">
                                    ₦{{ number_format($val, 2) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="p-2.5 bg-light rounded-3 border h-100 text-center">
                                <small class="text-muted font-size-11 text-uppercase fw-semibold d-block">Recipe Status</small>
                                @if($product->recipe)
                                    <span class="fw-bold text-info font-size-13">
                                        <i class="mdi mdi-check-circle me-1"></i> Configured
                                    </span>
                                @else
                                    <span class="fw-bold text-warning font-size-13">
                                        <i class="mdi mdi-alert me-1"></i> Missing
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Key Details Table --}}
                    <div class="table-responsive rounded border mb-0">
                        <table class="table table-sm table-bordered align-middle font-size-12 mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted bg-light" style="width: 35%;">Sales Unit</td>
                                    <td class="fw-bold text-dark">{{ $product->sales_unit }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted bg-light">Online Storefront</td>
                                    <td>
                                        @if($product->storeProduct)
                                            <span class="badge bg-success-subtle text-success">Listed in Online Store</span>
                                            <a href="{{ route('store.product.edit', $product->slug) }}" class="ms-2 font-size-11 text-primary">Edit Store Listing</a>
                                        @else
                                            <span class="text-muted">Not listed on online storefront</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted bg-light">Registered Date</td>
                                    <td class="text-dark">{{ $product->created_at->format('M d, Y h:i A') }} ({{ $product->created_at->diffForHumans() }})</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="modal-footer bg-light border-top px-4 py-2.5 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 font-size-12" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary rounded-pill px-4 font-size-12 fw-medium shadow-sm" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#editProduct{{ $product->id }}">
                        <i class="mdi mdi-pencil me-1"></i> Edit Product
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- EDIT PRODUCT MODAL --}}
    <div class="modal fade" id="editProduct{{ $product->id }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="editProductTitle{{ $product->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" action="{{ route('updateProduct') }}" style="width: 100%">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                    {{-- Header --}}
                    <div class="modal-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0" id="editProductTitle{{ $product->id }}">
                                <i class="bx bx-edit me-1 text-primary"></i> Edit Product: {{ $product->name }}
                            </h5>
                            <small class="text-muted">Update catalog details, sales unit, or retail pricing.</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold font-size-12 text-dark mb-1">Product Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-tag"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0 font-size-13" value="{{ $product->name }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold font-size-12 text-dark mb-1">Sales Unit <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-cube"></i></span>
                                    <input type="text" name="sales_unit" class="form-control border-start-0 font-size-13" value="{{ $product->sales_unit }}" placeholder="Loaf / Pcs / Box" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold font-size-12 text-dark mb-1">Selling Price (₦) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light font-size-13">₦</span>
                                <input type="number" step="0.01" min="0" name="selling_price" value="{{ $product->selling_price }}" class="form-control font-size-13" required>
                            </div>
                            <small class="text-muted font-size-11 mt-1 d-block">
                                <i class="bx bx-info-circle me-1"></i>Any price changes will automatically dispatch an email notice to all administrators.
                            </small>
                        </div>

                        <div class="p-3 bg-light rounded-3 border">
                            <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-between p-0">
                                <div>
                                    <label class="form-check-label fw-bold text-dark d-block font-size-13 mb-0" for="editActive{{ $product->id }}">
                                        Mark as Active
                                    </label>
                                    <small class="text-muted font-size-11">Active products appear on POS terminals and storefronts for commercial sales.</small>
                                </div>
                                <input class="form-check-input ms-3 float-none" type="checkbox" name="is_active" id="editActive{{ $product->id }}" {{ $product->is_active ? 'checked' : '' }} style="cursor: pointer; width: 36px; height: 20px;">
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="modal-footer bg-light border-top px-4 py-2.5 d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary rounded-pill px-4 font-size-12" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 font-size-12 fw-medium shadow-sm">
                            <i class="bx bx-check me-1 font-size-14"></i> Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- DELETE PRODUCT MODAL --}}
    <div class="modal fade" id="deleteProduct{{ $product->id }}" tabindex="-1" aria-labelledby="deleteProductTitle{{ $product->id }}" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <form method="POST" action="{{ route('deleteProduct') }}" style="width: 100%">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                    <div class="modal-body text-center p-4">
                        <div class="avatar-md mx-auto mb-3">
                            <span class="avatar-title rounded-circle bg-danger-subtle text-danger font-size-28">
                                <i class="bx bx-trash"></i>
                            </span>
                        </div>
                        <h5 class="fw-bold text-dark font-size-16 mb-2">Delete Commercial Product?</h5>
                        <p class="text-muted font-size-13 mb-3">
                            You are about to permanently delete <strong>{{ $product->name }}</strong> from the commercial catalog.
                        </p>

                        @if($product->recipe)
                            <div class="alert alert-danger border-0 font-size-12 text-start mb-4 py-2.5 px-3">
                                <i class="bx bx-error-circle me-1 font-size-15 align-middle"></i>
                                <strong>Deletion Blocked:</strong> This product has an active recipe attached. You must remove or detach the recipe formulation before deleting this product.
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="button" class="btn btn-secondary rounded-pill px-4 font-size-12" data-bs-dismiss="modal">Close</button>
                            </div>
                        @else
                            <div class="d-flex justify-content-center gap-2 mt-4">
                                <button type="button" class="btn btn-secondary rounded-pill px-4 font-size-12" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger rounded-pill px-4 font-size-12 fw-medium shadow-sm">
                                    <i class="bx bx-trash me-1"></i> Confirm Deletion
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

{{-- =====================================================
     ADD NEW PRODUCT MODAL
     ===================================================== --}}
<div class="modal fade" id="addProduct" data-bs-backdrop="static" tabindex="-1" aria-labelledby="addProductTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="{{ route('newProduct') }}" style="width: 100%">
            @csrf
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                {{-- Header --}}
                <div class="modal-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="addProductTitle">
                            <i class="bx bx-plus-circle me-1 text-primary"></i> Add New Product
                        </h5>
                        <small class="text-muted">Register a new commercial product with retail pricing and sales units.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold font-size-12 text-dark mb-1">Product Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-tag"></i></span>
                                <input type="text" name="name" class="form-control border-start-0 font-size-13" placeholder="e.g. Vanilla Celebration Cake, Sliced Bread" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold font-size-12 text-dark mb-1">Sales Unit <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-cube"></i></span>
                                <input type="text" name="sales_unit" class="form-control border-start-0 font-size-13" placeholder="e.g. Loaf, Pcs, Box, Pack" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold font-size-12 text-dark mb-1">Selling Price (₦) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light font-size-13">₦</span>
                            <input type="number" step="0.01" min="0" name="selling_price" class="form-control font-size-13" placeholder="0.00" required>
                        </div>
                        <small class="text-muted font-size-11 mt-1 d-block">Retail price per sales unit for POS cashiering and online ordering.</small>
                    </div>

                    <div class="p-3 bg-light rounded-3 border">
                        <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-between p-0">
                            <div>
                                <label class="form-check-label fw-bold text-dark d-block font-size-13 mb-0" for="newActive">
                                    Set as Active
                                </label>
                                <small class="text-muted font-size-11">Active products can be immediately cashiered via POS and linked to production batches.</small>
                            </div>
                            <input class="form-check-input ms-3 float-none" type="checkbox" name="is_active" id="newActive" checked style="cursor: pointer; width: 36px; height: 20px;">
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="modal-footer bg-light border-top px-4 py-2.5 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 font-size-12" data-bs-dismiss="modal">Discard</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 font-size-12 fw-medium shadow-sm">
                        <i class="bx bx-plus me-1 font-size-14"></i> Create Product
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
