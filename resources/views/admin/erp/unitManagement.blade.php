@extends('admin.layout.dashboard')

@section('content')

@php
    $totalUnits = $units->count();
    $massUnits = $units->where('unit_type', 'mass');
    $volumeUnits = $units->where('unit_type', 'volume');
    $countUnits = $units->where('unit_type', 'count');
    $activeUnits = $units->where('is_active', true)->count();
    $purchaseUnits = $units->where('use_for_purchase', true)->count();
    $recipeUnits = $units->where('use_for_recipe', true)->count();
@endphp

{{-- PAGE HEADER --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-sm-0 font-size-18 text-primary fw-bold">
                    <i class="bx bx-transfer-alt me-1 align-middle text-pink"></i> Unit Management & Conversion Engine
                </h4>
                <p class="text-muted font-size-12 mb-0">
                    Configure dimensional standards, base multipliers, and operational scopes for raw material procurement and recipe scaling.
                </p>
            </div>
            <div class="page-title-right d-flex align-items-center gap-2">
                <button type="button" class="btn btn-outline-dark btn-sm rounded-pill px-3 shadow-sm" id="btnToggleFloatingCalculator">
                    <i class="bx bx-calculator me-1 align-middle"></i> Unit Calculator
                </button>
                <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addUnit">
                    <i class="bx bx-plus me-1 align-middle"></i> Add Custom Unit
                </button>
                <ol class="breadcrumb m-0 ms-2 d-none d-md-flex">
                    <li class="breadcrumb-item active">Units</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- EXECUTIVE KPI METRIC CARDS --}}
<div class="row g-3 mb-4">
    {{-- Total Configured Units --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #581a62 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Defined Units</p>
                        <h3 class="mb-0 fw-bold text-dark">{{ $totalUnits }}</h3>
                        <small class="text-success font-size-11 fw-medium">
                            <i class="bx bx-check-circle me-1"></i>{{ $activeUnits }} Active in Registry
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20" style="background-color: #f7eef8; color: #581a62;">
                            <i class="bx bx-ruler"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Mass / Weight Dimension --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Mass / Weight</p>
                        <h3 class="mb-0 fw-bold text-dark">{{ $massUnits->count() }} <span class="font-size-13 text-muted fw-normal">Units</span></h3>
                        <small class="text-warning font-size-11 fw-semibold">
                            <i class="bx bx-lock-alt me-1"></i>Base: <b>1.0000 g</b> (Gram)
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-warning-subtle text-warning">
                            <i class="mdi mdi-weight-kilogram"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Liquid / Volume Dimension --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Liquid / Volume</p>
                        <h3 class="mb-0 fw-bold text-dark">{{ $volumeUnits->count() }} <span class="font-size-13 text-muted fw-normal">Units</span></h3>
                        <small class="text-info font-size-11 fw-semibold">
                            <i class="bx bx-lock-alt me-1"></i>Base: <b>1.0000 ml</b> (Millilitre)
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-info-subtle text-info">
                            <i class="mdi mdi-cup-water"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Discrete / Count Dimension --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #10b981 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Discrete / Count</p>
                        <h3 class="mb-0 fw-bold text-dark">{{ $countUnits->count() }} <span class="font-size-13 text-muted fw-normal">Units</span></h3>
                        <small class="text-success font-size-11 fw-semibold">
                            <i class="bx bx-lock-alt me-1"></i>Base: <b>1.0000 pcs</b> (Piece)
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
</div>

{{-- UNITS DIRECTORY CARD --}}
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 14px;">
            <div class="card-header bg-transparent border-bottom p-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h5 class="card-title mb-1 fw-bold text-dark">Measurement Registry & Conversion Multipliers</h5>
                    <p class="text-muted font-size-12 mb-0">
                        Base units (<code class="text-primary fw-bold">g</code>, <code class="text-info fw-bold">ml</code>, <code class="text-success fw-bold">pcs</code>) serve as mathematical roots. All ingredient usage scales against these constants.
                    </p>
                </div>

                {{-- DIMENSION FILTER PILLS --}}
                <div class="d-flex flex-wrap align-items-center gap-1 bg-light p-1 rounded-pill border">
                    <button type="button" class="btn btn-sm rounded-pill px-3 unit-filter-btn active" data-filter="all">
                        All Dimensions ({{ $totalUnits }})
                    </button>
                    <button type="button" class="btn btn-sm rounded-pill px-3 unit-filter-btn" data-filter="mass">
                        ⚖️ Mass ({{ $massUnits->count() }})
                    </button>
                    <button type="button" class="btn btn-sm rounded-pill px-3 unit-filter-btn" data-filter="volume">
                        🧪 Volume ({{ $volumeUnits->count() }})
                    </button>
                    <button type="button" class="btn btn-sm rounded-pill px-3 unit-filter-btn" data-filter="count">
                        📦 Count ({{ $countUnits->count() }})
                    </button>
                </div>
            </div>

            <div class="card-body p-3">
                <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-hover align-middle dt-responsive nowrap w-100 mb-0">
                        <thead class="table-light font-size-11 text-uppercase text-muted">
                            <tr>
                                <th class="text-center" style="width: 45px;">S/N</th>
                                <th>Unit Name & Symbol</th>
                                <th>Dimension Type</th>
                                <th>Conversion Math (Formula)</th>
                                <th>Base Standard</th>
                                <th class="text-center">Operational Scope</th>
                                <th class="text-center">Status</th>
                                <th class="text-end" style="width: 130px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($units as $unit)
                            @php
                                $isBase = in_array($unit->symbol, ['g', 'ml', 'pcs']);
                                $typeBadgeClass = [
                                    'mass' => 'bg-warning-subtle text-warning border-warning',
                                    'volume' => 'bg-info-subtle text-info border-info',
                                    'count' => 'bg-success-subtle text-success border-success'
                                ][$unit->unit_type] ?? 'bg-light text-dark';
                                
                                $typeIcon = [
                                    'mass' => 'mdi mdi-weight-kilogram',
                                    'volume' => 'mdi mdi-cup-water',
                                    'count' => 'bx bx-package'
                                ][$unit->unit_type] ?? 'bx bx-ruler';
                            @endphp
                            <tr data-dimension="{{ $unit->unit_type }}" data-purchase="{{ $unit->use_for_purchase ? '1' : '0' }}" data-recipe="{{ $unit->use_for_recipe ? '1' : '0' }}">
                                <td class="text-center text-muted font-size-12">{{ $loop->iteration }}</td>
                                
                                {{-- Unit Name & Symbol --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-bold text-dark font-size-14">{{ $unit->name }}</span>
                                                @if($isBase)
                                                    <span class="badge bg-purple-soft text-purple font-size-10 px-2 py-0" title="Core System Reference Root">
                                                        <i class="bx bx-shield-quarter me-1"></i>Core Base
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="d-flex align-items-center gap-2 mt-1">
                                                <span class="text-muted font-size-11">Symbol:</span>
                                                <code class="px-2 py-0 rounded fw-bold text-dark" style="background: #f1e9f3; font-size: 11px;">
                                                    {{ $unit->symbol }}
                                                </code>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Dimension Type --}}
                                <td>
                                    <span class="badge {{ $typeBadgeClass }} px-2 py-1 font-size-11 text-capitalize fw-semibold border">
                                        <i class="{{ $typeIcon }} me-1 align-middle"></i> {{ $unit->unit_type }}
                                    </span>
                                </td>

                                {{-- Conversion Math --}}
                                <td>
                                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill" style="background: #fcf9fd; border: 1px solid #ebddec;">
                                        <span class="font-size-12 fw-medium text-dark">1 {{ $unit->symbol }}</span>
                                        <i class="bx bx-right-arrow-alt text-muted font-size-14"></i>
                                        <span class="font-size-13 fw-bold text-primary font-monospace">
                                            {{ rtrim(rtrim(number_format($unit->base_multiplier, 4), '0'), '.') }}
                                        </span>
                                        <span class="font-size-12 text-muted fw-semibold">{{ $unit->base_unit }}</span>
                                    </div>
                                </td>

                                {{-- Base Standard Ref --}}
                                <td>
                                    <span class="badge bg-light text-dark font-size-11 border">
                                        Root: <b class="text-primary">{{ $unit->base_unit }}</b>
                                    </span>
                                </td>

                                {{-- Operational Scope --}}
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1 flex-wrap">
                                        @if($unit->use_for_purchase)
                                            <span class="badge bg-info-subtle text-info font-size-10 px-2 py-1" title="Available during Ingredient Stock-In & Purchase">
                                                <i class="bx bx-cart-download me-1"></i>Purchase
                                            </span>
                                        @endif
                                        @if($unit->use_for_recipe)
                                            <span class="badge bg-purple-soft text-purple font-size-10 px-2 py-1" title="Available in Bakery Production Recipes">
                                                <i class="bx bx-dish me-1"></i>Recipe
                                            </span>
                                        @endif
                                        @if(!$unit->use_for_purchase && !$unit->use_for_recipe)
                                            <span class="badge bg-light text-muted font-size-10 px-2 py-1">Internal Reference</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Active Status --}}
                                <td class="text-center">
                                    @if($unit->is_active)
                                        <span class="badge bg-success-subtle text-success font-size-11 px-2 py-1 fw-semibold">
                                            <i class="bx bx-check-circle me-1 align-middle"></i>Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger font-size-11 px-2 py-1 fw-semibold">
                                            <i class="bx bx-x-circle me-1 align-middle"></i>Inactive
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-1">
                                        {{-- Open in Floating Calculator shortcut --}}
                                        <button type="button" class="btn btn-sm btn-outline-dark border btn-load-floating-calc" 
                                                data-id="{{ $unit->id }}" title="Open in Floating Calculator">
                                            <i class="bx bx-calculator font-size-15 align-middle"></i>
                                        </button>

                                        {{-- Edit modal button --}}
                                        <button type="button" class="btn btn-sm btn-soft-primary" data-bs-toggle="modal" data-bs-target="#editUnit{{ $unit->id }}" title="Edit Unit Details">
                                            <i class="bx bx-pencil font-size-15 align-middle"></i>
                                        </button>

                                        {{-- Delete modal button --}}
                                        @if(!$isBase)
                                            <button type="button" class="btn btn-sm btn-soft-danger" data-bs-toggle="modal" data-bs-target="#deleteUnit{{ $unit->id }}" title="Delete Unit">
                                                <i class="bx bx-trash font-size-15 align-middle"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-light disabled text-muted border-0" title="Core base units are protected and cannot be deleted">
                                                <i class="bx bx-lock-alt font-size-15 align-middle"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- FLOATING DRAGGABLE UNIT CONVERSION CALCULATOR WIDGET                     --}}
{{-- ========================================================================= --}}
<div id="floatingCalculatorWidget" class="floating-calc-widget shadow-lg" style="display: none;">
    {{-- Draggable Grip Header (Black / Dark Slate with muted accents) --}}
    <div class="calc-drag-handle d-flex align-items-center justify-content-between px-3 py-2 text-white">
        <div class="d-flex align-items-center gap-2">
            <i class="bx bx-move font-size-16 text-muted"></i>
            <span class="fw-bold font-size-13 text-white">Unit Conversion Calculator</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-link text-white-50 p-0 text-decoration-none" id="calcMinimizeBtn" title="Minimize / Expand">
                <i class="bx bx-minus font-size-16"></i>
            </button>
            <button type="button" class="btn btn-sm btn-link text-white-50 p-0 text-decoration-none" id="calcCloseBtn" title="Close Calculator">
                <i class="bx bx-x font-size-18"></i>
            </button>
        </div>
    </div>

    {{-- Calculator Main Body (Muted & Black generalized colors) --}}
    <div id="calcBodyContent" class="p-3 bg-white">
        {{-- Digital Readout Display --}}
        <div class="calc-screen p-3 rounded-3 mb-3">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="font-size-11 text-uppercase fw-bold text-muted" id="calcScreenLabel">Calculated Equivalent</span>
                <span class="badge bg-white text-muted border font-size-10 font-monospace" id="calcFormulaTag">Ready</span>
            </div>
            <div class="d-flex align-items-baseline justify-content-between mt-1">
                <div class="fw-bold text-dark font-monospace" style="font-size: 26px; line-height: 1.1;" id="calcOutputValue">
                    1.0000
                </div>
                <div class="font-size-13 fw-bold text-dark ms-2 text-end" id="calcOutputUnit">
                    Kilogram (kg)
                </div>
            </div>
            <div class="mt-2 pt-2 border-top font-size-11 text-muted d-flex justify-content-between align-items-center" id="calcBaseEquivalentRow">
                <span>Base Standard Equivalent:</span>
                <span class="fw-bold text-dark font-monospace" id="calcBaseEquivalentVal">1,000.0000 g</span>
            </div>
        </div>

        {{-- Amount Input with Quick Stepper --}}
        <div class="mb-3">
            <label class="form-label font-size-11 fw-bold text-muted text-uppercase mb-1">Quantity / Amount</label>
            <div class="input-group">
                <button type="button" class="btn btn-light border text-dark font-size-13 px-2 btn-qty-step" data-step="-1">&minus;1</button>
                <input type="number" id="calcInputQty" class="form-control text-center font-monospace fw-bold font-size-15 text-dark" value="1" step="any" min="0.0001">
                <button type="button" class="btn btn-light border text-dark font-size-13 px-2 btn-qty-step" data-step="1">+1</button>
                <button type="button" class="btn btn-light border text-muted font-size-12 px-2" id="calcClearBtn" title="Reset Amount">AC</button>
            </div>
        </div>

        {{-- Units Conversion Grid: From -> To with Swap --}}
        <div class="row g-2 align-items-center mb-3">
            <div class="col-5">
                <label class="form-label font-size-11 fw-bold text-muted text-uppercase mb-1">From Unit</label>
                <select id="calcFromUnit" class="form-select form-select-sm font-size-12 text-dark fw-medium">
                    <optgroup label="⚖️ Mass (Grams)">
                        @foreach($massUnits as $u)
                            <option value="{{ $u->id }}" {{ $u->symbol == 'kg' ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->symbol }})
                            </option>
                        @endforeach
                    </optgroup>
                    <optgroup label="🧪 Volume (Millilitres)">
                        @foreach($volumeUnits as $u)
                            <option value="{{ $u->id }}">
                                {{ $u->name }} ({{ $u->symbol }})
                            </option>
                        @endforeach
                    </optgroup>
                    <optgroup label="📦 Count (Pieces)">
                        @foreach($countUnits as $u)
                            <option value="{{ $u->id }}">
                                {{ $u->name }} ({{ $u->symbol }})
                            </option>
                        @endforeach
                    </optgroup>
                </select>
            </div>

            <div class="col-2 text-center pt-3">
                <button type="button" class="btn btn-sm btn-light border rounded-circle p-1 shadow-none" id="calcSwapBtn" title="Swap Units" style="width: 30px; height: 30px;">
                    <i class="bx bx-transfer font-size-14 text-dark"></i>
                </button>
            </div>

            <div class="col-5">
                <label class="form-label font-size-11 fw-bold text-muted text-uppercase mb-1">To Target Unit</label>
                <select id="calcToUnit" class="form-select form-select-sm font-size-12 text-dark fw-medium">
                    {{-- Dynamically populated based on From Unit dimension --}}
                </select>
            </div>
        </div>

        {{-- Quick Preset Container Multipliers (Muted & Black) --}}
        <div class="p-2 rounded bg-light border">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="font-size-11 fw-bold text-muted text-uppercase">Container Amounts</span>
                <span class="font-size-10 text-muted" id="calcPresetDimNote">Mass Quick Fills</span>
            </div>
            <div class="d-flex flex-wrap gap-1" id="calcQuickPresets">
                {{-- Injected dynamically --}}
            </div>
        </div>

        {{-- Drag Instruction Hint --}}
        <div class="mt-2 text-center">
            <small class="text-muted font-size-10">
                <i class="bx bx-move me-1"></i>Click and hold the dark header bar to move this calculator anywhere.
            </small>
        </div>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- MODALS: ADD UNIT, EDIT UNIT, DELETE UNIT                                 --}}
{{-- ========================================================================= --}}

{{-- 1. ADD NEW CUSTOM UNIT MODAL --}}
<div class="modal fade" id="addUnit" data-bs-backdrop="static" tabindex="-1" aria-labelledby="addUnitLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="{{ url('/admin/newUnit') }}" class="w-100" id="formAddUnit">
            @csrf
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                {{-- Gradient Accent Bar --}}
                <div style="height: 5px; background: linear-gradient(90deg, #581a62 0%, #ce278c 50%, #f472b6 100%);"></div>

                <div class="modal-header border-bottom px-4 py-3 bg-white">
                    <div class="d-flex align-items-center gap-2">
                        <span class="avatar-title rounded-circle font-size-16 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #fdf2f9; color: #ce278c;">
                            <i class="bx bx-plus"></i>
                        </span>
                        <div>
                            <h5 class="modal-title fw-bold text-dark" id="addUnitLabel">Register New Measurement Unit</h5>
                            <p class="text-muted font-size-12 mb-0">Add wholesale packages, recipe measures, or custom containers to the ERP registry.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 bg-light">
                    <div class="card border-0 shadow-none mb-3" style="border-radius: 12px;">
                        <div class="card-body p-3">
                            <div class="row g-3">
                                {{-- Unit Name --}}
                                <div class="col-md-7">
                                    <label class="form-label font-size-12 fw-bold text-dark">Unit Display Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="addUnitName" class="form-control font-size-14" placeholder="e.g. 50kg Flour Bag, Carton (24pcs), Jerrycan" required>
                                    <small class="text-muted font-size-11">Human-friendly name shown in dropdowns and receipts.</small>
                                </div>

                                {{-- Unit Symbol --}}
                                <div class="col-md-5">
                                    <label class="form-label font-size-12 fw-bold text-dark">Symbol / Code <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bx bx-tag text-muted"></i></span>
                                        <input type="text" name="symbol" id="addUnitSymbol" class="form-control font-size-14 text-lowercase" placeholder="e.g. bag_50kg, ctn_24" required>
                                    </div>
                                    <small class="text-muted font-size-11">Short unique alphanumeric code.</small>
                                </div>

                                {{-- Dimension / Unit Type --}}
                                <div class="col-md-6">
                                    <label class="form-label font-size-12 fw-bold text-dark">Measurement Dimension <span class="text-danger">*</span></label>
                                    <select name="unit_type" id="addUnitType" class="form-select font-size-14" required>
                                        <option value="mass" selected>⚖️ Mass / Weight (Calculates in Grams 'g')</option>
                                        <option value="volume">🧪 Liquid / Volume (Calculates in Millilitres 'ml')</option>
                                        <option value="count">📦 Discrete / Count (Calculates in Pieces 'pcs')</option>
                                    </select>
                                </div>

                                {{-- Base Multiplier --}}
                                <div class="col-md-6">
                                    <label class="form-label font-size-12 fw-bold text-primary">
                                        Base Multiplier <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white fw-bold text-muted">&times;</span>
                                        <input type="number" step="0.0001" min="0.0001" name="base_multiplier" id="addBaseMultiplier" class="form-control font-size-14 fw-bold" placeholder="e.g. 50000" required>
                                        <span class="input-group-text bg-light fw-bold text-primary" id="addBaseUnitPreview">g</span>
                                    </div>
                                    <small class="text-muted font-size-11">How many base units are contained inside 1 of this unit?</small>
                                </div>

                                {{-- Quick Presets Pill Bar --}}
                                <div class="col-12">
                                    <div class="p-2 rounded bg-light border d-flex flex-wrap align-items-center gap-1 font-size-12">
                                        <span class="text-muted fw-bold me-2 font-size-11">Quick Container Multipliers:</span>
                                        <div id="addPresetsContainer" class="d-inline-flex flex-wrap gap-1">
                                            {{-- Dynamic presets populated via JS --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Dynamic Conversion Equation Card --}}
                    <div class="card border-0 mb-3" style="border-radius: 12px; background: #faf4fb; border: 1px dashed #ce278c !important;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="font-size-11 text-uppercase fw-bold text-muted">Preview Conversion Formula</span>
                                    <h4 class="mb-0 fw-bold text-dark mt-1" id="addEquationPreview">
                                        1 <span id="addEqSym">unit</span> = <span class="text-primary font-monospace" id="addEqMult">0</span> <span id="addEqBase">g</span>
                                    </h4>
                                </div>
                                <div class="avatar-sm">
                                    <span class="avatar-title rounded-circle font-size-18 bg-white text-pink shadow-sm">
                                        <i class="bx bx-check-double"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Usage Scopes --}}
                    <div class="card border-0 shadow-none mb-0" style="border-radius: 12px;">
                        <div class="card-body p-3">
                            <label class="form-label font-size-12 fw-bold text-dark mb-2">Operational Usage Scopes</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="form-check form-switch p-2 border rounded bg-white ps-5">
                                        <input class="form-check-input ms-n4" type="checkbox" name="use_for_purchase" id="addPurchase" checked>
                                        <label class="form-check-label fw-semibold font-size-13 text-dark" for="addPurchase">
                                            Enable for Purchasing
                                            <span class="d-block font-size-11 text-muted fw-normal">Available during raw material Stock-In & vendor procurement.</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch p-2 border rounded bg-white ps-5">
                                        <input class="form-check-input ms-n4" type="checkbox" name="use_for_recipe" id="addRecipe" checked>
                                        <label class="form-check-label fw-semibold font-size-13 text-dark" for="addRecipe">
                                            Enable for Recipes
                                            <span class="d-block font-size-11 text-muted fw-normal">Selectable when formulating ingredient lists in production.</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-white border-top px-4 py-3 d-flex justify-content-between">
                    <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-sm">
                        <i class="bx bx-check me-1 align-middle"></i> Save Unit to Registry
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- 2. EDIT UNIT MODALS --}}
@foreach($units as $unit)
    @php
        $isBase = in_array($unit->symbol, ['g', 'ml', 'pcs']);
    @endphp
    <div class="modal fade" id="editUnit{{ $unit->id }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="editUnitLabel{{ $unit->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" action="{{ url('/admin/updateUnit') }}" class="w-100">
                @csrf
                <input type="hidden" name="unit_id" value="{{ $unit->id }}">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                    {{-- Header bar --}}
                    <div style="height: 5px; background: {{ $isBase ? '#581a62' : 'linear-gradient(90deg, #581a62 0%, #ce278c 100%)' }};"></div>

                    <div class="modal-header border-bottom px-4 py-3 bg-white">
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar-title rounded-circle font-size-16 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #fdf2f9; color: #581a62;">
                                <i class="bx bx-edit-alt"></i>
                            </span>
                            <div>
                                <h5 class="modal-title fw-bold text-dark" id="editUnitLabel{{ $unit->id }}">Edit Unit: {{ $unit->name }}</h5>
                                <p class="text-muted font-size-12 mb-0">Modify display labels, base conversion values, and operational availability.</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4 bg-light">
                        @if($isBase)
                            <div class="alert alert-info border-0 shadow-none d-flex align-items-center gap-2 py-2 px-3 mb-3" style="border-radius: 10px;">
                                <i class="bx bx-shield-quarter font-size-20"></i>
                                <div class="font-size-13">
                                    <b>System Core Standard:</b> Multiplier, symbol, and dimension are locked to preserve database calculation integrity. You may update the display name.
                                </div>
                            </div>
                        @endif

                        <div class="card border-0 shadow-none mb-3" style="border-radius: 12px;">
                            <div class="card-body p-3">
                                <div class="row g-3">
                                    {{-- Display Name --}}
                                    <div class="col-md-6">
                                        <label class="form-label font-size-12 fw-bold text-dark">Display Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control font-size-14" value="{{ $unit->name }}" required>
                                    </div>

                                    {{-- Symbol --}}
                                    <div class="col-md-6">
                                        <label class="form-label font-size-12 fw-bold text-dark">Symbol <span class="text-danger">*</span></label>
                                        <input type="text" name="symbol" class="form-control font-size-14 {{ $isBase ? 'bg-light' : '' }}" value="{{ $unit->symbol }}" required {{ $isBase ? 'readonly' : '' }}>
                                    </div>

                                    {{-- Dimension Type --}}
                                    <div class="col-md-6">
                                        <label class="form-label font-size-12 fw-bold text-dark">Dimension Classification <span class="text-danger">*</span></label>
                                        <select name="unit_type" class="form-select font-size-14 {{ $isBase ? 'bg-light' : '' }}" required {{ $isBase ? 'disabled' : '' }}>
                                            <option value="mass" {{ $unit->unit_type == 'mass' ? 'selected' : '' }}>⚖️ Mass / Weight (Calculates in Grams 'g')</option>
                                            <option value="volume" {{ $unit->unit_type == 'volume' ? 'selected' : '' }}>🧪 Liquid / Volume (Calculates in Millilitres 'ml')</option>
                                            <option value="count" {{ $unit->unit_type == 'count' ? 'selected' : '' }}>📦 Discrete / Count (Calculates in Pieces 'pcs')</option>
                                        </select>
                                        @if($isBase)
                                            <input type="hidden" name="unit_type" value="{{ $unit->unit_type }}">
                                        @endif
                                    </div>

                                    {{-- Base Multiplier --}}
                                    <div class="col-md-6">
                                        <label class="form-label font-size-12 fw-bold text-primary">Base Multiplier <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white fw-bold text-muted">&times;</span>
                                            <input type="number" step="0.0001" min="0.0001" name="base_multiplier" class="form-control font-size-14 fw-bold {{ $isBase ? 'bg-light' : '' }}" value="{{ $unit->base_multiplier }}" required {{ $isBase ? 'readonly' : '' }}>
                                            <span class="input-group-text bg-light fw-bold text-primary">{{ $unit->base_unit }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Current Formula Preview --}}
                        <div class="p-3 rounded mb-3" style="background: #fdf8fe; border: 1px dashed #eedfed;">
                            <span class="font-size-11 text-muted text-uppercase fw-bold">Conversion Equation</span>
                            <div class="font-size-14 fw-bold text-dark mt-1">
                                1 {{ $unit->symbol }} = <span class="text-primary font-monospace">{{ rtrim(rtrim(number_format($unit->base_multiplier, 4), '0'), '.') }}</span> {{ $unit->base_unit }}
                            </div>
                        </div>

                        {{-- Scopes & Active Toggle --}}
                        <div class="card border-0 shadow-none mb-0" style="border-radius: 12px;">
                            <div class="card-body p-3">
                                <label class="form-label font-size-12 fw-bold text-dark mb-2">Usage Permissions & Registry Status</label>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="form-check form-switch p-2 border rounded bg-white ps-5">
                                            <input class="form-check-input ms-n4" type="checkbox" name="use_for_purchase" id="editPur{{ $unit->id }}" {{ $unit->use_for_purchase ? 'checked' : '' }} {{ $isBase ? 'disabled' : '' }}>
                                            <label class="form-check-label fw-semibold font-size-13 text-dark" for="editPur{{ $unit->id }}">Purchasing</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch p-2 border rounded bg-white ps-5">
                                            <input class="form-check-input ms-n4" type="checkbox" name="use_for_recipe" id="editRec{{ $unit->id }}" {{ $unit->use_for_recipe ? 'checked' : '' }} {{ $isBase ? 'disabled' : '' }}>
                                            <label class="form-check-label fw-semibold font-size-13 text-dark" for="editRec{{ $unit->id }}">Recipe</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch p-2 border rounded bg-white ps-5">
                                            <input class="form-check-input ms-n4" type="checkbox" name="is_active" id="editAct{{ $unit->id }}" {{ $unit->is_active ? 'checked' : '' }} {{ $isBase ? 'disabled' : '' }}>
                                            <label class="form-check-label fw-semibold font-size-13 text-dark" for="editAct{{ $unit->id }}">Active Status</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-white border-top px-4 py-3 d-flex justify-content-between">
                        <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-sm">
                            <i class="bx bx-check me-1 align-middle"></i> Update Unit Specifications
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

{{-- 3. DELETE UNIT MODALS --}}
@foreach($units as $unit)
    @php
        $isBase = in_array($unit->symbol, ['g', 'ml', 'pcs']);
    @endphp
    @if(!$isBase)
        <div class="modal fade" id="deleteUnit{{ $unit->id }}" tabindex="-1" aria-labelledby="deleteUnitLabel{{ $unit->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form method="POST" action="{{ url('/admin/deleteUnit') }}" class="w-100">
                    @csrf
                    <input type="hidden" name="unit_id" value="{{ $unit->id }}">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                        <div style="height: 5px; background: #ef4444;"></div>
                        <div class="modal-body text-center p-4">
                            <div class="avatar-lg mx-auto mb-3">
                                <span class="avatar-title rounded-circle bg-danger-subtle text-danger font-size-36">
                                    <i class="bx bx-trash"></i>
                                </span>
                            </div>
                            <h4 class="fw-bold text-dark mb-1" id="deleteUnitLabel{{ $unit->id }}">Delete Measurement Unit?</h4>
                            <p class="text-muted font-size-13 mb-3">
                                You are about to remove <b>{{ $unit->name }} ({{ $unit->symbol }})</b> from the active registry.
                            </p>

                            <div class="p-3 rounded text-start mb-3" style="background: #fef2f2; border: 1px solid #fee2e2;">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <i class="bx bx-error-circle text-danger font-size-18"></i>
                                    <span class="fw-bold text-danger font-size-12 text-uppercase">Impact Warning</span>
                                </div>
                                <p class="text-muted font-size-12 mb-0">
                                    Formula: <b>1 {{ $unit->symbol }} = {{ number_format($unit->base_multiplier, 4) }} {{ $unit->base_unit }}</b>.<br>
                                    Deleting this unit may invalidate purchase records or production recipes configured with this unit.
                                </p>
                            </div>

                            <div class="d-flex gap-2 justify-content-center">
                                <button type="button" class="btn btn-light px-4 rounded-pill w-50" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger px-4 rounded-pill w-50 shadow-sm">Yes, Delete</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endforeach

{{-- INLINE JAVASCRIPT & STYLES (RUNS BOTH INLINE AND VIA @section('script')) --}}
<style>
.bg-purple-soft {
    background-color: #f7eef8 !important;
}
.text-purple {
    color: #581a62 !important;
}
.text-pink {
    color: #ce278c !important;
}
.unit-filter-btn {
    color: #64748b;
    font-weight: 600;
    font-size: 12px;
    border: none;
    background: transparent;
    transition: all 0.2s ease;
}
.unit-filter-btn:hover {
    color: #581a62;
    background: rgba(88, 26, 98, 0.06);
}
.unit-filter-btn.active {
    background: #581a62 !important;
    color: #ffffff !important;
    box-shadow: 0 2px 6px rgba(88, 26, 98, 0.25);
}

/* FLOATING DRAGGABLE CALCULATOR WIDGET STYLES */
.floating-calc-widget {
    position: fixed;
    top: 95px;
    right: 28px;
    width: 395px;
    max-width: calc(100vw - 30px);
    z-index: 1060;
    border-radius: 16px;
    overflow: hidden;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    box-shadow: 0 20px 45px rgba(15, 23, 42, 0.24), 0 4px 14px rgba(15, 23, 42, 0.08);
    transition: box-shadow 0.2s ease;
}
.calc-drag-handle {
    background: #0f172a;
    cursor: grab;
    user-select: none;
    -webkit-user-select: none;
}
.calc-drag-handle:active {
    cursor: grabbing;
}
.calc-screen {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}
.calc-preset-btn {
    font-size: 11px;
    font-weight: 600;
    color: #334155;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    padding: 2px 8px;
    border-radius: 20px;
    transition: all 0.15s ease;
}
.calc-preset-btn:hover {
    background: #0f172a;
    color: #ffffff;
    border-color: #0f172a;
}
</style>

<script>
(function() {
    // Expose all units cleanly to JavaScript
    var ALL_UNITS = @json($units);

    function initUnitManagementEngine() {
        if (typeof $ === 'undefined') {
            setTimeout(initUnitManagementEngine, 50);
            return;
        }

        $(document).ready(function() {
            // 1. DATA TABLE INITIALIZATION / FILTERING
            function getUnitTable() {
                if (window.$ && $.fn.DataTable && $('#datatable-buttons').length) {
                    return $('#datatable-buttons').DataTable();
                }
                return null;
            }

            $('.unit-filter-btn').on('click', function() {
                $('.unit-filter-btn').removeClass('active');
                $(this).addClass('active');

                var filter = $(this).data('filter');
                var unitTable = getUnitTable();
                if (unitTable) {
                    if (filter === 'all') {
                        unitTable.column(2).search('').draw();
                    } else {
                        unitTable.column(2).search(filter).draw();
                    }
                }
            });

            // 2. DRAGGABLE CALCULATOR WIDGET CONTROLS
            var $calc = $('#floatingCalculatorWidget');
            var isMinimized = false;

            function openCalculator(unitId) {
                $calc.fadeIn(180);
                if (isMinimized) {
                    $('#calcBodyContent').show();
                    $('#calcMinimizeBtn').find('i').removeClass('bx-plus').addClass('bx-minus');
                    isMinimized = false;
                }
                if (unitId) {
                    $('#calcFromUnit').val(unitId).trigger('change');
                }
            }

            function toggleCalculator() {
                if ($calc.is(':visible')) {
                    $calc.fadeOut(150);
                } else {
                    openCalculator();
                }
            }

            $('#btnToggleFloatingCalculator').on('click', function(e) {
                e.preventDefault();
                toggleCalculator();
            });

            $('#calcCloseBtn').on('click', function(e) {
                e.preventDefault();
                $calc.fadeOut(150);
            });

            $('#calcMinimizeBtn').on('click', function(e) {
                e.preventDefault();
                isMinimized = !isMinimized;
                $('#calcBodyContent').slideToggle(150);
                $(this).find('i').toggleClass('bx-minus bx-plus');
            });

            // Shortcut button from table rows
            $('.btn-load-floating-calc').on('click', function(e) {
                e.preventDefault();
                var unitId = $(this).data('id');
                openCalculator(unitId);
            });

            // 3. DRAG FUNCTIONALITY (MOUSE & TOUCH)
            (function setupDragging() {
                var calcElem = document.getElementById('floatingCalculatorWidget');
                var handleElem = calcElem ? calcElem.querySelector('.calc-drag-handle') : null;
                if (!calcElem || !handleElem) return;

                var isDragging = false;
                var startX = 0, startY = 0;
                var initialLeft = 0, initialTop = 0;

                function dragStart(e) {
                    // Don't drag if clicking buttons
                    if (e.target.closest('button') || e.target.closest('a')) return;

                    isDragging = true;
                    handleElem.style.cursor = 'grabbing';

                    var pageX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
                    var pageY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;

                    startX = pageX;
                    startY = pageY;

                    var rect = calcElem.getBoundingClientRect();
                    initialLeft = rect.left;
                    initialTop = rect.top;

                    // Switch from right-anchored to absolute left/top
                    calcElem.style.right = 'auto';
                    calcElem.style.bottom = 'auto';
                    calcElem.style.left = initialLeft + 'px';
                    calcElem.style.top = initialTop + 'px';

                    document.addEventListener('mousemove', dragMove);
                    document.addEventListener('mouseup', dragEnd);
                    document.addEventListener('touchmove', dragMove, { passive: false });
                    document.addEventListener('touchend', dragEnd);
                }

                function dragMove(e) {
                    if (!isDragging) return;
                    if (e.cancelable) e.preventDefault();

                    var pageX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
                    var pageY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;

                    var dx = pageX - startX;
                    var dy = pageY - startY;

                    var newLeft = initialLeft + dx;
                    var newTop = initialTop + dy;

                    var maxLeft = window.innerWidth - calcElem.offsetWidth - 12;
                    var maxTop = window.innerHeight - calcElem.offsetHeight - 12;

                    newLeft = Math.max(12, Math.min(newLeft, maxLeft));
                    newTop = Math.max(12, Math.min(newTop, maxTop));

                    calcElem.style.left = newLeft + 'px';
                    calcElem.style.top = newTop + 'px';
                }

                function dragEnd() {
                    if (!isDragging) return;
                    isDragging = false;
                    handleElem.style.cursor = 'grab';

                    document.removeEventListener('mousemove', dragMove);
                    document.removeEventListener('mouseup', dragEnd);
                    document.removeEventListener('touchmove', dragMove);
                    document.removeEventListener('touchend', dragEnd);
                }

                handleElem.addEventListener('mousedown', dragStart);
                handleElem.addEventListener('touchstart', dragStart, { passive: false });
            })();

            // 4. CONVERSION CALCULATOR MATHEMATICAL LOGIC
            var dimensionPresets = {
                mass: [
                    { label: '1 kg', val: 1 },
                    { label: '5 kg', val: 5 },
                    { label: '10 kg', val: 10 },
                    { label: '25 kg Bag', val: 25 },
                    { label: '50 kg Bag', val: 50 },
                    { label: '500 g', val: 0.5 }
                ],
                volume: [
                    { label: '1 tsp', val: 1 },
                    { label: '1 tbsp', val: 1 },
                    { label: '1 Cup', val: 1 },
                    { label: '1 Litre', val: 1 },
                    { label: '5 Litres', val: 5 },
                    { label: '25L Keg', val: 25 }
                ],
                count: [
                    { label: '1 pc', val: 1 },
                    { label: 'Half Doz (6)', val: 6 },
                    { label: '1 Dozen', val: 12 },
                    { label: 'Carton 24', val: 24 },
                    { label: 'Egg Crate 30', val: 30 }
                ]
            };

            function syncTargetUnitsDropdown() {
                var fromId = $('#calcFromUnit').val();
                var fromUnit = ALL_UNITS.find(function(u) { return u.id == fromId; });
                if (!fromUnit) return;

                var prevToId = $('#calcToUnit').val();
                var compatible = ALL_UNITS.filter(function(u) { return u.unit_type === fromUnit.unit_type; });

                var html = '';
                compatible.forEach(function(u) {
                    var isSelected = false;
                    if (prevToId && u.id == prevToId) {
                        isSelected = true;
                    } else if (!prevToId && u.symbol === fromUnit.base_unit) {
                        isSelected = true;
                    }
                    html += '<option value="' + u.id + '" ' + (isSelected ? 'selected' : '') + '>' + u.name + ' (' + u.symbol + ')</option>';
                });
                $('#calcToUnit').html(html);

                // If previously selected wasn't compatible, pick the base unit
                if (!$('#calcToUnit').val()) {
                    var baseU = compatible.find(function(u) { return u.symbol === fromUnit.base_unit; });
                    if (baseU) $('#calcToUnit').val(baseU.id);
                }

                // Update quick presets strip
                updateCalculatorPresets(fromUnit.unit_type);

                recalculateConversion();
            }

            function updateCalculatorPresets(dimType) {
                var list = dimensionPresets[dimType] || [];
                var html = '';
                list.forEach(function(p) {
                    html += '<button type="button" class="btn btn-sm calc-preset-btn btn-calc-preset" data-val="' + p.val + '">' + p.label + '</button>';
                });
                $('#calcQuickPresets').html(html);

                var dimLabels = { mass: 'Mass Presets', volume: 'Liquid / Volume Presets', count: 'Discrete / Count Presets' };
                $('#calcPresetDimNote').text(dimLabels[dimType] || 'Presets');
            }

            function recalculateConversion() {
                var qty = parseFloat($('#calcInputQty').val());
                if (isNaN(qty) || qty <= 0) qty = 0;

                var fromId = $('#calcFromUnit').val();
                var toId = $('#calcToUnit').val();

                var fromUnit = ALL_UNITS.find(function(u) { return u.id == fromId; });
                var toUnit = ALL_UNITS.find(function(u) { return u.id == toId; });

                if (!fromUnit || !toUnit) return;

                // 1. Convert source into base units
                var inBase = qty * parseFloat(fromUnit.base_multiplier);

                // 2. Convert base into target unit
                var targetMult = parseFloat(toUnit.base_multiplier) || 1;
                var result = inBase / targetMult;

                // 3. Render outputs in clear black and muted format
                var formattedResult = result.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 4
                });

                $('#calcOutputValue').text(formattedResult);
                $('#calcOutputUnit').text(toUnit.name + ' (' + toUnit.symbol + ')');

                // Base standard row
                var baseStandardLabel = fromUnit.base_unit === 'g' ? 'Grams (g)' : (fromUnit.base_unit === 'ml' ? 'Millilitres (ml)' : 'Pieces (pcs)');
                var formattedBase = inBase.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 4
                }) + ' ' + fromUnit.base_unit;
                $('#calcBaseEquivalentVal').text(formattedBase);

                // Step tag
                $('#calcFormulaTag').text(qty + ' ' + fromUnit.symbol + ' → ' + toUnit.symbol);
            }

            // Events for calculator
            $('#calcInputQty').on('input keyup change', recalculateConversion);
            $('#calcFromUnit').on('change', syncTargetUnitsDropdown);
            $('#calcToUnit').on('change', recalculateConversion);

            // Stepper buttons
            $('.btn-qty-step').on('click', function() {
                var step = parseFloat($(this).data('step')) || 1;
                var cur = parseFloat($('#calcInputQty').val()) || 0;
                var next = Math.max(0.0001, cur + step);
                $('#calcInputQty').val(next).trigger('change');
            });

            // AC Clear button
            $('#calcClearBtn').on('click', function() {
                $('#calcInputQty').val(1).trigger('change');
            });

            // Swap units button
            $('#calcSwapBtn').on('click', function() {
                var fromId = $('#calcFromUnit').val();
                var toId = $('#calcToUnit').val();
                if (fromId && toId && fromId !== toId) {
                    $('#calcFromUnit').val(toId);
                    syncTargetUnitsDropdown();
                    $('#calcToUnit').val(fromId);
                    recalculateConversion();
                }
            });

            // Quick preset click
            $(document).on('click', '.btn-calc-preset', function() {
                var val = $(this).data('val');
                $('#calcInputQty').val(val).trigger('change');
            });

            // Initial calculator sync
            syncTargetUnitsDropdown();

            // 5. ADD UNIT MODAL PRESETS & LIVE PREVIEW
            var addPresets = {
                mass: [
                    { label: '1 kg', val: 1000 },
                    { label: '5 kg Bag', val: 5000 },
                    { label: '10 kg Bag', val: 10000 },
                    { label: '25 kg Bag', val: 25000 },
                    { label: '50 kg Bag', val: 50000 }
                ],
                volume: [
                    { label: '1 tsp (5ml)', val: 5 },
                    { label: '1 tbsp (15ml)', val: 15 },
                    { label: '1 Cup (250ml)', val: 250 },
                    { label: '1 Litre', val: 1000 },
                    { label: '5L Gallon', val: 5000 },
                    { label: '25L Keg', val: 25000 }
                ],
                count: [
                    { label: 'Half Dozen (6)', val: 6 },
                    { label: 'Dozen (12)', val: 12 },
                    { label: 'Carton 24', val: 24 },
                    { label: 'Egg Crate (30)', val: 30 },
                    { label: 'Gross (144)', val: 144 }
                ]
            };

            function updateAddModalPresets() {
                var type = $('#addUnitType').val();
                var baseMapping = { mass: 'g', volume: 'ml', count: 'pcs' };
                var base = baseMapping[type] || 'g';

                $('#addBaseUnitPreview').text(base);
                $('#addEqBase').text(base);

                var list = addPresets[type] || [];
                var html = '';
                list.forEach(function(p) {
                    html += '<button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 rounded-pill font-size-11 btn-preset-val" data-val="' + p.val + '">' + p.label + '</button>';
                });
                $('#addPresetsContainer').html(html);

                updateAddEquationPreview();
            }

            function updateAddEquationPreview() {
                var sym = $('#addUnitSymbol').val().trim() || 'unit';
                var mult = parseFloat($('#addBaseMultiplier').val()) || 0;
                var type = $('#addUnitType').val();
                var baseMapping = { mass: 'g', volume: 'ml', count: 'pcs' };

                $('#addEqSym').text(sym);
                $('#addEqMult').text(mult.toLocaleString());
                $('#addEqBase').text(baseMapping[type] || 'g');
            }

            $('#addUnitType').on('change', updateAddModalPresets);
            $('#addUnitSymbol').on('input', updateAddEquationPreview);
            $('#addBaseMultiplier').on('input', updateAddEquationPreview);

            $(document).on('click', '.btn-preset-val', function() {
                var val = $(this).data('val');
                $('#addBaseMultiplier').val(val).trigger('input');
            });

            updateAddModalPresets();
        });
    }

    initUnitManagementEngine();
})();
</script>
@endsection
