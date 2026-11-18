@extends('staff.layout.dashboard')

@section('content')
<style>
    /* ==========================================================================
       MOSHEL STAFF INVENTORY MANAGEMENT STYLES
       ========================================================================== */
    .inventory-kpi-card {
        border-radius: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .inventory-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06) !important;
    }
    .inventory-icon-avatar {
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
</style>

@php
    $totalInventoryItems = $inventories->count();
    $lowStockCount = 0;
    $outOfStockCount = 0;
    $optimalStockCount = 0;

    foreach ($inventories as $inv) {
        $qty = (float) $inv->quantity;
        $threshold = $inv->ingredient ? (float) $inv->ingredient->effective_reorder_level : 0;

        if ($qty <= 0) {
            $outOfStockCount++;
        } elseif ($qty <= $threshold) {
            $lowStockCount++;
        } else {
            $optimalStockCount++;
        }
    }
    $totalAlerts = $lowStockCount + $outOfStockCount;
@endphp

{{-- =====================================================
     BREADCRUMB & PAGE HEADER
     ===================================================== --}}
<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between pb-2">
            <div>
                <h4 class="mb-sm-0 font-size-18 fw-bold">Inventory Management</h4>
                <ol class="breadcrumb m-0 mt-1">
                    <li class="breadcrumb-item active">Raw Materials Stock</li>
                </ol>
            </div>
            <div class="d-flex align-items-center flex-wrap gap-2">
                <a href="{{ route('production') }}" class="btn btn-primary rounded-pill px-3 py-1.5 font-size-12 fw-medium shadow-sm">
                    <i class="bx bx-layer me-1 font-size-14"></i> Production Workbench
                </a>
                <a href="{{ route('pos') }}" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 font-size-12 fw-medium">
                    <i class="bx bx-calculator me-1"></i> POS Terminal
                </a>
            </div>
        </div>
    </div>
</div>

{{-- =====================================================
     OPERATIONAL KPI STATS ROW
     ===================================================== --}}
<div class="row g-3 mb-4">
    {{-- Card 1: Cataloged Raw Materials --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 inventory-kpi-card" style="border-left: 4px solid #059669 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Raw Materials Tracked</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ $totalInventoryItems }}</h4>
                        <small class="text-success font-size-11 fw-semibold">
                            <i class="bx bx-package me-1"></i>Kitchen & Bakery Inputs
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-success-subtle text-success">
                            <i class="bx bx-cube"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Reorder Alerts --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 inventory-kpi-card" style="border-left: 4px solid #f59e0b !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Low Stock Alerts</p>
                        <h4 class="mb-0 fw-bold {{ $lowStockCount > 0 ? 'text-warning' : 'text-dark' }}">{{ $lowStockCount }}</h4>
                        <small class="text-warning font-size-11 fw-medium">
                            <i class="bx bx-bell me-1"></i>Approaching Reorder Level
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-warning-subtle text-warning">
                            <i class="bx bx-bell"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 3: Out of Stock --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 inventory-kpi-card" style="border-left: 4px solid #dc2626 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Depleted Stock</p>
                        <h4 class="mb-0 fw-bold {{ $outOfStockCount > 0 ? 'text-danger' : 'text-dark' }}">{{ $outOfStockCount }}</h4>
                        <small class="{{ $outOfStockCount > 0 ? 'text-danger' : 'text-muted' }} font-size-11 fw-semibold">
                            <i class="bx bx-error-circle me-1"></i>{{ $outOfStockCount > 0 ? 'Unavailable for Production' : 'Zero Depletions' }}
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-danger-subtle text-danger">
                            <i class="bx bx-x-circle"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 4: Production Ready --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 inventory-kpi-card" style="border-left: 4px solid #0284c7 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Ready for Production</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ $optimalStockCount }}</h4>
                        <small class="text-info font-size-11 fw-semibold">
                            <i class="bx bx-check-circle me-1"></i>Sufficient Stock on Hand
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-info-subtle text-info">
                            <i class="bx bx-check-shield"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- =====================================================
     MAIN INVENTORY DIRECTORY CARD & TABLE
     ===================================================== --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-white border-bottom py-3 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h5 class="card-title mb-0 fw-bold text-dark">
                <i class="bx bx-cube me-1 text-primary"></i> Real-Time Inventory Stock Levels
            </h5>
            <small class="text-muted">Live view of kitchen raw materials and production ingredient availability.</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill bg-light text-dark border px-3 py-1 font-size-11">
                {{ $totalInventoryItems }} {{ Str::plural('Item', $totalInventoryItems) }} Available
            </span>
        </div>
    </div>

    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-hover align-middle dt-responsive nowrap w-100 font-size-13 mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">S/N</th>
                        <th style="min-width: 240px;">Raw Material</th>
                        <th style="min-width: 130px;">Base Unit</th>
                        <th style="min-width: 150px;">Stock on Hand</th>
                        <th style="min-width: 160px;">Reorder Threshold</th>
                        <th style="min-width: 140px;">Stock Status</th>
                        <th style="min-width: 140px;">Last Activity</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventories as $inventory)
                        @php
                            $qty = (float) $inventory->quantity;
                            $threshold = $inventory->ingredient ? (float) $inventory->ingredient->effective_reorder_level : 0;
                            $unitSymbol = $inventory->ingredient?->baseUnit?->symbol ?? 'unit';
                            $unitName = $inventory->ingredient?->baseUnit?->name ?? 'Base Unit';
                            $unitType = $inventory->ingredient?->baseUnit?->unit_type ?? 'mass';

                            $typeIcon = match($unitType) {
                                'mass' => 'mdi-scale-balance',
                                'volume' => 'mdi-waves',
                                'count' => 'mdi-archive',
                                default => 'mdi-cube-outline'
                            };

                            $avatarColor = match($unitType) {
                                'mass' => 'background: #fef3c7; color: #b45309;',
                                'volume' => 'background: #e0f2fe; color: #0369a1;',
                                'count' => 'background: #dcfce7; color: #15803d;',
                                default => 'background: #e0e7ff; color: #4338ca;'
                            };

                            if ($qty <= 0) {
                                $statusBadge = 'bg-danger-subtle text-danger border border-danger-subtle';
                                $statusIcon = 'bx bx-x-circle';
                                $statusText = 'Out of Stock';
                            } elseif ($qty <= $threshold) {
                                $statusBadge = 'bg-warning-subtle text-warning border border-warning-subtle';
                                $statusIcon = 'bx bx-error';
                                $statusText = 'Low Stock';
                            } else {
                                $statusBadge = 'bg-success-subtle text-success border border-success-subtle';
                                $statusIcon = 'bx bx-check-circle';
                                $statusText = 'Optimal';
                            }
                        @endphp
                        <tr>
                            {{-- S/N --}}
                            <td class="text-center text-muted fw-semibold">
                                {{ $loop->iteration }}
                            </td>

                            {{-- RAW MATERIAL --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="inventory-icon-avatar me-2 shadow-sm" style="{{ $avatarColor }}">
                                        {{ strtoupper(substr($inventory->ingredient?->name ?? 'M', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark font-size-13">
                                            {{ $inventory->ingredient?->name ?? 'Uncataloged Item' }}
                                        </div>
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="text-muted font-monospace font-size-11">
                                                {{ $inventory->ingredient?->slug ?: Str::slug($inventory->ingredient?->name ?? 'item') }}
                                            </span>
                                            @if($inventory->ingredient && !$inventory->ingredient->is_active)
                                                <span class="badge bg-secondary-subtle text-secondary font-size-10 px-1 py-0">Inactive</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- BASE UNIT --}}
                            <td>
                                <span class="badge rounded-pill bg-light text-dark border px-2.5 py-1 font-size-11" title="{{ $unitName }}">
                                    <i class="mdi {{ $typeIcon }} me-1 text-muted"></i>{{ $unitName }}
                                </span>
                            </td>

                            {{-- STOCK ON HAND --}}
                            <td>
                                <div class="fw-bold font-size-14 {{ $qty <= 0 ? 'text-danger' : 'text-dark' }}">
                                    {{ number_format($qty, 3) }} <span class="font-size-11 fw-normal text-muted">{{ $unitSymbol }}</span>
                                </div>
                                @if($threshold > 0)
                                    <small class="text-muted font-size-11">
                                        {{ round(($qty / $threshold) * 100) }}% of reorder target
                                    </small>
                                @endif
                            </td>

                            {{-- REORDER THRESHOLD --}}
                            <td>
                                <div class="fw-medium text-dark font-size-12">
                                    {{ number_format($threshold, 3) }} {{ $unitSymbol }}
                                </div>
                                <small class="text-muted font-size-11">Minimum safe stock</small>
                            </td>

                            {{-- STOCK STATUS --}}
                            <td>
                                <span class="badge rounded-pill {{ $statusBadge }} px-2.5 py-1 font-size-11">
                                    <i class="{{ $statusIcon }} me-1"></i>{{ $statusText }}
                                </span>
                            </td>

                            {{-- LAST ACTIVITY --}}
                            <td>
                                <div class="text-dark font-size-12" title="{{ $inventory->updated_at->format('M d, Y h:i A') }}">
                                    <i class="mdi mdi-clock-outline me-1 text-muted"></i>{{ $inventory->updated_at->diffForHumans() }}
                                </div>
                                <small class="text-muted font-size-11">{{ $inventory->updated_at->format('M d, Y') }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bx bx-cube font-size-24 d-block mb-1 text-muted"></i>
                                No inventory records found in ledger.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
