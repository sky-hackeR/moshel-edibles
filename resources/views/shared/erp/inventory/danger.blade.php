@php
    $entityType = $entityType ?? 'ingredient';
    $isProduct = ($entityType === 'product');

    $pageTitle = $isProduct ? 'Stock Control — Danger Zone (Finished Products)' : 'Raw Materials Stock Control — Danger Zone';
    $pageSubtitle = $isProduct ? 'Authorized commercial inventory depletion & emergency catalog resets' : 'Authorized raw material inventory depletion & warehouse resets';
    $returnUrl = $returnUrl ?? ($isProduct ? route('products') : route('ingredients'));
    $returnText = $isProduct ? 'Products Catalog' : 'Ingredients Catalog';

    $clearStockRoute = $clearStockRoute ?? ($isProduct ? route('admin.products.clearStock') : route('admin.ingredients.clearStock'));
    $clearAllStockRoute = $clearAllStockRoute ?? ($isProduct ? route('admin.products.clearAllStock') : route('admin.ingredients.clearAllStock'));

    $clearSingleModalId = $isProduct ? 'clearProductStockModal' : 'clearIngredientStockModal';
    $clearAllModalId = $isProduct ? 'clearAllStockModal' : 'clearAllIngredientStockModal';

    $modalInputId = $isProduct ? 'modalProductId' : 'modalIngredientId';
    $modalNameId = $isProduct ? 'modalProductName' : 'modalIngredientName';
    $modalStockId = $isProduct ? 'modalProductStock' : 'modalIngredientStock';
    $modalValueId = $isProduct ? 'modalProductValue' : 'modalIngredientValue';
    $confirmCheckboxId = $isProduct ? 'confirmDepleteSingle' : 'confirmDepleteSingleIngredient';

    $entityIdField = $isProduct ? 'product_id' : 'ingredient_id';
    $entityNoun = $isProduct ? 'Product' : 'Ingredient';
    $entityPlural = $isProduct ? 'Products' : 'Ingredients';

    $catalogCount = $stats[$isProduct ? 'total_products' : 'total_ingredients'] ?? 0;
    $catalogLabel = $isProduct ? 'Cataloged Products' : 'Cataloged Materials';
    $activeSubLabel = $isProduct ? 'Active Commercial SKUs' : 'In Stock (>0)';
    $valuationLabel = $isProduct ? 'Retail Valuation' : 'Warehouse Valuation';
    $valuationSubtext = $isProduct ? 'Finished Retail Valuation' : 'At Average Purchase Cost';
    $inventorySubtext = $isProduct ? 'Catalog Finished Goods' : 'Warehouse Raw Inventory';
    $tableTitle = $isProduct ? 'Commercial Products &amp; Finished Goods Depletion Controls' : 'Raw Materials &amp; Ingredients Depletion Controls';
    $tableSubtitle = $isProduct ? 'Direct stock depletion, spoilage write-offs, and emergency catalog resets for commercial SKUs.' : 'Direct stock depletion, spoilage write-offs, and batch audit resets for recipe ingredients.';
    $unitPriceColLabel = $isProduct ? 'Selling Price' : 'Average Cost';
    $emptyMessage = $isProduct ? 'No commercial products found in catalog.' : 'No raw materials or ingredients found in catalog.';
    $emergencyExecuteBtn = $isProduct ? 'EXECUTE FULL CATALOG WIPEOUT' : 'EXECUTE FULL INGREDIENT WIPEOUT';
    $emergencyWarningScope = $isProduct ? 'every commercial product SKU' : 'EVERY raw material';
    $emergencyImpactNoun = $isProduct ? 'Commercial SKUs' : 'Ingredients';

    $noticeText = $isProduct
        ? 'Zeroing stock on hand halts retail checkout and POS sales for that commercial product. Choose between an <strong class="text-dark">Operational Adjustment</strong> (physical inventory recount with no disruption to revenue figures) or an <strong class="text-danger">Inventory Loss &amp; Spoilage Write-Off</strong>. All operations are recorded in the security audit trail and immediately emailed to all administrators.'
        : 'Zeroing warehouse stock on hand immediately halts kitchen production runs and recipe fulfillment requiring that ingredient. Choose between an <strong class="text-dark">Operational Adjustment</strong> (physical warehouse recount with no impact on accounting revenue) or an <strong class="text-danger">Inventory Loss &amp; Spoilage Write-Off</strong>. All operations are recorded in the security audit trail and dispatched to all administrators.';
@endphp

<style>
    .product-icon-avatar,
    .ingredient-icon-avatar,
    .entity-icon-avatar {
        width: 38px;
        min-width: 38px;
        height: 38px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
    }
</style>

{{-- =====================================================
     BREADCRUMB & PAGE TITLE
     ===================================================== --}}
<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between pb-2">
            <div class="d-flex align-items-center">
                <div class="danger-header-badge me-2 shadow-sm">
                    <i class="bx bx-shield-quarter"></i>
                </div>
                <div>
                    <h4 class="mb-0 font-size-18 fw-bold text-dark">{!! $pageTitle !!}</h4>
                    <small class="text-muted font-size-12">{{ $pageSubtitle }}</small>
                </div>
            </div>
            <div class="d-flex align-items-center flex-wrap gap-2 mt-2 mt-sm-0">
                <a href="{{ $returnUrl }}" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 font-size-12 fw-medium shadow-sm">
                    <i class="bx bx-arrow-back me-1"></i> {{ $returnText }}
                </a>
                <button type="button" class="btn btn-danger rounded-pill px-3 py-1.5 font-size-12 fw-medium shadow-sm" data-bs-toggle="modal" data-bs-target="#{{ $clearAllModalId }}">
                    <i class="bx bx-radiation me-1"></i> Emergency Full Reset
                </button>
            </div>
        </div>
    </div>
</div>

{{-- =====================================================
     SECURITY & AUDIT NOTICE CALLOUT
     ===================================================== --}}
<div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #fff5f5 0%, #fef2f2 100%); border-left: 4px solid #ef4444 !important; border-radius: 12px;">
    <div class="card-body p-3">
        <div class="d-flex align-items-start gap-3">
            <div class="flex-shrink-0 mt-1">
                <span class="avatar-title rounded-circle bg-danger text-white font-size-16" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">
                    <i class="bx bx-error-alt"></i>
                </span>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <h6 class="mb-1 text-danger fw-bold font-size-13">Restricted Operations Protocol (Admins Only)</h6>
                    <span class="badge bg-danger-subtle text-danger font-size-11 px-2 py-0.5 rounded-pill border border-danger-subtle">
                        <i class="bx bx-lock-alt me-1"></i> Immutable Audit Logging
                    </span>
                </div>
                <p class="mb-0 text-muted font-size-12 line-height-1.4">
                    {!! $noticeText !!}
                </p>
            </div>
        </div>
    </div>
</div>

{{-- =====================================================
     EXECUTIVE KPI STATS ROW
     ===================================================== --}}
<div class="row g-3 mb-4">
    {{-- Card 1: Cataloged Count --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card 
            :label="$catalogLabel" 
            :value="number_format($catalogCount)" 
            color="success" 
            border-color="#059669" 
            :icon="$isProduct ? 'bx bx-package' : 'bx bx-food-menu'" 
            :subtitle="number_format($stats['in_stock_count'] ?? 0) . ' ' . $activeSubLabel" 
            subicon="bx bx-check-circle" 
            subcolor="text-success" />
    </div>

    {{-- Card 2: Total Physical Units --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card 
            label="Total Physical Units" 
            :value="number_format($stats['total_units'] ?? 0, 2)" 
            color="info" 
            border-color="#0284c7" 
            icon="bx bx-layer" 
            :subtitle="$inventorySubtext" 
            subicon="bx bx-layer" 
            subcolor="text-info" />
    </div>

    {{-- Card 3: Valuation --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card 
            :label="$valuationLabel" 
            :value="'₦' . number_format($stats['total_valuation'] ?? 0, 2)" 
            color="primary" 
            border-color="#4f46e5" 
            icon="bx bx-wallet" 
            :subtitle="$valuationSubtext" 
            subicon="bx bx-wallet" 
            subcolor="text-primary" />
    </div>

    {{-- Card 4: Depleted / Zeroed Alerts --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card 
            label="Depleted / Zeroed" 
            :value="number_format($stats['depleted_count'] ?? 0)" 
            color="danger" 
            border-color="#dc2626" 
            icon="bx bx-error-alt" 
            :subtitle="number_format($stats['low_stock_count'] ?? 0) . ' Below Reorder Level'" 
            subicon="bx bx-error" 
            subcolor="text-warning" />
    </div>
</div>

{{-- =====================================================
     STOCK DIRECTORY TABLE CARD
     ===================================================== --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-white border-bottom py-3 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h5 class="card-title mb-0 fw-bold text-dark">
                <i class="bx bx-shield text-danger me-1"></i> {!! $tableTitle !!}
            </h5>
            <small class="text-muted">{{ $tableSubtitle }}</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill bg-light text-dark border px-3 py-1 font-size-11">
                {{ number_format($catalogCount) }} {{ Str::plural($entityNoun, $catalogCount) }} Registered
            </span>
        </div>
    </div>

    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-hover align-middle dt-responsive nowrap w-100 font-size-13 mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center py-2" style="width: 40px;">#</th>
                        <th class="py-2" style="min-width: 220px;">{{ $entityNoun }} Name</th>
                        <th class="py-2" style="width: 100px;">Base Unit</th>
                        <th class="py-2" style="width: 120px;">{{ $unitPriceColLabel }}</th>
                        <th class="py-2" style="width: 150px;">Stock on Hand</th>
                        <th class="py-2" style="width: 140px;">Valuation</th>
                        <th class="text-end py-2 pe-3" style="width: 140px;">Danger Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        @php
                            if ($isProduct) {
                                $qty = (float) $item->stock_on_hand;
                                $priceOrCost = (float) $item->selling_price;
                                $stockValue = $qty * $priceOrCost;
                                $unitSymbol = $item->sales_unit ?: 'Unit';
                                $threshold = 10;
                                $avatarColor = 'background: #eef2ff; color: #4338ca;';
                            } else {
                                $qty = $item->inventory ? (float) $item->inventory->quantity : 0;
                                $priceOrCost = $item->inventory ? (float) $item->inventory->average_cost : 0;
                                $stockValue = $qty * $priceOrCost;
                                $threshold = $item->effective_reorder_level;
                                $unitSymbol = $item->baseUnit ? ($item->baseUnit->symbol ?: $item->baseUnit->name) : 'unit';
                                $unitType = $item->baseUnit->unit_type ?? 'dimension';
                                $avatarColor = match($unitType) {
                                    'mass' => 'background: #fef3c7; color: #b45309;',
                                    'volume' => 'background: #e0f2fe; color: #0369a1;',
                                    'count' => 'background: #dcfce7; color: #15803d;',
                                    default => 'background: #e0e7ff; color: #4338ca;'
                                };
                            }
                        @endphp
                        <tr>
                            <td class="text-center text-muted font-size-11 py-2">
                                {{ $loop->iteration }}
                            </td>
                            <td class="py-2">
                                <div class="d-flex align-items-center">
                                    <div class="product-icon-avatar me-2 shadow-sm" style="{{ $avatarColor }}">
                                        {{ strtoupper(substr($item->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="text-dark fw-bold d-block lh-1 mb-1">{{ $item->name }}</span>
                                        <div class="d-flex gap-1 align-items-center font-size-10">
                                            @if($item->is_active)
                                                <span class="text-success fw-semibold"><i class="bx bxs-circle font-size-8 me-0.5"></i> Active</span>
                                            @else
                                                <span class="text-danger fw-semibold"><i class="bx bxs-circle font-size-8 me-0.5"></i> Disabled</span>
                                            @endif
                                            <span class="text-muted">·</span>
                                            <span class="text-muted font-monospace">{{ $item->slug ?: Str::slug($item->name) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2">
                                <span class="badge bg-light text-dark text-uppercase font-size-11 border px-2 py-1">{{ $unitSymbol }}</span>
                            </td>
                            <td class="py-2 font-size-13 text-dark fw-semibold">
                                ₦{{ number_format($priceOrCost, 2) }}
                            </td>
                            <td class="py-2">
                                @if($qty > $threshold)
                                    <span class="badge bg-soft-success text-success font-size-12 px-2.5 py-1 rounded-pill">
                                        <i class="bx bx-check-circle me-1"></i> {{ $qty }} {{ $unitSymbol }}
                                    </span>
                                @elseif($qty > 0)
                                    <span class="badge bg-soft-warning text-warning font-size-12 px-2.5 py-1 rounded-pill">
                                        <i class="bx bx-error me-1"></i> {{ $qty }} {{ $unitSymbol }} (Low)
                                    </span>
                                @else
                                    <span class="badge bg-soft-secondary text-muted font-size-11 px-2.5 py-1 rounded-pill">
                                        0 {{ $unitSymbol }} (Depleted)
                                    </span>
                                @endif
                            </td>
                            <td class="py-2 font-size-12 text-muted fw-medium">
                                ₦{{ number_format($stockValue, 2) }}
                            </td>
                            <td class="text-end py-2 pe-3">
                                @if($qty > 0)
                                    <button type="button" 
                                        class="btn btn-sm btn-outline-danger rounded-pill py-1 px-2.5 font-size-11 fw-medium open-deplete-modal-btn shadow-sm"
                                        data-id="{{ $item->id }}"
                                        data-name="{{ $item->name }}"
                                        data-stock="{{ $qty }}"
                                        data-unit="{{ $unitSymbol }}"
                                        @if($isProduct)
                                            data-price="{{ $priceOrCost }}"
                                        @else
                                            data-cost="{{ $priceOrCost }}"
                                        @endif
                                    >
                                        <i class="bx bx-trash-alt me-1"></i> Clear Stock
                                    </button>
                                @else
                                    <span class="badge bg-light text-muted border px-2 py-1 font-size-11">
                                        <i class="bx bx-check me-0.5"></i> Already 0.00
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <p class="text-muted font-size-13 mb-0">{{ $emptyMessage }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- =====================================================
     SINGLE REUSABLE MODAL: CLEAR ITEM STOCK
     ===================================================== --}}
<div class="modal fade" id="{{ $clearSingleModalId }}" tabindex="-1" aria-labelledby="{{ $clearSingleModalId }}Label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            <form action="{{ $clearStockRoute }}" method="POST">
                @csrf
                <input type="hidden" name="{{ $entityIdField }}" id="{{ $modalInputId }}" value="">

                <div class="modal-header py-3 px-3 text-white" style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);">
                    <div class="d-flex align-items-center">
                        <span class="rounded-circle bg-white text-danger font-size-16 me-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">
                            <i class="bx bx-error-alt"></i>
                        </span>
                        <div>
                            <h6 class="modal-title text-white fw-bold font-size-14 mb-0" id="{{ $clearSingleModalId }}Label">
                                Confirm {{ $entityNoun }} Stock Depletion
                            </h6>
                            <small class="text-white-50 font-size-11">Zeroing available inventory on hand</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-3 font-size-13">
                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted font-size-12">Target {{ $entityNoun }}:</span>
                            <strong class="text-dark font-size-14" id="{{ $modalNameId }}">—</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted font-size-12">Stock Quantity on Hand:</span>
                            <strong class="text-danger font-size-13 font-monospace" id="{{ $modalStockId }}">—</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <span class="text-muted font-size-12">Estimated Lost Inventory Valuation:</span>
                            <span class="text-dark fw-bold font-size-13" id="{{ $modalValueId }}">—</span>
                        </div>
                    </div>

                    {{-- 2 TYPES OF DEPLETION --}}
                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold mb-1 font-size-12">Depletion &amp; Accounting Mode <span class="text-danger">*</span></label>
                        
                        <div class="form-check p-2.5 border rounded-3 mb-2 accounting-radio-card bg-light">
                            <input class="form-check-input ms-1 me-2" type="radio" name="accounting_type" id="typeOperational" value="operational" checked>
                            <label class="form-check-label font-size-12 text-dark" for="typeOperational">
                                <strong class="d-block text-dark font-size-12">Operational Stock Adjustment (Default)</strong>
                                <span class="text-muted font-size-11">Physical recount only. Does <strong>not</strong> affect sales figures, paid revenues, or income reports.</span>
                            </label>
                        </div>

                        <div class="form-check p-2.5 border rounded-3 accounting-radio-card bg-light">
                            <input class="form-check-input ms-1 me-2" type="radio" name="accounting_type" id="typeWriteOff" value="write_off">
                            <label class="form-check-label font-size-12 text-dark" for="typeWriteOff">
                                <strong class="d-block text-danger font-size-12">Recognize as Inventory Loss &amp; Spoilage Write-Off</strong>
                                <span class="text-muted font-size-11">Formally registers lost material valuation into administrative loss records and executive audit reports.</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold mb-1 font-size-12">Operational Reason <span class="text-danger">*</span></label>
                        <select name="reason" class="form-select form-select-sm font-size-12" required>
                            <option value="" disabled selected>Select an operational justification...</option>
                            <option value="Physical Stock Count Discrepancy / Audit Correction">Physical Stock Count Discrepancy / Audit Correction</option>
                            <option value="Batch Spoilage / Quality Issue">Batch Spoilage / Quality Issue</option>
                            <option value="Batch Spoilage / Contamination / Expiration / Quality Issue">Batch Spoilage / Contamination / Expiration / Quality Issue</option>
                            <option value="Stock Write-off / Damaged Packaging / Spillage">Stock Write-off / Damaged Packaging / Spillage</option>
                            <option value="Recipe Formulation / Testing Write-off">Recipe Formulation / Testing Write-off</option>
                            <option value="Seasonal Catalog Clearance / Discontinuation">Seasonal Catalog Clearance / Discontinuation</option>
                            <option value="Administrative Inventory Reset">Administrative Inventory Reset</option>
                            <option value="Other Manual Depletion">Other Manual Depletion</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold mb-1 font-size-12">Administrative Notes (Optional)</label>
                        <textarea name="notes" class="form-control form-control-sm font-size-12" rows="2" placeholder="Optional details for the permanent audit trail and alert email..."></textarea>
                    </div>

                    <div class="form-check p-2.5 bg-light rounded-3 border">
                        <input class="form-check-input ms-1 me-2" type="checkbox" name="confirm_deplete" id="{{ $confirmCheckboxId }}" value="1" required>
                        <label class="form-check-label font-size-12 text-dark fw-semibold" for="{{ $confirmCheckboxId }}">
                            I confirm zeroing out warehouse stock for this {{ strtolower($entityNoun) }}. An immediate alert email will be dispatched to all administrators.
                        </label>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2 px-3">
                    <button type="button" class="btn btn-secondary rounded-pill btn-sm px-3 font-size-12" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-pill btn-sm px-3 font-size-12 fw-medium shadow-sm">
                        <i class="bx bx-trash-alt me-1"></i> Confirm &amp; Set Stock to 0
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- =====================================================
     MODAL: FULL EMERGENCY WIPEOUT RESET
     ===================================================== --}}
<div class="modal fade" id="{{ $clearAllModalId }}" tabindex="-1" aria-labelledby="{{ $clearAllModalId }}Label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            <form action="{{ $clearAllStockRoute }}" method="POST">
                @csrf
                <div class="modal-header py-3 px-3 text-white" style="background: linear-gradient(135deg, #b91c1c 0%, #7f1d1d 100%);">
                    <div class="d-flex align-items-center">
                        <span class="rounded-circle bg-white text-danger font-size-16 me-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">
                            <i class="bx bx-radiation"></i>
                        </span>
                        <div>
                            <h6 class="modal-title text-white fw-bold font-size-14 mb-0" id="{{ $clearAllModalId }}Label">
                                EMERGENCY: Full {{ $entityPlural }} Inventory Wipeout
                            </h6>
                            <small class="text-white-50 font-size-11">Universal inventory zeroing across all {{ strtolower($entityPlural) }}</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-3 font-size-13">
                    <div class="alert alert-danger font-size-12 mb-3 py-2 px-3 border-0" style="background-color: #fee2e2; color: #991b1b; border-left: 4px solid #b91c1c !important;">
                        <strong>High-Impact Security Warning:</strong> This immediately sets stock on hand to <strong>0.00 for {{ $emergencyWarningScope }}</strong>. Active operations will halt immediately until restocked.
                    </div>

                    <div class="p-3 bg-light rounded-3 border mb-3 font-size-12">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">In-Stock {{ $emergencyImpactNoun }} Affected:</span>
                            <strong class="text-danger">{{ $stats['in_stock_count'] ?? 0 }} {{ $emergencyImpactNoun }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Stock Units Wiped:</span>
                            <strong class="text-danger">{{ number_format($stats['total_units'] ?? 0, 2) }} Units</strong>
                        </div>
                        <div class="d-flex justify-content-between pt-2 border-top">
                            <span class="text-muted">Total Valuation Wiped:</span>
                            <strong class="text-danger font-size-13">₦{{ number_format($stats['total_valuation'] ?? 0, 2) }}</strong>
                        </div>
                    </div>

                    {{-- 2 TYPES OF BULK DEPLETION --}}
                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold mb-1 font-size-12">Depletion &amp; Accounting Mode <span class="text-danger">*</span></label>
                        
                        <div class="form-check p-2.5 border rounded-3 mb-2 accounting-radio-card bg-light">
                            <input class="form-check-input ms-1 me-2" type="radio" name="accounting_type" id="bulkTypeOperational" value="operational" checked>
                            <label class="form-check-label font-size-12 text-dark" for="bulkTypeOperational">
                                <strong class="d-block text-dark font-size-12">Operational Stock Adjustment (Default)</strong>
                                <span class="text-muted font-size-11">Physical count reset only. Does <strong>not</strong> affect sales figures, paid revenues, or projected income reports.</span>
                            </label>
                        </div>

                        <div class="form-check p-2.5 border rounded-3 accounting-radio-card bg-light">
                            <input class="form-check-input ms-1 me-2" type="radio" name="accounting_type" id="bulkTypeWriteOff" value="write_off">
                            <label class="form-check-label font-size-12 text-dark" for="bulkTypeWriteOff">
                                <strong class="d-block text-danger font-size-12">Recognize as Inventory Loss &amp; Spoilage Write-Off</strong>
                                <span class="text-muted font-size-11">Flags full catalog inventory wipeout for executive write-off accounting.</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold mb-1 font-size-12">Type confirmation phrase to unlock <span class="text-danger">*</span></label>
                        <input type="text" name="confirmation_text" class="form-control form-control-sm font-size-12 font-monospace text-center fw-bold" placeholder="CONFIRM EMERGENCY RESET" required autocomplete="off" style="letter-spacing: 1px;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold mb-1 font-size-12">Executive Justification / Reason <span class="text-danger">*</span></label>
                        <input type="text" name="reason" class="form-control form-control-sm font-size-12" placeholder="e.g. Annual physical count reset..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold mb-1 font-size-12">Additional Notes</label>
                        <textarea name="notes" class="form-control form-control-sm font-size-12" rows="2" placeholder="Optional notes for the permanent security audit record..."></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2 px-3">
                    <button type="button" class="btn btn-secondary rounded-pill btn-sm px-3 font-size-12" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-pill btn-sm px-3 font-size-12 fw-medium shadow-sm">
                        <i class="bx bx-radiation me-1"></i> {{ $emergencyExecuteBtn }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const depleteModalEl = document.getElementById('{{ $clearSingleModalId }}');
    if (!depleteModalEl) return;

    const depleteModal = new bootstrap.Modal(depleteModalEl);
    const inputId = document.getElementById('{{ $modalInputId }}');
    const labelName = document.getElementById('{{ $modalNameId }}');
    const labelStock = document.getElementById('{{ $modalStockId }}');
    const labelValue = document.getElementById('{{ $modalValueId }}');

    // Delegated click handler so it works seamlessly across all DataTable pagination pages & filtered searches
    $(document).on('click', '.open-deplete-modal-btn', function () {
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        const stock = parseFloat(this.getAttribute('data-stock')) || 0;
        const unit = this.getAttribute('data-unit') || 'Units';
        const costOrPrice = parseFloat(this.getAttribute('data-cost') || this.getAttribute('data-price')) || 0;
        const totalVal = stock * costOrPrice;

        if (inputId) inputId.value = id;
        if (labelName) labelName.textContent = name;
        if (labelStock) labelStock.textContent = `${stock} ${unit} → 0.00`;
        if (labelValue) labelValue.textContent = `₦${totalVal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;

        // Reset confirmation checkbox
        const chk = document.getElementById('{{ $confirmCheckboxId }}');
        if (chk) chk.checked = false;

        depleteModal.show();
    });
});
</script>
