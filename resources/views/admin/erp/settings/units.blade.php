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
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Defined Units"
            value="{{ $totalUnits }}"
            subtitle="{{ $activeUnits }} Active in Registry"
            subicon="bx bx-check-circle"
            color="purple"
            borderColor="#581a62"
            icon="bx bx-ruler"
            cardClass="unit-kpi-card"
        />
    </div>
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Mass / Weight"
            value="{{ $massUnits->count() }}"
            color="warning"
            borderColor="#f59e0b"
            icon="mdi mdi-weight-kilogram"
            cardClass="unit-kpi-card"
        >
            <small class="text-warning font-size-11 fw-semibold d-block mt-1">
                <i class="bx bx-lock-alt me-1"></i>Base: <b>1.0000 g</b> (Gram)
            </small>
        </x-kpi-card>
    </div>
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Liquid / Volume"
            value="{{ $volumeUnits->count() }}"
            color="info"
            borderColor="#0284c7"
            icon="mdi mdi-cup-water"
            cardClass="unit-kpi-card"
        >
            <small class="text-info font-size-11 fw-semibold d-block mt-1">
                <i class="bx bx-lock-alt me-1"></i>Base: <b>1.0000 ml</b> (Millilitre)
            </small>
        </x-kpi-card>
    </div>
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Discrete / Count"
            value="{{ $countUnits->count() }}"
            color="success"
            borderColor="#10b981"
            icon="bx bx-package"
            cardClass="unit-kpi-card"
        >
            <small class="text-success font-size-11 fw-semibold d-block mt-1">
                <i class="bx bx-lock-alt me-1"></i>Base: <b>1.0000 pcs</b> (Piece)
            </small>
        </x-kpi-card>
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
{{-- MODULAR PARTIALS: FLOATING CALCULATOR, MODALS & CLIENT SCRIPTS           --}}
{{-- ========================================================================= --}}

{{-- Floating Draggable Conversion Calculator Widget --}}
@include('admin.erp.partials.workbenches.unit-floating-calculator')

{{-- Add Custom Unit Modal --}}
@include('admin.erp.partials.modals.units.add-unit-modal')

{{-- Edit & Delete Unit Modals (Looping over units) --}}
@include('admin.erp.partials.modals.units.unit-modals')

{{-- Client-side JavaScript Engine & Event Handlers --}}
@include('admin.erp.partials.scripts.unit-management-scripts')
@endsection
