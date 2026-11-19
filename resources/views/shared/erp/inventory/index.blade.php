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
    $portal = $portal ?? 'admin';
    $isAdmin = ($portal === 'admin');

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
                    <li class="breadcrumb-item active">{{ $isAdmin ? 'Raw Materials Inventory' : 'Raw Materials Stock' }}</li>
                </ol>
            </div>
            <div class="d-flex align-items-center flex-wrap gap-2">
                @if($isAdmin)
                    <a href="{{ route('stockIn') }}" class="btn btn-primary rounded-pill px-3 py-1.5 font-size-12 fw-medium shadow-sm">
                        <i class="bx bx-plus me-1 font-size-14"></i> Record Stock In
                    </a>
                    <a href="{{ route('openingStock') }}" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 font-size-12 fw-medium">
                        <i class="bx bx-archive-in me-1"></i> Opening Stock
                    </a>
                    <a href="{{ route('ingredients') }}" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 font-size-12 fw-medium">
                        <i class="bx bx-food-menu me-1"></i> Ingredients Catalog
                    </a>
                @else
                    <a href="{{ route('production') }}" class="btn btn-primary rounded-pill px-3 py-1.5 font-size-12 fw-medium shadow-sm">
                        <i class="bx bx-layer me-1 font-size-14"></i> Production Workbench
                    </a>
                    <a href="{{ route('pos') }}" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 font-size-12 fw-medium">
                        <i class="bx bx-calculator me-1"></i> POS Terminal
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- =====================================================
     KPI STATS ROW
     ===================================================== --}}
<div class="row g-3 mb-4">
    @if($isAdmin)
        {{-- Card 1: Cataloged Raw Materials --}}
        <div class="col-sm-6 col-xl-3">
            <x-kpi-card 
                label="Cataloged Materials" 
                :value="$totalInventoryItems" 
                color="success" 
                border-color="#059669" 
                icon="bx bx-cube" 
                subtitle="Active Stock Records" 
                subicon="bx bx-check-circle" 
                subcolor="text-success" />
        </div>

        {{-- Card 2: Reorder & Low Stock Alerts --}}
        <div class="col-sm-6 col-xl-3">
            <x-kpi-card 
                label="Stock Alerts" 
                :value="$totalAlerts" 
                :color="$totalAlerts > 0 ? 'danger' : 'warning'" 
                :border-color="$totalAlerts > 0 ? '#dc2626' : '#f59e0b'" 
                icon="bx bx-bell" 
                :subtitle="$outOfStockCount > 0 ? ($outOfStockCount . ' Depleted, ' . $lowStockCount . ' Low') : ($lowStockCount > 0 ? ($lowStockCount . ' Below Threshold') : 'Stock Levels Healthy')" 
                :subicon="$outOfStockCount > 0 ? 'bx bx-error-circle' : ($lowStockCount > 0 ? 'bx bx-error' : 'bx bx-check')" 
                :subcolor="$outOfStockCount > 0 ? 'text-danger' : ($lowStockCount > 0 ? 'text-warning' : 'text-success')" />
        </div>

        {{-- Card 3: Inventory Valuation --}}
        <div class="col-sm-6 col-xl-3">
            <x-kpi-card 
                label="Inventory Valuation" 
                :value="'₦' . number_format($totalInventoryValuation, 2)" 
                color="primary" 
                border-color="#4f46e5" 
                icon="bx bx-wallet" 
                subtitle="Weighted On-Hand Cost" 
                subicon="bx bx-wallet" 
                subcolor="text-primary" />
        </div>

        {{-- Card 4: Optimal Stock Health --}}
        <div class="col-sm-6 col-xl-3">
            <x-kpi-card 
                label="Optimal Health" 
                :value="$optimalStockCount" 
                color="info" 
                border-color="#0284c7" 
                icon="bx bx-check-shield" 
                :subtitle="($totalInventoryItems > 0 ? round(($optimalStockCount / $totalInventoryItems) * 100) : 100) . '% Ready for Production'" 
                subicon="bx bx-shield-quarter" 
                subcolor="text-info" />
        </div>
    @else
        {{-- Card 1: Raw Materials Tracked --}}
        <div class="col-sm-6 col-xl-3">
            <x-kpi-card 
                label="Raw Materials Tracked" 
                :value="$totalInventoryItems" 
                color="success" 
                border-color="#059669" 
                icon="bx bx-cube" 
                subtitle="Kitchen & Bakery Inputs" 
                subicon="bx bx-package" 
                subcolor="text-success" />
        </div>

        {{-- Card 2: Reorder Alerts --}}
        <div class="col-sm-6 col-xl-3">
            <x-kpi-card 
                label="Low Stock Alerts" 
                :value="$lowStockCount" 
                color="warning" 
                border-color="#f59e0b" 
                icon="bx bx-bell" 
                subtitle="Approaching Reorder Level" 
                subicon="bx bx-bell" 
                subcolor="text-warning" />
        </div>

        {{-- Card 3: Out of Stock --}}
        <div class="col-sm-6 col-xl-3">
            <x-kpi-card 
                label="Depleted Stock" 
                :value="$outOfStockCount" 
                color="danger" 
                border-color="#dc2626" 
                icon="bx bx-x-circle" 
                :subtitle="$outOfStockCount > 0 ? 'Unavailable for Production' : 'Zero Depletions'" 
                :subicon="$outOfStockCount > 0 ? 'bx bx-error-circle' : 'bx bx-check'" 
                :subcolor="$outOfStockCount > 0 ? 'text-danger' : 'text-muted'" />
        </div>

        {{-- Card 4: Production Ready --}}
        <div class="col-sm-6 col-xl-3">
            <x-kpi-card 
                label="Ready for Production" 
                :value="$optimalStockCount" 
                color="info" 
                border-color="#0284c7" 
                icon="bx bx-check-shield" 
                subtitle="Sufficient Stock on Hand" 
                subicon="bx bx-check-circle" 
                subcolor="text-info" />
        </div>
    @endif
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
            <small class="text-muted">
                {{ $isAdmin ? 'Live ledger tracking raw material balances, average unit costs, and replenishment thresholds.' : 'Live view of kitchen raw materials and production ingredient availability.' }}
            </small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill bg-light text-dark border px-3 py-1 font-size-11">
                {{ $totalInventoryItems }} {{ Str::plural('Item', $totalInventoryItems) }} {{ $isAdmin ? 'Tracked' : 'Available' }}
            </span>
        </div>
    </div>

    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-hover align-middle dt-responsive nowrap w-100 font-size-13 mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">S/N</th>
                        <th style="min-width: {{ $isAdmin ? '220px' : '240px' }};">Raw Material</th>
                        <th style="min-width: {{ $isAdmin ? '120px' : '130px' }};">Base Unit</th>
                        <th style="min-width: {{ $isAdmin ? '140px' : '150px' }};">Stock on Hand</th>
                        <th style="min-width: 160px;">{{ $isAdmin ? 'Reorder Level & Status' : 'Reorder Threshold' }}</th>
                        @if($isAdmin)
                            <th style="min-width: 130px;">Average Cost</th>
                            <th style="min-width: 140px;">Total Valuation</th>
                        @else
                            <th style="min-width: 140px;">Stock Status</th>
                        @endif
                        <th style="min-width: {{ $isAdmin ? '130px' : '140px' }};">Last Activity</th>
                        @if($isAdmin)
                            <th class="text-center" style="width: 110px;">Actions</th>
                        @endif
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

                            {{-- REORDER LEVEL / THRESHOLD --}}
                            @if($isAdmin)
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
                            @else
                                <td>
                                    <div class="fw-medium text-dark font-size-12">
                                        {{ number_format($threshold, 3) }} {{ $unitSymbol }}
                                    </div>
                                    <small class="text-muted font-size-11">Minimum safe stock</small>
                                </td>
                            @endif

                            {{-- STAFF: STOCK STATUS / ADMIN: FINANCIALS --}}
                            @if($isAdmin)
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
                            @else
                                <td>
                                    <span class="badge rounded-pill {{ $statusBadge }} px-2.5 py-1 font-size-11">
                                        <i class="{{ $statusIcon }} me-1"></i>{{ $statusText }}
                                    </span>
                                </td>
                            @endif

                            {{-- LAST ACTIVITY --}}
                            <td>
                                <div class="text-dark font-size-12" title="{{ $inventory->updated_at->format('M d, Y h:i A') }}">
                                    <i class="mdi mdi-clock-outline me-1 text-muted"></i>{{ $inventory->updated_at->diffForHumans() }}
                                </div>
                                <small class="text-muted font-size-11">{{ $inventory->updated_at->format('M d, Y') }}</small>
                            </td>

                            {{-- ADMIN: RESTOCK ACTION --}}
                            @if($isAdmin)
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <a href="{{ route('stockIn') }}"
                                           class="btn btn-sm btn-soft-primary rounded-pill px-2.5 py-1 font-size-11 fw-medium"
                                           title="Purchase & Restock Material">
                                            <i class="bx bx-plus me-1"></i> Restock
                                        </a>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isAdmin ? '9' : '7' }}" class="text-center py-5 text-muted">
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
