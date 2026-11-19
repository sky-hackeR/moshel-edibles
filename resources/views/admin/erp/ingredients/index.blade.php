@extends('admin.layout.dashboard')

@section('content')
<style>
    /* ==========================================================================
       MOSHEL EXECUTIVE INGREDIENT MANAGEMENT STYLES
       ========================================================================== */
    .ingredient-kpi-card {
        border-radius: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .ingredient-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06) !important;
    }
    .ingredient-icon-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
    }
    .status-indicator-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
    }
</style>

@php
    $totalIngredients = $ingredients->count();
    $activeIngredients = $ingredients->where('is_active', true)->count();
    $inactiveIngredients = $totalIngredients - $activeIngredients;

    $lowStockIngredients = $ingredients->filter(function($ing) {
        $qty = $ing->inventory ? (float) $ing->inventory->quantity : 0;
        return $qty <= $ing->effective_reorder_level;
    });
    $lowStockCount = $lowStockIngredients->count();

    $totalInventoryValue = $ingredients->sum(function($ing) {
        if (!$ing->inventory) return 0;
        return ((float) $ing->inventory->quantity) * ((float) $ing->inventory->average_cost);
    });

    $usedInRecipesCount = $ingredients->filter(function($ing) {
        return $ing->recipeItems && $ing->recipeItems->isNotEmpty();
    })->count();
@endphp

{{-- =====================================================
     BREADCRUMB & PAGE HEADER
     ===================================================== --}}
<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between pb-2">
            <div>
                <h4 class="mb-sm-0 font-size-18 fw-bold">Ingredient Management</h4>
                <ol class="breadcrumb m-0 mt-1">
                    <li class="breadcrumb-item active">Ingredients & Raw Materials</li>
                </ol>
            </div>
            <div class="d-flex align-items-center flex-wrap gap-2">
                <button type="button" class="btn btn-primary rounded-pill px-3 py-1.5 font-size-12 fw-medium shadow-sm" data-bs-toggle="modal" data-bs-target="#addIngredient">
                    <i class="bx bx-plus me-1 font-size-14"></i> Add Ingredient
                </button>
                <a href="{{ route('admin.ingredients.stock') }}" class="btn btn-outline-danger rounded-pill px-3 py-1.5 font-size-12 fw-medium">
                    <i class="bx bx-error-alt me-1"></i> Stock Danger Zone
                </a>
                <a href="{{ route('openingStock') }}" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 font-size-12 fw-medium">
                    <i class="bx bx-archive-in me-1"></i> Opening Stock
                </a>
                <a href="{{ route('inventory') }}" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 font-size-12 fw-medium">
                    <i class="bx bx-layer me-1"></i> Inventory Batches
                </a>
            </div>
        </div>
    </div>
</div>

{{-- =====================================================
     EXECUTIVE KPI STATS ROW
     ===================================================== --}}
<div class="row g-3 mb-4">
    {{-- Card 1: Total Cataloged Raw Materials --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card 
            label="Cataloged Materials" 
            :value="$totalIngredients" 
            color="success" 
            border-color="#059669" 
            icon="bx bx-food-menu" 
            :subtitle="$activeIngredients . ' Active in Catalog'" 
            subicon="bx bx-check-circle" 
            subcolor="text-success" />
    </div>

    {{-- Card 2: Reorder & Low Stock Alerts --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card 
            label="Reorder Alerts" 
            :value="$lowStockCount" 
            :color="$lowStockCount > 0 ? 'danger' : 'warning'" 
            :border-color="$lowStockCount > 0 ? '#ef4444' : '#f59e0b'" 
            icon="bx bx-bell" 
            :subtitle="$lowStockCount > 0 ? 'Restock Required' : 'All Levels Healthy'" 
            :subicon="$lowStockCount > 0 ? 'bx bx-error-circle' : 'bx bx-badge-check'" 
            :subcolor="$lowStockCount > 0 ? 'text-danger' : 'text-success'" />
    </div>

    {{-- Card 3: Estimated Stock Value --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card 
            label="Inventory Valuation" 
            :value="'₦' . number_format($totalInventoryValue, 2)" 
            color="primary" 
            border-color="#4f46e5" 
            icon="bx bx-wallet" 
            subtitle="Stock-on-Hand Value" 
            subicon="bx bx-wallet" 
            subcolor="text-primary" />
    </div>

    {{-- Card 4: Production Dependencies --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card 
            label="Recipe Formulations" 
            :value="$usedInRecipesCount" 
            color="info" 
            border-color="#0284c7" 
            icon="bx bx-layer" 
            subtitle="Used in Active Recipes" 
            subicon="bx bx-bowl-rice" 
            subcolor="text-info" />
    </div>
</div>

{{-- =====================================================
     MAIN INGREDIENTS DIRECTORY CARD
     ===================================================== --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-white border-bottom py-3 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h5 class="card-title mb-0 fw-bold text-dark">
                <i class="bx bx-food-menu me-1 text-primary"></i> Raw Materials Catalog
            </h5>
            <small class="text-muted">Master registry of production inputs, base unit conversions, and reorder levels.</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill bg-light text-dark border px-3 py-1 font-size-11">
                {{ $totalIngredients }} {{ Str::plural('Ingredient', $totalIngredients) }} Registered
            </span>
        </div>
    </div>

    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-hover align-middle dt-responsive nowrap w-100 font-size-13 mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">S/N</th>
                        <th style="min-width: 200px;">Ingredient Name</th>
                        <th style="min-width: 140px;">Base Unit</th>
                        <th style="min-width: 140px;">Stock on Hand</th>
                        <th style="min-width: 140px;">Reorder Level</th>
                        <th style="min-width: 120px;">Recipe Usage</th>
                        <th style="min-width: 110px;">Status</th>
                        <th class="text-center" style="width: 110px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ingredients as $ingredient)
                        @php
                            $qty = $ingredient->inventory ? (float) $ingredient->inventory->quantity : 0;
                            $cost = $ingredient->inventory ? (float) $ingredient->inventory->average_cost : 0;
                            $stockValue = $qty * $cost;
                            $threshold = $ingredient->effective_reorder_level;
                            $isLowStock = $qty <= $threshold;
                            $recipeCount = $ingredient->recipeItems ? $ingredient->recipeItems->count() : 0;
                            $unitSymbol = $ingredient->baseUnit->symbol ?? 'unit';
                            $unitName = $ingredient->baseUnit->name ?? 'Base Unit';
                            $unitType = $ingredient->baseUnit->unit_type ?? 'dimension';
                            $typeIcon = match($unitType) {
                                'mass' => 'mdi-scale-balance',
                                'volume' => 'mdi-waves',
                                'count' => 'mdi-archive',
                                default => 'mdi-exclamation'
                            };
                            $avatarColor = match($unitType) {
                                'mass' => 'background: #fef3c7; color: #b45309;',
                                'volume' => 'background: #e0f2fe; color: #0369a1;',
                                'count' => 'background: #dcfce7; color: #15803d;',
                                default => 'background: #e0e7ff; color: #4338ca;'
                            };
                        @endphp
                        <tr>
                            {{-- S/N --}}
                            <td class="text-center text-muted fw-semibold">
                                {{ $loop->iteration }}
                            </td>

                            {{-- INGREDIENT NAME & SLUG --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="ingredient-icon-avatar me-2 shadow-sm" style="{{ $avatarColor }}">
                                        {{ strtoupper(substr($ingredient->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="javascript:void(0);"
                                           class="fw-bold text-dark text-decoration-none d-block font-size-13"
                                           data-bs-toggle="modal"
                                           data-bs-target="#editIngredient{{ $ingredient->id }}">
                                            {{ $ingredient->name }}
                                        </a>
                                        <small class="text-muted font-monospace font-size-11">
                                            {{ $ingredient->slug ?: Str::slug($ingredient->name) }}
                                        </small>
                                    </div>
                                </div>
                            </td>

                            {{-- BASE UNIT --}}
                            <td>
                                <span class="badge bg-light text-dark border font-size-12 px-2.5 py-1">
                                    <i class="mdi {{ $typeIcon }} me-1 text-primary"></i>{{ $unitName }} ({{ $unitSymbol }})
                                </span>
                                <small class="text-muted d-block font-size-11 mt-0.5 text-capitalize">{{ $unitType }} dimension</small>
                            </td>

                            {{-- STOCK ON HAND --}}
                            <td>
                                <strong class="d-block font-size-13 {{ $isLowStock ? 'text-danger' : 'text-dark' }}">
                                    {{ number_format($qty, 3) }} {{ $unitSymbol }}
                                </strong>
                                @if($stockValue > 0)
                                    <small class="text-muted font-size-11">Valued @ ₦{{ number_format($stockValue, 2) }}</small>
                                @else
                                    <small class="text-muted font-size-11">Zero recorded valuation</small>
                                @endif
                            </td>

                            {{-- REORDER LEVEL --}}
                            <td>
                                <div class="d-flex align-items-center gap-1.5">
                                    <span class="fw-semibold text-dark font-size-13">
                                        {{ $ingredient->reorder_level !== null ? number_format($ingredient->reorder_level, 3) : number_format($threshold, 3) }} {{ $unitSymbol }}
                                    </span>
                                </div>
                                @if($isLowStock)
                                    <span class="badge bg-danger-subtle text-danger font-size-10 px-2 py-0.5 mt-0.5">
                                        <i class="bx bx-down-arrow-alt me-0.5"></i>Low Stock
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success font-size-10 px-2 py-0.5 mt-0.5">
                                        <i class="bx bx-check me-0.5"></i>Sufficient
                                    </span>
                                @endif
                            </td>

                            {{-- RECIPE USAGE --}}
                            <td>
                                @if($recipeCount > 0)
                                    <span class="badge bg-info-subtle text-info font-size-11 px-2.5 py-1">
                                        <i class="bx bx-bowl-rice me-1"></i>{{ $recipeCount }} {{ Str::plural('Recipe', $recipeCount) }}
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted border font-size-11 px-2 py-1">
                                        Unlinked
                                    </span>
                                @endif
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @if($ingredient->is_active)
                                    <span class="badge bg-success-subtle text-success font-size-11 px-2.5 py-1">
                                        <span class="status-indicator-dot bg-success"></span>Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary font-size-11 px-2.5 py-1">
                                        <span class="status-indicator-dot bg-secondary"></span>Disabled
                                    </span>
                                @endif
                            </td>

                            {{-- ACTIONS --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-1">
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-1 font-size-12"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editIngredient{{ $ingredient->id }}"
                                            title="Edit Ingredient">
                                        <i class="bx bx-edit me-1"></i>Edit
                                    </button>

                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1 font-size-12"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteIngredient{{ $ingredient->id }}"
                                            title="Delete Ingredient">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <div class="avatar-md mx-auto mb-3">
                                    <span class="avatar-title rounded-circle bg-light text-muted font-size-24">
                                        <i class="bx bx-food-menu"></i>
                                    </span>
                                </div>
                                <h6 class="fw-bold text-dark">No Ingredients Found</h6>
                                <p class="text-muted small mb-3">You haven't added any raw materials to your production catalog yet.</p>
                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#addIngredient">
                                    <i class="bx bx-plus me-1"></i> Add First Ingredient
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- =====================================================
     EDIT, DELETE & ADD INGREDIENT MODALS
     ===================================================== --}}
@include('admin.erp.partials.modals.ingredients.ingredient-modals')
@include('admin.erp.partials.modals.ingredients.add-ingredient-modal')

@endsection
