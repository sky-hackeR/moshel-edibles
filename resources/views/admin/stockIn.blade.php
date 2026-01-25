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
                <h6 class="text-muted">Monthly Spend</h6>
                <h3>₦{{ number_format($stats['month'], 2) }}</h3>
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
        <div class="card text-center" style="min-height: 99px">
            <div class="card-body">
                <h6 class="text-muted">Last Purchase</h6>
                <h4 class="mt-2">{{ $stats['last'] ?? '—' }}</h4>
            </div>
        </div>
    </div>
</div>

{{-- STOCK HISTORY --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Stock In History</h4>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Purchase Date</th>
                            <th>Supplier</th>
                            <th>Total Cost</th>
                            <th>Items</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockIns as $stock)
                            <tr>
                                <td>{{ $stock->reference }}</td>
                                <td>{{ \Carbon\Carbon::parse($stock->purchase_date)->format('M d, Y') }}</td>
                                <td>{{ $stock->supplier ?? '—' }}</td>
                                <td class="fw-bold">
                                    ₦{{ number_format($stock->items->sum('total_price'), 2) }}
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $stock->items->count() }} items
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewStock{{ $stock->id }}">
                                        View
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
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

{{-- VIEW MODALS --}}
@foreach($stockIns as $stock)
<div class="modal fade" id="viewStock{{ $stock->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg">

            <div class="modal-header bg-light">
                <div>
                    <h5 class="modal-title mb-0">Stock Purchase Details</h5>
                    <small class="text-muted">Ref: {{ $stock->reference }}</small>
                </div>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row mb-4 text-center">
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <small class="text-muted">Date</small>
                            <h6>{{ \Carbon\Carbon::parse($stock->purchase_date)->format('M d, Y') }}</h6>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <small class="text-muted">Supplier</small>
                            <h6>{{ $stock->supplier ?? '—' }}</h6>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <small class="text-muted">Total Cost</small>
                            <h6 class="fw-bold">
                                ₦{{ number_format($stock->items->sum('total_price'), 2) }}
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Ingredient</th>
                                <th class="text-end">Qty</th>
                                <th class="text-center">Unit</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stock->items as $item)
                            <tr>
                                <td>{{ $item->ingredient->name }}</td>
                                <td class="text-end">{{ $item->quantity }}</td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $item->unit->symbol }}</span>
                                </td>
                                <td class="text-end">
                                    ₦{{ number_format($item->unit_price, 2) }}
                                </td>
                                <td class="text-end fw-bold">
                                    ₦{{ number_format($item->total_price, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer bg-light">
                <small class="text-success me-auto">
                    Created {{ $stock->created_at->format('M d, Y • h:i A') }}
                    @if($stock->admin)
                        by <strong>{{ $stock->admin->name }}</strong>
                    @endif
                </small>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
@endforeach

{{-- ADD STOCK MODAL --}}
<div class="modal fade" id="addStock" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <form method="POST" action="{{ url('/admin/newStockIn') }}">
            @csrf
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">New Stock Purchase</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label>Date</label>
                            <input type="date" name="purchase_date"
                                   value="{{ date('Y-m-d') }}"
                                   class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label>Supplier</label>
                            <input name="supplier" class="form-control">
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Ingredient</th>
                                <th>Qty</th>
                                <th>Unit</th>
                                <th>Total Price</th>
                                <th></th>
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
                                <td>
                                    <input type="number" step="0.01" name="items[0][total_price]" class="form-control" required>
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>

                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addRow()">
                        + Add Item
                    </button>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Save Purchase</button>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
let rowCount = 1;

function addRow() {
    const tbody = document.getElementById('itemsBody');
    const row = document.createElement('tr');

    row.innerHTML = `
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
        <td>
            <input type="number" step="0.01" name="items[${rowCount}][total_price]" class="form-control" required>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-sm"
                onclick="this.closest('tr').remove()">✕</button>
        </td>
    `;

    tbody.appendChild(row);
    rowCount++;
}
</script>

@endsection