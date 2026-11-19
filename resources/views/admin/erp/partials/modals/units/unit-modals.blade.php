{{-- ========================================================================= --}}
{{-- 2. EDIT UNIT MODALS                                                      --}}
{{-- ========================================================================= --}}
@foreach($units as $unit)
    @php
        $isBase = in_array($unit->symbol, ['g', 'ml', 'pcs']);
    @endphp
    <div class="modal fade" id="editUnit{{ $unit->id }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="editUnitLabel{{ $unit->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" action="{{ url('/admin/updateUnit') }}" class="w-100">
                @csrf
                <input type="hidden" name="unit_id" value="{{ $unit->id }}">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                    {{-- Header bar --}}
                    <div style="height: 5px; background: {{ $isBase ? '#581a62' : 'linear-gradient(90deg, #581a62 0%, #ce278c 100%)' }};"></div>

                    <div class="modal-header border-bottom px-4 py-3 bg-white">
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar-title rounded-circle font-size-16 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #fdf2f9; color: #581a62;">
                                <i class="bx bx-edit-alt"></i>
                            </span>
                            <div>
                                <h5 class="modal-title fw-bold text-dark" id="editUnitLabel{{ $unit->id }}">Edit Unit: {{ $unit->name }}</h5>
                                <p class="text-muted font-size-12 mb-0">Modify display labels, base conversion values, and operational availability.</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4 bg-light">
                        @if($isBase)
                            <div class="alert alert-info border-0 shadow-none d-flex align-items-center gap-2 py-2 px-3 mb-3" style="border-radius: 10px;">
                                <i class="bx bx-shield-quarter font-size-20"></i>
                                <div class="font-size-13">
                                    <b>System Core Standard:</b> Multiplier, symbol, and dimension are locked to preserve database calculation integrity. You may update the display name.
                                </div>
                            </div>
                        @endif

                        <div class="card border-0 shadow-none mb-3" style="border-radius: 12px;">
                            <div class="card-body p-3">
                                <div class="row g-3">
                                    {{-- Display Name --}}
                                    <div class="col-md-6">
                                        <label class="form-label font-size-12 fw-bold text-dark">Display Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control font-size-14" value="{{ $unit->name }}" required>
                                    </div>

                                    {{-- Symbol --}}
                                    <div class="col-md-6">
                                        <label class="form-label font-size-12 fw-bold text-dark">Symbol <span class="text-danger">*</span></label>
                                        <input type="text" name="symbol" class="form-control font-size-14 {{ $isBase ? 'bg-light' : '' }}" value="{{ $unit->symbol }}" required {{ $isBase ? 'readonly' : '' }}>
                                    </div>

                                    {{-- Dimension Type --}}
                                    <div class="col-md-6">
                                        <label class="form-label font-size-12 fw-bold text-dark">Dimension Classification <span class="text-danger">*</span></label>
                                        <select name="unit_type" class="form-select font-size-14 {{ $isBase ? 'bg-light' : '' }}" required {{ $isBase ? 'disabled' : '' }}>
                                            <option value="mass" {{ $unit->unit_type == 'mass' ? 'selected' : '' }}>⚖️ Mass / Weight (Calculates in Grams 'g')</option>
                                            <option value="volume" {{ $unit->unit_type == 'volume' ? 'selected' : '' }}>🧪 Liquid / Volume (Calculates in Millilitres 'ml')</option>
                                            <option value="count" {{ $unit->unit_type == 'count' ? 'selected' : '' }}>📦 Discrete / Count (Calculates in Pieces 'pcs')</option>
                                        </select>
                                        @if($isBase)
                                            <input type="hidden" name="unit_type" value="{{ $unit->unit_type }}">
                                        @endif
                                    </div>

                                    {{-- Base Multiplier --}}
                                    <div class="col-md-6">
                                        <label class="form-label font-size-12 fw-bold text-primary">Base Multiplier <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white fw-bold text-muted">&times;</span>
                                            <input type="number" step="0.0001" min="0.0001" name="base_multiplier" class="form-control font-size-14 fw-bold {{ $isBase ? 'bg-light' : '' }}" value="{{ $unit->base_multiplier }}" required {{ $isBase ? 'readonly' : '' }}>
                                            <span class="input-group-text bg-light fw-bold text-primary">{{ $unit->base_unit }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Current Formula Preview --}}
                        <div class="p-3 rounded mb-3" style="background: #fdf8fe; border: 1px dashed #eedfed;">
                            <span class="font-size-11 text-muted text-uppercase fw-bold">Conversion Equation</span>
                            <div class="font-size-14 fw-bold text-dark mt-1">
                                1 {{ $unit->symbol }} = <span class="text-primary font-monospace">{{ rtrim(rtrim(number_format($unit->base_multiplier, 4), '0'), '.') }}</span> {{ $unit->base_unit }}
                            </div>
                        </div>

                        {{-- Scopes & Active Toggle --}}
                        <div class="card border-0 shadow-none mb-0" style="border-radius: 12px;">
                            <div class="card-body p-3">
                                <label class="form-label font-size-12 fw-bold text-dark mb-2">Usage Permissions & Registry Status</label>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="form-check form-switch p-2 border rounded bg-white ps-5">
                                            <input class="form-check-input ms-n4" type="checkbox" name="use_for_purchase" id="editPur{{ $unit->id }}" {{ $unit->use_for_purchase ? 'checked' : '' }} {{ $isBase ? 'disabled' : '' }}>
                                            <label class="form-check-label fw-semibold font-size-13 text-dark" for="editPur{{ $unit->id }}">Purchasing</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch p-2 border rounded bg-white ps-5">
                                            <input class="form-check-input ms-n4" type="checkbox" name="use_for_recipe" id="editRec{{ $unit->id }}" {{ $unit->use_for_recipe ? 'checked' : '' }} {{ $isBase ? 'disabled' : '' }}>
                                            <label class="form-check-label fw-semibold font-size-13 text-dark" for="editRec{{ $unit->id }}">Recipe</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch p-2 border rounded bg-white ps-5">
                                            <input class="form-check-input ms-n4" type="checkbox" name="is_active" id="editAct{{ $unit->id }}" {{ $unit->is_active ? 'checked' : '' }} {{ $isBase ? 'disabled' : '' }}>
                                            <label class="form-check-label fw-semibold font-size-13 text-dark" for="editAct{{ $unit->id }}">Active Status</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-white border-top px-4 py-3 d-flex justify-content-between">
                        <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-sm">
                            <i class="bx bx-check me-1 align-middle"></i> Update Unit Specifications
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

{{-- ========================================================================= --}}
{{-- 3. DELETE UNIT MODALS                                                    --}}
{{-- ========================================================================= --}}
@foreach($units as $unit)
    @php
        $isBase = in_array($unit->symbol, ['g', 'ml', 'pcs']);
    @endphp
    @if(!$isBase)
        <div class="modal fade" id="deleteUnit{{ $unit->id }}" tabindex="-1" aria-labelledby="deleteUnitLabel{{ $unit->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form method="POST" action="{{ url('/admin/deleteUnit') }}" class="w-100">
                    @csrf
                    <input type="hidden" name="unit_id" value="{{ $unit->id }}">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                        <div style="height: 5px; background: #ef4444;"></div>
                        <div class="modal-body text-center p-4">
                            <div class="avatar-lg mx-auto mb-3">
                                <span class="avatar-title rounded-circle bg-danger-subtle text-danger font-size-36">
                                    <i class="bx bx-trash"></i>
                                </span>
                            </div>
                            <h4 class="fw-bold text-dark mb-1" id="deleteUnitLabel{{ $unit->id }}">Delete Measurement Unit?</h4>
                            <p class="text-muted font-size-13 mb-3">
                                You are about to remove <b>{{ $unit->name }} ({{ $unit->symbol }})</b> from the active registry.
                            </p>

                            <div class="p-3 rounded text-start mb-3" style="background: #fef2f2; border: 1px solid #fee2e2;">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <i class="bx bx-error-circle text-danger font-size-18"></i>
                                    <span class="fw-bold text-danger font-size-12 text-uppercase">Impact Warning</span>
                                </div>
                                <p class="text-muted font-size-12 mb-0">
                                    Formula: <b>1 {{ $unit->symbol }} = {{ number_format($unit->base_multiplier, 4) }} {{ $unit->base_unit }}</b>.<br>
                                    Deleting this unit may invalidate purchase records or production recipes configured with this unit.
                                </p>
                            </div>

                            <div class="d-flex gap-2 justify-content-center">
                                <button type="button" class="btn btn-light px-4 rounded-pill w-50" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger px-4 rounded-pill w-50 shadow-sm">Yes, Delete</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endforeach
