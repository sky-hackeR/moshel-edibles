@extends('admin.layout.dashboard')

@section('content')
<style>
    /* ==========================================================================
       MOSHEL EXECUTIVE RECIPE MANAGEMENT STYLES
       ========================================================================== */
    .recipe-kpi-card {
        border-radius: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .recipe-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06) !important;
    }
    .recipe-icon-avatar {
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
    $totalRecipes = $recipes->count();
    $activeRecipes = $recipes->where('is_active', true)->count();

    // Products missing recipes:
    $productsWithRecipe = $recipes->pluck('product_id')->filter()->unique();
    $unconfiguredProductsCount = $products->whereNotIn('id', $productsWithRecipe)->count();

    // Unique ingredients linked across all recipes:
    $uniqueIngredientIds = collect();
    $recipes->each(function($rec) use (&$uniqueIngredientIds) {
        $uniqueIngredientIds = $uniqueIngredientIds->merge($rec->items->pluck('ingredient_id'));
    });
    $totalUniqueIngredients = $uniqueIngredientIds->unique()->count();

    // Average BOM line items per recipe:
    $avgItems = $totalRecipes > 0 ? round($recipes->sum(function($r) { return $r->items->count(); }) / $totalRecipes, 1) : 0;
@endphp

{{-- =====================================================
     BREADCRUMB & PAGE HEADER
     ===================================================== --}}
<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between pb-2">
            <div>
                <h4 class="mb-sm-0 font-size-18 fw-bold">Recipe Management</h4>
                <ol class="breadcrumb m-0 mt-1">
                    <li class="breadcrumb-item active">Recipe Formulations</li>
                </ol>
            </div>
            <div class="d-flex align-items-center flex-wrap gap-2">
                <button type="button" class="btn btn-primary rounded-pill px-3 py-1.5 font-size-12 fw-medium shadow-sm" data-bs-toggle="modal" data-bs-target="#addRecipe">
                    <i class="bx bx-plus me-1 font-size-14"></i> Add Recipe
                </button>
                <a href="{{ route('products') }}" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 font-size-12 fw-medium">
                    <i class="bx bx-package me-1"></i> Manage Products
                </a>
                <a href="{{ route('ingredients') }}" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 font-size-12 fw-medium">
                    <i class="bx bx-food-menu me-1"></i> Ingredients Catalog
                </a>
            </div>
        </div>
    </div>
</div>

{{-- =====================================================
     EXECUTIVE KPI STATS ROW
     ===================================================== --}}
<div class="row g-3 mb-4">
    {{-- Card 1: Configured Formulations --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 recipe-kpi-card" style="border-left: 4px solid #059669 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Recipe Formulations</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ $totalRecipes }}</h4>
                        <small class="text-success font-size-11 fw-semibold">
                            <i class="bx bx-check-circle me-1"></i>{{ $activeRecipes }} Active Production Formulas
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-success-subtle text-success">
                            <i class="bx bx-book-open"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Catalog Coverage --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 recipe-kpi-card" style="border-left: 4px solid {{ $unconfiguredProductsCount > 0 ? '#f59e0b' : '#0284c7' }} !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Unformulated Products</p>
                        <h4 class="mb-0 fw-bold {{ $unconfiguredProductsCount > 0 ? 'text-warning' : 'text-dark' }}">{{ $unconfiguredProductsCount }}</h4>
                        @if($unconfiguredProductsCount > 0)
                            <small class="text-warning font-size-11 fw-semibold">
                                <i class="bx bx-error me-1"></i>Missing Production Formula
                            </small>
                        @else
                            <small class="text-success font-size-11 fw-medium">
                                <i class="bx bx-check-shield me-1"></i>100% Products Configured
                            </small>
                        @endif
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 {{ $unconfiguredProductsCount > 0 ? 'bg-warning-subtle text-warning' : 'bg-info-subtle text-info' }}">
                            <i class="bx bx-check-shield"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 3: Raw Materials Linked --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 recipe-kpi-card" style="border-left: 4px solid #4f46e5 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Raw Materials Linked</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ $totalUniqueIngredients }}</h4>
                        <small class="text-primary font-size-11 fw-medium">
                            <i class="bx bx-layer me-1"></i>Active Recipe Ingredients
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-primary-subtle text-primary">
                            <i class="bx bx-layer"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 4: Average BOM Complexity --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 recipe-kpi-card" style="border-left: 4px solid #0284c7 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">BOM Complexity</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ $avgItems }} <span class="font-size-12 fw-normal text-muted">items</span></h4>
                        <small class="text-info font-size-11 fw-semibold">
                            <i class="mdi mdi-bowl-mix-outline me-1"></i>Avg Inputs per Formula
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-info-subtle text-info">
                            <i class="mdi mdi-bowl-mix-outline"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- =====================================================
     MAIN RECIPES DIRECTORY CARD & TABLE
     ===================================================== --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-white border-bottom py-3 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h5 class="card-title mb-0 fw-bold text-dark">
                <i class="bx bx-book-content me-1 text-primary"></i> Master Recipe Formulations
            </h5>
            <small class="text-muted">Standard bill of materials (BOM), ingredient ratios, and production cost estimates.</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill bg-light text-dark border px-3 py-1 font-size-11">
                {{ $totalRecipes }} {{ Str::plural('Recipe', $totalRecipes) }} Registered
            </span>
        </div>
    </div>

    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-hover align-middle dt-responsive nowrap w-100 font-size-13 mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">S/N</th>
                        <th style="min-width: 220px;">Target Product</th>
                        <th style="min-width: 180px;">Recipe Formula</th>
                        <th style="min-width: 220px;">Bill of Materials (BOM)</th>
                        <th style="min-width: 150px;">Est. Material Cost</th>
                        <th style="min-width: 110px;">Status</th>
                        <th class="text-center" style="width: 110px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recipes as $recipe)
                        @php
                            $itemsCount = $recipe->items->count();
                            $topItems = $recipe->items->take(2);
                            $estCost = $recipe->items->sum(function($item) {
                                return ((float)$item->base_quantity) * ((float)($item->ingredient->inventory->average_cost ?? 0));
                            });
                            $sellingPrice = (float)($recipe->product->selling_price ?? 0);
                        @endphp
                        <tr>
                            {{-- S/N --}}
                            <td class="text-center text-muted fw-semibold">
                                {{ $loop->iteration }}
                            </td>

                            {{-- TARGET PRODUCT --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="recipe-icon-avatar me-2 shadow-sm">
                                        {{ strtoupper(substr($recipe->product->name ?? 'P', 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="javascript:void(0);"
                                           class="fw-bold text-dark text-decoration-none font-size-13 d-block"
                                           data-bs-toggle="modal"
                                           data-bs-target="#viewRecipe{{ $recipe->id }}">
                                            {{ $recipe->product->name ?? 'Unlinked Product' }}
                                        </a>
                                        <small class="text-muted font-size-11">
                                            Retail: <span class="fw-semibold text-dark">₦{{ number_format($sellingPrice, 2) }}</span> / {{ $recipe->product->sales_unit ?? 'unit' }}
                                        </small>
                                    </div>
                                </div>
                            </td>

                            {{-- RECIPE NAME & NOTE --}}
                            <td>
                                <div class="fw-bold text-dark font-size-13">
                                    {{ $recipe->name }}
                                </div>
                                @if(!empty($recipe->note))
                                    <small class="text-muted text-truncate d-block" style="max-width: 180px;" title="{{ $recipe->note }}">
                                        <i class="bx bx-note me-1"></i>{{ Str::limit($recipe->note, 30) }}
                                    </small>
                                @else
                                    <small class="text-muted font-size-11">Standard Batch Formula</small>
                                @endif
                            </td>

                            {{-- BILL OF MATERIALS (BOM) --}}
                            <td>
                                <div class="d-flex align-items-center gap-1 mb-1">
                                    <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5 font-size-11">
                                        <i class="mdi mdi-flask-outline me-1"></i>{{ $itemsCount }} {{ Str::plural('ingredient', $itemsCount) }}
                                    </span>
                                </div>
                                <div class="d-flex flex-wrap gap-1 align-items-center">
                                    @foreach($topItems as $item)
                                        <span class="badge rounded-pill bg-light text-dark border px-2 py-0.5 font-size-10">
                                            {{ $item->ingredient->name ?? 'Item' }}: {{ (float)$item->quantity }} {{ $item->unit->symbol ?? '' }}
                                        </span>
                                    @endforeach
                                    @if($itemsCount > 2)
                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary font-size-10 px-1.5 py-0.5">
                                            +{{ $itemsCount - 2 }} more
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- ESTIMATED MATERIAL COST --}}
                            <td>
                                <div class="fw-bold font-size-13 {{ $estCost > 0 ? 'text-dark' : 'text-muted' }}">
                                    ₦{{ number_format($estCost, 2) }}
                                </div>
                                @if($sellingPrice > $estCost && $estCost > 0)
                                    @php $margin = round((($sellingPrice - $estCost) / $sellingPrice) * 100, 1); @endphp
                                    <small class="text-success font-size-11 fw-medium">
                                        <i class="bx bx-trending-up me-1"></i>{{ $margin }}% est. margin
                                    </small>
                                @elseif($estCost > 0)
                                    <small class="text-muted font-size-11">Cost per batch</small>
                                @else
                                    <small class="text-muted font-size-11">Costing uncalculated</small>
                                @endif
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @if($recipe->is_active)
                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-2.5 py-1 font-size-11">
                                        <i class="bx bx-check-circle me-1"></i>Active
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 font-size-11">
                                        <i class="bx bx-x-circle me-1"></i>Inactive
                                    </span>
                                @endif
                            </td>

                            {{-- ACTIONS --}}
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <button type="button"
                                            class="btn btn-sm btn-soft-primary rounded-circle p-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewRecipe{{ $recipe->id }}"
                                            title="View Formulation">
                                        <i class="mdi mdi-eye-outline font-size-15"></i>
                                    </button>

                                    <button type="button"
                                            class="btn btn-sm btn-soft-info rounded-circle p-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editRecipe{{ $recipe->id }}"
                                            title="Edit Recipe">
                                        <i class="mdi mdi-pencil-outline font-size-15"></i>
                                    </button>

                                    <button type="button"
                                            class="btn btn-sm btn-soft-danger rounded-circle p-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteRecipe{{ $recipe->id }}"
                                            title="Delete Recipe">
                                        <i class="mdi mdi-delete-outline font-size-15"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bx bx-book-open font-size-24 d-block mb-1 text-muted"></i>
                                No recipe formulations registered yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- =====================================================
     VIEW RECIPE SPECIFICATIONS MODALS
     ===================================================== --}}
@foreach($recipes as $recipe)
    @php
        $estCost = $recipe->items->sum(function($item) {
            return ((float)$item->base_quantity) * ((float)($item->ingredient->inventory->average_cost ?? 0));
        });
        $sellingPrice = (float)($recipe->product->selling_price ?? 0);
    @endphp
    <div class="modal fade" id="viewRecipe{{ $recipe->id }}" tabindex="-1" aria-labelledby="viewRecipeTitle{{ $recipe->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                {{-- Modal Header --}}
                <div class="modal-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="viewRecipeTitle{{ $recipe->id }}">
                            <i class="bx bx-book-open me-1 text-primary"></i> Recipe Formulation Details
                        </h5>
                        <small class="text-muted">Formula: {{ $recipe->name }}</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    {{-- Hero Summary Card --}}
                    <div class="p-3 bg-light rounded-3 border mb-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="recipe-icon-avatar me-3" style="width: 48px; height: 48px; font-size: 18px;">
                                {{ strtoupper(substr($recipe->product->name ?? 'P', 0, 1)) }}
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0 font-size-16">{{ $recipe->product->name ?? 'Product' }}</h5>
                                <span class="text-muted font-size-12">Formula Name: <strong>{{ $recipe->name }}</strong></span>
                            </div>
                        </div>
                        <div>
                            @if($recipe->is_active)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 font-size-11">
                                    <i class="mdi mdi-check-circle-outline me-1"></i> Active Formula
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 font-size-11">
                                    <i class="mdi mdi-block-helper me-1"></i> Inactive
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Metrics Grid --}}
                    <div class="row g-2 mb-4">
                        <div class="col-sm-6 col-md-3">
                            <div class="p-2.5 bg-light rounded-3 border h-100 text-center">
                                <small class="text-muted font-size-11 text-uppercase fw-semibold d-block">Retail Price</small>
                                <span class="fw-bold text-dark font-size-14">
                                    ₦{{ number_format($sellingPrice, 2) }}
                                </span>
                                <small class="text-muted font-size-10 d-block">/ {{ $recipe->product->sales_unit ?? 'unit' }}</small>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="p-2.5 bg-light rounded-3 border h-100 text-center">
                                <small class="text-muted font-size-11 text-uppercase fw-semibold d-block">Est. Material Cost</small>
                                <span class="fw-bold text-dark font-size-14">
                                    ₦{{ number_format($estCost, 2) }}
                                </span>
                                <small class="text-muted font-size-10 d-block">Based on avg costing</small>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="p-2.5 bg-success-subtle rounded-3 border border-success-subtle h-100 text-center">
                                <small class="text-success font-size-11 text-uppercase fw-bold d-block">Est. Gross Margin</small>
                                @if($sellingPrice > $estCost && $estCost > 0)
                                    @php $profit = $sellingPrice - $estCost; $margin = round(($profit / $sellingPrice) * 100, 1); @endphp
                                    <span class="fw-bold text-success font-size-14">
                                        ₦{{ number_format($profit, 2) }}
                                    </span>
                                    <small class="text-success font-size-10 d-block fw-semibold">{{ $margin }}% margin</small>
                                @else
                                    <span class="fw-bold text-muted font-size-13">—</span>
                                    <small class="text-muted font-size-10 d-block">Awaiting costs</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="p-2.5 bg-light rounded-3 border h-100 text-center">
                                <small class="text-muted font-size-11 text-uppercase fw-semibold d-block">BOM Components</small>
                                <span class="fw-bold text-primary font-size-14">
                                    {{ $recipe->items->count() }} {{ Str::plural('Item', $recipe->items->count()) }}
                                </span>
                                <small class="text-muted font-size-10 d-block">Raw material inputs</small>
                            </div>
                        </div>
                    </div>

                    {{-- Preparation Notes if available --}}
                    @if(!empty($recipe->note))
                        <div class="alert alert-light border mb-4 p-2.5 font-size-12">
                            <i class="bx bx-info-circle me-1 text-primary"></i><strong>Preparation Notes:</strong> {!! nl2br(e($recipe->note)) !!}
                        </div>
                    @endif

                    {{-- Detailed BOM Table --}}
                    <label class="text-muted small text-uppercase fw-bold mb-2">Ingredients Composition (Bill of Materials)</label>
                    <div class="table-responsive rounded border mb-0">
                        <table class="table table-hover align-middle font-size-12 mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width: 180px;">Ingredient</th>
                                    <th class="text-center" style="min-width: 110px;">Recipe Quantity</th>
                                    <th class="text-center" style="min-width: 120px;">Stored Base Quantity</th>
                                    <th class="text-end" style="min-width: 110px;">Avg Unit Cost</th>
                                    <th class="text-end" style="min-width: 120px;">Est. Line Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recipe->items as $item)
                                    @php
                                        $avgCost = (float)($item->ingredient->inventory->average_cost ?? 0);
                                        $lineCost = ((float)$item->base_quantity) * $avgCost;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->ingredient->name ?? 'Raw Material' }}</div>
                                            <small class="text-muted font-monospace font-size-11">
                                                Base: {{ $item->ingredient->baseUnit->symbol ?? 'unit' }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-bold text-dark">{{ (float)$item->quantity }}</span>
                                            <span class="badge bg-light text-dark border ms-1">{{ $item->unit->symbol ?? '' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-primary fw-semibold">{{ number_format($item->base_quantity, 2) }}</span>
                                            <span class="text-muted font-size-11">{{ $item->unit->base_unit ?? $item->ingredient->baseUnit->symbol ?? '' }}</span>
                                        </td>
                                        <td class="text-end text-muted font-size-12">
                                            ₦{{ number_format($avgCost, 2) }}
                                        </td>
                                        <td class="text-end fw-bold text-dark font-size-13">
                                            ₦{{ number_format($lineCost, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="4" class="text-end fw-bold font-size-12">Total Est. Material Cost:</th>
                                    <th class="text-end fw-bold text-dark font-size-14">
                                        ₦{{ number_format($estCost, 2) }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="modal-footer bg-light border-top px-4 py-2.5 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 font-size-12" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary rounded-pill px-4 font-size-12 fw-medium shadow-sm" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#editRecipe{{ $recipe->id }}">
                        <i class="mdi mdi-pencil me-1"></i> Edit Formulation
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- EDIT RECIPE MODAL --}}
    <div class="modal fade" id="editRecipe{{ $recipe->id }}" tabindex="-1" aria-labelledby="editRecipeTitle{{ $recipe->id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <form method="POST" action="{{ route('updateRecipe') }}" style="width: 100%">
                @csrf
                <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                    {{-- Modal Header --}}
                    <div class="modal-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0" id="editRecipeTitle{{ $recipe->id }}">
                                <i class="bx bx-edit me-1 text-primary"></i> Edit Recipe: {{ $recipe->product->name ?? 'Product' }}
                            </h5>
                            <small class="text-muted">Modify formulation name, batch notes, or raw material proportions.</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold font-size-12 text-dark mb-1">Recipe Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-tag"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0 font-size-13" value="{{ $recipe->name }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold font-size-12 text-dark mb-1">Status</label>
                                <div class="p-2.5 bg-light rounded-3 border">
                                    <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-between p-0">
                                        <label class="form-check-label fw-bold text-dark font-size-13 mb-0" for="editActive{{ $recipe->id }}">Active Formula</label>
                                        <input class="form-check-input ms-3 float-none" type="checkbox" name="is_active" id="editActive{{ $recipe->id }}" {{ $recipe->is_active ? 'checked' : '' }} style="cursor: pointer; width: 36px; height: 20px;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold font-size-12 text-dark mb-1">Preparation / Batch Notes</label>
                                <textarea name="note" class="form-control font-size-13" rows="2" placeholder="Optional mixing instructions, bake times, or notes...">{{ $recipe->note }}</textarea>
                            </div>
                        </div>

                        {{-- Composition Table --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold text-dark font-size-13 mb-0">Ingredients Composition (BOM)</h6>
                            <button type="button" class="btn btn-soft-primary btn-sm rounded-pill px-3" onclick="addRecipeRow('editRecipeItems{{ $recipe->id }}')">
                                <i class="bx bx-plus me-1"></i> Add Ingredient
                            </button>
                        </div>

                        <div class="table-responsive rounded border mb-3">
                            <table class="table table-bordered table-hover align-middle mb-0 font-size-13">
                                <thead class="table-light">
                                    <tr>
                                        <th style="min-width: 280px;">Ingredient / Raw Material <span class="text-danger">*</span></th>
                                        <th style="min-width: 140px;">Quantity <span class="text-danger">*</span></th>
                                        <th style="min-width: 160px;">Recipe Unit <span class="text-danger">*</span></th>
                                        <th class="text-center" style="width: 60px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="editRecipeItems{{ $recipe->id }}">
                                    @foreach($recipe->items as $index => $item)
                                    <tr class="table-line-item-row">
                                        <td>
                                            <select name="items[{{ $index }}][ingredient_id]" class="form-select font-size-13" required>
                                                @foreach($ingredients as $ingredient)
                                                    <option value="{{ $ingredient->id }}" {{ $ingredient->id == $item->ingredient_id ? 'selected' : '' }}>
                                                        {{ $ingredient->name }} (Base: {{ $ingredient->baseUnit->symbol ?? 'unit' }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" step="0.001" min="0.001" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" class="form-control font-size-13" required>
                                        </td>
                                        <td>
                                            <select name="items[{{ $index }}][unit_id]" class="form-select font-size-13" required>
                                                @foreach($units as $unit)
                                                    <option value="{{ $unit->id }}" {{ $unit->id == $item->unit_id ? 'selected' : '' }}>
                                                        {{ $unit->name }} ({{ $unit->symbol }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()" title="Remove item">
                                                <i class="mdi mdi-trash-can-outline"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="modal-footer bg-light border-top px-4 py-2.5 d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary rounded-pill px-4 font-size-12" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 font-size-12 fw-medium shadow-sm">
                            <i class="bx bx-check-double me-1 font-size-14"></i> Save Recipe Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- DELETE RECIPE MODAL --}}
    <div class="modal fade" id="deleteRecipe{{ $recipe->id }}" tabindex="-1" aria-labelledby="deleteRecipeTitle{{ $recipe->id }}" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <form method="POST" action="{{ route('deleteRecipe') }}" style="width: 100%">
                @csrf
                <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                    <div class="modal-body text-center p-4">
                        <div class="avatar-md mx-auto mb-3">
                            <span class="avatar-title rounded-circle bg-danger-subtle text-danger font-size-28">
                                <i class="bx bx-trash"></i>
                            </span>
                        </div>
                        <h5 class="fw-bold text-dark font-size-16 mb-2">Delete Recipe Formulation?</h5>
                        <p class="text-muted font-size-13 mb-3">
                            You are about to remove the formulation for <strong>{{ $recipe->product->name ?? 'Product' }}</strong>. An automated notification will alert all administrators.
                        </p>
                        <div class="d-flex justify-content-center gap-2 mt-4">
                            <button type="button" class="btn btn-secondary rounded-pill px-4 font-size-12" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger rounded-pill px-4 font-size-12 fw-medium shadow-sm">
                                <i class="bx bx-trash me-1"></i> Confirm Deletion
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

{{-- =====================================================
     ADD NEW RECIPE MODAL
     ===================================================== --}}
<div class="modal fade" id="addRecipe" tabindex="-1" aria-labelledby="addRecipeTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <form method="POST" action="{{ route('newRecipe') }}" style="width: 100%">
            @csrf
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                {{-- Header --}}
                <div class="modal-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="addRecipeTitle">
                            <i class="bx bx-plus-circle me-1 text-primary"></i> Create New Recipe Formulation
                        </h5>
                        <small class="text-muted">Link a commercial product to its production bill of materials.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold font-size-12 text-dark mb-1">Target Product <span class="text-danger">*</span></label>
                            <select name="product_id" class="form-select font-size-13" required>
                                <option value="">-- Choose unconfigured product --</option>
                                @foreach($products as $product)
                                    @if(!$product->recipe)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }} (Sales Unit: {{ $product->sales_unit }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <small class="text-muted font-size-11 mt-1 d-block">Each commercial product can have one master recipe formulation.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold font-size-12 text-dark mb-1">Recipe Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-tag"></i></span>
                                <input type="text" name="name" class="form-control border-start-0 font-size-13" placeholder="e.g. Standard Batch Formula" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold font-size-12 text-dark mb-1">Preparation / Batch Notes</label>
                            <textarea name="note" class="form-control font-size-13" rows="2" placeholder="Optional mixing instructions, oven settings, or production notes..."></textarea>
                        </div>
                    </div>

                    {{-- Composition Table --}}
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="fw-bold text-dark font-size-13 mb-0">Ingredients Composition (BOM) <span class="text-danger">*</span></h6>
                        <button type="button" class="btn btn-soft-primary btn-sm rounded-pill px-3" onclick="addRecipeRow('recipeItems')">
                            <i class="bx bx-plus me-1"></i> Add Another Ingredient
                        </button>
                    </div>

                    <div class="table-responsive rounded border mb-3">
                        <table class="table table-bordered table-hover align-middle mb-0 font-size-13">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width: 280px;">Ingredient / Raw Material <span class="text-danger">*</span></th>
                                    <th style="min-width: 140px;">Quantity <span class="text-danger">*</span></th>
                                    <th style="min-width: 160px;">Recipe Unit <span class="text-danger">*</span></th>
                                    <th class="text-center" style="width: 60px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="recipeItems">
                                <tr class="table-line-item-row">
                                    <td>
                                        <select name="items[0][ingredient_id]" class="form-select font-size-13" required>
                                            <option value="">-- Choose Raw Material --</option>
                                            @foreach($ingredients as $ingredient)
                                                <option value="{{ $ingredient->id }}">{{ $ingredient->name }} (Base: {{ $ingredient->baseUnit->symbol ?? 'unit' }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="0.001" min="0.001" name="items[0][quantity]" class="form-control font-size-13" required placeholder="0.000">
                                    </td>
                                    <td>
                                        <select name="items[0][unit_id]" class="form-select font-size-13" required>
                                            <option value="">-- Unit --</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->symbol }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" disabled title="First ingredient cannot be removed">
                                            <i class="mdi mdi-trash-can-outline"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="modal-footer bg-light border-top px-4 py-2.5 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 font-size-12" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 font-size-12 fw-medium shadow-sm">
                        <i class="bx bx-check me-1 font-size-14"></i> Save Recipe
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Script for dynamic row addition --}}
<script>
function addRecipeRow(containerId) {
    const tbody = document.getElementById(containerId);
    if (!tbody) return;
    const index = tbody.children.length;

    const row = document.createElement('tr');
    row.className = 'table-line-item-row';
    row.innerHTML = `
        <td>
            <select name="items[${index}][ingredient_id]" class="form-select font-size-13" required>
                <option value="">-- Choose Raw Material --</option>
                @foreach($ingredients as $ingredient)
                    <option value="{{ $ingredient->id }}">{{ $ingredient->name }} (Base: {{ $ingredient->baseUnit->symbol ?? 'unit' }})</option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" step="0.001" min="0.001" name="items[${index}][quantity]" class="form-control font-size-13" required placeholder="0.000">
        </td>
        <td>
            <select name="items[${index}][unit_id]" class="form-select font-size-13" required>
                <option value="">-- Unit --</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->symbol }})</option>
                @endforeach
            </select>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()" title="Remove item">
                <i class="mdi mdi-trash-can-outline"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
}
</script>

@endsection
