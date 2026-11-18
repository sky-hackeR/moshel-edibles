@extends('staff.layout.dashboard')

@section('content')

    {{-- PAGE HEADER --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
                <div>
                    <h4 class="mb-sm-0 font-size-18 text-primary fw-bold">Bakery Production Floor</h4>
                    <p class="text-muted font-size-12 mb-0">Live recipe audit & inventory feasibility engine</p>
                </div>
                <div class="page-title-right d-flex align-items-center">
                    <span class="badge bg-soft-success text-success border border-success d-inline-flex align-items-center px-2 py-1 font-size-12">
                        <span class="spinner-grow spinner-grow-sm text-success me-1" style="width: 8px; height: 8px;" role="status"></span>
                        <span id="liveSyncStatus">Live Stock Synced</span>
                    </span>
                    <button type="button" id="btnRefreshStock" class="btn btn-sm btn-outline-secondary ms-2" title="Force sync latest inventory numbers">
                        <i class="mdi mdi-sync me-1"></i>Sync Stock
                    </button>
                    <ol class="breadcrumb m-0 ms-3 d-none d-md-flex">
                        <li class="breadcrumb-item active">New Batch</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- LEFT COLUMN: BATCH BUILDER & LIVE FEASIBILITY --}}
        <div class="col-xl-8">

            {{-- LIVE FEASIBILITY STATUS BANNER --}}
            <div id="feasibilityBanner" class="card border shadow-sm mb-4" style="border-radius: 12px; transition: all 0.3s ease;">
                <div class="card-body p-3 p-md-4">
                    <div id="statusPlaceholder" class="text-center py-2">
                        <div class="avatar-md mx-auto mb-2 text-muted">
                            <span class="avatar-title bg-light text-secondary rounded-circle font-size-24">
                                <i class="bx bx-calculator"></i>
                            </span>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Production Feasibility Engine</h5>
                        <p class="text-muted font-size-13 mb-0">Select a product and enter target quantity below to instantly audit pantry stocks and verify if production is possible.</p>
                    </div>

                    {{-- READY STATE --}}
                    <div id="statusReady" class="d-none">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0 me-3">
                                    <span class="avatar-title bg-success text-white rounded-circle font-size-20 shadow-sm">
                                        <i class="mdi mdi-check-bold"></i>
                                    </span>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge bg-success font-size-11 px-2 py-1 text-uppercase fw-bold">Ready for Production</span>
                                        <span class="text-success fw-bold font-size-13">100% Stock Available</span>
                                    </div>
                                    <p class="text-muted font-size-13 mb-0" id="readyMessage">All required ingredients are in stock. This batch can be recorded safely.</p>
                                </div>
                            </div>
                            <div class="text-md-end flex-shrink-0">
                                <div class="d-inline-block text-start text-md-end bg-soft-success px-3 py-2 rounded border border-success border-opacity-25">
                                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 10px;">Max Possible Batch</small>
                                    <span class="fw-bolder text-success font-size-16" id="readyMaxOutput">0 Units</span>
                                    <button type="button" class="btn btn-xs btn-success ms-2 py-0 px-2 btn-set-max" style="font-size: 11px;">Set Max</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BLOCKED STATE --}}
                    <div id="statusBlocked" class="d-none">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0 me-3">
                                    <span class="avatar-title bg-danger text-white rounded-circle font-size-20 shadow-sm">
                                        <i class="mdi mdi-alert-octagon"></i>
                                    </span>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge bg-danger font-size-11 px-2 py-1 text-uppercase fw-bold">Production Blocked</span>
                                        <span class="text-danger fw-bold font-size-13" id="blockedShortageBadge">0 Ingredients Deficit</span>
                                    </div>
                                    <p class="text-muted font-size-13 mb-0" id="blockedMessage">Insufficient raw materials in inventory to complete this batch quantity.</p>
                                </div>
                            </div>
                            <div class="text-md-end flex-shrink-0">
                                <div class="d-inline-block text-start text-md-end bg-soft-warning px-3 py-2 rounded border border-warning border-opacity-50">
                                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 10px;">Max With Current Stock</small>
                                    <span class="fw-bolder text-warning font-size-16" id="blockedMaxOutput">0 Units</span>
                                    <button type="button" class="btn btn-xs btn-warning text-dark ms-2 py-0 px-2 fw-bold btn-set-max" style="font-size: 11px;">Set to Max</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- NO RECIPE STATE --}}
                    <div id="statusNoRecipe" class="d-none">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0 me-3">
                                <span class="avatar-title bg-warning text-dark rounded-circle font-size-20">
                                    <i class="mdi mdi-silverware-fork-knife"></i>
                                </span>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">No Recipe Configured</h6>
                                <p class="text-muted font-size-13 mb-0">
                                    This product does not have any ingredients assigned yet. Contact your administrator to add a recipe.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MAIN PRODUCTION FORM CARD --}}
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="bx bx-cog text-primary me-2 font-size-18"></i>Batch Parameters
                    </h5>
                    <span class="badge bg-light text-secondary font-size-11">Step 1 of 2</span>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ url('/staff/produce') }}" id="productionForm">
                        @csrf

                        <div class="row g-3 mb-4">
                            {{-- PRODUCT PICKER --}}
                            <div class="col-md-7">
                                <label class="form-label fw-bold text-dark">
                                    Target Product <span class="text-danger">*</span>
                                </label>
                                <select name="product_id" id="product_select" class="form-select select2" required>
                                    <option value="">-- Choose Product to Produce --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" 
                                                data-unit="{{ $product->sales_unit ?? 'Units' }}"
                                                data-stock="{{ (float)$product->stock_on_hand }}"
                                                data-price="{{ (float)$product->selling_price }}"
                                                data-has-recipe="{{ $product->recipe && $product->recipe->items->isNotEmpty() ? '1' : '0' }}">
                                            {{ $product->name }} (In Stock: {{ (float)$product->stock_on_hand }} {{ $product->sales_unit ?? 'Units' }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="d-flex align-items-center justify-content-between mt-1">
                                    <small class="text-muted">Recipe ingredient deduction will be computed dynamically.</small>
                                    <span class="badge bg-soft-info text-info font-size-11 d-none" id="productStockBadge">Finished Stock: 0</span>
                                </div>
                            </div>

                            {{-- QUANTITY INPUT WITH QUICK CONTROLS --}}
                            <div class="col-md-5">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <label class="form-label fw-bold text-dark mb-0">
                                        Batch Quantity <span class="text-danger">*</span>
                                    </label>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-secondary py-0 px-2 font-size-11 btn-quick-qty" data-add="5">+5</button>
                                        <button type="button" class="btn btn-outline-secondary py-0 px-2 font-size-11 btn-quick-qty" data-add="10">+10</button>
                                        <button type="button" class="btn btn-outline-secondary py-0 px-2 font-size-11 btn-quick-qty" data-add="25">+25</button>
                                    </div>
                                </div>
                                <div class="input-group">
                                    <input type="number" 
                                           name="quantity" 
                                           id="production_quantity" 
                                           step="any" 
                                           min="0.01" 
                                           class="form-control form-control-lg fw-bold text-primary" 
                                           placeholder="0" 
                                           required
                                           autocomplete="off">
                                    <span class="input-group-text bg-light fw-bold" id="unit_label">Units</span>
                                </div>
                                <small class="text-muted">Type or use quick buttons to calculate availability.</small>
                            </div>
                        </div>

                        {{-- REAL-TIME RECIPE & INGREDIENT AUDIT MATRIX --}}
                        <div class="border rounded p-3 mb-4 bg-light bg-opacity-50" id="ingredientMatrixSection">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">
                                        <i class="bx bx-layer text-primary me-1"></i>Recipe Requirements & Live Pantry Audit
                                    </h6>
                                    <small class="text-muted">Real-time comparison between required batch materials and available stock</small>
                                </div>
                                <span class="badge bg-secondary font-size-11" id="matrixItemsCount">0 Ingredients</span>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm table-hover align-middle mb-0 bg-white rounded border" id="ingredientTable">
                                    <thead class="table-light">
                                        <tr class="font-size-11 text-uppercase text-muted">
                                            <th style="min-width: 140px;">Raw Material</th>
                                            <th class="text-center" style="width: 110px;">Recipe Ratio</th>
                                            <th class="text-center" style="width: 120px;">Needed</th>
                                            <th class="text-center" style="width: 120px;">In Stock</th>
                                            <th style="min-width: 160px;">Stock Health & Remaining</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ingredientTableBody">
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted font-size-13">
                                                <i class="bx bx-info-circle me-1"></i>Select a product above to display its recipe ingredients.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- METADATA: BAKER NOTES & PRODUCTION DATE --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label class="form-label font-size-13 text-muted">Baker / Batch Notes (Optional)</label>
                                <input type="text" name="notes" class="form-control" placeholder="e.g. Morning shift run, batch #1, standard bake">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-size-13 text-muted">Production Date</label>
                                <input type="date" name="produced_at" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        {{-- SUBMISSION ACTION BAR --}}
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                            <button type="reset" id="btnResetForm" class="btn btn-light px-4">
                                <i class="bx bx-reset me-1"></i>Clear Form
                            </button>

                            <button type="submit" id="btnSubmitProduction" class="btn btn-success px-5 py-2 fw-bold font-size-15 shadow-sm" disabled>
                                <i class="bx bx-play-circle me-1 font-size-18 align-middle"></i>
                                <span id="btnSubmitText">Enter Batch Quantity</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: TODAY'S OUTPUT & PANTRY BOTTLENECK --}}
        <div class="col-xl-4">

            {{-- TODAY'S OUTPUT HERO CARD --}}
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; background: linear-gradient(135deg, #581a62 0%, #7b2284 100%); color: #ffffff;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-white-50 font-size-12 text-uppercase fw-bold mb-1">Your Shifts Today</p>
                            <h2 class="text-white fw-bolder mb-1">{{ $stats['today_production'] ?? 0 }}</h2>
                            <small class="text-white-50">Batches recorded today</small>
                        </div>
                        <div class="avatar-md flex-shrink-0">
                            <span class="avatar-title bg-white bg-opacity-25 rounded-circle text-white font-size-24">
                                <i class="mdi mdi-cookie"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-top border-white border-opacity-25 d-flex justify-content-between align-items-center font-size-12">
                        <span class="text-white-50">Review history</span>
                        <a href="{{ url('/staff/productionHistory') }}" class="text-white fw-bold text-decoration-underline">
                            View Production Log &rarr;
                        </a>
                    </div>
                </div>
            </div>

            {{-- BOTTLENECK & PANTRY INSIGHT CARD --}}
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h6 class="card-title mb-0 fw-bold text-dark">
                        <i class="bx bx-shield-check text-primary me-2 font-size-18"></i>Pantry Safety Guard
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div id="bottleneckNotice" class="alert alert-soft-info border-info font-size-12 mb-3">
                        <i class="mdi mdi-information-outline me-1"></i>
                        The system prevents negative inventory by validating exact gram (g) and milliliter (ml) deductions before recording.
                    </div>

                    <div id="bottleneckCard" class="d-none p-3 border rounded bg-soft-warning border-warning">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="mdi mdi-ray-start-arrow text-warning font-size-18"></i>
                            <span class="fw-bold text-dark font-size-13">Primary Bottleneck</span>
                        </div>
                        <p class="font-size-12 text-muted mb-2" id="bottleneckText">
                            Raw material with the lowest stock capacity.
                        </p>
                        <div class="d-flex justify-content-between align-items-center font-size-11">
                            <span class="text-muted">Limiting Resource:</span>
                            <strong class="text-dark" id="bottleneckName">-</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center font-size-11 mt-1">
                            <span class="text-muted">Max Output Allowed:</span>
                            <strong class="text-warning font-size-13" id="bottleneckMax">-</strong>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- SCRIPTS: LIVE FEASIBILITY & INVENTORY MATRIX ENGINE --}}
    <script>
        (function() {
            const productionMatrix = @json($productionMatrix);
            const inventoryStatusUrl = "{{ route('staff.production.inventoryStatus') }}";

            const productSelect = document.getElementById('product_select');
            const qtyInput = document.getElementById('production_quantity');
            const unitLabel = document.getElementById('unit_label');
            const productStockBadge = document.getElementById('productStockBadge');

            const statusPlaceholder = document.getElementById('statusPlaceholder');
            const statusReady = document.getElementById('statusReady');
            const statusBlocked = document.getElementById('statusBlocked');
            const statusNoRecipe = document.getElementById('statusNoRecipe');

            const readyMessage = document.getElementById('readyMessage');
            const readyMaxOutput = document.getElementById('readyMaxOutput');
            const blockedShortageBadge = document.getElementById('blockedShortageBadge');
            const blockedMessage = document.getElementById('blockedMessage');
            const blockedMaxOutput = document.getElementById('blockedMaxOutput');

            const ingredientTableBody = document.getElementById('ingredientTableBody');
            const matrixItemsCount = document.getElementById('matrixItemsCount');

            const bottleneckCard = document.getElementById('bottleneckCard');
            const bottleneckNotice = document.getElementById('bottleneckNotice');
            const bottleneckName = document.getElementById('bottleneckName');
            const bottleneckMax = document.getElementById('bottleneckMax');
            const bottleneckText = document.getElementById('bottleneckText');

            const btnSubmit = document.getElementById('btnSubmitProduction');
            const btnSubmitText = document.getElementById('btnSubmitText');
            const btnRefreshStock = document.getElementById('btnRefreshStock');
            const liveSyncStatus = document.getElementById('liveSyncStatus');

            let currentMaxFeasible = 0;

            function formatNumber(val, decimals = 2) {
                return Number(val || 0).toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: decimals });
            }

            function recalculateFeasibility() {
                const productId = productSelect.value;
                const enteredQty = parseFloat(qtyInput.value) || 0;

                if (!productId) {
                    showBannerState('placeholder');
                    renderEmptyTable('Select a product above to display its recipe ingredients.');
                    updateSubmitButton(false, 'Select a Product');
                    unitLabel.textContent = 'Units';
                    productStockBadge.classList.add('d-none');
                    bottleneckCard.classList.add('d-none');
                    bottleneckNotice.classList.remove('d-none');
                    currentMaxFeasible = 0;
                    return;
                }

                const product = productionMatrix[productId];
                if (!product) return;

                const salesUnit = product.sales_unit || 'Units';
                unitLabel.textContent = salesUnit;

                productStockBadge.textContent = 'Finished Stock: ' + formatNumber(product.stock_on_hand) + ' ' + salesUnit;
                productStockBadge.classList.remove('d-none');

                if (!product.has_recipe || !product.items || product.items.length === 0) {
                    showBannerState('no_recipe');
                    renderEmptyTable('This product does not have any recipe items configured in the database.');
                    updateSubmitButton(false, 'Recipe Missing (Disabled)');
                    bottleneckCard.classList.add('d-none');
                    bottleneckNotice.classList.remove('d-none');
                    currentMaxFeasible = 0;
                    return;
                }

                matrixItemsCount.textContent = product.items.length + ' Ingredients';

                let maxFeasibleBatch = Infinity;
                let bottleneckItem = null;

                product.items.forEach(item => {
                    const basePerUnit = item.base_qty_per_unit || 0;
                    const available = item.stock_available || 0;

                    if (basePerUnit > 0) {
                        const canMake = Math.floor(available / basePerUnit);
                        if (canMake < maxFeasibleBatch) {
                            maxFeasibleBatch = canMake;
                            bottleneckItem = item;
                        }
                    }
                });

                if (maxFeasibleBatch === Infinity) maxFeasibleBatch = 0;
                currentMaxFeasible = maxFeasibleBatch;

                if (bottleneckItem) {
                    bottleneckCard.classList.remove('d-none');
                    bottleneckNotice.classList.add('d-none');
                    bottleneckName.textContent = bottleneckItem.name;
                    bottleneckMax.textContent = formatNumber(maxFeasibleBatch) + ' ' + salesUnit;
                    bottleneckText.textContent = 'Limited by ' + bottleneckItem.name + ' (' + formatNumber(bottleneckItem.stock_available) + ' ' + bottleneckItem.base_unit + ' in stock).';
                }

                if (enteredQty <= 0) {
                    showBannerState('placeholder');
                    renderTableRows(product.items, 0);
                    updateSubmitButton(false, 'Enter Quantity to Produce');
                    return;
                }

                let hasDeficit = false;
                let deficitCount = 0;

                const evaluatedItems = product.items.map(item => {
                    const required = (item.base_qty_per_unit || 0) * enteredQty;
                    const available = item.stock_available || 0;
                    const remaining = available - required;
                    const isShort = remaining < 0;
                    const shortage = isShort ? Math.abs(remaining) : 0;

                    if (isShort) {
                        hasDeficit = true;
                        deficitCount++;
                    }

                    return {
                        ...item,
                        required,
                        available,
                        remaining,
                        isShort,
                        shortage,
                    };
                });

                renderTableRows(evaluatedItems, enteredQty);

                if (!hasDeficit) {
                    showBannerState('ready');
                    readyMessage.textContent = 'All ' + product.items.length + ' required ingredients are in stock for ' + formatNumber(enteredQty) + ' ' + salesUnit + '.';
                    readyMaxOutput.textContent = formatNumber(maxFeasibleBatch) + ' ' + salesUnit;
                    updateSubmitButton(true, 'Produce ' + formatNumber(enteredQty) + ' ' + salesUnit);
                } else {
                    showBannerState('blocked');
                    blockedShortageBadge.textContent = deficitCount + ' ' + (deficitCount === 1 ? 'Resource Deficit' : 'Resources Deficit');
                    blockedMessage.textContent = 'Stock deficit detected on ' + deficitCount + ' ingredient(s). Replenishment is required to bake ' + formatNumber(enteredQty) + ' ' + salesUnit + '.';
                    blockedMaxOutput.textContent = formatNumber(maxFeasibleBatch) + ' ' + salesUnit;
                    updateSubmitButton(false, 'Cannot Produce — ' + deficitCount + ' Shortage(s)');
                }
            }

            function showBannerState(state) {
                statusPlaceholder.classList.add('d-none');
                statusReady.classList.add('d-none');
                statusBlocked.classList.add('d-none');
                statusNoRecipe.classList.add('d-none');

                const banner = document.getElementById('feasibilityBanner');
                banner.className = 'card border shadow-sm mb-4';

                if (state === 'ready') {
                    statusReady.classList.remove('d-none');
                    banner.classList.add('border-success', 'bg-soft-success', 'bg-opacity-10');
                } else if (state === 'blocked') {
                    statusBlocked.classList.remove('d-none');
                    banner.classList.add('border-danger', 'bg-soft-danger', 'bg-opacity-10');
                } else if (state === 'no_recipe') {
                    statusNoRecipe.classList.remove('d-none');
                    banner.classList.add('border-warning', 'bg-soft-warning', 'bg-opacity-10');
                } else {
                    statusPlaceholder.classList.remove('d-none');
                }
            }

            function renderEmptyTable(message) {
                ingredientTableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted font-size-13">
                            <i class="bx bx-info-circle me-1"></i>${message}
                        </td>
                    </tr>
                `;
                matrixItemsCount.textContent = '0 Ingredients';
            }

            function renderTableRows(items, enteredQty) {
                if (!items || items.length === 0) {
                    renderEmptyTable('No ingredients found for this recipe.');
                    return;
                }

                let html = '';
                items.forEach(item => {
                    const required = enteredQty > 0 ? (item.required !== undefined ? item.required : (item.base_qty_per_unit * enteredQty)) : 0;
                    const available = item.stock_available || 0;
                    const remaining = available - required;
                    const isShort = enteredQty > 0 && remaining < 0;
                    const shortage = isShort ? Math.abs(remaining) : 0;

                    let pctUsed = 0;
                    if (available > 0 && required > 0) {
                        pctUsed = Math.min(100, Math.round((required / available) * 100));
                    } else if (required > 0 && available <= 0) {
                        pctUsed = 100;
                    }

                    let progressClass = 'bg-success';
                    if (isShort) {
                        progressClass = 'bg-danger progress-bar-striped progress-bar-animated';
                    } else if (pctUsed > 80) {
                        progressClass = 'bg-warning';
                    }

                    html += `
                        <tr class="${isShort ? 'table-danger bg-opacity-25' : ''}">
                            <td>
                                <div class="fw-bold text-dark font-size-13">${item.name}</div>
                                <small class="text-muted">Base unit: <span class="badge bg-light text-secondary font-size-10">${item.base_unit}</span></small>
                            </td>
                            <td class="text-center font-size-12">
                                <span class="text-secondary fw-semibold">${formatNumber(item.recipe_qty)} ${item.recipe_unit}</span>
                            </td>
                            <td class="text-center font-size-12">
                                <span class="fw-bold ${isShort ? 'text-danger' : 'text-dark'}">
                                    ${enteredQty > 0 ? formatNumber(required) + ' ' + item.base_unit : '—'}
                                </span>
                            </td>
                            <td class="text-center font-size-12">
                                <span class="fw-semibold text-secondary">
                                    ${formatNumber(available)} ${item.base_unit}
                                </span>
                            </td>
                            <td>
                                ${enteredQty > 0 ? `
                                    <div class="d-flex align-items-center justify-content-between mb-1" style="font-size: 11px;">
                                        <span class="${isShort ? 'text-danger fw-bold' : 'text-muted'}">
                                            ${isShort ? `⚠ Short by ${formatNumber(shortage)} ${item.base_unit}` : `Balance: ${formatNumber(remaining)} ${item.base_unit}`}
                                        </span>
                                        <span class="text-muted fw-semibold">${pctUsed}% used</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar ${progressClass}" role="progressbar" style="width: ${pctUsed}%;" aria-valuenow="${pctUsed}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                ` : `
                                    <span class="text-muted font-size-11">Enter quantity to audit balance</span>
                                `}
                            </td>
                        </tr>
                    `;
                });

                ingredientTableBody.innerHTML = html;
            }

            function updateSubmitButton(enabled, label) {
                btnSubmit.disabled = !enabled;
                btnSubmitText.textContent = label;

                if (enabled) {
                    btnSubmit.className = 'btn btn-success px-5 py-2 fw-bold font-size-15 shadow-sm';
                } else {
                    btnSubmit.className = 'btn btn-secondary px-5 py-2 fw-bold font-size-15 shadow-sm opacity-50';
                }
            }

            document.querySelectorAll('.btn-quick-qty').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const add = parseFloat(this.getAttribute('data-add')) || 0;
                    const current = parseFloat(qtyInput.value) || 0;
                    qtyInput.value = current + add;
                    recalculateFeasibility();
                });
            });

            document.querySelectorAll('.btn-set-max').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (currentMaxFeasible > 0) {
                        qtyInput.value = currentMaxFeasible;
                        recalculateFeasibility();
                    }
                });
            });

            productSelect.addEventListener('change', recalculateFeasibility);
            qtyInput.addEventListener('input', recalculateFeasibility);
            qtyInput.addEventListener('change', recalculateFeasibility);

            document.getElementById('btnResetForm').addEventListener('click', function() {
                setTimeout(recalculateFeasibility, 50);
            });

            function syncLiveInventory() {
                liveSyncStatus.textContent = 'Checking Stock...';
                fetch(inventoryStatusUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data && data.inventories) {
                        Object.keys(productionMatrix).forEach(pId => {
                            const prod = productionMatrix[pId];
                            if (prod && prod.items) {
                                prod.items.forEach(item => {
                                    if (data.inventories[item.ingredient_id]) {
                                        item.stock_available = parseFloat(data.inventories[item.ingredient_id].quantity) || 0;
                                        item.average_cost = parseFloat(data.inventories[item.ingredient_id].average_cost) || 0;
                                    }
                                });
                            }
                        });

                        liveSyncStatus.textContent = 'Live Stock Synced (' + (data.timestamp || 'Just now') + ')';
                        recalculateFeasibility();
                    }
                })
                .catch(() => {
                    liveSyncStatus.textContent = 'Offline / Sync Failed';
                });
            }

            btnRefreshStock.addEventListener('click', function(e) {
                e.preventDefault();
                syncLiveInventory();
            });

            setInterval(syncLiveInventory, 30000);
            recalculateFeasibility();
        })();
    </script>
@endsection
