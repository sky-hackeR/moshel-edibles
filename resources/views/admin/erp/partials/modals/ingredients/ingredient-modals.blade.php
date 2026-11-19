{{-- =====================================================
     EDIT & DELETE INGREDIENT MODALS
     ===================================================== --}}
@foreach($ingredients as $ingredient)
    {{-- EDIT MODAL --}}
    <div class="modal fade" id="editIngredient{{ $ingredient->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <form method="POST" action="{{ url('admin/updateIngredient') }}">
                    @csrf
                    <input type="hidden" name="ingredient_id" value="{{ $ingredient->id }}">

                    <div class="modal-header border-bottom px-4 py-3 bg-light">
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar-xs">
                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                    <i class="bx bx-edit font-size-16"></i>
                                </span>
                            </span>
                            <h5 class="modal-title fw-bold text-dark mb-0">Edit Ingredient</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark font-size-12 mb-1">Ingredient Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-purchase-tag-alt"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0" value="{{ $ingredient->name }}" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark font-size-12 mb-1">Base Unit of Measure <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-ruler"></i></span>
                                    <select name="base_unit_id" class="form-select border-start-0" required>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}" {{ $ingredient->base_unit_id == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->name }} ({{ $unit->symbol }}) — {{ ucfirst($unit->unit_type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-muted font-size-11 mt-1 d-block">Base units represent the fundamental standard used in recipe item formulas.</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark font-size-12 mb-1">
                                    Reorder Level ({{ $ingredient->baseUnit->symbol ?? 'unit' }})
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-bell"></i></span>
                                    <input type="number" name="reorder_level" class="form-control border-start-0" min="0" step="0.001" value="{{ $ingredient->reorder_level }}">
                                </div>
                                <small class="text-muted font-size-11 mt-1 d-block">
                                    Leave blank to use the system default (1,000 g / 1,000 ml / 10 pcs).
                                </small>
                            </div>

                            <div class="col-12">
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-between p-0">
                                        <div>
                                            <label class="form-check-label fw-bold text-dark d-block font-size-13 mb-0" for="editActive{{ $ingredient->id }}">
                                                Catalog Status
                                            </label>
                                            <small class="text-muted font-size-11">Inactive materials cannot be allocated into new production batches.</small>
                                        </div>
                                        <input class="form-check-input ms-3 float-none" type="checkbox" name="is_active" id="editActive{{ $ingredient->id }}" {{ $ingredient->is_active ? 'checked' : '' }} style="cursor: pointer; width: 36px; height: 20px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-top px-4 py-3 bg-white d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary px-3 font-size-12" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 font-size-12 fw-medium shadow-sm">
                            <i class="bx bx-check me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- DELETE MODAL --}}
    <div class="modal fade" id="deleteIngredient{{ $ingredient->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <form method="POST" action="{{ url('admin/deleteIngredient') }}">
                    @csrf
                    <input type="hidden" name="ingredient_id" value="{{ $ingredient->id }}">

                    <div class="modal-body text-center p-4">
                        <div class="avatar-md mx-auto mb-3">
                            <span class="avatar-title rounded-circle bg-danger-subtle text-danger font-size-28">
                                <i class="bx bx-error-circle"></i>
                            </span>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Delete Ingredient?</h5>
                        <p class="text-muted font-size-13 mb-3">
                            Are you sure you want to remove <strong class="text-dark">{{ $ingredient->name }}</strong> from your raw material catalog?
                        </p>

                        <div class="alert alert-warning text-start font-size-12 mb-4 p-2.5 border-0 rounded-3">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bx bx-shield-quarter font-size-16 text-warning mt-0.5"></i>
                                <div>
                                    <strong>Safety Verification:</strong> Ingredients with active inventory balances or associated recipe formulations cannot be deleted and will trigger security audit alerts.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light w-50 font-size-12 fw-medium" data-bs-dismiss="modal">Keep Ingredient</button>
                            <button type="submit" class="btn btn-danger w-50 font-size-12 fw-medium shadow-sm">
                                <i class="bx bx-trash me-1"></i> Confirm Delete
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
