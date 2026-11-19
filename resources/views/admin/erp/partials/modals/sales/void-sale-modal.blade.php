{{-- VOID TRANSACTION MODAL --}}
<div class="modal fade" id="voidSaleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <form action="{{ url('admin/sales/void') }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white border-0 px-4 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-error-circle font-size-22"></i>
                        <h5 class="modal-title text-white fw-bold mb-0">Void In-Store Sale</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="sale_id" id="void_sale_id">
                    <div class="alert alert-soft-danger d-flex align-items-center gap-2 mb-3">
                        <i class="bx bx-info-circle font-size-20 flex-shrink-0"></i>
                        <div class="small">
                            Voiding cancels this transaction and <strong>automatically restores product and ingredient inventory</strong> back to stock.
                        </div>
                    </div>

                    <p class="mb-3">Are you sure you want to void transaction <strong id="void_ref_display" class="font-monospace text-danger"></strong>?</p>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Reason for Voiding <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="e.g. Customer returned items, cashier duplicate entry, terminal payment declined..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top px-4 py-3 bg-light">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger px-4 fw-bold">
                        <i class="bx bx-trash me-1"></i> Confirm Void & Restore Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
