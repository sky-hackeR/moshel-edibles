{{-- =====================================================
     OPENING STOCK ENTRY WORKBENCH PARTIAL
     ===================================================== --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-white border-bottom py-3 px-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <h5 class="card-title mb-0 fw-bold text-dark">
                <i class="bx bx-import me-1 text-primary"></i> Stock Initialization Workbench
            </h5>
            <small class="text-muted">Enter pre-existing physical inventory prior to automated system tracking.</small>
        </div>

        {{-- SEGMENTED TABS --}}
        <ul class="nav nav-segment-tabs" id="openingStockTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="product-tab" data-bs-toggle="tab" data-bs-target="#product-pane" type="button" role="tab" aria-controls="product-pane" aria-selected="true">
                    <i class="bx bx-package me-1"></i> Finished Products
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="ingredient-tab" data-bs-toggle="tab" data-bs-target="#ingredient-pane" type="button" role="tab" aria-controls="ingredient-pane" aria-selected="false">
                    <i class="bx bx-bowl-rice me-1"></i> Raw Ingredients
                </button>
            </li>
        </ul>
    </div>

    <div class="card-body p-4">
        <div class="tab-content" id="openingStockTabContent">
            {{-- ================================================= --}}
            {{-- TAB 1: PRODUCT OPENING STOCK --}}
            {{-- ================================================= --}}
            <div class="tab-pane fade show active" id="product-pane" role="tabpanel" aria-labelledby="product-tab">
                <form action="{{ route('addProductOpeningStock') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        {{-- Form Inputs --}}
                        <div class="col-lg-7">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark font-size-12 mb-1">Select Finished Product <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-package"></i></span>
                                        <select name="product_id" id="product_id" class="form-select border-start-0" required>
                                            <option value="" selected disabled>Choose a product from catalog...</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}"
                                                        data-name="{{ $product->name }}"
                                                        data-stock="{{ $product->stock_on_hand }}"
                                                        data-unit="{{ $product->sales_unit }}"
                                                        data-price="{{ $product->selling_price }}">
                                                    {{ $product->name }} (Current: {{ number_format($product->stock_on_hand, 0) }} {{ $product->sales_unit }} · ₦{{ number_format($product->selling_price, 2) }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <small class="text-muted font-size-11 mt-1 d-block">Only currently active finished catalog products appear in this list.</small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark font-size-12 mb-1">Opening Quantity <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-plus-circle"></i></span>
                                        <input type="number" name="quantity" id="product_quantity" class="form-control border-start-0" min="0.001" step="0.001" placeholder="e.g. 50" required>
                                        <span class="input-group-text bg-light text-muted font-size-12" id="product_unit_badge">Units</span>
                                    </div>
                                    <small class="text-muted font-size-11 mt-1 d-block">Physical count available at commencement.</small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark font-size-12 mb-1">Audit Reason / Origin</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-comment-detail"></i></span>
                                        <input type="text" name="reason" class="form-control border-start-0" value="Existing stock before system commencement" placeholder="Reason note...">
                                    </div>
                                    <small class="text-muted font-size-11 mt-1 d-block">Recorded in permanent audit trail.</small>
                                </div>
                            </div>
                        </div>

                        {{-- Live Summary Card --}}
                        <div class="col-lg-5">
                            <div class="preview-stat-box h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between pb-2 mb-2 border-bottom">
                                        <span class="fw-bold text-dark font-size-12 text-uppercase tracking-wider">
                                            <i class="bx bx-line-chart me-1 text-primary"></i> Live Impact Simulator
                                        </span>
                                        <span class="badge bg-primary-subtle text-primary font-size-10 px-2 py-0.5">Finished Goods</span>
                                    </div>

                                    <div class="mb-2">
                                        <span class="text-muted font-size-11 d-block">Selected Item:</span>
                                        <strong class="text-dark font-size-13 d-block text-truncate" id="preview_product_name">No product selected</strong>
                                    </div>

                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <div class="p-2 bg-white rounded border">
                                                <small class="text-muted font-size-10 d-block">Current Stock</small>
                                                <span class="fw-bold text-secondary font-size-13" id="preview_current_stock">—</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 bg-white rounded border">
                                                <small class="text-muted font-size-10 d-block">Adding Quantity</small>
                                                <span class="fw-bold text-primary font-size-13" id="preview_adding_stock">—</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <div class="p-2 bg-white rounded border">
                                                <small class="text-muted font-size-10 d-block">Retail Price</small>
                                                <span class="fw-bold text-dark font-size-13" id="preview_product_price">—</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 bg-white rounded border">
                                                <small class="text-muted font-size-10 d-block">Added Value</small>
                                                <span class="fw-bold text-success font-size-13" id="preview_product_valuation">—</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-2.5 rounded border border-primary-subtle" style="background: #eef2ff;">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <small class="fw-bold text-primary font-size-11">Projected Total In-Stock:</small>
                                            <strong class="font-size-14 text-primary" id="preview_projected_stock">—</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 font-size-12 fw-medium shadow-sm w-100">
                                        <i class="bx bx-check-circle me-1 font-size-14"></i> Post Product Opening Stock
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ================================================= --}}
            {{-- TAB 2: INGREDIENT OPENING STOCK --}}
            {{-- ================================================= --}}
            <div class="tab-pane fade" id="ingredient-pane" role="tabpanel" aria-labelledby="ingredient-tab">
                <form action="{{ route('addIngredientOpeningStock') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        {{-- Form Inputs --}}
                        <div class="col-lg-7">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark font-size-12 mb-1">Select Raw Material / Ingredient <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-bowl-rice"></i></span>
                                        <select name="ingredient_id" id="ingredient_id" class="form-select border-start-0" required>
                                            <option value="" selected disabled>Choose an ingredient from catalog...</option>
                                            @foreach($ingredients as $ingredient)
                                                @php
                                                    $uSymbol = optional($ingredient->baseUnit)->symbol ?? 'unit';
                                                    $uName = optional($ingredient->baseUnit)->name ?? 'Base Unit';
                                                @endphp
                                                <option value="{{ $ingredient->id }}"
                                                        data-name="{{ $ingredient->name }}"
                                                        data-unit="{{ $uSymbol }}"
                                                        data-unitname="{{ $uName }}">
                                                    {{ $ingredient->name }} — {{ $uName }} ({{ $uSymbol }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <small class="text-muted font-size-11 mt-1 d-block">
                                        All raw material inventory is stored and costed using the ingredient's <strong>base unit</strong>.
                                    </small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark font-size-12 mb-1">Opening Quantity <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-scale"></i></span>
                                        <input type="number" name="quantity" id="ingredient_quantity" class="form-control border-start-0" min="0.001" step="0.001" placeholder="e.g. 25000" required>
                                        <span class="input-group-text bg-light text-muted font-size-12" id="ingredient_unit_badge">Base Units</span>
                                    </div>
                                    <small class="text-muted font-size-11 mt-1 d-block" id="ingredient_unit_help">
                                        Enter in base units (e.g. 25,000 for 25 kg).
                                    </small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark font-size-12 mb-1">Estimated Total Stock Value (₦) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0">₦</span>
                                        <input type="number" name="total_value" id="ingredient_total_value" class="form-control border-start-0" min="0" step="0.01" placeholder="e.g. 37500" required>
                                    </div>
                                    <small class="text-muted font-size-11 mt-1 d-block">Total monetary worth of the batch.</small>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark font-size-12 mb-1">Audit Reason / Origin</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-comment-detail"></i></span>
                                        <input type="text" name="reason" class="form-control border-start-0" value="Existing stock before system commencement" placeholder="Reason note...">
                                    </div>
                                    <small class="text-muted font-size-11 mt-1 d-block">Recorded in permanent audit trail.</small>
                                </div>
                            </div>
                        </div>

                        {{-- Live Valuation & Costing Simulator --}}
                        <div class="col-lg-5">
                            <div class="preview-stat-box h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between pb-2 mb-2 border-bottom">
                                        <span class="fw-bold text-dark font-size-12 text-uppercase tracking-wider">
                                            <i class="bx bx-calculator me-1 text-success"></i> Auto Costing Engine
                                        </span>
                                        <span class="badge bg-success-subtle text-success font-size-10 px-2 py-0.5">Weighted Cost</span>
                                    </div>

                                    <div class="mb-2">
                                        <span class="text-muted font-size-11 d-block">Selected Material:</span>
                                        <strong class="text-dark font-size-13 d-block text-truncate" id="preview_ingredient_name">No ingredient selected</strong>
                                    </div>

                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <div class="p-2 bg-white rounded border">
                                                <small class="text-muted font-size-10 d-block">Quantity Logged</small>
                                                <span class="fw-bold text-dark font-size-13" id="preview_ing_quantity">—</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 bg-white rounded border">
                                                <small class="text-muted font-size-10 d-block">Total Value</small>
                                                <span class="fw-bold text-dark font-size-13" id="preview_ing_value">—</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-2.5 rounded border border-success-subtle" style="background: #f0fdf4;">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <small class="fw-bold text-success font-size-11 d-block">Calculated Unit Cost:</small>
                                                <span class="text-muted font-size-10">Used for recipe production formulas</span>
                                            </div>
                                            <strong class="font-size-14 text-success" id="preview_calculated_unit_cost">₦0.00 / unit</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-success rounded-pill px-4 py-2 font-size-12 fw-medium shadow-sm w-100">
                                        <i class="bx bx-check-circle me-1 font-size-14"></i> Post Ingredient Opening Stock
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
