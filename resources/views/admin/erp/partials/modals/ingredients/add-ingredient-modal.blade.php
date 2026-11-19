{{-- =====================================================
     ADD INGREDIENT MODAL
     ===================================================== --}}
<div class="modal fade" id="addIngredient" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <form method="POST" action="{{ url('admin/newIngredient') }}">
                @csrf
                <div class="modal-header border-bottom px-4 py-3 bg-light">
                    <div class="d-flex align-items-center gap-2">
                        <span class="avatar-xs">
                            <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                <i class="bx bx-plus font-size-16"></i>
                            </span>
                        </span>
                        <h5 class="modal-title fw-bold text-dark mb-0">Add New Ingredient</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark font-size-12 mb-1">Ingredient Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-purchase-tag-alt"></i></span>
                                <input type="text" name="name" class="form-control border-start-0" placeholder="e.g. Bread Flour, White Sugar, Yeast" required autofocus>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark font-size-12 mb-1">Base Unit of Measure <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-ruler"></i></span>
                                <select name="base_unit_id" class="form-select border-start-0" required>
                                    <option value="" selected disabled>Select Core Base Unit...</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}">
                                            {{ $unit->name }} ({{ $unit->symbol }}) — {{ ucfirst($unit->unit_type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-muted font-size-11 mt-1 d-block">All inventory stock-in and recipe item weights calculate from this base unit.</small>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark font-size-12 mb-1">
                                Reorder Level Threshold
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-bell"></i></span>
                                <input type="number" name="reorder_level" class="form-control border-start-0" min="0" step="0.001" placeholder="e.g. 5000">
                            </div>
                            <small class="text-muted font-size-11 mt-1 d-block">
                                Minimum threshold in base units. Leave blank for default (1,000 g / 1,000 ml / 10 pcs).
                            </small>
                        </div>

                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-between p-0">
                                    <div>
                                        <label class="form-check-label fw-bold text-dark d-block font-size-13 mb-0" for="newActive">
                                            Mark as Active
                                        </label>
                                        <small class="text-muted font-size-11">Active raw materials are immediately available for recipes and purchase orders.</small>
                                    </div>
                                    <input class="form-check-input ms-3 float-none" type="checkbox" name="is_active" id="newActive" checked style="cursor: pointer; width: 36px; height: 20px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top px-4 py-3 bg-white d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary px-3 font-size-12" data-bs-dismiss="modal">Discard</button>
                    <button type="submit" class="btn btn-primary px-4 font-size-12 fw-medium shadow-sm">
                        <i class="bx bx-plus me-1"></i> Create Ingredient
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
