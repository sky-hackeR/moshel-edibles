{{-- ========================================================================= --}}
{{-- 1. ADD NEW CUSTOM UNIT MODAL                                             --}}
{{-- ========================================================================= --}}
<div class="modal fade" id="addUnit" data-bs-backdrop="static" tabindex="-1" aria-labelledby="addUnitLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="{{ url('/admin/newUnit') }}" class="w-100" id="formAddUnit">
            @csrf
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                {{-- Gradient Accent Bar --}}
                <div style="height: 5px; background: linear-gradient(90deg, #581a62 0%, #ce278c 50%, #f472b6 100%);"></div>

                <div class="modal-header border-bottom px-4 py-3 bg-white">
                    <div class="d-flex align-items-center gap-2">
                        <span class="avatar-title rounded-circle font-size-16 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #fdf2f9; color: #ce278c;">
                            <i class="bx bx-plus"></i>
                        </span>
                        <div>
                            <h5 class="modal-title fw-bold text-dark" id="addUnitLabel">Register New Measurement Unit</h5>
                            <p class="text-muted font-size-12 mb-0">Add wholesale packages, recipe measures, or custom containers to the ERP registry.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 bg-light">
                    <div class="card border-0 shadow-none mb-3" style="border-radius: 12px;">
                        <div class="card-body p-3">
                            <div class="row g-3">
                                {{-- Unit Name --}}
                                <div class="col-md-7">
                                    <label class="form-label font-size-12 fw-bold text-dark">Unit Display Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="addUnitName" class="form-control font-size-14" placeholder="e.g. 50kg Flour Bag, Carton (24pcs), Jerrycan" required>
                                    <small class="text-muted font-size-11">Human-friendly name shown in dropdowns and receipts.</small>
                                </div>

                                {{-- Unit Symbol --}}
                                <div class="col-md-5">
                                    <label class="form-label font-size-12 fw-bold text-dark">Symbol / Code <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bx bx-tag text-muted"></i></span>
                                        <input type="text" name="symbol" id="addUnitSymbol" class="form-control font-size-14 text-lowercase" placeholder="e.g. bag_50kg, ctn_24" required>
                                    </div>
                                    <small class="text-muted font-size-11">Short unique alphanumeric code.</small>
                                </div>

                                {{-- Dimension / Unit Type --}}
                                <div class="col-md-6">
                                    <label class="form-label font-size-12 fw-bold text-dark">Measurement Dimension <span class="text-danger">*</span></label>
                                    <select name="unit_type" id="addUnitType" class="form-select font-size-14" required>
                                        <option value="mass" selected>⚖️ Mass / Weight (Calculates in Grams 'g')</option>
                                        <option value="volume">🧪 Liquid / Volume (Calculates in Millilitres 'ml')</option>
                                        <option value="count">📦 Discrete / Count (Calculates in Pieces 'pcs')</option>
                                    </select>
                                </div>

                                {{-- Base Multiplier --}}
                                <div class="col-md-6">
                                    <label class="form-label font-size-12 fw-bold text-primary">
                                        Base Multiplier <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white fw-bold text-muted">&times;</span>
                                        <input type="number" step="0.0001" min="0.0001" name="base_multiplier" id="addBaseMultiplier" class="form-control font-size-14 fw-bold" placeholder="e.g. 50000" required>
                                        <span class="input-group-text bg-light fw-bold text-primary" id="addBaseUnitPreview">g</span>
                                    </div>
                                    <small class="text-muted font-size-11">How many base units are contained inside 1 of this unit?</small>
                                </div>

                                {{-- Quick Presets Pill Bar --}}
                                <div class="col-12">
                                    <div class="p-2 rounded bg-light border d-flex flex-wrap align-items-center gap-1 font-size-12">
                                        <span class="text-muted fw-bold me-2 font-size-11">Quick Container Multipliers:</span>
                                        <div id="addPresetsContainer" class="d-inline-flex flex-wrap gap-1">
                                            {{-- Dynamic presets populated via JS --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Dynamic Conversion Equation Card --}}
                    <div class="card border-0 mb-3" style="border-radius: 12px; background: #faf4fb; border: 1px dashed #ce278c !important;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="font-size-11 text-uppercase fw-bold text-muted">Preview Conversion Formula</span>
                                    <h4 class="mb-0 fw-bold text-dark mt-1" id="addEquationPreview">
                                        1 <span id="addEqSym">unit</span> = <span class="text-primary font-monospace" id="addEqMult">0</span> <span id="addEqBase">g</span>
                                    </h4>
                                </div>
                                <div class="avatar-sm">
                                    <span class="avatar-title rounded-circle font-size-18 bg-white text-pink shadow-sm">
                                        <i class="bx bx-check-double"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Usage Scopes --}}
                    <div class="card border-0 shadow-none mb-0" style="border-radius: 12px;">
                        <div class="card-body p-3">
                            <label class="form-label font-size-12 fw-bold text-dark mb-2">Operational Usage Scopes</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="form-check form-switch p-2 border rounded bg-white ps-5">
                                        <input class="form-check-input ms-n4" type="checkbox" name="use_for_purchase" id="addPurchase" checked>
                                        <label class="form-check-label fw-semibold font-size-13 text-dark" for="addPurchase">
                                            Enable for Purchasing
                                            <span class="d-block font-size-11 text-muted fw-normal">Available during raw material Stock-In & vendor procurement.</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch p-2 border rounded bg-white ps-5">
                                        <input class="form-check-input ms-n4" type="checkbox" name="use_for_recipe" id="addRecipe" checked>
                                        <label class="form-check-label fw-semibold font-size-13 text-dark" for="addRecipe">
                                            Enable for Recipes
                                            <span class="d-block font-size-11 text-muted fw-normal">Selectable when formulating ingredient lists in production.</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-white border-top px-4 py-3 d-flex justify-content-between">
                    <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-sm">
                        <i class="bx bx-check me-1 align-middle"></i> Save Unit to Registry
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
