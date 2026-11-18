@extends('admin.layout.dashboard')

@section('content')

<style>
    /* ==========================================================================
       MOSHEL EXECUTIVE DANGER ZONE STYLES
       ========================================================================== */
    .danger-kpi-card {
        border-radius: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .danger-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06) !important;
    }
    .product-icon-avatar {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        flex-shrink: 0;
        background: #fee2e2;
        color: #b91c1c;
    }
    .danger-header-badge {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #fee2e2;
        color: #dc2626;
        font-size: 20px;
    }
    .accounting-radio-card {
        cursor: pointer;
        transition: border-color 0.2s ease, background-color 0.2s ease;
    }
    .accounting-radio-card:hover {
        background-color: #f8fafc !important;
        border-color: #cbd5e1 !important;
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
                    <h4 class="mb-0 font-size-18 fw-bold text-dark">Stock Control — Danger Zone (Finished Products)</h4>
                    <small class="text-muted font-size-12">Authorized commercial inventory depletion &amp; emergency catalog resets</small>
                </div>
            </div>
            <div class="d-flex align-items-center flex-wrap gap-2 mt-2 mt-sm-0">
                <a href="{{ route('products') }}" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 font-size-12 fw-medium shadow-sm">
                    <i class="bx bx-arrow-back me-1"></i> Products Catalog
                </a>
                <button type="button" class="btn btn-danger rounded-pill px-3 py-1.5 font-size-12 fw-medium shadow-sm" data-bs-toggle="modal" data-bs-target="#clearAllStockModal">
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
                    Zeroing stock on hand halts retail checkout and POS sales for that commercial product. Choose between an 
                    <strong class="text-dark">Operational Adjustment</strong> (physical inventory recount with no disruption to revenue figures) or an 
                    <strong class="text-danger">Inventory Loss &amp; Spoilage Write-Off</strong>. All operations are recorded in the security audit trail and immediately emailed to all administrators.
                </p>
            </div>
        </div>
    </div>
</div>

{{-- =====================================================
     EXECUTIVE KPI STATS ROW
     ===================================================== --}}
<div class="row g-3 mb-4">
    {{-- Card 1: Cataloged Products --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 danger-kpi-card" style="border-left: 4px solid #059669 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Cataloged Products</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ number_format($stats['total_products']) }}</h4>
                        <small class="text-success font-size-11 fw-semibold">
                            <i class="bx bx-check-circle me-1"></i>{{ number_format($stats['in_stock_count']) }} In Stock (>0)
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-success-subtle text-success">
                            <i class="bx bx-package"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Physical Units on Hand --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 danger-kpi-card" style="border-left: 4px solid #0284c7 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Physical Units</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ number_format($stats['total_units'], 2) }}</h4>
                        <small class="text-info font-size-11 fw-semibold">
                            <i class="bx bx-layer me-1"></i>Finished Commercial Stock
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-info-subtle text-info">
                            <i class="bx bx-layer"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 3: Retail Inventory Valuation --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 danger-kpi-card" style="border-left: 4px solid #4f46e5 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div style="min-width: 0;">
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">At-Risk Retail Value</p>
                        <h4 class="mb-0 fw-bold text-dark text-truncate" title="₦{{ number_format($stats['total_valuation'], 2) }}">
                            ₦{{ number_format($stats['total_valuation'], 2) }}
                        </h4>
                        <small class="text-primary font-size-11 fw-medium">
                            <i class="bx bx-wallet me-1"></i>Active Finished Goods
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0 ms-2">
                        <span class="avatar-title rounded-circle font-size-20 bg-primary-subtle text-primary">
                            <i class="bx bx-wallet"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 4: Depleted & Low Stock Alerts --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 danger-kpi-card" style="border-left: 4px solid #dc2626 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Depleted / Zeroed</p>
                        <h4 class="mb-0 fw-bold text-danger">{{ number_format($stats['depleted_count']) }}</h4>
                        <small class="text-warning font-size-11 fw-semibold">
                            <i class="bx bx-error me-1"></i>{{ number_format($stats['low_stock_count']) }} Low Stock (&le;10)
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-danger-subtle text-danger">
                            <i class="bx bx-error-alt"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- =====================================================
     PRODUCTS STOCK TABLE CARD (STANDARD DATATABLE)
     ===================================================== --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-white border-bottom py-3 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h5 class="card-title mb-0 fw-bold text-dark">
                <i class="bx bx-shield text-danger me-1"></i> Commercial Inventory Depletion Controls
            </h5>
            <small class="text-muted">Targeted stock zeroing, manual audit corrections, and loss write-offs for finished bakery goods.</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill bg-light text-dark border px-3 py-1 font-size-11">
                {{ number_format($stats['total_products']) }} {{ Str::plural('Product', $stats['total_products']) }} Registered
            </span>
        </div>
    </div>

    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-hover align-middle dt-responsive nowrap w-100 font-size-13 mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center py-2" style="width: 40px;">#</th>
                        <th class="py-2" style="min-width: 220px;">Product Name</th>
                        <th class="py-2" style="width: 100px;">Sales Unit</th>
                        <th class="py-2" style="width: 120px;">Selling Price</th>
                        <th class="py-2" style="width: 150px;">Stock on Hand</th>
                        <th class="py-2" style="width: 140px;">Total Valuation</th>
                        <th class="text-end py-2 pe-3" style="width: 140px;">Danger Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        @php
                            $qty = (float) $product->stock_on_hand;
                            $price = (float) $product->selling_price;
                            $valuation = $qty * $price;
                        @endphp
                        <tr>
                            <td class="text-center text-muted font-size-11 py-2">
                                {{ $loop->iteration }}
                            </td>
                            <td class="py-2">
                                <div class="d-flex align-items-center">
                                    @if($product->storeProduct && $product->storeProduct->primaryImage)
                                        <img src="{{ asset($product->storeProduct->primaryImage->image_path) }}" alt="{{ $product->name }}" class="rounded me-2 object-fit-cover shadow-sm" style="width: 32px; height: 32px;">
                                    @else
                                        <div class="product-icon-avatar me-2 shadow-sm">
                                            {{ strtoupper(substr($product->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <span class="text-dark fw-bold d-block lh-1 mb-1">{{ $product->name }}</span>
                                        <div class="d-flex gap-1 align-items-center font-size-10">
                                            @if($product->is_active)
                                                <span class="text-success fw-semibold"><i class="bx bxs-circle font-size-8 me-0.5"></i> Active</span>
                                            @else
                                                <span class="text-danger fw-semibold"><i class="bx bxs-circle font-size-8 me-0.5"></i> Disabled</span>
                                            @endif
                                            <span class="text-muted">·</span>
                                            <span class="text-muted font-monospace">{{ $product->slug ?: Str::slug($product->name) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2">
                                <span class="badge bg-light text-dark text-uppercase font-size-11 border px-2 py-1">{{ $product->sales_unit }}</span>
                            </td>
                            <td class="py-2 font-size-13 text-dark fw-semibold">
                                ₦{{ number_format($price, 2) }}
                            </td>
                            <td class="py-2">
                                @if($qty > 10)
                                    <span class="badge bg-soft-success text-success font-size-12 px-2.5 py-1 rounded-pill">
                                        <i class="bx bx-check-circle me-1"></i> {{ $qty }} {{ $product->sales_unit }}
                                    </span>
                                @elseif($qty > 0)
                                    <span class="badge bg-soft-warning text-warning font-size-12 px-2.5 py-1 rounded-pill">
                                        <i class="bx bx-error me-1"></i> {{ $qty }} {{ $product->sales_unit }} (Low)
                                    </span>
                                @else
                                    <span class="badge bg-soft-secondary text-muted font-size-11 px-2.5 py-1 rounded-pill">
                                        0 {{ $product->sales_unit }} (Depleted)
                                    </span>
                                @endif
                            </td>
                            <td class="py-2 font-size-12 text-muted fw-medium">
                                ₦{{ number_format($valuation, 2) }}
                            </td>
                            <td class="text-end py-2 pe-3">
                                @if($qty > 0)
                                    <button type="button" 
                                        class="btn btn-sm btn-outline-danger rounded-pill py-1 px-2.5 font-size-11 fw-medium open-deplete-modal-btn shadow-sm"
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-stock="{{ $qty }}"
                                        data-unit="{{ $product->sales_unit }}"
                                        data-price="{{ $price }}">
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
                                <p class="text-muted font-size-13 mb-0">No commercial products found in catalog.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- =====================================================
     SINGLE REUSABLE MODAL: CLEAR PRODUCT STOCK
     ===================================================== --}}
<div class="modal fade" id="clearProductStockModal" tabindex="-1" aria-labelledby="clearProductStockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            <form action="{{ route('admin.products.clearStock') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" id="modalProductId" value="">

                <div class="modal-header py-3 px-3 text-white" style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);">
                    <div class="d-flex align-items-center">
                        <span class="rounded-circle bg-white text-danger font-size-16 me-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">
                            <i class="bx bx-error-alt"></i>
                        </span>
                        <div>
                            <h6 class="modal-title text-white fw-bold font-size-14 mb-0" id="clearProductStockModalLabel">
                                Confirm Commercial Inventory Depletion
                            </h6>
                            <small class="text-white-50 font-size-11">Zeroing available inventory on hand</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-3 font-size-13">
                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted font-size-12">Target Product:</span>
                            <strong class="text-dark font-size-14" id="modalProductName">—</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted font-size-12">Stock Quantity on Hand:</span>
                            <strong class="text-danger font-size-13 font-monospace" id="modalProductStock">—</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <span class="text-muted font-size-12">Estimated Lost Retail Valuation:</span>
                            <span class="text-dark fw-bold font-size-13" id="modalProductValue">—</span>
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
                                <span class="text-muted font-size-11">Formally registers lost retail valuation into administrative loss records and executive audit reports.</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold mb-1 font-size-12">Operational Reason <span class="text-danger">*</span></label>
                        <select name="reason" class="form-select form-select-sm font-size-12" required>
                            <option value="" disabled selected>Select an operational justification...</option>
                            <option value="Physical Stock Count Discrepancy / Audit Correction">Physical Stock Count Discrepancy / Audit Correction</option>
                            <option value="Batch Spoilage / Contamination / Quality Issue">Batch Spoilage / Contamination / Quality Issue</option>
                            <option value="Stock Write-off / Damaged Goods">Stock Write-off / Damaged Goods</option>
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
                        <input class="form-check-input ms-1 me-2" type="checkbox" name="confirm_deplete" id="confirmDepleteSingle" value="1" required>
                        <label class="form-check-label font-size-12 text-dark fw-semibold" for="confirmDepleteSingle">
                            I confirm zeroing out commercial stock for this product. An immediate alert email will be dispatched to all administrators.
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
     MODAL: FULL CATALOG EMERGENCY RESET
     ===================================================== --}}
<div class="modal fade" id="clearAllStockModal" tabindex="-1" aria-labelledby="clearAllStockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            <form action="{{ route('admin.products.clearAllStock') }}" method="POST">
                @csrf
                <div class="modal-header py-3 px-3 text-white" style="background: linear-gradient(135deg, #b91c1c 0%, #7f1d1d 100%);">
                    <div class="d-flex align-items-center">
                        <span class="rounded-circle bg-white text-danger font-size-16 me-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">
                            <i class="bx bx-radiation"></i>
                        </span>
                        <div>
                            <h6 class="modal-title text-white fw-bold font-size-14 mb-0" id="clearAllStockModalLabel">
                                EMERGENCY: Full Commercial Catalog Stock Wipeout
                            </h6>
                            <small class="text-white-50 font-size-11">Universal inventory zeroing across all finished products</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-3 font-size-13">
                    <div class="alert alert-danger font-size-12 mb-3 py-2 px-3 border-0" style="background-color: #fee2e2; color: #991b1b; border-left: 4px solid #b91c1c !important;">
                        <strong>High-Impact Security Warning:</strong> This immediately sets stock on hand to <strong>0.00 for EVERY product</strong> in your catalog. Storefront and POS checkout will be disabled for all items until restocked.
                    </div>

                    <div class="p-3 bg-light rounded-3 border mb-3 font-size-12">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">In-Stock Products Affected:</span>
                            <strong class="text-danger">{{ $stats['in_stock_count'] }} Products</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Stock Units Wiped:</span>
                            <strong class="text-danger">{{ number_format($stats['total_units'], 2) }} Units</strong>
                        </div>
                        <div class="d-flex justify-content-between pt-2 border-top">
                            <span class="text-muted">Total Retail Valuation Wiped:</span>
                            <strong class="text-danger font-size-13">₦{{ number_format($stats['total_valuation'], 2) }}</strong>
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
                        <i class="bx bx-radiation me-1"></i> EXECUTE FULL CATALOG WIPEOUT
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const depleteModalEl = document.getElementById('clearProductStockModal');
    if (!depleteModalEl) return;

    const depleteModal = new bootstrap.Modal(depleteModalEl);
    const inputId = document.getElementById('modalProductId');
    const labelName = document.getElementById('modalProductName');
    const labelStock = document.getElementById('modalProductStock');
    const labelValue = document.getElementById('modalProductValue');

    // Delegated click handler so it works seamlessly across all DataTable pagination pages & filtered searches
    $(document).on('click', '.open-deplete-modal-btn', function () {
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        const stock = parseFloat(this.getAttribute('data-stock')) || 0;
        const unit = this.getAttribute('data-unit') || 'Units';
        const price = parseFloat(this.getAttribute('data-price')) || 0;
        const totalVal = stock * price;

        inputId.value = id;
        labelName.textContent = name;
        labelStock.textContent = `${stock} ${unit} → 0.00`;
        labelValue.textContent = `₦${totalVal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;

        // Reset confirmation checkbox
        const chk = document.getElementById('confirmDepleteSingle');
        if (chk) chk.checked = false;

        depleteModal.show();
    });
});
</script>

@endsection
