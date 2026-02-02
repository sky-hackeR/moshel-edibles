@extends('staff.layout.dashboard')

@section('content')

{{-- PAGE HEADER --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Staff Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Welcome back, {{ Auth::guard('staff')->user()->name }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- STATS CARDS --}}
<div class="row">
    <div class="col-md-4">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Today's Production</p>
                        <h4 class="mb-0">{{ $todayProductionCount }} Batches</h4>
                    </div>
                    <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                        <span class="avatar-title">
                            <i class="bx bx-cookie font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Today's Sales</p>
                        <h4 class="mb-0">{{ $todaySalesCount }} Orders</h4>
                    </div>
                    <div class="mini-stat-icon avatar-sm rounded-circle bg-success align-self-center">
                        <span class="avatar-title">
                            <i class="bx bx-cart font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Low Stock Alerts</p>
                        <h4 class="mb-0 text-danger">{{ $lowStockCount }} Items</h4>
                    </div>
                    <div class="mini-stat-icon avatar-sm rounded-circle bg-danger align-self-center">
                        <span class="avatar-title">
                            <i class="bx bx-error font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- RECENT PRODUCTION LOG --}}
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Recent Production Activity</h4>
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
                                <td>{{ $prod->product->name }}</td>
                                <td>{{ $prod->quantity_produced }} Units</td>
                                <td>{{ $prod->created_at->diffForHumans() }}</td>
                                <td><span class="badge badge-pill badge-soft-success font-size-11">Completed</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No production recorded today.</td>
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
                    <a href="{{ url('staff/newProduction') }}" class="btn btn-primary btn-lg">
                        <i class="bx bx-plus me-1"></i> Record New Batch
                    </a>
                    <a href="{{ url('staff/pos') }}" class="btn btn-success btn-lg">
                        <i class="bx bx-shopping-bag me-1"></i> Open POS Termnal
                    </a>
                    <a href="{{ url('staff/inventory') }}" class="btn btn-warning btn-lg">
                        <i class="bx bx-list-check me-1"></i> Check Inventory
                    </a>
                </div>
                
                <div class="mt-4 p-3 bg-light rounded">
                    <p class="text-muted mb-2"><i class="bx bx-info-circle me-1"></i> Inventory Tip</p>
                    <small>Always record production <strong>before</strong> sales to ensure stock levels remain accurate.</small>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection