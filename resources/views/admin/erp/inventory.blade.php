@extends('admin.layout.dashboard')

@section('content')

{{-- PAGE HEADER --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Inventory Management</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Inventory Overview</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- CONTENT --}}
<div class="row">
    <div class="col-12">
        <div class="card border shadow-none">
            <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="card-title mb-0">Inventory Stock Levels</h4>
                    <p class="text-muted mb-0 small">Real-time tracking of ingredient quantities and status.</p>
                </div>
            </div>

            <div class="card-body">
                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100 align-middle">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">S/N</th>
                            <th>Ingredient</th>
                            <th>Current Quantity</th>
                            <th>Base Unit</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($inventories as $inventory)
                            <tr>
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <h5 class="font-size-14 mb-1">
                                        <a href="javascript: void(0);" class="text-dark">{{ $inventory->ingredient->name }}</a>
                                    </h5>
                                </td>
                                <td class="fw-bold">
                                    {{ number_format($inventory->quantity, 3) }}
                                </td>
                                <td>
                                    <span class="badge badge-soft-secondary">
                                        {{ $inventory->ingredient->baseUnit->symbol }}
                                    </span>
                                </td>
                                <td>
                                    @if($inventory->ingredient->is_active)
                                        <span class="badge badge-soft-success">
                                            <i class="mdi mdi-check-circle-outline me-1"></i> Active
                                        </span>
                                    @else
                                        <span class="badge badge-soft-danger">
                                            <i class="mdi mdi-block-helper me-1"></i> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="text-muted">
                                    <i class="mdi mdi-clock-outline me-1"></i>
                                    {{ $inventory->updated_at->diffForHumans() }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection