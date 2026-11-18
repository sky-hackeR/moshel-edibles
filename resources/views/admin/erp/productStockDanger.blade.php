@extends('admin.layout.dashboard')

@section('content')

{{-- PAGE HEADER --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between py-2 mb-3">
            <div class="d-flex align-items-center">
                <span class="avatar-rounded bg-soft-grey text-danger font-size-18 me-2" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                    <i class="bx bx-shield"></i>
                </span>
                <div>
                    <h4 class="mb-0 font-size-16 text-danger fw-bold">Stock Control — Danger Zone</h4>
                    <small class="text-muted font-size-11">Authorized inventory depletion &amp; catalog resets</small>
                </div>
            </div>
            <div class="page-title-right">
                <ol class="breadcrumb m-0 font-size-12">
                    <li class="breadcrumb-item active text-danger">Stock Danger Zone</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- SENSITIVE AREA SLIM NOTICE BAR --}}
<div class="alert alert-danger py-2 px-3 font-size-12 d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 border-0 shadow-none" style="background-color: #fff5f5; color: #9b2c2c; border-left: 3px solid #e53e3e !important;">
    <div class="d-flex align-items-center">
        <i class="bx bx-error-alt me-2 font-size-16 text-danger flex-shrink-0"></i>
        <span>
            <strong>Restricted Access (Admin Only):</strong> Zeroing out stock halts customer orders for that product. Choose between <strong>Operational Adjustment</strong> (no effect on sales/income) or <strong>Loss Write-Off</strong>. All admins are emailed.
        </span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('products') }}" class="btn btn-outline-secondary btn-sm py-1 px-2 font-size-11">
            <i class="bx bx-arrow-back me-1"></i> Products Catalog
        </a>
        <button type="button" class="btn btn-danger btn-sm py-1 px-2 font-size-11" data-bs-toggle="modal" data-bs-target="#clearAllStockModal">
            <i class="bx bx-radiation me-1"></i> Emergency Full Reset
        </button>
    </div>
</div>

{{-- COMPACT STATISTIC PILLS (Tight single-row toolbar) --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-2 px-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="badge bg-light text-dark font-size-12 px-3 py-2 border">
                    <i class="bx bx-package text-primary me-1"></i> Catalog Products: <strong>{{ number_format($stats['total_products']) }}</strong>
                </span>
                <span class="badge bg-light text-dark font-size-12 px-3 py-2 border">
                    <i class="bx bx-layer text-success me-1"></i> Physical Stock Units: <strong>{{ number_format($stats['total_units'], 2) }}</strong>
                </span>
                <span class="badge bg-light text-dark font-size-12 px-3 py-2 border">
                    <i class="bx bx-check-circle text-info me-1"></i> In Stock (&gt;0): <strong>{{ number_format($stats['in_stock_count']) }}</strong>
                </span>
                <span class="badge bg-light text-dark font-size-12 px-3 py-2 border">
                    <i class="bx bx-x-circle text-danger me-1"></i> Depleted (0): <strong>{{ number_format($stats['depleted_count']) }}</strong>
                </span>
            </div>

            {{-- INLINE SEARCH & FILTER --}}
            <form method="GET" action="{{ route('admin.products.stock') }}" class="d-flex align-items-center gap-1 m-0">
                <div class="input-group input-group-sm" style="width: 220px;">
                    <span class="input-group-text bg-white border-end-0"><i class="bx bx-search text-muted font-size-12"></i></span>
                    <input type="text" name="search" class="form-control form-control-sm border-start-0" placeholder="Search product..." value="{{ $search }}">
                </div>
                <select name="status" class="form-select form-select-sm" style="width: 140px;" onchange="this.form.submit()">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Levels</option>
                    <option value="in_stock" {{ $status === 'in_stock' ? 'selected' : '' }}>In Stock Only</option>
                    <option value="low_stock" {{ $status === 'low_stock' ? 'selected' : '' }}>Low Stock (≤10)</option>
                    <option value="depleted" {{ $status === 'depleted' ? 'selected' : '' }}>Depleted (0)</option>
                </select>
                <button type="submit" class="btn btn-sm btn-dark px-2" title="Filter"><i class="bx bx-filter-alt"></i></button>
                @if(!empty($search) || $status !== 'all')
                    <a href="{{ route('admin.products.stock') }}" class="btn btn-sm btn-light px-2" title="Reset Filters"><i class="bx bx-reset"></i></a>
                @endif
            </form>
        </div>
    </div>
</div>

{{-- PRODUCTS STOCK TABLE CARD --}}
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm align-middle mb-0 font-size-13">
                <thead class="table-light">
                    <tr>
                        <th class="text-center py-2" style="width: 40px;">#</th>
                        <th class="py-2">Product Name</th>
                        <th class="py-2" style="width: 110px;">Unit</th>
                        <th class="py-2" style="width: 120px;">Price</th>
                        <th class="py-2" style="width: 160px;">Stock on Hand</th>
                        <th class="text-end py-2 pe-3" style="width: 140px;">Danger Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="text-center text-muted font-size-11 py-2">
                                {{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}
                            </td>
                            <td class="py-2">
                                <div class="d-flex align-items-center">
                                    @if($product->storeProduct && $product->storeProduct->primaryImage)
                                        <img src="{{ asset($product->storeProduct->primaryImage->image_path) }}" alt="{{ $product->name }}" class="rounded me-2 object-fit-cover" style="width: 28px; height: 28px;">
                                    @else
                                        <div class="avatar-xs me-2 rounded bg-soft-secondary text-secondary d-flex align-items-center justify-content-center font-size-13" style="width: 28px; height: 28px;">
                                            <i class="bx bx-package"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <span class="text-dark fw-bold d-block lh-1 mb-1">{{ $product->name }}</span>
                                        <div class="d-flex gap-1 align-items-center font-size-10">
                                            @if($product->is_active)
                                                <span class="text-success">Active</span>
                                            @else
                                                <span class="text-danger">Disabled</span>
                                            @endif
                                            <span class="text-muted">·</span>
                                            <span class="text-muted">{{ $product->slug }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2">
                                <span class="badge bg-light text-dark text-uppercase font-size-11">{{ $product->sales_unit }}</span>
                            </td>
                            <td class="py-2 font-size-13 text-dark fw-semibold">
                                ₦{{ number_format($product->selling_price, 2) }}
                            </td>
                            <td class="py-2">
                                @if($product->stock_on_hand > 10)
                                    <span class="badge bg-soft-success text-success font-size-12 px-2 py-1">
                                        <i class="bx bx-check-circle me-1"></i> {{ (float) $product->stock_on_hand }} {{ $product->sales_unit }}
                                    </span>
                                @elseif($product->stock_on_hand > 0)
                                    <span class="badge bg-soft-warning text-warning font-size-12 px-2 py-1">
                                        <i class="bx bx-error me-1"></i> {{ (float) $product->stock_on_hand }} {{ $product->sales_unit }} (Low)
                                    </span>
                                @else
                                    <span class="badge bg-soft-secondary text-muted font-size-11 px-2 py-1">
                                        0 {{ $product->sales_unit }} (Depleted)
                                    </span>
                                @endif
                            </td>
                            <td class="text-end py-2 pe-3">
                                @if($product->stock_on_hand > 0)
                                    <button type="button" 
                                        class="btn btn-sm btn-outline-danger py-1 px-2 font-size-11 open-deplete-modal-btn"
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-stock="{{ (float)$product->stock_on_hand }}"
                                        data-unit="{{ $product->sales_unit }}"
                                        data-price="{{ (float)$product->selling_price }}">
                                        <i class="bx bx-trash-alt me-1"></i> Clear Stock
                                    </button>
                                @else
                                    <span class="text-muted font-size-11 fst-italic">Depleted</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <p class="text-muted font-size-13 mb-0">No catalog products matched your selected criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($products->hasPages())
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted font-size-12">
                    Showing <strong>{{ $products->firstItem() }}</strong> to <strong>{{ $products->lastItem() }}</strong> of <strong>{{ $products->total() }}</strong> products
                </div>
                <div>
                    {{ $products->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

{{-- SINGLE REUSABLE MODAL: CLEAR PRODUCT STOCK --}}
<div class="modal fade" id="clearProductStockModal" tabindex="-1" aria-labelledby="clearProductStockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.products.clearStock') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" id="modalProductId" value="">

                <div class="modal-header bg-danger text-white py-2 px-3">
                    <h6 class="modal-title text-white fw-bold font-size-14" id="clearProductStockModalLabel">
                        <i class="bx bx-error-alt me-1"></i> Confirm Stock Depletion
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-3 font-size-13">
                    <div class="p-2 bg-light rounded border mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Target Product:</span>
                            <strong class="text-dark" id="modalProductName">—</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Stock Quantity:</span>
                            <strong class="text-danger" id="modalProductStock">—</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Estimated Lost Value:</span>
                            <span class="text-dark fw-semibold" id="modalProductValue">—</span>
                        </div>
                    </div>

                    {{-- 2 TYPES OF DEPLETION --}}
                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold mb-1 font-size-12">Depletion &amp; Accounting Mode <span class="text-danger">*</span></label>
                        
                        <div class="form-check p-2 border rounded mb-2 bg-light">
                            <input class="form-check-input ms-1 me-2" type="radio" name="accounting_type" id="typeOperational" value="operational" checked>
                            <label class="form-check-label font-size-12 text-dark" for="typeOperational">
                                <strong>Operational Stock Adjustment (Default)</strong>
                                <div class="text-muted font-size-11">Physical count reset only. Does <strong>not</strong> affect sales figures, paid revenue, or projected income reports.</div>
                            </label>
                        </div>

                        <div class="form-check p-2 border rounded bg-light">
                            <input class="form-check-input ms-1 me-2" type="radio" name="accounting_type" id="typeWriteOff" value="write_off">
                            <label class="form-check-label font-size-12 text-dark" for="typeWriteOff">
                                <strong>Recognize as Inventory Loss &amp; Spoilage Write-Off</strong>
                                <div class="text-muted font-size-11">Flags lost inventory valuation in administrative records and executive security alerts.</div>
                            </label>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-dark fw-semibold mb-1 font-size-12">Operational Reason <span class="text-danger">*</span></label>
                        <select name="reason" class="form-select form-select-sm font-size-12" required>
                            <option value="" disabled selected>Select an operational justification...</option>
                            <option value="Physical Stock Count Discrepancy / Audit Correction">Physical Stock Count Discrepancy / Audit Correction</option>
                            <option value="Batch Spoilage / Contamination / Quality Issue">Batch Spoilage / Contamination / Quality Issue</option>
                            <option value="Stock Write-off / Damaged Goods">Stock Write-off / Damaged Goods</option>
                            <option value="Seasonal Catalog Clearance / Discontinuation">Seasonal Catalog Clearance / Discontinuation</option>
                            <option value="Administrative Inventory Reset">Administrative Inventory Reset</option>
                            <option value="Other Manual Depletion">Other Manual Depletion</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold mb-1 font-size-12">Administrative Notes (Optional)</label>
                        <textarea name="notes" class="form-control form-control-sm font-size-12" rows="2" placeholder="Optional details for the audit log and alert email..."></textarea>
                    </div>

                    <div class="form-check p-2 bg-light rounded border">
                        <input class="form-check-input ms-1 me-2" type="checkbox" name="confirm_deplete" id="confirmDepleteSingle" value="1" required>
                        <label class="form-check-label font-size-12 text-dark fw-semibold" for="confirmDepleteSingle">
                            I confirm zeroing out stock for this product. An alert email will be sent to all administrators.
                        </label>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2 px-3">
                    <button type="button" class="btn btn-secondary btn-sm font-size-12" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm font-size-12">
                        <i class="bx bx-trash-alt me-1"></i> Yes, Clear Stock to 0
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL: FULL CATALOG EMERGENCY RESET --}}
<div class="modal fade" id="clearAllStockModal" tabindex="-1" aria-labelledby="clearAllStockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.products.clearAllStock') }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white py-2 px-3">
                    <h6 class="modal-title text-white fw-bold font-size-14" id="clearAllStockModalLabel">
                        <i class="bx bx-radiation me-1"></i> EMERGENCY: Wipe Full Catalog Inventory
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-3 font-size-13">
                    <div class="alert alert-danger font-size-12 mb-3 py-2 px-3">
                        <strong>Critical Action Warning:</strong> This zeroes out stock on hand for <strong>EVERY single product</strong> across your entire business catalog. All items immediately become unavailable for online and POS sales.
                    </div>

                    <div class="p-2 bg-light rounded border mb-3 font-size-12">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">In-Stock Products Affected:</span>
                            <strong class="text-danger">{{ $stats['in_stock_count'] }} Products</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Total Stock Units Wiped:</span>
                            <strong class="text-danger">{{ number_format($stats['total_units'], 2) }} Units</strong>
                        </div>
                    </div>

                    {{-- 2 TYPES OF BULK DEPLETION --}}
                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold mb-1 font-size-12">Depletion &amp; Accounting Mode <span class="text-danger">*</span></label>
                        
                        <div class="form-check p-2 border rounded mb-2 bg-light">
                            <input class="form-check-input ms-1 me-2" type="radio" name="accounting_type" id="bulkTypeOperational" value="operational" checked>
                            <label class="form-check-label font-size-12 text-dark" for="bulkTypeOperational">
                                <strong>Operational Stock Adjustment (Default)</strong>
                                <div class="text-muted font-size-11">Physical count reset only. Does <strong>not</strong> affect sales figures, paid revenue, or projected income reports.</div>
                            </label>
                        </div>

                        <div class="form-check p-2 border rounded bg-light">
                            <input class="form-check-input ms-1 me-2" type="radio" name="accounting_type" id="bulkTypeWriteOff" value="write_off">
                            <label class="form-check-label font-size-12 text-dark" for="bulkTypeWriteOff">
                                <strong>Recognize as Inventory Loss &amp; Spoilage Write-Off</strong>
                                <div class="text-muted font-size-11">Flags full inventory reset for executive review and write-off records.</div>
                            </label>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-dark fw-semibold mb-1 font-size-12">Type confirmation phrase to unlock <span class="text-danger">*</span></label>
                        <input type="text" name="confirmation_text" class="form-control form-control-sm font-size-12 font-monospace" placeholder="CONFIRM EMERGENCY RESET" required autocomplete="off">
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-dark fw-semibold mb-1 font-size-12">Executive Justification / Reason <span class="text-danger">*</span></label>
                        <input type="text" name="reason" class="form-control form-control-sm font-size-12" placeholder="e.g. Annual physical count reset..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold mb-1 font-size-12">Additional Notes</label>
                        <textarea name="notes" class="form-control form-control-sm font-size-12" rows="2" placeholder="Optional notes for the permanent security audit record..."></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2 px-3">
                    <button type="button" class="btn btn-secondary btn-sm font-size-12" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm font-size-12">
                        <i class="bx bx-radiation me-1"></i> EXECUTE FULL CATALOG WIPEOUT
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const depleteModalEl = document.getElementById('clearProductStockModal');
    if (!depleteModalEl) return;

    const depleteModal = new bootstrap.Modal(depleteModalEl);
    const inputId = document.getElementById('modalProductId');
    const labelName = document.getElementById('modalProductName');
    const labelStock = document.getElementById('modalProductStock');
    const labelValue = document.getElementById('modalProductValue');

    document.querySelectorAll('.open-deplete-modal-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const stock = parseFloat(this.getAttribute('data-stock')) || 0;
            const unit = this.getAttribute('data-unit') || 'Units';
            const price = parseFloat(this.getAttribute('data-price')) || 0;
            const totalVal = stock * price;

            inputId.value = id;
            labelName.textContent = name;
            labelStock.textContent = `${stock} ${unit} → 0.00`;
            labelValue.textContent = `₦${totalVal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;

            // Reset confirmation checkbox
            const chk = document.getElementById('confirmDepleteSingle');
            if (chk) chk.checked = false;

            depleteModal.show();
        });
    });
});
</script>

@endsection
