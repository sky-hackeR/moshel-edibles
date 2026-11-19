@extends('admin.layout.dashboard')

@section('content')

    {{-- PAGE HEADER --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
                <div>
                    <h4 class="mb-sm-0 font-size-18 text-primary fw-bold">Production Audit & Batch History</h4>
                    <p class="text-muted font-size-12 mb-0">Complete historical records of completed bakery batches, raw material deductions, and yields.</p>
                </div>
                <div class="page-title-right d-flex align-items-center">
                    <a href="{{ url('admin/production') }}" class="btn btn-primary shadow-sm">
                        <i class="bx bx-plus-circle me-1 font-size-15 align-middle"></i> Record New Batch
                    </a>
                    <ol class="breadcrumb m-0 ms-3 d-none d-md-flex">
                        <li class="breadcrumb-item active">Production History</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @php
        $totalBatches = $history->count();
        $totalUnitsProduced = $history->sum('quantity');
        $totalCost = $history->sum('total_cost');
        $totalRevenue = $history->sum('expected_revenue');
        $totalProfit = $history->sum('profit');
        $overallMargin = $totalRevenue > 0 ? round(($totalProfit / $totalRevenue) * 100, 1) : 0;
    @endphp

    {{-- EXECUTIVE KPI METRIC CARDS --}}
    <div class="row g-3 mb-4">
        {{-- Total Batches --}}
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #581a62 !important;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Total Batches Run</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ number_format($totalBatches) }}</h3>
                            <small class="text-muted font-size-11">Recorded lifetime batches</small>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-white text-white rounded-circle font-size-20">
                                <i class="bx bx-history"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Units Produced --}}
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #3b82f6 !important;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Volume Output</p>
                            <h3 class="mb-0 fw-bold text-primary">{{ number_format($totalUnitsProduced) }}</h3>
                            <small class="text-muted font-size-11">Total finished units</small>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info text-white rounded-circle font-size-20">
                                <i class="mdi mdi-cookie"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Production Cost --}}
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #ef4444 !important;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Raw Material Cost</p>
                            <h3 class="mb-0 fw-bold text-danger">₦{{ number_format($totalCost, 2) }}</h3>
                            <small class="text-muted font-size-11">Total inventory consumed</small>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-danger text-white rounded-circle font-size-20">
                                <i class="bx bx-receipt"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Net Production Profit / Loss --}}
        @php
            $isProfitable = $totalProfit >= 0;
            $profitBorderColor = $isProfitable ? '#10b981' : '#ef4444';
            $profitTextColor = $isProfitable ? 'text-success' : 'text-danger';
            $profitBgSoft = $isProfitable ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger';
            $profitIcon = $isProfitable ? 'bx bx-trending-up' : 'bx bx-trending-down';
            $profitTitle = $isProfitable ? 'Projected Yield Profit' : 'Projected Yield Loss';
            $profitBadgeText = $isProfitable ? "{$overallMargin}% Gross Margin" : abs($overallMargin) . "% Loss Margin";
        @endphp
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid {{ $profitBorderColor }} !important;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">{{ $profitTitle }}</p>
                            <h3 class="mb-0 fw-bold {{ $profitTextColor }}">
                                {{ $isProfitable ? '+' : '-' }}₦{{ number_format(abs($totalProfit), 2) }}
                            </h3>
                            <span class="badge {{ $profitBgSoft }} font-size-11 fw-bold">
                                {{ $profitBadgeText }}
                            </span>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title {{ $profitBgSoft }} rounded-circle font-size-20">
                                <i class="{{ $profitIcon }}"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN DATA TABLE CARD --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-transparent border-bottom py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bx bx-list-check text-primary me-2 font-size-18"></i>All Production Batches
                        </h5>
                        <small class="text-muted">Itemized breakdown of baking runs, unit economics, and authorizer audit trails.</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-light text-secondary font-size-12 px-3 py-2 border">
                            <i class="bx bx-check-circle text-success me-1"></i> {{ $totalBatches }} Total Batches
                        </span>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-hover align-middle w-100">
                            <thead class="table-light">
                                <tr class="text-muted text-uppercase font-size-11">
                                    <th style="width: 50px;">#</th>
                                    <th>Batch Date & Ref</th>
                                    <th>Finished Product</th>
                                    <th class="text-center">Quantity Produced</th>
                                    <th class="text-end">Batch Cost</th>
                                    <th class="text-end">Retail Value</th>
                                    <th class="text-center">Profit & Margin</th>
                                    <th>Recorded By</th>
                                    <th class="text-center" style="width: 100px;">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($history as $record)
                                    @php
                                        $marginPct = $record->expected_revenue > 0 
                                            ? round(($record->profit / $record->expected_revenue) * 100, 1) 
                                            : 0;
                                        $salesUnit = $record->product->sales_unit ?? 'Units';
                                    @endphp
                                    <tr>
                                        <td class="text-muted font-size-12 fw-bold">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark font-size-13">
                                                {{ $record->created_at->format('d M, Y') }}
                                            </div>
                                            <small class="text-muted font-size-11">
                                                <i class="mdi mdi-clock-outline me-1"></i>{{ $record->created_at->format('h:i A') }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark font-size-14">
                                                {{ $record->product->name ?? 'Unknown Product' }}
                                            </div>
                                            <small class="text-muted font-size-11">
                                                Selling Price: ₦{{ number_format($record->selling_price, 2) }} / {{ $salesUnit }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-soft-primary text-primary font-size-13 px-2 py-1 fw-bold">
                                                {{ number_format($record->quantity) }} {{ $salesUnit }}
                                            </span>
                                            <div class="text-muted font-size-11 mt-1">
                                                ₦{{ number_format($record->unit_cost, 2) }} / {{ $salesUnit }}
                                            </div>
                                        </td>
                                        <td class="text-end fw-bold text-danger font-size-13">
                                            ₦{{ number_format($record->total_cost, 2) }}
                                        </td>
                                        <td class="text-end fw-bold text-dark font-size-13">
                                            ₦{{ number_format($record->expected_revenue, 2) }}
                                        </td>
                                        <td class="text-center">
                                            @if($record->profit >= 0)
                                                <span class="badge bg-soft-success text-success font-size-12 px-2 py-1 fw-bold">
                                                    +₦{{ number_format($record->profit, 2) }}
                                                </span>
                                                <div class="text-success font-size-10 fw-bold mt-1">
                                                    {{ $marginPct }}% Margin
                                                </div>
                                            @else
                                                <span class="badge bg-soft-danger text-danger font-size-12 px-2 py-1 fw-bold">
                                                    -₦{{ number_format(abs($record->profit), 2) }}
                                                </span>
                                                <div class="text-danger font-size-10 fw-bold mt-1">
                                                    Loss
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($record->staff_id && $record->staff)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs me-2">
                                                        <span class="avatar-title rounded-circle bg-soft-info text-white font-size-11">
                                                            {{ substr($record->staff->name, 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <span class="fw-bold text-dark font-size-12 d-block">{{ $record->staff->name }}</span>
                                                        <span class="badge bg-light text-muted border font-size-10">Staff</span>
                                                    </div>
                                                </div>
                                            @elseif($record->admin_id && $record->admin)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs me-2">
                                                        <span class="avatar-title rounded-circle bg-soft-primary text-white font-size-11">
                                                            {{ substr($record->admin->name, 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <span class="fw-bold text-dark font-size-12 d-block">{{ $record->admin->name }}</span>
                                                        <span class="badge bg-soft-primary text-primary font-size-10">Admin</span>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="badge bg-light text-muted">System Automated</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-primary px-3 shadow-none"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewBatch{{ $record->id }}"
                                                    title="View Batch Recipe Audit">
                                                <i class="mdi mdi-eye me-1"></i> Audit
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <div class="avatar-md mx-auto mb-3">
                                                <span class="avatar-title bg-light text-muted rounded-circle font-size-24">
                                                    <i class="bx bx-receipt"></i>
                                                </span>
                                            </div>
                                            <h5 class="fw-bold text-dark">No Production Records Found</h5>
                                            <p class="text-muted font-size-13 mb-3">No bakery production runs have been recorded yet.</p>
                                            <a href="{{ url('admin/production') }}" class="btn btn-primary btn-sm">
                                                Record First Production Batch
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- REDESIGNED DETAIL AUDIT MODALS --}}
    @foreach($history as $record)
        @php
            $modalSalesUnit = $record->product->sales_unit ?? 'Units';
            $modalMarginPct = $record->expected_revenue > 0 
                ? round(($record->profit / $record->expected_revenue) * 100, 1) 
                : 0;
        @endphp
        <div class="modal fade" id="viewBatch{{ $record->id }}" tabindex="-1" aria-labelledby="batchTitle{{ $record->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 14px;">

                    {{-- MODAL HEADER --}}
                    <div class="modal-header bg-light border-bottom py-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar-title bg-primary text-white rounded-circle p-2 font-size-18 d-inline-flex" style="width: 36px; height: 36px;">
                                <i class="mdi mdi-cookie"></i>
                            </span>
                            <div>
                                <h5 class="modal-title fw-bold text-dark mb-0" id="batchTitle{{ $record->id }}">
                                    {{ $record->product->name ?? 'Production Batch' }}
                                </h5>
                                <small class="text-muted font-size-12">Batch #{{ $record->id }} &bull; Recorded on {{ $record->created_at->format('M d, Y | h:i A') }}</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    {{-- MODAL BODY --}}
                    <div class="modal-body p-4">
                        
                        {{-- 4-METRIC STRIP --}}
                        <div class="row g-2 mb-3 text-center">
                            <div class="col-6 col-md-3">
                                <div class="p-3 bg-light rounded border">
                                    <small class="text-muted d-block font-size-11 text-uppercase fw-bold">Output Yield</small>
                                    <h5 class="fw-bold text-primary mb-0 font-size-15">{{ number_format($record->quantity) }} {{ $modalSalesUnit }}</h5>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 bg-light rounded border">
                                    <small class="text-muted d-block font-size-11 text-uppercase fw-bold">Unit Cost</small>
                                    <h5 class="fw-bold text-dark mb-0 font-size-15">₦{{ number_format($record->unit_cost, 2) }}</h5>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 bg-light rounded border">
                                    <small class="text-muted d-block font-size-11 text-uppercase fw-bold">Total Batch Cost</small>
                                    <h5 class="fw-bold text-danger mb-0 font-size-15">₦{{ number_format($record->total_cost, 2) }}</h5>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 bg-light rounded border">
                                    <small class="text-muted d-block font-size-11 text-uppercase fw-bold">Expected Revenue</small>
                                    <h5 class="fw-bold text-dark mb-0 font-size-15">₦{{ number_format($record->expected_revenue, 2) }}</h5>
                                </div>
                            </div>
                        </div>

                        {{-- BATCH PROFIT / LOSS BANNER --}}
                        <div class="p-2 px-3 {{ $record->profit >= 0 ? 'bg-soft-success border border-success' : 'bg-soft-danger border border-danger' }} rounded d-flex justify-content-between align-items-center mb-4">
                            <span class="font-size-13 {{ $record->profit >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                <i class="bx {{ $record->profit >= 0 ? 'bx-trending-up' : 'bx-trending-down' }} me-1 font-size-16 align-middle"></i>
                                Batch Net Yield:
                            </span>
                            <span class="font-size-14 fw-bold {{ $record->profit >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $record->profit >= 0 ? '+' : '-' }}₦{{ number_format(abs($record->profit), 2) }}
                                <span class="badge {{ $record->profit >= 0 ? 'bg-success' : 'bg-danger' }} text-white font-size-11 ms-2">
                                    {{ $modalMarginPct }}% Margin
                                </span>
                            </span>
                        </div>

                        {{-- INGREDIENTS CONSUMED TABLE --}}
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold text-dark mb-0 font-size-14">
                                <i class="bx bx-layer text-primary me-1"></i>Raw Materials Consumed
                            </h6>
                            <span class="badge bg-light text-secondary font-size-11">{{ $record->items->count() }} Ingredients Deducted</span>
                        </div>

                        <div class="table-responsive rounded border mb-3">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="font-size-11 text-uppercase text-muted">
                                        <th>Raw Material</th>
                                        <th class="text-center">Quantity Used</th>
                                        <th class="text-end">Unit Cost</th>
                                        <th class="text-end">Total Line Cost</th>
                                        <th class="text-end" style="width: 80px;">Cost %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($record->items as $item)
                                        @php
                                            $costPct = $record->total_cost > 0 
                                                ? round(($item->total_cost / $record->total_cost) * 100, 1) 
                                                : 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark font-size-13">{{ $item->ingredient->name ?? 'Ingredient' }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-soft-secondary text-dark font-size-12 px-2 py-1">
                                                    {{ number_format($item->quantity_used, 2) }} <small class="text-muted">g/ml</small>
                                                </span>
                                            </td>
                                            <td class="text-end text-muted font-size-12">
                                                ₦{{ number_format($item->unit_cost, 2) }}
                                            </td>
                                            <td class="text-end fw-bold text-dark font-size-13">
                                                ₦{{ number_format($item->total_cost, 2) }}
                                            </td>
                                            <td class="text-end text-muted font-size-11 fw-semibold">
                                                {{ $costPct }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr class="fw-bold">
                                        <td colspan="3" class="text-end text-uppercase font-size-11 text-muted">Total Batch Cost:</td>
                                        <td class="text-end text-danger font-size-14">₦{{ number_format($record->total_cost, 2) }}</td>
                                        <td class="text-end font-size-11 text-muted">100%</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- BAKER NOTES --}}
                        @if($record->notes)
                            <div class="alert alert-light border rounded p-3 mb-0">
                                <div class="d-flex align-items-center gap-1 text-primary fw-bold font-size-12 mb-1">
                                    <i class="bx bx-comment-detail"></i> Baker / Production Shift Notes:
                                </div>
                                <p class="text-dark font-size-13 mb-0 fst-italic">"{{ $record->notes }}"</p>
                            </div>
                        @endif

                    </div>

                    {{-- MODAL FOOTER WITH OPERATOR AUDIT --}}
                    <div class="modal-footer bg-light border-top py-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-xs">
                                <span class="avatar-title rounded-circle bg-soft-primary text-white font-size-13">
                                    <i class="bx bx-user-check"></i>
                                </span>
                            </div>
                            <div class="text-start">
                                <small class="text-muted d-block text-uppercase" style="font-size: 10px;">Executed & Recorded By</small>
                                <span class="fw-bold text-dark font-size-13">
                                    @if($record->staff_id && $record->staff)
                                        {{ $record->staff->name }} <span class="badge bg-light text-muted border font-size-10">Production Staff</span>
                                    @elseif($record->admin_id && $record->admin)
                                        {{ $record->admin->name }} <span class="badge bg-soft-primary text-primary font-size-10">Administrator</span>
                                    @else
                                        System Automated
                                    @endif
                                </span>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endforeach

@endsection
