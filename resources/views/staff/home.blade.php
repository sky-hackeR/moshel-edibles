@extends('staff.layout.dashboard')

@section('content')

@php
    $staff = Auth::guard('staff')->user();
    // Helper to get first name
    $firstName = explode(' ', trim($staff->name))[0] ?? 'Staff';
@endphp

{{-- PAGE HEADER --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Staff Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Welcome back, {{ $firstName }}!</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- STATS CARDS --}}
<div class="row">
    <div class="col-md-3">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-2">My Production</p>
                        <h4 class="mb-0">{{ $myProductionCount }} <small class="text-muted font-size-12">Batches</small></h4>
                        <div class="mt-2">
                            <span class="badge bg-soft-primary text-primary">Overall: {{ $overallProductionCount }}</span>
                        </div>
                    </div>
                    <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                        <span class="avatar-title"><i class="mdi mdi-cookie-check font-size-24"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-2">My Sales (Orders)</p>
                        <h4 class="mb-0">{{ $mySalesCount }}</h4>
                        <div class="mt-2">
                            <span class="badge bg-soft-success text-success">Overall: {{ $overallSalesCount }}</span>
                        </div>
                    </div>
                    <div class="mini-stat-icon avatar-sm rounded-circle bg-success align-self-center">
                        <span class="avatar-title"><i class="bx bx-cart font-size-24"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-2">My Sales (Value)</p>
                        <h4 class="mb-0">₦{{ number_format($mySalesTotal, 2) }}</h4>
                        <div class="mt-2">
                            <span class="badge bg-soft-info text-info">Total: ₦{{ number_format($overallSalesTotal, 0) }}</span>
                        </div>
                    </div>
                    <div class="mini-stat-icon avatar-sm rounded-circle bg-info align-self-center">
                        <span class="avatar-title"><i class="bx bx-money font-size-24"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-2">Stock Alerts</p>
                        <h4 class="mb-0 text-danger">{{ $lowStockCount }} <small class="text-muted font-size-12">Items</small></h4>
                        <div class="mt-2">
                             <a href="{{ url('staff/inventory') }}" class="text-danger small fw-bold">Check Levels <i class="mdi mdi-arrow-right"></i></a>
                        </div>
                    </div>
                    <div class="mini-stat-icon avatar-sm rounded-circle bg-danger align-self-center">
                        <span class="avatar-title"><i class="bx bx-error font-size-24"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- PRODUCTION TABLE --}}
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">My Recent Production Activity</h4>
                <div class="table-responsive">
                    <table class="table table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentProductions as $prod)
                            <tr>
                                <td>{{ $prod->product->name ?? 'Unknown' }}</td>
                                <td>{{ $prod->quantity }} Units</td>
                                <td>{{ $prod->created_at->diffForHumans() }}</td>
                                <td><span class="badge badge-pill badge-soft-success font-size-11">Completed</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">You haven't recorded any production today.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Quick Actions</h4>
                <div class="d-grid gap-2">
                    <a href="{{ url('staff/production') }}" class="btn btn-primary btn-lg">
                        <i class="bx bx-plus me-1"></i> Record New Batch
                    </a>
                    <a href="{{ url('staff/pos') }}" class="btn btn-success btn-lg">
                        <i class="bx bx-shopping-bag me-1"></i> Open POS Terminal
                    </a>
                    <a href="{{ url('staff/inventory') }}" class="btn btn-warning btn-lg">
                        <i class="bx bx-list-check me-1"></i> Check Inventory
                    </a>
                </div>
                
                <div class="mt-4 p-3 bg-light rounded text-center">
                    <p class="text-muted mb-1 small">Your Sales Contribution</p>
                    <h5 class="mb-0">
                        {{ $overallSalesCount > 0 ? round(($mySalesCount / $overallSalesCount) * 100) : 0 }}%
                    </h5>
                    <div class="progress mt-2" style="height: 5px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ $overallSalesCount > 0 ? ($mySalesCount / $overallSalesCount) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection