{{-- =====================================================
     ADD NEW PRODUCT MODAL
     ===================================================== --}}
<div class="modal fade" id="addProduct" data-bs-backdrop="static" tabindex="-1" aria-labelledby="addProductTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="{{ route('newProduct') }}" style="width: 100%">
            @csrf
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                {{-- Header --}}
                <div class="modal-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="addProductTitle">
                            <i class="bx bx-plus-circle me-1 text-primary"></i> Add New Product
                        </h5>
                        <small class="text-muted">Register a new commercial product with retail pricing and sales units.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold font-size-12 text-dark mb-1">Product Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-tag"></i></span>
                                <input type="text" name="name" class="form-control border-start-0 font-size-13" placeholder="e.g. Vanilla Celebration Cake, Sliced Bread" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold font-size-12 text-dark mb-1">Sales Unit <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-cube"></i></span>
                                <input type="text" name="sales_unit" class="form-control border-start-0 font-size-13" placeholder="e.g. Loaf, Pcs, Box, Pack" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold font-size-12 text-dark mb-1">Selling Price (₦) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light font-size-13">₦</span>
                            <input type="number" step="0.01" min="0" name="selling_price" class="form-control font-size-13" placeholder="0.00" required>
                        </div>
                        <small class="text-muted font-size-11 mt-1 d-block">Retail price per sales unit for POS cashiering and online ordering.</small>
                    </div>

                    <div class="p-3 bg-light rounded-3 border">
                        <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-between p-0">
                            <div>
                                <label class="form-check-label fw-bold text-dark d-block font-size-13 mb-0" for="newActive">
                                    Set as Active
                                </label>
                                <small class="text-muted font-size-11">Active products can be immediately cashiered via POS and linked to production batches.</small>
                            </div>
                            <input class="form-check-input ms-3 float-none" type="checkbox" name="is_active" id="newActive" checked style="cursor: pointer; width: 36px; height: 20px;">
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="modal-footer bg-light border-top px-4 py-2.5 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 font-size-12" data-bs-dismiss="modal">Discard</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 font-size-12 fw-medium shadow-sm">
                        <i class="bx bx-plus me-1 font-size-14"></i> Create Product
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
