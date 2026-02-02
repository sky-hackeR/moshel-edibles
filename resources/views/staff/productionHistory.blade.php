@extends('staff.layout.dashboard')

@section('content')

{{-- PAGE HEADER (Admin Style) --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">My Production History</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">History</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- MAIN CARD (Admin Style) --}}
<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Personal Production Logs</h4>
                <a href="{{ url('staff/production') }}" class="btn btn-primary">
                    Record New Batch
                </a>
            </div>

            <div class="card-body">
                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Date & Time</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($history as $record)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $record->created_at->format('d M, Y') }}<br>
                                    <small class="text-muted">{{ $record->created_at->format('h:i A') }}</small>
                                </td>
                                <td>{{ $record->product->name }}</td>
                                <td>{{ number_format($record->quantity) }} {{ $record->product->sales_unit ?? 'Units' }}</td>
                                <td>
                                    <span class="badge bg-success">Completed</span>
                                </td>
                                <td>
                                    <button class="btn btn-info btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewBatch{{ $record->id }}">
                                        <i class="mdi mdi-eye"></i> View Details
                                    </button>
                                </td>
                            </tr>
                        @empty
                            {{-- Handled by DataTables --}}
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

{{-- DETAIL MODALS --}}
@foreach($history as $record)
    <div class="modal fade" id="viewBatch{{ $record->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Batch Details: {{ $record->product->name }}</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Produced At</p>
                            <p class="fw-bold mb-0">{{ $record->created_at->format('M d, Y') }}</p>
                            <small class="text-muted">{{ $record->created_at->format('h:i A') }}</small>
                        </div>

                        <div class="col-md-6 text-md-end">
                            <p class="mb-1 text-muted small">Recorded By</p>
                            <p class="fw-bold text-primary mb-0">
                                @if($record->staff_id)
                                    {{ $record->staff->name }}
                                @elseif($record->admin_id)
                                    {{ $record->admin->name }}
                                @else
                                    System
                                @endif
                            </p>
                            <small class="badge bg-light text-muted border">
                                {{ $record->staff_id ? 'Staff' : 'Admin' }}
                            </small>
                        </div>
                    </div>

                    <div class="bg-light p-3 rounded mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Total Batch Quantity:</span>
                            <span class="fw-bold h5 mb-0">{{ number_format($record->quantity) }} {{ $record->product->sales_unit }}</span>
                        </div>
                    </div>

                    <h6>Resource Consumption</h6>
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Ingredient</th>
                                <th class="text-end">Qty Used</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($record->items as $item)
                            <tr>
                                <td>{{ $item->ingredient->name }}</td>
                                <td class="text-end fw-medium">{{ number_format($item->quantity_used, 2) }} <small>g/ml</small></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($record->notes)
                        <div class="alert alert-light border mt-3">
                            <strong>Shift Notes:</strong><br>
                            {{ $record->notes }}
                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
@endforeach

@endsection