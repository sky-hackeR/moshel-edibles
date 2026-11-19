{{-- =====================================================
     VIEW OPENING STOCK DETAILS MODAL PARTIAL
     ===================================================== --}}
<div class="modal fade" id="viewOpeningStockModal{{ $openingStock->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom px-4 py-3 bg-light">
                <div class="d-flex align-items-center gap-2">
                    <span class="avatar-xs">
                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                            <i class="bx bx-file font-size-16"></i>
                        </span>
                    </span>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0">Opening Stock Details</h5>
                        <small class="text-muted">Audit Record #{{ $openingStock->id }}</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                {{-- TYPE BANNER --}}
                <div class="text-center mb-4">
                    @if($isProduct)
                        <span class="badge bg-primary-subtle text-primary font-size-12 px-3 py-1.5 rounded-pill">
                            <i class="bx bx-package me-1"></i> Finished Product Starting Stock
                        </span>
                    @else
                        <span class="badge bg-success-subtle text-success font-size-12 px-3 py-1.5 rounded-pill">
                            <i class="bx bx-bowl-rice me-1"></i> Raw Material Starting Stock
                        </span>
                    @endif
                </div>

                {{-- AUDIT DETAILS LIST --}}
                <div class="p-3 bg-light rounded-3 border">
                    <div class="d-flex justify-content-between py-1.5 border-bottom">
                        <span class="text-muted font-size-12">Item Name</span>
                        <strong class="text-dark font-size-12">{{ $itemName }}</strong>
                    </div>

                    <div class="d-flex justify-content-between py-1.5 border-bottom">
                        <span class="text-muted font-size-12">Opening Quantity</span>
                        <strong class="text-dark font-size-12">{{ number_format($openingStock->quantity, 3) }} {{ $unitLabel }}</strong>
                    </div>

                    <div class="d-flex justify-content-between py-1.5 border-bottom">
                        <span class="text-muted font-size-12">{{ $isProduct ? 'Retail Price' : 'Opening Unit Cost' }}</span>
                        <strong class="{{ $isProduct ? 'text-primary' : 'text-success' }} font-size-12">
                            ₦{{ number_format($unitRate, $isProduct ? 2 : 4) }} / {{ $unitLabel }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between py-1.5 border-bottom">
                        <span class="text-muted font-size-12">Total Valuation</span>
                        <strong class="text-dark font-size-12">₦{{ number_format($totalValuation, 2) }}</strong>
                    </div>

                    <div class="d-flex justify-content-between py-1.5 border-bottom">
                        <span class="text-muted font-size-12">Audit Reason</span>
                        <span class="text-dark font-size-12 text-end text-break" style="max-width: 250px;">{{ $openingStock->reason ?? 'Standard commencement initialization' }}</span>
                    </div>

                    <div class="d-flex justify-content-between py-1.5 border-bottom">
                        <span class="text-muted font-size-12">Authorized Officer</span>
                        <span class="text-dark font-size-12 fw-medium"><i class="bx bx-user me-1 text-primary"></i>{{ optional($openingStock->admin)->name ?? 'System Officer' }}</span>
                    </div>

                    <div class="d-flex justify-content-between py-1.5">
                        <span class="text-muted font-size-12">Logged Timestamp</span>
                        <span class="text-dark font-size-12">{{ $openingStock->created_at->format('d M, Y h:i A') }}</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-top px-4 py-3 bg-white d-flex justify-content-end">
                <button type="button" class="btn btn-secondary px-3 font-size-12" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
