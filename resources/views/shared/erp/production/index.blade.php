@php
    $portal = $portal ?? 'admin';
    $isAdmin = ($portal === 'admin');

    $pageTitle = $isAdmin ? 'Bakery Production Control' : 'Bakery Production Floor';
    $showEconomics = $isAdmin;
    $produceUrl = url('/' . $portal . '/produce');
    $inventoryStatusUrl = url('/' . $portal . '/productionInventoryStatus');
    $productionHistoryUrl = url('/' . $portal . '/productionHistory');

    $todayTitle = $isAdmin ? "Today's Bakery Output" : "Your Shifts Today";
    $todaySubtitle = $isAdmin ? "Batches completed today" : "Batches recorded today";
    $historySubtitle = $isAdmin ? "Track all shifts" : "Review history";
    $historyLinkText = $isAdmin ? "View Production History &rarr;" : "View Production Log &rarr;";
@endphp

{{-- PAGE HEADER --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-sm-0 font-size-18 text-primary fw-bold">{!! $pageTitle !!}</h4>
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
                                @if($isAdmin)
                                    This product does not have any ingredients assigned yet. 
                                    <a href="{{ url('/admin/recipes') }}" class="text-primary fw-bold text-decoration-underline ms-1">Create a recipe first</a>.
                                @else
                                    This product does not have any ingredients assigned yet. Contact your administrator to add a recipe.
                                @endif
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
                <form method="POST" action="{{ $produceUrl }}" id="productionForm">
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
                            <div class="form-text d-flex align-items-center justify-content-between mt-1">
                                <span class="text-muted font-size-12">Pantry stock automatically deducts upon recording.</span>
                                <span id="productStockBadge" class="badge bg-light text-dark font-size-11 d-none">Finished Stock: 0</span>
                            </div>
                        </div>

                        {{-- TARGET QUANTITY --}}
                        <div class="col-md-5">
                            <label class="form-label fw-bold text-dark">
                                Target Batch Quantity <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" 
                                       step="any" 
                                       min="0.01" 
                                       name="quantity_produced" 
                                       id="quantity_produced" 
                                       class="form-control form-control-lg fw-bold font-size-16" 
                                       placeholder="0.00" 
                                       required>
                                <span class="input-group-text bg-light fw-semibold text-secondary" id="unitLabel">Units</span>
                            </div>

                            {{-- QUICK INCREMENT CHIPS --}}
                            <div class="d-flex align-items-center gap-1 mt-2">
                                <span class="text-muted font-size-11 me-1">Quick:</span>
                                <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-quick-qty" data-add="5">+5</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-quick-qty" data-add="10">+10</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-quick-qty" data-add="25">+25</button>
                                <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 ms-auto btn-set-max" title="Max quantity achievable with pantry stock">Max Possible</button>
                            </div>
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

                        <div class="table-responsive border rounded bg-white" style="max-height: 380px; overflow-y: auto;">
                            <table class="table table-sm table-hover align-middle mb-0" id="ingredientTable">
                                <thead class="table-light sticky-top">
                                    <tr class="font-size-12 text-uppercase text-muted">
                                        <th style="min-width: 180px;">Ingredient</th>
                                        <th class="text-center" style="width: 130px;">Recipe Qty/Unit</th>
                                        <th class="text-center" style="width: 140px;">Total Required</th>
                                        <th class="text-center" style="width: 130px;">Stock Available</th>
                                        <th style="min-width: 180px;">Stock Balance & Usage</th>
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

                        @if($showEconomics)
                            <div id="matrixCostSummary" class="d-none mt-3 pt-2 border-top d-flex justify-content-between align-items-center font-size-12">
                                <span class="text-muted">Estimated Raw Material Batch Cost:</span>
                                <span class="fw-bold text-dark font-size-14" id="matrixTotalCost">₦0.00</span>
                            </div>
                        @endif
                    </div>

                    {{-- METADATA: BAKER NOTES & PRODUCTION DATE --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-7">
                            <label class="form-label text-dark font-size-13 fw-semibold">Baker Notes / Shift Remarks</label>
                            <input type="text" 
                                   name="notes" 
                                   class="form-control" 
                                   placeholder="e.g. Morning bake shift, reduced yeast slightly due to ambient humidity">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label text-dark font-size-13 fw-semibold">Production Timestamp</label>
                            <input type="datetime-local" 
                                   name="produced_at" 
                                   class="form-control" 
                                   value="{{ date('Y-m-d\TH:i') }}">
                        </div>
                    </div>

                    {{-- SUBMIT BAR --}}
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                        <button type="reset" id="btnResetForm" class="btn btn-outline-secondary px-3">
                            <i class="mdi mdi-refresh me-1"></i>Reset Form
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

    {{-- RIGHT COLUMN: TODAY'S OUTPUT & BATCH FINANCIAL FORECAST / BOTTLENECK --}}
    <div class="col-xl-4">

        {{-- TODAY'S OUTPUT HERO CARD --}}
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; background: linear-gradient(135deg, #581a62 0%, #7b2284 100%); color: #ffffff;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-white-50 font-size-12 text-uppercase fw-bold mb-1">{{ $todayTitle }}</p>
                        <h2 class="text-white fw-bolder mb-1">{{ $stats['today_production'] ?? 0 }}</h2>
                        <small class="text-white-50">{{ $todaySubtitle }}</small>
                    </div>
                    <div class="avatar-md flex-shrink-0">
                        <span class="avatar-title bg-white bg-opacity-25 rounded-circle text-white font-size-24">
                            <i class="mdi mdi-cookie"></i>
                        </span>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top border-white border-opacity-25 d-flex justify-content-between align-items-center font-size-12">
                    <span class="text-white-50">{{ $historySubtitle }}</span>
                    <a href="{{ $productionHistoryUrl }}" class="text-white fw-bold text-decoration-underline">
                        {!! $historyLinkText !!}
                    </a>
                </div>
            </div>
        </div>

        {{-- LIVE BATCH FINANCIAL PROJECTION CARD (ADMIN ONLY) --}}
        @if($showEconomics)
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h6 class="card-title mb-0 fw-bold text-dark">
                        <i class="bx bx-line-chart text-success me-2 font-size-18"></i>Live Batch Economics
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted font-size-13">Estimated Total Cost:</span>
                            <span class="fw-bold text-danger font-size-15" id="econBatchCost">₦0.00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted font-size-13">Est. Unit Cost:</span>
                            <span class="fw-bold text-dark font-size-13" id="econUnitCost">₦0.00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted font-size-13">Retail Selling Value:</span>
                            <span class="fw-bold text-dark font-size-15" id="econRetailValue">₦0.00</span>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded text-center">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 11px;">Projected Gross Profit</small>
                        <h4 class="fw-bolder text-success mb-1" id="econGrossProfit">₦0.00</h4>
                        <span class="badge bg-soft-success text-success font-size-11 px-2 py-1" id="econMarginPct">0.0% Margin</span>
                    </div>

                    <p class="font-size-11 text-muted text-center mt-3 mb-0">
                        *Calculated using weighted average cost of stored raw ingredients and catalogue selling price.
                    </p>
                </div>
            </div>
        @endif

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
                        Limited by ingredient stock availability.
                    </p>
                    <div class="d-flex justify-content-between align-items-center font-size-12">
                        <span class="text-muted">Ingredient: <strong id="bottleneckName" class="text-dark">—</strong></span>
                        <span class="badge bg-warning text-dark font-size-11" id="bottleneckMax">Max 0</span>
                    </div>
                </div>

                <div class="border-top pt-3 mt-3">
                    <div class="d-flex align-items-center gap-2 text-muted font-size-12">
                        <i class="mdi mdi-clock-check-outline text-success font-size-16"></i>
                        <span>Audit matrix continuously polls live pantry inventory.</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

    <script>
        (function() {
            'use strict';

            // Production Matrix loaded from Controller
            const productionMatrix = @json($productionMatrix);
            const inventoryStatusUrl = '{{ $inventoryStatusUrl }}';

            // DOM Elements
            const productSelect = document.getElementById('product_select');
            const qtyInput = document.getElementById('quantity_produced');
            const unitLabel = document.getElementById('unitLabel');
            const productStockBadge = document.getElementById('productStockBadge');
            const ingredientTableBody = document.getElementById('ingredientTableBody');
            const matrixItemsCount = document.getElementById('matrixItemsCount');

            // Feasibility States
            const statusPlaceholder = document.getElementById('statusPlaceholder');
            const statusReady = document.getElementById('statusReady');
            const statusBlocked = document.getElementById('statusBlocked');
            const statusNoRecipe = document.getElementById('statusNoRecipe');
            const readyMessage = document.getElementById('readyMessage');
            const readyMaxOutput = document.getElementById('readyMaxOutput');
            const blockedMessage = document.getElementById('blockedMessage');
            const blockedShortageBadge = document.getElementById('blockedShortageBadge');
            const blockedMaxOutput = document.getElementById('blockedMaxOutput');

            // Bottleneck elements
            const bottleneckCard = document.getElementById('bottleneckCard');
            const bottleneckNotice = document.getElementById('bottleneckNotice');
            const bottleneckName = document.getElementById('bottleneckName');
            const bottleneckMax = document.getElementById('bottleneckMax');
            const bottleneckText = document.getElementById('bottleneckText');

            // Economics elements (available in Admin portal)
            const econBatchCost = document.getElementById('econBatchCost');
            const econUnitCost = document.getElementById('econUnitCost');
            const econRetailValue = document.getElementById('econRetailValue');
            const econGrossProfit = document.getElementById('econGrossProfit');
            const econMarginPct = document.getElementById('econMarginPct');
            const matrixCostSummary = document.getElementById('matrixCostSummary');
            const matrixTotalCost = document.getElementById('matrixTotalCost');

            // Submit Button
            const btnSubmit = document.getElementById('btnSubmitProduction');
            const btnSubmitText = document.getElementById('btnSubmitText');
            const liveSyncStatus = document.getElementById('liveSyncStatus');
            const btnRefreshStock = document.getElementById('btnRefreshStock');

            let currentMaxFeasible = 0;

            function formatCurrency(val) {
                return '₦' + Number(val || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            function formatNumber(val, decimals = 2) {
                return Number(val || 0).toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: decimals });
            }

            // Core live calculation
            function recalculateFeasibility() {
                const productId = productSelect.value;
                const enteredQty = parseFloat(qtyInput.value) || 0;

                if (!productId) {
                    showBannerState('placeholder');
                    renderEmptyTable('Select a product above to display its recipe ingredients.');
                    resetEconomics();
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

                // Check recipe existence
                if (!product.has_recipe || !product.items || product.items.length === 0) {
                    showBannerState('no_recipe');
                    renderEmptyTable('This product does not have any recipe items configured in the database.');
                    resetEconomics();
                    updateSubmitButton(false, 'Recipe Missing (Disabled)');
                    bottleneckCard.classList.add('d-none');
                    bottleneckNotice.classList.remove('d-none');
                    currentMaxFeasible = 0;
                    return;
                }

                matrixItemsCount.textContent = product.items.length + ' Ingredients';

                // Calculate maximum feasible batch output across all ingredients
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

                // Update bottleneck card
                if (bottleneckItem) {
                    bottleneckCard.classList.remove('d-none');
                    bottleneckNotice.classList.add('d-none');
                    bottleneckName.textContent = bottleneckItem.name;
                    bottleneckMax.textContent = formatNumber(maxFeasibleBatch) + ' ' + salesUnit;
                    bottleneckText.textContent = 'Limited by ' + bottleneckItem.name + ' (' + formatNumber(bottleneckItem.stock_available) + ' ' + bottleneckItem.base_unit + ' in stock).';
                }

                // If quantity is 0 or negative
                if (enteredQty <= 0) {
                    showBannerState('placeholder');
                    renderTableRows(product.items, 0);
                    resetEconomics();
                    updateSubmitButton(false, 'Enter Quantity to Produce');
                    return;
                }

                // Evaluate batch feasibility
                let hasDeficit = false;
                let deficitCount = 0;
                let totalBatchCost = 0;

                const evaluatedItems = product.items.map(item => {
                    const required = (item.base_qty_per_unit || 0) * enteredQty;
                    const available = item.stock_available || 0;
                    const remaining = available - required;
                    const isShort = remaining < 0;
                    const shortage = isShort ? Math.abs(remaining) : 0;
                    const itemCost = required * (item.average_cost || 0);

                    totalBatchCost += itemCost;

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
                        itemCost
                    };
                });

                // Render table with visual indicators
                renderTableRows(evaluatedItems, enteredQty);

                // Economics calculations (if enabled)
                if (econBatchCost) {
                    const sellingPrice = product.selling_price || 0;
                    const totalRevenue = sellingPrice * enteredQty;
                    const grossProfit = totalRevenue - totalBatchCost;
                    const marginPct = totalRevenue > 0 ? ((grossProfit / totalRevenue) * 100).toFixed(1) : '0.0';
                    const unitCost = enteredQty > 0 ? (totalBatchCost / enteredQty) : 0;

                    econBatchCost.textContent = formatCurrency(totalBatchCost);
                    if (econUnitCost) econUnitCost.textContent = formatCurrency(unitCost) + ' / ' + salesUnit;
                    if (econRetailValue) econRetailValue.textContent = formatCurrency(totalRevenue);
                    if (econGrossProfit) {
                        econGrossProfit.textContent = formatCurrency(grossProfit);
                        econGrossProfit.className = grossProfit >= 0 ? 'fw-bolder text-success mb-1' : 'fw-bolder text-danger mb-1';
                    }
                    if (econMarginPct) {
                        econMarginPct.textContent = marginPct + '% Margin';
                        econMarginPct.className = grossProfit >= 0 ? 'badge bg-soft-success text-success font-size-11 px-2 py-1' : 'badge bg-soft-danger text-danger font-size-11 px-2 py-1';
                    }
                }

                if (matrixTotalCost) {
                    matrixTotalCost.textContent = formatCurrency(totalBatchCost);
                }
                if (matrixCostSummary) {
                    matrixCostSummary.classList.remove('d-none');
                }

                // Update Feasibility Banner & Submit Button
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
                if (matrixCostSummary) {
                    matrixCostSummary.classList.add('d-none');
                }
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

                    // Calculate usage percentage
                    let pctUsed = 0;
                    if (available > 0 && required > 0) {
                        pctUsed = Math.min(100, Math.round((required / available) * 100));
                    } else if (required > 0 && available <= 0) {
                        pctUsed = 100;
                    }

                    // Progress bar color
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

            function resetEconomics() {
                if (econBatchCost) econBatchCost.textContent = '₦0.00';
                if (econUnitCost) econUnitCost.textContent = '₦0.00';
                if (econRetailValue) econRetailValue.textContent = '₦0.00';
                if (econGrossProfit) {
                    econGrossProfit.textContent = '₦0.00';
                    econGrossProfit.className = 'fw-bolder text-dark mb-1';
                }
                if (econMarginPct) {
                    econMarginPct.textContent = '0.0% Margin';
                    econMarginPct.className = 'badge bg-light text-muted font-size-11 px-2 py-1';
                }
                if (matrixCostSummary) {
                    matrixCostSummary.classList.add('d-none');
                }
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

            // Quick increment quantity buttons (+5, +10, +25)
            document.querySelectorAll('.btn-quick-qty').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const add = parseFloat(this.getAttribute('data-add')) || 0;
                    const current = parseFloat(qtyInput.value) || 0;
                    qtyInput.value = current + add;
                    recalculateFeasibility();
                });
            });

            // Set Max Possible Batch buttons
            document.querySelectorAll('.btn-set-max').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (currentMaxFeasible > 0) {
                        qtyInput.value = currentMaxFeasible;
                        recalculateFeasibility();
                    }
                });
            });

            // Input Event Listeners
            productSelect.addEventListener('change', recalculateFeasibility);
            qtyInput.addEventListener('input', recalculateFeasibility);
            qtyInput.addEventListener('change', recalculateFeasibility);

            // Reset form button
            document.getElementById('btnResetForm').addEventListener('click', function() {
                setTimeout(recalculateFeasibility, 50);
            });

            // Background Live Stock Synchronization
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
                        // Update stock_available inside productionMatrix
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

            // Auto-polling every 30 seconds to keep pantry stock 100% live
            setInterval(syncLiveInventory, 30000);

            // Initial check
            recalculateFeasibility();
        })();
    </script>
