@extends('admin.layout.dashboard')

@section('content')

{{-- PAGE HEADER --}}
<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between align-items-center">
            <h4 class="mb-sm-0 font-size-18">Stock In (Purchases)</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStock">
                <i class="mdi mdi-plus me-1"></i> New Purchase
            </button>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted text-uppercase fw-semibold">Purchases Today</h6>
                <h3 class="mb-0">{{ $stats['today'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted text-uppercase fw-semibold">Monthly Spend</h6>
                <h3 class="mb-0">₦{{ number_format($stats['month'], 2) }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted text-uppercase fw-semibold">Items Restocked Today</h6>
                <h3 class="mb-0">{{ $stats['items'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center shadow-sm" style="min-height: 15px">
            <div class="card-body">
                <h6 class="text-muted text-uppercase fw-semibold">Last Purchase</h6>
                <h4 class="mt-2 mb-0">{{ $stats['last'] ? \Carbon\Carbon::parse($stats['last'])->format('M d, Y') : '—' }}</h4>
            </div>
        </div>
    </div>
</div>

{{-- STOCK HISTORY --}}
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h4 class="card-title mb-0">Stock In History</h4>
            </div>

            <div class="card-body">
                <table id="datatable" class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
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
                                <td class="fw-medium text-primary">{{ $stock->reference }}</td>
                                <td>{{ \Carbon\Carbon::parse($stock->purchase_date)->format('M d, Y') }}</td>
                                <td>{{ $stock->supplier ?? '—' }}</td>
                                <td class="fw-bold text-dark">
                                    ₦{{ number_format($stock->items->sum('total_price'), 2) }}
                                </td>
                                <td>
                                    <span class="badge bg-soft-info text-info border border-info px-2">
                                        {{ $stock->items->count() }} line items
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewStock{{ $stock->id }}">
                                        <i class="mdi mdi-eye"></i> Details
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
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
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-primary text-white">
                <div>
                    <h5 class="modal-title text-white mb-0">Purchase Details</h5>
                    <small class="opacity-75">Ref: {{ $stock->reference }}</small>
                </div>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row mb-4 text-center">
                    <div class="col-md-4">
                        <div class="bg-light rounded p-2">
                            <small class="text-muted d-block">Date</small>
                            <span class="fw-semibold">{{ \Carbon\Carbon::parse($stock->purchase_date)->format('M d, Y') }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light rounded p-2">
                            <small class="text-muted d-block">Supplier</small>
                            <span class="fw-semibold text-truncate d-block px-1">{{ $stock->supplier ?? 'General Vendor' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light rounded p-2">
                            <small class="text-muted d-block">Grand Total</small>
                            <span class="fw-bold text-success">₦{{ number_format($stock->items->sum('total_price'), 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle">
                        <thead class="table-light text-uppercase font-size-11">
                            <tr>
                                <th>Ingredient</th>
                                <th class="text-center">Purchased Qty</th>
                                <th class="text-center">Stored (Base)</th>
                                <th class="text-end">Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stock->items as $item)
                            <tr>
                                <td>
                                    <span class="fw-medium">{{ $item->ingredient->name }}</span><br>
                                    <small class="text-muted">₦{{ number_format($item->unit_price, 2) }} / {{ $item->unit->symbol }}</small>
                                </td>
                                <td class="text-center">
                                    {{ $item->quantity }} <span class="text-muted small">{{ $item->unit->symbol }}</span>
                                </td>
                                <td class="text-center text-primary fw-medium">
                                    {{ number_format($item->base_quantity, 2) }} {{ $item->unit->base_unit }}
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

            <div class="modal-footer bg-light border-top">
                <div class="me-auto small text-muted">
                    <i class="mdi mdi-account-circle-outline"></i> Recorded by: 
                    <strong>{{ $stock->admin->name ?? 'System' }}</strong> on {{ $stock->created_at->format('d/m/y H:i') }}
                </div>
                <button class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
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
            <div class="modal-content shadow-lg border-0">

                <div class="modal-header bg-light text-white">
                    <h5 class="modal-title text-dark">Create New Stock Purchase</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Purchase Date *</label>
                            <input type="date" name="purchase_date"
                                   value="{{ date('Y-m-d') }}"
                                   class="form-control" required>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-bold">Supplier Name</label>
                            <input name="supplier" class="form-control" placeholder="Optional: Name of the vendor">
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-bold">Note</label>
                            <textarea name="note" class="form-control" rows="1" placeholder="Optional purchase notes..."></textarea>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40%">Ingredient</th>
                                    <th style="width: 15%">Quantity</th>
                                    <th style="width: 15%">Unit</th>
                                    <th style="width: 20%">Total Cost (₦)</th>
                                    
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <tr>
                                    <td>
                                        <select name="items[0][ingredient_id]" class="form-select select2" required>
                                            <option value="">-- Choose Ingredient --</option>
                                            @foreach($ingredients as $ingredient)
                                                <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="0.001" name="items[0][quantity]" class="form-control" required placeholder="0.00">
                                    </td>
                                    <td>
                                        <select name="items[0][unit_id]" class="form-select" required>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->symbol }} (x{{ (float)$unit->base_multiplier }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="items[0][total_price]" class="form-control" required placeholder="0.00">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addRow()">
                        <i class="mdi mdi-plus"></i> Add Another Item
                    </button>
                </div>

                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success px-5">
                        <i class="mdi mdi-check-all me-1"></i> Save Stock Purchase
                    </button>
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
                <option value="">-- Choose Ingredient --</option>
                @foreach($ingredients as $ingredient)
                    <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" step="0.001" name="items[${rowCount}][quantity]" class="form-control" required placeholder="0.00">
        </td>
        <td>
            <select name="items[${rowCount}][unit_id]" class="form-select" required>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->symbol }} (x{{ (float)$unit->base_multiplier }})</option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" step="0.01" name="items[${rowCount}][total_price]" class="form-control" required placeholder="0.00">
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-sm"
                onclick="this.closest('tr').remove()">
                <i class="mdi mdi-trash-can-outline"></i>
            </button>
        </td>
    `;

    tbody.appendChild(row);
    rowCount++;
}
</script>

@endsection