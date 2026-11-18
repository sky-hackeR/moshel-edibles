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
        <div class="card border-0 shadow-sm h-100 ingredient-kpi-card" style="border-left: 4px solid #059669 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Cataloged Materials</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ $totalIngredients }}</h4>
                        <small class="text-success font-size-11 fw-semibold">
                            <i class="bx bx-check-circle me-1"></i>{{ $activeIngredients }} Active in Catalog
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-success-subtle text-success">
                            <i class="bx bx-food-menu"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Reorder & Low Stock Alerts --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 ingredient-kpi-card" style="border-left: 4px solid {{ $lowStockCount > 0 ? '#ef4444' : '#f59e0b' }} !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Reorder Alerts</p>
                        <h4 class="mb-0 fw-bold {{ $lowStockCount > 0 ? 'text-danger' : 'text-dark' }}">{{ $lowStockCount }}</h4>
                        @if($lowStockCount > 0)
                            <small class="text-danger font-size-11 fw-semibold">
                                <i class="bx bx-error-circle me-1"></i>Restock Required
                            </small>
                        @else
                            <small class="text-success font-size-11 fw-semibold">
                                <i class="bx bx-badge-check me-1"></i>All Levels Healthy
                            </small>
                        @endif
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 {{ $lowStockCount > 0 ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning' }}">
                            <i class="bx bx-bell"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 3: Estimated Stock Value --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 ingredient-kpi-card" style="border-left: 4px solid #4f46e5 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div style="min-width: 0;">
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Inventory Valuation</p>
                        <h4 class="mb-0 fw-bold text-dark text-truncate" title="₦{{ number_format($totalInventoryValue, 2) }}">
                            ₦{{ number_format($totalInventoryValue, 2) }}
                        </h4>
                        <small class="text-primary font-size-11 fw-medium">
                            <i class="bx bx-wallet me-1"></i>Stock-on-Hand Value
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

    {{-- Card 4: Production Dependencies --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 ingredient-kpi-card" style="border-left: 4px solid #0284c7 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Recipe Formulations</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ $usedInRecipesCount }}</h4>
                        <small class="text-info font-size-11 fw-semibold">
                            <i class="bx bx-bowl-rice me-1"></i>Used in Active Recipes
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
     MODALS: EDIT & DELETE (Per Ingredient)
     ===================================================== --}}
@foreach($ingredients as $ingredient)
    {{-- EDIT MODAL --}}
    <div class="modal fade" id="editIngredient{{ $ingredient->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <form method="POST" action="{{ url('admin/updateIngredient') }}">
                    @csrf
                    <input type="hidden" name="ingredient_id" value="{{ $ingredient->id }}">

                    <div class="modal-header border-bottom px-4 py-3 bg-light">
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar-xs">
                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                    <i class="bx bx-edit font-size-16"></i>
                                </span>
                            </span>
                            <h5 class="modal-title fw-bold text-dark mb-0">Edit Ingredient</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark font-size-12 mb-1">Ingredient Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-purchase-tag-alt"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0" value="{{ $ingredient->name }}" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark font-size-12 mb-1">Base Unit of Measure <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-ruler"></i></span>
                                    <select name="base_unit_id" class="form-select border-start-0" required>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}" {{ $ingredient->base_unit_id == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->name }} ({{ $unit->symbol }}) — {{ ucfirst($unit->unit_type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-muted font-size-11 mt-1 d-block">Base units represent the fundamental standard used in recipe item formulas.</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark font-size-12 mb-1">
                                    Reorder Level ({{ $ingredient->baseUnit->symbol ?? 'unit' }})
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-bell"></i></span>
                                    <input type="number" name="reorder_level" class="form-control border-start-0" min="0" step="0.001" value="{{ $ingredient->reorder_level }}">
                                </div>
                                <small class="text-muted font-size-11 mt-1 d-block">
                                    Leave blank to use the system default (1,000 g / 1,000 ml / 10 pcs).
                                </small>
                            </div>

                            <div class="col-12">
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-between p-0">
                                        <div>
                                            <label class="form-check-label fw-bold text-dark d-block font-size-13 mb-0" for="editActive{{ $ingredient->id }}">
                                                Catalog Status
                                            </label>
                                            <small class="text-muted font-size-11">Inactive materials cannot be allocated into new production batches.</small>
                                        </div>
                                        <input class="form-check-input ms-3 float-none" type="checkbox" name="is_active" id="editActive{{ $ingredient->id }}" {{ $ingredient->is_active ? 'checked' : '' }} style="cursor: pointer; width: 36px; height: 20px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-top px-4 py-3 bg-white d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary px-3 font-size-12" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 font-size-12 fw-medium shadow-sm">
                            <i class="bx bx-check me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- DELETE MODAL --}}
    <div class="modal fade" id="deleteIngredient{{ $ingredient->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <form method="POST" action="{{ url('admin/deleteIngredient') }}">
                    @csrf
                    <input type="hidden" name="ingredient_id" value="{{ $ingredient->id }}">

                    <div class="modal-body text-center p-4">
                        <div class="avatar-md mx-auto mb-3">
                            <span class="avatar-title rounded-circle bg-danger-subtle text-danger font-size-28">
                                <i class="bx bx-error-circle"></i>
                            </span>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Delete Ingredient?</h5>
                        <p class="text-muted font-size-13 mb-3">
                            Are you sure you want to remove <strong class="text-dark">{{ $ingredient->name }}</strong> from your raw material catalog?
                        </p>

                        <div class="alert alert-warning text-start font-size-12 mb-4 p-2.5 border-0 rounded-3">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bx bx-shield-quarter font-size-16 text-warning mt-0.5"></i>
                                <div>
                                    <strong>Safety Verification:</strong> Ingredients with active inventory balances or associated recipe formulations cannot be deleted and will trigger security audit alerts.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light w-50 font-size-12 fw-medium" data-bs-dismiss="modal">Keep Ingredient</button>
                            <button type="submit" class="btn btn-danger w-50 font-size-12 fw-medium shadow-sm">
                                <i class="bx bx-trash me-1"></i> Confirm Delete
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

{{-- =====================================================
     ADD INGREDIENT MODAL
     ===================================================== --}}
<div class="modal fade" id="addIngredient" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <form method="POST" action="{{ url('admin/newIngredient') }}">
                @csrf
                <div class="modal-header border-bottom px-4 py-3 bg-light">
                    <div class="d-flex align-items-center gap-2">
                        <span class="avatar-xs">
                            <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                <i class="bx bx-plus font-size-16"></i>
                            </span>
                        </span>
                        <h5 class="modal-title fw-bold text-dark mb-0">Add New Ingredient</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark font-size-12 mb-1">Ingredient Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-purchase-tag-alt"></i></span>
                                <input type="text" name="name" class="form-control border-start-0" placeholder="e.g. Bread Flour, White Sugar, Yeast" required autofocus>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark font-size-12 mb-1">Base Unit of Measure <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-ruler"></i></span>
                                <select name="base_unit_id" class="form-select border-start-0" required>
                                    <option value="" selected disabled>Select Core Base Unit...</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}">
                                            {{ $unit->name }} ({{ $unit->symbol }}) — {{ ucfirst($unit->unit_type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-muted font-size-11 mt-1 d-block">All inventory stock-in and recipe item weights calculate from this base unit.</small>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark font-size-12 mb-1">
                                Reorder Level Threshold
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-bell"></i></span>
                                <input type="number" name="reorder_level" class="form-control border-start-0" min="0" step="0.001" placeholder="e.g. 5000">
                            </div>
                            <small class="text-muted font-size-11 mt-1 d-block">
                                Minimum threshold in base units. Leave blank for default (1,000 g / 1,000 ml / 10 pcs).
                            </small>
                        </div>

                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-between p-0">
                                    <div>
                                        <label class="form-check-label fw-bold text-dark d-block font-size-13 mb-0" for="newActive">
                                            Mark as Active
                                        </label>
                                        <small class="text-muted font-size-11">Active raw materials are immediately available for recipes and purchase orders.</small>
                                    </div>
                                    <input class="form-check-input ms-3 float-none" type="checkbox" name="is_active" id="newActive" checked style="cursor: pointer; width: 36px; height: 20px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top px-4 py-3 bg-white d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary px-3 font-size-12" data-bs-dismiss="modal">Discard</button>
                    <button type="submit" class="btn btn-primary px-4 font-size-12 fw-medium shadow-sm">
                        <i class="bx bx-plus me-1"></i> Create Ingredient
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
