{{-- =====================================================
     VIEW PURCHASE DETAILS MODALS PARTIAL
     ===================================================== --}}
@foreach($stockIns as $stock)
<div class="modal fade" id="viewStock{{ $stock->id }}" tabindex="-1" aria-labelledby="viewStockTitle{{ $stock->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">

            {{-- Modal Header --}}
            <div class="modal-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="modal-title fw-bold text-dark mb-0" id="viewStockTitle{{ $stock->id }}">
                        <i class="bx bx-receipt me-1 text-primary"></i> Purchase Invoice Details
                    </h5>
                    <span class="badge bg-primary-subtle text-primary font-monospace font-size-11 mt-1">
                        {{ $stock->reference }}
                    </span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                {{-- Quick Summary Info Grid --}}
                <div class="row g-2 mb-4">
                    <div class="col-sm-6 col-md-3">
                        <div class="p-2.5 bg-light rounded-3 border h-100 text-center">
                            <small class="text-muted font-size-11 text-uppercase fw-semibold d-block">Purchase Date</small>
                            <span class="fw-bold text-dark font-size-13">
                                {{ \Carbon\Carbon::parse($stock->purchase_date)->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="p-2.5 bg-light rounded-3 border h-100 text-center">
                            <small class="text-muted font-size-11 text-uppercase fw-semibold d-block">Supplier / Vendor</small>
                            <span class="fw-bold text-dark font-size-13 text-truncate d-block">
                                {{ $stock->supplier ?: 'General Vendor' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="p-2.5 bg-light rounded-3 border h-100 text-center">
                            <small class="text-muted font-size-11 text-uppercase fw-semibold d-block">Total Line Items</small>
                            <span class="fw-bold text-primary font-size-13">
                                {{ $stock->items->count() }} {{ Str::plural('Item', $stock->items->count()) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="p-2.5 bg-success-subtle rounded-3 border border-success-subtle h-100 text-center">
                            <small class="text-success font-size-11 text-uppercase fw-bold d-block">Grand Total</small>
                            <span class="fw-bold text-success font-size-14">
                                ₦{{ number_format($stock->items->sum('total_price'), 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Optional Note --}}
                @if(!empty($stock->note))
                    <div class="alert alert-light border mb-4 p-2.5 font-size-12">
                        <i class="bx bx-info-circle me-1 text-primary"></i><strong>Purchase Note:</strong> {{ $stock->note }}
                    </div>
                @endif

                {{-- Purchased Items Breakdown Table --}}
                <div class="table-responsive rounded border mb-2">
                    <table class="table table-hover align-middle font-size-12 mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="min-width: 160px;">Ingredient</th>
                                <th class="text-center" style="min-width: 110px;">Purchased Qty</th>
                                <th class="text-center" style="min-width: 120px;">Stored Base Qty</th>
                                <th class="text-end" style="min-width: 110px;">Purchase Rate</th>
                                <th class="text-end" style="min-width: 120px;">Total Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stock->items as $item)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $item->ingredient->name ?? 'Raw Material' }}</div>
                                    <small class="text-muted font-monospace font-size-11">
                                        {{ $item->ingredient->slug ?? '' }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-dark">{{ number_format($item->quantity, 3) }}</span>
                                    <span class="badge bg-light text-dark border ms-1">{{ $item->unit->symbol ?? '' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="text-primary fw-semibold">{{ number_format($item->base_quantity, 2) }}</span>
                                    <span class="text-muted font-size-11">{{ $item->unit->base_unit ?? '' }}</span>
                                </td>
                                <td class="text-end text-muted font-size-12">
                                    ₦{{ number_format($item->unit_price, 2) }}
                                    <small class="d-block font-size-10">/ {{ $item->unit->symbol ?? 'unit' }}</small>
                                </td>
                                <td class="text-end fw-bold text-dark font-size-13">
                                    ₦{{ number_format($item->total_price, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="4" class="text-end fw-bold font-size-12">Grand Total:</th>
                                <th class="text-end fw-bold text-success font-size-14">
                                    ₦{{ number_format($stock->items->sum('total_price'), 2) }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer bg-light border-top px-4 py-2.5 d-flex justify-content-between">
                <div class="font-size-11 text-muted">
                    <i class="mdi mdi-account-circle-outline me-1"></i> Recorded by: 
                    <strong>{{ $stock->admin->name ?? 'System Admin' }}</strong> on {{ $stock->created_at->format('M d, Y h:i A') }}
                </div>
                <button type="button" class="btn btn-secondary rounded-pill px-4 font-size-12" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
@endforeach
