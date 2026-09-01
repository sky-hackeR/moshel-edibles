@extends('admin.layout.dashboard')

@section('content')

{{-- PAGE HEADER --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Production History</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">History</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- MAIN CARD --}}
<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Production Logs</h4>
                <a href="{{ url('admin/production') }}" class="btn btn-primary">
                    Record New Production
                </a>
            </div>

            <div class="card-body">
                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Total Cost</th>
                            <th>Revenue (Est.)</th>
                            <th>Profit</th>
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
                                <td>{{ number_format($record->quantity) }} Units</td>
                                <td>{{ number_format($record->total_cost, 2) }}</td>
                                <td>{{ number_format($record->expected_revenue, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $record->profit >= 0 ? 'success' : 'danger' }}">
                                        {{ $record->profit >= 0 ? '+' : '' }}{{ number_format($record->profit, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-info btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewBatch{{ $record->id }}">
                                        <i class="mdi mdi-eye"></i> Details
                                    </button>
                                </td>
                            </tr>
                        @empty
                            
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
                            <p class="mb-1 text-muted small">Production Date</p>
                            <p class="fw-bold">{{ $record->created_at->format('F d, Y - h:i A') }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-1 text-muted small">Batch Total Cost</p>
                            <h4 class="text-primary">{{ number_format($record->total_cost, 2) }}</h4>
                        </div>
                    </div>

                    <h6>Ingredients Consumed</h6>
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Ingredient</th>
                                <th class="text-center">Qty Consumed</th>
                                <th class="text-end">Cost Contribution</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($record->items as $item)
                            <tr>
                                <td>{{ $item->ingredient->name }}</td>
                                <td class="text-center">{{ number_format($item->quantity_used, 2) }} <small>g/ml</small></td>
                                <td class="text-end">{{ number_format($item->total_cost, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($record->notes)
                        <div class="alert alert-light border mt-3">
                            <strong>Notes:</strong><br>
                            {{ $record->notes }}
                        </div>
                    @endif
                </div>

                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <div class="text-start">
                        <p class="mb-0 text-muted small uppercase">Recorded By</p>
                        <p class="fw-bold text-primary mb-0">
                            @if($record->staff_id)
                                {{ $record->staff->name }}
                            @elseif($record->admin_id)
                                {{ $record->admin->name }}
                            @else
                                System
                            @endif
                            <span class="badge bg-soft-light text-muted border fw-normal ms-1">
                                {{ $record->staff_id ? 'Staff' : 'Admin' }}
                            </span>
                        </p>
                    </div>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
@endforeach

@endsection