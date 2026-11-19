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
        <x-kpi-card 
            label="Cataloged Products" 
            :value="$totalProducts" 
            color="success" 
            border-color="#059669" 
            icon="bx bx-package" 
            :subtitle="$activeProducts . ' Active Commercial SKUs'" 
            subicon="bx bx-check-circle" 
            subcolor="text-success" />
    </div>

    {{-- Card 2: Finished Goods Stock --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card 
            label="Stock on Hand" 
            :value="number_format($totalStockUnits, 0)" 
            color="info" 
            border-color="#0284c7" 
            icon="bx bx-layer" 
            :subtitle="$inStockCount . ' In Stock, ' . $outOfStockCount . ' Depleted'" 
            subicon="bx bx-layer" 
            subcolor="text-info" />
    </div>

    {{-- Card 3: Retail Inventory Valuation --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card 
            label="Retail Valuation" 
            :value="'₦' . number_format($totalRetailValuation, 2)" 
            color="primary" 
            border-color="#4f46e5" 
            icon="bx bx-wallet" 
            subtitle="Finished Goods Retail Value" 
            subicon="bx bx-wallet" 
            subcolor="text-primary" />
    </div>

    {{-- Card 4: Recipe Formulations --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card 
            label="Recipe Formulations" 
            :value="$withRecipeCount . ' / ' . $totalProducts" 
            color="warning" 
            border-color="#f59e0b" 
            icon="bx bx-book-open" 
            :subtitle="$withoutRecipeCount > 0 ? ($withoutRecipeCount . ' Missing Recipe') : '100% Production Ready'" 
            :subicon="$withoutRecipeCount > 0 ? 'bx bx-error' : 'bx bx-check'" 
            :subcolor="$withoutRecipeCount > 0 ? 'text-warning' : 'text-success'" />
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
     VIEW, EDIT, DELETE & ADD PRODUCT MODALS
     ===================================================== --}}
@include('admin.erp.partials.modals.products.product-modals')
@include('admin.erp.partials.modals.products.add-product-modal')

@endsection
