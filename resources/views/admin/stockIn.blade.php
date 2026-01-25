@extends('admin.layout.dashboard')

@section('content')

{{-- PAGE HEADER --}}
<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between align-items-center">
            <h4>Stock In (Purchases)</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStock">
                New Purchase
            </button>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="text-muted">Purchases Today</h6>
                <h3>{{ $stats['today'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="text-muted">This Month</h6>
                <h3>{{ $stats['month'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="text-muted">Items Restocked Today</h6>
                <h3>{{ $stats['items'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3"> 
        <div class="card text-center" style="min-height: 99px;">
            <div class="card-body">
                <h6 class="text-muted">Last Purchase</h6>
                <h3 class="medium" style="font-size: 0.85rem; font-weight: normal;">{{ $stats['last'] ?? '—' }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- STOCK IN HISTORY --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Stock In History</h4>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Purchase Date</th>
                            <th>Supplier</th>
                            <th>Items</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockIns as $stock)
                            <tr>
                                <td>{{ $stock->reference }}</td>
                                <td>{{ $stock->purchase_date }}</td>
                                <td>{{ $stock->supplier ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $stock->items->count() }} items
                                    </span>
                                </td>
                                <td><button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewStock{{ $stock->id }}">View</button></td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No stock purchases recorded yet.
                                </td>
                            </tr>

                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@foreach($stockIns as $stock)
    {{-- VIEW STOCK MODAL --}}
    <div class="modal fade" id="viewStock{{ $stock->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg">

                {{-- HEADER --}}
                <div class="modal-header bg-light">
                    <div>
                        <h5 class="modal-title mb-0">
                            Stock Purchase Details
                        </h5>
                        <small class="text-muted">
                            Ref: {{ $stock->reference }}
                        </small>
                    </div>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                {{-- BODY --}}
                <div class="modal-body">

                    {{-- META INFO --}}
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="border rounded p-3 text-center h-100">
                                <small class="text-muted">Purchase Date</small>
                                <h6 class="mt-1 mb-0">
                                    {{ \Carbon\Carbon::parse($stock->purchase_date)->format('M d, Y') }}
                                </h6>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="border rounded p-3 text-center h-100">
                                <small class="text-muted">Supplier</small>
                                <h6 class="mt-1 mb-0">
                                    {{ $stock->supplier ?? '—' }}
                                </h6>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="border rounded p-3 text-center h-100">
                                <small class="text-muted">Items Count</small>
                                <h6 class="mt-1 mb-0">
                                    {{ $stock->items->count() }}
                                </h6>
                            </div>
                        </div>
                    </div>

                    {{-- ITEMS --}}
                    <h6 class="mb-2">Purchased Items</h6>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Ingredient</th>
                                    <th class="text-end">Quantity</th>
                                    <th class="text-center">Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stock->items as $item)
                                    <tr>
                                        <td>{{ $item->ingredient->name }}</td>
                                        <td class="text-end fw-semibold">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">
                                                {{ $item->unit->symbol }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer bg-light">
                    <small class="text-muted me-auto">
                        Created {{ $stock->created_at->format('M d, Y • h:i A') }}
                    </small>

                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>
@endforeach



{{-- ADD STOCK MODAL --}}
<div class="modal fade" id="addStock" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <form method="POST" action="{{ url('/admin/newStockIn') }}" style="width: 100%">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Stock Purchase</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Date</label>
                            <input type="date" name="purchase_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Supplier</label>
                            <input name="supplier" class="form-control">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="itemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Ingredient</th>
                                    <th style="width: 20%;">Quantity</th>
                                    <th style="width: 20%;">Unit</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <tr>
                                    <td>
                                        <select name="items[0][ingredient_id]" class="form-select" required>
                                            <option value="">Select Ingredient</option>
                                            @foreach($ingredients as $ingredient)
                                                <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="0.001" name="items[0][quantity]" class="form-control" required>
                                    </td>
                                    <td>
                                        <select name="items[0][unit_id]" class="form-select" required>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->symbol }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addRow()">
                        <i class="fas fa-plus"></i> Add Another Item
                    </button>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Stock Purchase</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let rowCount = 1;

    function addRow() {
        const tbody = document.getElementById('itemsBody');
        const newRow = document.createElement('tr');
        
        newRow.innerHTML = `
            <td>
                <select name="items[${rowCount}][ingredient_id]" class="form-select" required>
                    <option value="">Select Ingredient</option>
                    @foreach($ingredients as $ingredient)
                        <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" step="0.001" name="items[${rowCount}][quantity]" class="form-control" required>
            </td>
            <td>
                <select name="items[${rowCount}][unit_id]" class="form-select" required>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->symbol }}</option>
                    @endforeach
                </select>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="removeRow(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        
        tbody.appendChild(newRow);
        rowCount++;
    }

    function removeRow(btn) {
        btn.closest('tr').remove();
    }
</script>   

@endsection

