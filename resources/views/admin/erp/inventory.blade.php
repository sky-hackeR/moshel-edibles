@extends('admin.layout.dashboard')

@section('content')
<style>
    /* ==========================================================================
       MOSHEL EXECUTIVE INVENTORY MANAGEMENT STYLES
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
    $totalInventoryValuation = 0;

    foreach ($inventories as $inv) {
        $qty = (float) $inv->quantity;
        $cost = (float) $inv->average_cost;
        $totalInventoryValuation += ($qty * $cost);
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
                    <li class="breadcrumb-item active">Raw Materials Inventory</li>
                </ol>
            </div>
            <div class="d-flex align-items-center flex-wrap gap-2">
                <a href="{{ route('stockIn') }}" class="btn btn-primary rounded-pill px-3 py-1.5 font-size-12 fw-medium shadow-sm">
                    <i class="bx bx-plus me-1 font-size-14"></i> Record Stock In
                </a>
                <a href="{{ route('openingStock') }}" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 font-size-12 fw-medium">
                    <i class="bx bx-archive-in me-1"></i> Opening Stock
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
    {{-- Card 1: Cataloged Raw Materials --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 inventory-kpi-card" style="border-left: 4px solid #059669 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Cataloged Materials</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ $totalInventoryItems }}</h4>
                        <small class="text-success font-size-11 fw-semibold">
                            <i class="bx bx-check-circle me-1"></i>Active Stock Records
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

    {{-- Card 2: Reorder & Low Stock Alerts --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 inventory-kpi-card" style="border-left: 4px solid {{ $totalAlerts > 0 ? '#dc2626' : '#f59e0b' }} !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Stock Alerts</p>
                        <h4 class="mb-0 fw-bold {{ $totalAlerts > 0 ? 'text-danger' : 'text-dark' }}">{{ $totalAlerts }}</h4>
                        @if($outOfStockCount > 0)
                            <small class="text-danger font-size-11 fw-bold">
                                <i class="bx bx-error-circle me-1"></i>{{ $outOfStockCount }} Depleted, {{ $lowStockCount }} Low
                            </small>
                        @elseif($lowStockCount > 0)
                            <small class="text-warning font-size-11 fw-semibold">
                                <i class="bx bx-error me-1"></i>{{ $lowStockCount }} Below Threshold
                            </small>
                        @else
                            <small class="text-success font-size-11 fw-medium">
                                <i class="bx bx-check me-1"></i>Stock Levels Healthy
                            </small>
                        @endif
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 {{ $totalAlerts > 0 ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning' }}">
                            <i class="bx bx-bell"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 3: Inventory Valuation --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 inventory-kpi-card" style="border-left: 4px solid #4f46e5 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div style="min-width: 0;">
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Inventory Valuation</p>
                        <h4 class="mb-0 fw-bold text-dark text-truncate" title="₦{{ number_format($totalInventoryValuation, 2) }}">
                            ₦{{ number_format($totalInventoryValuation, 2) }}
                        </h4>
                        <small class="text-primary font-size-11 fw-medium">
                            <i class="bx bx-wallet me-1"></i>Weighted On-Hand Cost
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

    {{-- Card 4: Optimal Stock Health --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 inventory-kpi-card" style="border-left: 4px solid #0284c7 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Optimal Health</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ $optimalStockCount }}</h4>
                        <small class="text-info font-size-11 fw-semibold">
                            <i class="bx bx-shield-quarter me-1"></i>{{ $totalInventoryItems > 0 ? round(($optimalStockCount / $totalInventoryItems) * 100) : 100 }}% Ready for Production
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
            <small class="text-muted">Live ledger tracking raw material balances, average unit costs, and replenishment thresholds.</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill bg-light text-dark border px-3 py-1 font-size-11">
                {{ $totalInventoryItems }} {{ Str::plural('Item', $totalInventoryItems) }} Tracked
            </span>
        </div>
    </div>

    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-hover align-middle dt-responsive nowrap w-100 font-size-13 mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">S/N</th>
                        <th style="min-width: 220px;">Raw Material</th>
                        <th style="min-width: 120px;">Base Unit</th>
                        <th style="min-width: 140px;">Stock on Hand</th>
                        <th style="min-width: 160px;">Reorder Level & Status</th>
                        <th style="min-width: 130px;">Average Cost</th>
                        <th style="min-width: 140px;">Total Valuation</th>
                        <th style="min-width: 130px;">Last Activity</th>
                        <th class="text-center" style="width: 110px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventories as $inventory)
                        @php
                            $qty = (float) $inventory->quantity;
                            $cost = (float) $inventory->average_cost;
                            $valuation = $qty * $cost;
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

                            {{-- REORDER LEVEL & STATUS --}}
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <div>
                                        <span class="badge rounded-pill {{ $statusBadge }} px-2 py-0.5 font-size-11">
                                            <i class="{{ $statusIcon }} me-1"></i>{{ $statusText }}
                                        </span>
                                    </div>
                                    <small class="text-muted font-size-11">
                                        Threshold: <span class="fw-semibold text-dark">{{ number_format($threshold, 3) }} {{ $unitSymbol }}</span>
                                    </small>
                                </div>
                            </td>

                            {{-- AVERAGE COST --}}
                            <td>
                                <div class="fw-semibold text-dark font-size-13">
                                    ₦{{ number_format($cost, 2) }}
                                </div>
                                <small class="text-muted font-size-11">per {{ $unitSymbol }}</small>
                            </td>

                            {{-- TOTAL VALUATION --}}
                            <td>
                                <div class="fw-bold font-size-13 {{ $valuation > 0 ? 'text-primary' : 'text-muted' }}">
                                    ₦{{ number_format($valuation, 2) }}
                                </div>
                                <small class="text-muted font-size-11">
                                    {{ $qty > 0 ? 'Asset valuation' : 'Zero balance' }}
                                </small>
                            </td>

                            {{-- LAST ACTIVITY --}}
                            <td>
                                <div class="text-dark font-size-12" title="{{ $inventory->updated_at->format('M d, Y h:i A') }}">
                                    <i class="mdi mdi-clock-outline me-1 text-muted"></i>{{ $inventory->updated_at->diffForHumans() }}
                                </div>
                                <small class="text-muted font-size-11">{{ $inventory->updated_at->format('M d, Y') }}</small>
                            </td>

                            {{-- ACTIONS --}}
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <a href="{{ route('stockIn') }}"
                                       class="btn btn-sm btn-soft-primary rounded-pill px-2.5 py-1 font-size-11 fw-medium"
                                       title="Purchase & Restock Material">
                                        <i class="bx bx-plus me-1"></i> Restock
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
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
