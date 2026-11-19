{{-- ========================================================================= --}}
{{-- FLOATING DRAGGABLE UNIT CONVERSION CALCULATOR WIDGET                     --}}
{{-- ========================================================================= --}}
<div id="floatingCalculatorWidget" class="floating-calc-widget shadow-lg" style="display: none;">
    {{-- Draggable Grip Header (Black / Dark Slate with muted accents) --}}
    <div class="calc-drag-handle d-flex align-items-center justify-content-between px-3 py-2 text-white">
        <div class="d-flex align-items-center gap-2">
            <i class="bx bx-move font-size-16 text-muted"></i>
            <span class="fw-bold font-size-13 text-white">Unit Conversion Calculator</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-link text-white-50 p-0 text-decoration-none" id="calcMinimizeBtn" title="Minimize / Expand">
                <i class="bx bx-minus font-size-16"></i>
            </button>
            <button type="button" class="btn btn-sm btn-link text-white-50 p-0 text-decoration-none" id="calcCloseBtn" title="Close Calculator">
                <i class="bx bx-x font-size-18"></i>
            </button>
        </div>
    </div>

    {{-- Calculator Main Body (Muted & Black generalized colors) --}}
    <div id="calcBodyContent" class="p-3 bg-white">
        {{-- Digital Readout Display --}}
        <div class="calc-screen p-3 rounded-3 mb-3">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="font-size-11 text-uppercase fw-bold text-muted" id="calcScreenLabel">Calculated Equivalent</span>
                <span class="badge bg-white text-muted border font-size-10 font-monospace" id="calcFormulaTag">Ready</span>
            </div>
            <div class="d-flex align-items-baseline justify-content-between mt-1">
                <div class="fw-bold text-dark font-monospace" style="font-size: 26px; line-height: 1.1;" id="calcOutputValue">
                    1.0000
                </div>
                <div class="font-size-13 fw-bold text-dark ms-2 text-end" id="calcOutputUnit">
                    Kilogram (kg)
                </div>
            </div>
            <div class="mt-2 pt-2 border-top font-size-11 text-muted d-flex justify-content-between align-items-center" id="calcBaseEquivalentRow">
                <span>Base Standard Equivalent:</span>
                <span class="fw-bold text-dark font-monospace" id="calcBaseEquivalentVal">1,000.0000 g</span>
            </div>
        </div>

        {{-- Amount Input with Quick Stepper --}}
        <div class="mb-3">
            <label class="form-label font-size-11 fw-bold text-muted text-uppercase mb-1">Quantity / Amount</label>
            <div class="input-group">
                <button type="button" class="btn btn-light border text-dark font-size-13 px-2 btn-qty-step" data-step="-1">&minus;1</button>
                <input type="number" id="calcInputQty" class="form-control text-center font-monospace fw-bold font-size-15 text-dark" value="1" step="any" min="0.0001">
                <button type="button" class="btn btn-light border text-dark font-size-13 px-2 btn-qty-step" data-step="1">+1</button>
                <button type="button" class="btn btn-light border text-muted font-size-12 px-2" id="calcClearBtn" title="Reset Amount">AC</button>
            </div>
        </div>

        {{-- Units Conversion Grid: From -> To with Swap --}}
        <div class="row g-2 align-items-center mb-3">
            <div class="col-5">
                <label class="form-label font-size-11 fw-bold text-muted text-uppercase mb-1">From Unit</label>
                <select id="calcFromUnit" class="form-select form-select-sm font-size-12 text-dark fw-medium">
                    <optgroup label="⚖️ Mass (Grams)">
                        @foreach($massUnits as $u)
                            <option value="{{ $u->id }}" {{ $u->symbol == 'kg' ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->symbol }})
                            </option>
                        @endforeach
                    </optgroup>
                    <optgroup label="🧪 Volume (Millilitres)">
                        @foreach($volumeUnits as $u)
                            <option value="{{ $u->id }}">
                                {{ $u->name }} ({{ $u->symbol }})
                            </option>
                        @endforeach
                    </optgroup>
                    <optgroup label="📦 Count (Pieces)">
                        @foreach($countUnits as $u)
                            <option value="{{ $u->id }}">
                                {{ $u->name }} ({{ $u->symbol }})
                            </option>
                        @endforeach
                    </optgroup>
                </select>
            </div>

            <div class="col-2 text-center pt-3">
                <button type="button" class="btn btn-sm btn-light border rounded-circle p-1 shadow-none" id="calcSwapBtn" title="Swap Units" style="width: 30px; height: 30px;">
                    <i class="bx bx-transfer font-size-14 text-dark"></i>
                </button>
            </div>

            <div class="col-5">
                <label class="form-label font-size-11 fw-bold text-muted text-uppercase mb-1">To Target Unit</label>
                <select id="calcToUnit" class="form-select form-select-sm font-size-12 text-dark fw-medium">
                    {{-- Dynamically populated based on From Unit dimension --}}
                </select>
            </div>
        </div>

        {{-- Quick Preset Container Multipliers (Muted & Black) --}}
        <div class="p-2 rounded bg-light border">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="font-size-11 fw-bold text-muted text-uppercase">Container Amounts</span>
                <span class="font-size-10 text-muted" id="calcPresetDimNote">Mass Quick Fills</span>
            </div>
            <div class="d-flex flex-wrap gap-1" id="calcQuickPresets">
                {{-- Injected dynamically --}}
            </div>
        </div>

        {{-- Drag Instruction Hint --}}
        <div class="mt-2 text-center">
            <small class="text-muted font-size-10">
                <i class="bx bx-move me-1"></i>Click and hold the dark header bar to move this calculator anywhere.
            </small>
        </div>
    </div>
</div>
