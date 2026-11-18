@extends('staff.layout.dashboard')

@section('content')

    {{-- PAGE HEADER --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
                <div>
                    <h4 class="mb-sm-0 font-size-18 text-primary fw-bold">My Production History</h4>
                    <p class="text-muted font-size-12 mb-0">Personal log of recorded bakery production runs and material usage.</p>
                </div>
                <div class="page-title-right d-flex align-items-center">
                    <a href="{{ url('staff/production') }}" class="btn btn-primary shadow-sm">
                        <i class="bx bx-plus-circle me-1 font-size-15 align-middle"></i> Record New Batch
                    </a>
                    <ol class="breadcrumb m-0 ms-3 d-none d-md-flex">
                        <li class="breadcrumb-item active">History</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @php
        $totalBatches = $history->count();
        $totalUnitsProduced = $history->sum('quantity');
    @endphp

    {{-- SUMMARY KPI CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #581a62 !important;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">My Total Batches</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ number_format($totalBatches) }}</h3>
                            <small class="text-muted font-size-11">Total baking runs recorded by you</small>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-primary text-white rounded-circle font-size-20">
                                <i class="bx bx-history"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #10b981 !important;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Total Finished Output</p>
                            <h3 class="mb-0 fw-bold text-success">{{ number_format($totalUnitsProduced) }}</h3>
                            <small class="text-muted font-size-11">Cumulative units baked</small>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-success text-white rounded-circle font-size-20">
                                <i class="mdi mdi-cookie"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN TABLE CARD --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="bx bx-list-check text-primary me-2 font-size-18"></i>Recorded Production Entries
                    </h5>
                    <span class="badge bg-light text-secondary font-size-12 px-3 py-2 border">
                        <i class="bx bx-check-circle text-success me-1"></i> {{ $totalBatches }} Batches
                    </span>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-hover align-middle w-100">
                            <thead class="table-light">
                                <tr class="text-muted text-uppercase font-size-11">
                                    <th style="width: 50px;">#</th>
                                    <th>Date & Time</th>
                                    <th>Product</th>
                                    <th class="text-center">Quantity Produced</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" style="width: 100px;">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($history as $record)
                                    @php
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
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-soft-primary text-primary font-size-13 px-2 py-1 fw-bold">
                                                {{ number_format($record->quantity) }} {{ $salesUnit }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-soft-success text-success font-size-12 px-2 py-1 fw-bold">
                                                <i class="bx bx-check-double me-1"></i> Completed
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-primary px-3 shadow-none"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewBatch{{ $record->id }}"
                                                    title="View Batch Details">
                                                <i class="mdi mdi-eye me-1"></i> View Details
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="avatar-md mx-auto mb-3">
                                                <span class="avatar-title bg-light text-muted rounded-circle font-size-24">
                                                    <i class="bx bx-receipt"></i>
                                                </span>
                                            </div>
                                            <h5 class="fw-bold text-dark">No Production Records Found</h5>
                                            <p class="text-muted font-size-13 mb-3">You have not recorded any production batches yet.</p>
                                            <a href="{{ url('staff/production') }}" class="btn btn-primary btn-sm">
                                                Record New Batch
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

    {{-- REDESIGNED DETAIL MODALS --}}
    @foreach($history as $record)
        @php
            $modalSalesUnit = $record->product->sales_unit ?? 'Units';
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
                                <small class="text-muted font-size-12">Batch #{{ $record->id }} &bull; Produced on {{ $record->created_at->format('M d, Y | h:i A') }}</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    {{-- MODAL BODY --}}
                    <div class="modal-body p-4">
                        <div class="p-3 bg-light rounded border mb-4 d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted d-block text-uppercase font-size-11 fw-bold">Total Batch Yield</small>
                                <h4 class="fw-bold text-primary mb-0 font-size-18">{{ number_format($record->quantity) }} {{ $modalSalesUnit }}</h4>
                            </div>
                            <span class="badge bg-soft-success text-success font-size-12 px-3 py-2 fw-bold">
                                <i class="bx bx-check-circle me-1"></i> Recorded Successfully
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold text-dark mb-0 font-size-14">
                                <i class="bx bx-layer text-primary me-1"></i>Raw Materials Consumed
                            </h6>
                            <span class="badge bg-light text-secondary font-size-11">{{ $record->items->count() }} Ingredients</span>
                        </div>

                        <div class="table-responsive rounded border mb-3">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="font-size-11 text-uppercase text-muted">
                                        <th>Raw Material</th>
                                        <th class="text-end">Quantity Deducted</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($record->items as $item)
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark font-size-13">{{ $item->ingredient->name ?? 'Ingredient' }}</div>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge bg-soft-secondary text-dark font-size-12 px-2 py-1">
                                                    {{ number_format($item->quantity_used, 2) }} <small class="text-muted">g/ml</small>
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($record->notes)
                            <div class="alert alert-light border rounded p-3 mb-0">
                                <div class="d-flex align-items-center gap-1 text-primary fw-bold font-size-12 mb-1">
                                    <i class="bx bx-comment-detail"></i> Shift Notes:
                                </div>
                                <p class="text-dark font-size-13 mb-0 fst-italic">"{{ $record->notes }}"</p>
                            </div>
                        @endif
                    </div>

                    {{-- MODAL FOOTER --}}
                    <div class="modal-footer bg-light border-top py-3 d-flex justify-content-between align-items-center">
                        <div class="text-start">
                            <small class="text-muted d-block text-uppercase" style="font-size: 10px;">Recorded By</small>
                            <span class="fw-bold text-dark font-size-13">{{ Auth::guard('staff')->user()->name ?? 'Staff' }}</span>
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
