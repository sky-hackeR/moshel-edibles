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
        <x-kpi-card
            label="Recipe Formulations"
            value="{{ $totalRecipes }}"
            subtitle="{{ $activeRecipes }} Active Production Formulas"
            subicon="bx bx-check-circle"
            color="success"
            borderColor="#059669"
            icon="bx bx-book-open"
            cardClass="recipe-kpi-card"
        />
    </div>

    {{-- Card 2: Catalog Coverage --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Unformulated Products"
            value="{{ $unconfiguredProductsCount }}"
            color="{{ $unconfiguredProductsCount > 0 ? 'warning' : 'info' }}"
            borderColor="{{ $unconfiguredProductsCount > 0 ? '#f59e0b' : '#0284c7' }}"
            icon="bx bx-check-shield"
            cardClass="recipe-kpi-card"
        >
            @if($unconfiguredProductsCount > 0)
                <small class="text-warning font-size-11 fw-semibold d-block mt-1">
                    <i class="bx bx-error me-1"></i>Missing Production Formula
                </small>
            @else
                <small class="text-success font-size-11 fw-medium d-block mt-1">
                    <i class="bx bx-check-shield me-1"></i>100% Products Configured
                </small>
            @endif
        </x-kpi-card>
    </div>

    {{-- Card 3: Raw Materials Linked --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Raw Materials Linked"
            value="{{ $totalUniqueIngredients }}"
            subtitle="Active Recipe Ingredients"
            subicon="bx bx-layer"
            color="indigo"
            borderColor="#4f46e5"
            icon="bx bx-layer"
            cardClass="recipe-kpi-card"
        />
    </div>

    {{-- Card 4: Average BOM Complexity --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="BOM Complexity"
            value="{{ $avgItems }} items"
            subtitle="Avg Inputs per Formula"
            subicon="mdi mdi-bowl-mix-outline"
            color="info"
            borderColor="#0284c7"
            icon="mdi mdi-bowl-mix-outline"
            cardClass="recipe-kpi-card"
        />
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
     VIEW, EDIT, DELETE & ADD RECIPE MODALS
     ===================================================== --}}
@include('admin.erp.partials.modals.recipes.recipe-modals')
@include('admin.erp.partials.modals.recipes.add-recipe-modal')

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
