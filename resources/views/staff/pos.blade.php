@extends('staff.layout.dashboard')

@section('content')
<style>
    /* ==========================================================================
       MOSHEL POS EXECUTIVE DESIGN SYSTEM (STAFF TERMINAL)
       ========================================================================== */
    :root {
        --pos-primary: #556ee6;
        --pos-primary-dark: #4057c7;
        --pos-primary-subtle: #edf1fd;
        --pos-success: #34c38f;
        --pos-success-subtle: #e6f9f2;
        --pos-warning: #f1b44c;
        --pos-warning-subtle: #fef7ec;
        --pos-danger: #f46a6a;
        --pos-danger-subtle: #fdeeee;
        --pos-dark: #2a3042;
        --pos-border: #e9ecef;
        --pos-card-bg: #ffffff;
        --pos-surface: #f8f9fa;
    }

    .pos-terminal-container {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 145px);
        min-height: 600px;
        background: #f4f6fa;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    /* Terminal Header Bar */
    .pos-top-bar {
        background: #ffffff;
        padding: 12px 20px;
        border-bottom: 1px solid var(--pos-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        flex-shrink: 0;
    }

    .pos-brand-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--pos-primary-subtle);
        color: var(--pos-primary);
        font-weight: 700;
        font-size: 13px;
        padding: 6px 14px;
        border-radius: 30px;
    }

    .pos-clock-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f1f5f9;
        color: #475569;
        font-family: 'SFMono-Regular', Menlo, Monaco, Consolas, monospace;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 20px;
    }

    .pos-cashier-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #334155;
        font-size: 12px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
    }

    /* Terminal Workspace Body */
    .pos-main-workspace {
        display: flex;
        flex: 1;
        overflow: hidden;
        position: relative;
    }

    /* Left Panel: Catalog Browser */
    .pos-catalog-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #f8f9fc;
        overflow: hidden;
        border-right: 1px solid #e2e8f0;
    }

    .pos-catalog-filter-bar {
        padding: 16px 20px 12px;
        background: #ffffff;
        border-bottom: 1px solid #edf2f7;
        flex-shrink: 0;
    }

    .pos-search-wrapper {
        position: relative;
    }

    .pos-search-input {
        padding-left: 42px;
        padding-right: 80px;
        height: 44px;
        font-size: 14px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        transition: all 0.2s ease;
    }

    .pos-search-input:focus {
        background: #ffffff;
        border-color: var(--pos-primary);
        box-shadow: 0 0 0 3px rgba(85, 110, 230, 0.15);
    }

    .pos-search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 18px;
    }

    .pos-search-shortcut {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 11px;
        font-weight: 600;
        background: #e2e8f0;
        color: #64748b;
        padding: 2px 7px;
        border-radius: 6px;
        pointer-events: none;
    }

    .pos-filter-chips {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-top: 10px;
        scrollbar-width: none;
    }
    .pos-filter-chips::-webkit-scrollbar { display: none; }

    .pos-filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
    }

    .pos-filter-chip:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
        color: #334155;
    }

    .pos-filter-chip.active {
        background: var(--pos-primary);
        color: #ffffff;
        border-color: var(--pos-primary);
        box-shadow: 0 2px 6px rgba(85, 110, 230, 0.3);
    }

    /* Product Grid */
    .pos-products-scroll {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
    }

    .pos-product-card {
        background: #ffffff;
        border: 1px solid #e8ecf3;
        border-radius: 14px;
        padding: 14px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        user-select: none;
    }

    .pos-product-card:hover {
        transform: translateY(-3px);
        border-color: #c7d2fe;
        box-shadow: 0 10px 22px rgba(85, 110, 230, 0.08);
    }

    .pos-product-card:active {
        transform: scale(0.98);
    }

    .pos-product-card.out-of-stock {
        opacity: 0.55;
        background: #f8fafc;
        cursor: not-allowed;
    }
    .pos-product-card.out-of-stock:hover {
        transform: none;
        box-shadow: none;
        border-color: #e8ecf3;
    }

    .pos-product-card.is-selected {
        border-color: var(--pos-primary);
        box-shadow: 0 0 0 2px rgba(85, 110, 230, 0.25), 0 8px 20px rgba(85, 110, 230, 0.1);
        background: #fbfcfe;
    }

    .pos-product-thumb {
        height: 100px;
        border-radius: 10px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        overflow: hidden;
        position: relative;
        z-index: 1;
    }

    .pos-product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;
    }

    .pos-product-card:hover .pos-product-thumb img {
        transform: scale(1.05);
    }

    .pos-thumb-overlay {
        position: absolute;
        inset: 0;
        background: rgba(85, 110, 230, 0.2);
        z-index: 10;
        display: none;
        pointer-events: none;
    }

    .pos-product-thumb .thumb-fallback {
        font-size: 36px;
        color: #94a3b8;
    }

    /* IN-CART FLOATING COUNTER (ALWAYS ON TOP OF IMAGE) */
    .pos-cart-in-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        z-index: 30 !important; /* CRITICAL: Displays on top of the image */
        background: var(--pos-primary);
        color: #ffffff;
        font-size: 11px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        display: none;
        pointer-events: none;
        align-items: center;
        border: 2px solid #ffffff;
        letter-spacing: 0.2px;
        animation: popBadge 0.25s ease-out;
    }

    @keyframes popBadge {
        0% { transform: scale(0.5); opacity: 0; }
        80% { transform: scale(1.15); }
        100% { transform: scale(1); opacity: 1; }
    }

    .pos-product-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 6px;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 36px;
    }

    .pos-product-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px dashed #f1f5f9;
    }

    .pos-product-price {
        font-size: 15px;
        font-weight: 800;
        color: var(--pos-primary);
    }

    .pos-stock-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 2px 7px;
        border-radius: 6px;
    }
    .pos-stock-badge.in-stock {
        background: var(--pos-success-subtle);
        color: #0d8a56;
    }
    .pos-stock-badge.low-stock {
        background: var(--pos-warning-subtle);
        color: #b45309;
    }
    .pos-stock-badge.out-stock {
        background: var(--pos-danger-subtle);
        color: #b91c1c;
    }

    /* Right Panel: Cart Terminal */
    .pos-cart-panel {
        width: 440px;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
    }

    .pos-cart-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--pos-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
    }

    .pos-cart-title-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pos-cart-items-wrapper {
        flex: 1;
        overflow-y: auto;
        padding: 12px 16px;
        background: #ffffff;
    }

    .pos-empty-cart {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }
    .pos-empty-cart i {
        font-size: 54px;
        color: #cbd5e1;
        margin-bottom: 12px;
    }

    .pos-cart-item {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 10px;
        transition: all 0.2s ease;
    }

    .pos-cart-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    .pos-cart-item-info {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 8px;
    }

    .pos-cart-item-name {
        font-size: 13.5px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 2px;
        line-height: 1.25;
    }

    .pos-cart-item-rate {
        font-size: 11.5px;
        color: #64748b;
    }

    .pos-cart-item-subtotal {
        font-size: 14px;
        font-weight: 800;
        color: #1e293b;
        text-align: right;
    }

    .pos-cart-item-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 8px;
        border-top: 1px dashed #f1f5f9;
    }

    .pos-qty-stepper {
        display: inline-flex;
        align-items: center;
        background: #f1f5f9;
        border-radius: 8px;
        padding: 3px;
        gap: 2px;
    }

    .pos-qty-btn {
        width: 28px;
        height: 28px;
        border: none;
        background: #ffffff;
        color: #334155;
        border-radius: 6px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        cursor: pointer;
        transition: all 0.15s;
    }
    .pos-qty-btn:hover {
        background: var(--pos-primary);
        color: #ffffff;
    }
    .pos-qty-btn.del:hover {
        background: var(--pos-danger);
        color: #ffffff;
    }

    .pos-qty-input {
        width: 44px;
        height: 28px;
        border: none;
        background: transparent;
        text-align: center;
        font-weight: 700;
        font-size: 13px;
        color: #1e293b;
        outline: none;
    }

    /* Cart Calculation & Footer */
    .pos-cart-summary {
        background: #f8fafc;
        border-top: 1px solid var(--pos-border);
        padding: 16px 20px;
    }

    .pos-summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        color: #64748b;
        margin-bottom: 8px;
    }

    .pos-summary-row.total-row {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 2px dashed #cbd5e1;
        color: #0f172a;
    }

    .pos-discount-pills {
        display: flex;
        gap: 6px;
        margin-bottom: 8px;
    }

    .pos-discount-pill {
        flex: 1;
        padding: 4px 6px;
        font-size: 11px;
        font-weight: 700;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        color: #475569;
        cursor: pointer;
        text-align: center;
        transition: all 0.15s;
    }
    .pos-discount-pill:hover,
    .pos-discount-pill.active {
        background: var(--pos-primary-subtle);
        border-color: var(--pos-primary);
        color: var(--pos-primary);
    }

    .pos-checkout-btn {
        width: 100%;
        height: 52px;
        border-radius: 12px;
        background: linear-gradient(135deg, #556ee6 0%, #3b50c4 100%);
        color: #ffffff;
        border: none;
        font-size: 15px;
        font-weight: 800;
        letter-spacing: 0.3px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 4px 14px rgba(85, 110, 230, 0.35);
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .pos-checkout-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(85, 110, 230, 0.45);
        background: linear-gradient(135deg, #465fd6 0%, #3044b3 100%);
    }
    .pos-checkout-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }

    /* Checkout Modal Enhancements */
    .pay-method-option {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
        background: #ffffff;
    }
    .pay-method-option:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }
    .btn-check:checked + .pay-method-option {
        border-color: var(--pos-primary);
        background: var(--pos-primary-subtle);
        color: var(--pos-primary);
    }

    .denom-pill {
        border: 1px solid #cbd5e1;
        background: #ffffff;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 12px;
        font-weight: 700;
        color: #334155;
        cursor: pointer;
        transition: all 0.15s;
    }
    .denom-pill:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
    }

    /* Printable Thermal Receipt */
    .thermal-paper {
        width: 80mm;
        max-width: 100%;
        margin: 0 auto;
        background: #ffffff;
        color: #000000;
        font-family: 'SFMono-Regular', Menlo, Monaco, Consolas, 'Courier New', Courier, monospace;
        padding: 20px 18px;
        border-radius: 6px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        position: relative;
    }

    .receipt-dashed-line {
        border-top: 1px dashed #000000;
        margin: 10px 0;
    }
    .receipt-double-line {
        border-top: 2px solid #000000;
        margin: 10px 0;
    }

    .receipt-barcode {
        height: 32px;
        background: repeating-linear-gradient(
            90deg,
            #000000,
            #000000 2px,
            transparent 2px,
            transparent 4px,
            #000000 4px,
            #000000 7px,
            transparent 7px,
            transparent 9px
        );
        margin: 10px auto 4px;
        width: 75%;
    }

    @media print {
        body * {
            visibility: hidden;
        }
        #printableReceiptArea, #printableReceiptArea * {
            visibility: visible;
        }
        #printableReceiptArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 80mm;
            padding: 0;
            margin: 0;
        }
        .modal, .modal-backdrop, .pos-terminal-container {
            display: none !important;
        }
    }

    /* Scrollbars */
    .custom-pos-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-pos-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-pos-scroll::-webkit-scrollbar-track { background: transparent; }
</style>

{{-- POS TERMINAL WRAPPER --}}
<div class="pos-terminal-container">

    {{-- TOP COMMAND BAR --}}
    <div class="pos-top-bar">
        <div class="d-flex align-items-center gap-2">
            <span class="pos-brand-pill">
                <i class="bx bx-store-alt font-size-16"></i>
                <span>FOOD PRODUCTION & SALES TERMINAL</span>
            </span>
            <span class="pos-cashier-pill d-none d-md-inline-flex">
                <i class="bx bx-user-circle text-primary"></i>
                <span>Staff: <strong>{{ Auth::guard('staff')->user()->name ?? 'Staff Member' }}</strong></span>
            </span>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="pos-clock-pill d-none d-sm-inline-flex">
                <i class="bx bx-time-five"></i>
                <span id="posLiveClock">--:--:--</span>
            </span>

            <a href="{{ url('/staff/salesHistory') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold">
                <i class="bx bx-history me-1"></i> My Sales Log
            </a>

            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-semibold" onclick="confirmResetCart()">
                <i class="bx bx-trash me-1"></i> Clear Ticket
            </button>

            <button type="button" class="btn btn-sm btn-soft-primary rounded-circle" id="toggleSoundBtn" onclick="toggleMute()" title="Toggle Sound">
                <i class="bx bx-volume-full font-size-16" id="soundIcon"></i>
            </button>
        </div>
    </div>

    {{-- MAIN WORKSPACE --}}
    <div class="pos-main-workspace">

        {{-- LEFT PANEL: PRODUCTS & INVENTORY --}}
        <div class="pos-catalog-panel">
            {{-- SEARCH & FILTER BAR --}}
            <div class="pos-catalog-filter-bar">
                <div class="row g-2 align-items-center">
                    <div class="col-md-7">
                        <div class="pos-search-wrapper">
                            <i class="bx bx-search pos-search-icon"></i>
                            <input type="text" id="posSearchInput" class="form-control pos-search-input" placeholder="Search product name, unit, or barcode... [Press /]">
                            <span class="pos-search-shortcut">/ or F2</span>
                        </div>
                    </div>
                    <div class="col-md-5 d-flex justify-content-end align-items-center gap-2">
                        <div class="d-flex align-items-center gap-1 text-muted small">
                            <i class="bx bx-package"></i>
                            <span id="productsCountBadge">{{ count($products) }} items</span>
                        </div>
                        <select id="sortSelect" class="form-select form-select-sm border-0 bg-light rounded-pill w-auto fw-semibold" onchange="applySorting()">
                            <option value="name_asc">Name (A-Z)</option>
                            <option value="name_desc">Name (Z-A)</option>
                            <option value="price_low">Price (Low-High)</option>
                            <option value="price_high">Price (High-Low)</option>
                            <option value="stock_high">Most In Stock</option>
                        </select>
                    </div>
                </div>

                {{-- FILTER CHIPS --}}
                <div class="pos-filter-chips">
                    <button type="button" class="pos-filter-chip active" data-filter="all" onclick="setCatalogFilter('all', this)">
                        <i class="bx bx-grid-alt"></i> All Catalog ({{ count($products) }})
                    </button>
                    <button type="button" class="pos-filter-chip" data-filter="instock" onclick="setCatalogFilter('instock', this)">
                        <i class="bx bx-check-circle text-success"></i> In Stock Only
                    </button>
                    <button type="button" class="pos-filter-chip" data-filter="lowstock" onclick="setCatalogFilter('lowstock', this)">
                        <i class="bx bx-error text-warning"></i> Low Stock (≤10)
                    </button>

                    @php
                        $distinctUnits = $products->pluck('sales_unit')->filter()->unique()->values();
                    @endphp
                    @foreach($distinctUnits as $unit)
                        <button type="button" class="pos-filter-chip" data-unit="{{ strtolower($unit) }}" onclick="setUnitFilter('{{ strtolower($unit) }}', this)">
                            <i class="bx bx-tag-alt"></i> {{ ucfirst($unit) }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- PRODUCTS GRID --}}
            <div class="pos-products-scroll custom-pos-scroll">
                <div class="row g-3" id="productsGrid">
                    @forelse($products as $product)
                        @php
                            $stock = (float)$product->stock_on_hand;
                            $isOut = $stock <= 0;
                            $isLow = $stock > 0 && $stock <= 10;
                            $imgPath = $product->storeProduct?->primaryImage?->image_path;
                        @endphp
                        <div class="col-sm-6 col-md-4 col-xl-3 product-card-wrapper" 
                             data-id="{{ $product->id }}"
                             data-name="{{ strtolower($product->name) }}"
                             data-price="{{ (float)$product->selling_price }}"
                             data-stock="{{ $stock }}"
                             data-unit="{{ strtolower($product->sales_unit ?? 'pcs') }}">
                            <div class="pos-product-card {{ $isOut ? 'out-of-stock' : '' }}" 
                                 onclick="handleProductClick({{ $product->id }}, '{{ addslashes($product->name) }}', {{ (float)$product->selling_price }}, {{ $stock }}, '{{ addslashes($product->sales_unit ?? 'unit') }}')">
                                
                                {{-- PRODUCT THUMBNAIL (WITH FLOATING IN-CART BADGE OVERLAY) --}}
                                <div class="pos-product-thumb">
                                    {{-- IN-CART FLOATING COUNTER (ON TOP OF IMAGE) --}}
                                    <div class="pos-cart-in-badge" id="badge-prod-{{ $product->id }}">
                                        <i class="bx bx-shopping-bag me-1"></i><span class="count">0</span>
                                    </div>
                                    <div class="pos-thumb-overlay" id="overlay-prod-{{ $product->id }}"></div>

                                    @if($imgPath)
                                        <img src="{{ asset($imgPath) }}" alt="{{ $product->name }}" loading="lazy">
                                    @else
                                        <div class="thumb-fallback">
                                            <i class="bx bx-dish"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- TITLE & METRICS --}}
                                <div>
                                    <div class="pos-product-title" title="{{ $product->name }}">{{ $product->name }}</div>
                                    <span class="badge bg-light text-muted small text-uppercase mb-1">{{ $product->sales_unit ?? 'Item' }}</span>
                                </div>

                                <div class="pos-product-meta">
                                    <div class="pos-product-price">₦{{ number_format($product->selling_price, 2) }}</div>
                                    <div>
                                        @if($isOut)
                                            <span class="pos-stock-badge out-stock">Out of Stock</span>
                                        @elseif($isLow)
                                            <span class="pos-stock-badge low-stock">{{ $stock }} left</span>
                                        @else
                                            <span class="pos-stock-badge in-stock">{{ $stock }} in stock</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="bx bx-package text-muted" style="font-size: 48px;"></i>
                            <h5 class="mt-2 text-muted">No products available</h5>
                            <p class="text-muted small">Only items with positive stock are listed for cashier checkout.</p>
                        </div>
                    @endforelse
                </div>

                <div id="noSearchMatch" class="text-center py-5 d-none">
                    <i class="bx bx-search text-muted" style="font-size: 48px;"></i>
                    <h5 class="mt-3 text-dark fw-bold">No matching products found</h5>
                    <p class="text-muted small">Try checking your spelling or selecting "All Catalog".</p>
                </div>
            </div>
        </div>

        {{-- RIGHT PANEL: LIVE CART TERMINAL --}}
        <div class="pos-cart-panel shadow-sm">
            {{-- CART HEADER --}}
            <div class="pos-cart-header">
                <div class="pos-cart-title-group">
                    <div class="avatar-xs">
                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                            <i class="bx bx-receipt font-size-18"></i>
                        </span>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Active Ticket</h6>
                        <small class="text-muted" id="cartTicketInfo">Order #<span id="ticketTimestamp">NEW</span></small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary rounded-pill px-2 py-1" id="cartItemCountBadge">0 items</span>
                </div>
            </div>

            {{-- CUSTOMER / ORDER IDENTIFIER (OPTIONAL) --}}
            <div class="px-3 pt-2 pb-1 bg-white border-bottom">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-0 text-muted"><i class="bx bx-user"></i></span>
                    <input type="text" id="customerRefInput" class="form-control border-0 bg-light" placeholder="Customer name or table # (Optional)">
                </div>
            </div>

            {{-- CART ITEMS LIST --}}
            <div class="pos-cart-items-wrapper custom-pos-scroll" id="cartContainer">
                <div id="emptyCartView" class="pos-empty-cart">
                    <i class="bx bx-cart-alt"></i>
                    <h6 class="fw-bold text-dark mt-2">Ticket is empty</h6>
                    <p class="small text-muted mb-0">Tap any available product on the left catalog to start building your order.</p>
                </div>
                <div id="cartItemsList"></div>
            </div>

            {{-- CART SUMMARY & ACTIONS --}}
            <div class="pos-cart-summary">
                <div class="pos-summary-row">
                    <span>Subtotal</span>
                    <span class="fw-bold text-dark" id="summarySubtotal">₦0.00</span>
                </div>

                {{-- DISCOUNT SHORTCUTS --}}
                <div class="mt-2 mb-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small">Discount</span>
                        <span class="fw-semibold text-danger small" id="discountDeductionLabel">-₦0.00</span>
                    </div>
                    <div class="pos-discount-pills">
                        <button type="button" class="pos-discount-pill active" onclick="applyPresetDiscount(0, this)">0%</button>
                        <button type="button" class="pos-discount-pill" onclick="applyPresetDiscount(5, this)">5%</button>
                        <button type="button" class="pos-discount-pill" onclick="applyPresetDiscount(10, this)">10%</button>
                        <button type="button" class="pos-discount-pill" onclick="applyPresetDiscount(500, this, true)">₦500</button>
                        <button type="button" class="pos-discount-pill" onclick="applyPresetDiscount(1000, this, true)">₦1k</button>
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-0 text-muted">₦</span>
                        <input type="number" id="discountAmountInput" class="form-control form-control-sm border-0 text-end fw-bold" value="0" min="0" step="any" oninput="onCustomDiscountInput()">
                    </div>
                </div>

                {{-- TOTAL PAYABLE --}}
                <div class="pos-summary-row total-row">
                    <span class="h6 fw-bold mb-0">Total Payable</span>
                    <span class="h4 fw-extrabold text-primary mb-0" id="summaryTotal">₦0.00</span>
                </div>

                {{-- CHECKOUT BUTTON --}}
                <div class="mt-3">
                    <button type="button" class="pos-checkout-btn" id="checkoutBtn" onclick="openCheckoutModal()" disabled>
                        <i class="bx bx-check-shield font-size-20"></i>
                        <span>CHARGE <span id="checkoutBtnTotal">₦0.00</span> (F4)</span>
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- CHECKOUT & TENDER MODAL --}}
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom px-4 py-3 bg-light">
                <div class="d-flex align-items-center gap-2">
                    <span class="avatar-xs">
                        <span class="avatar-title bg-primary text-white rounded-circle"><i class="bx bx-wallet font-size-16"></i></span>
                    </span>
                    <h5 class="modal-title fw-bold text-dark">Checkout & Payment Settlement</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                {{-- ORDER RECAP CARD --}}
                <div class="card border bg-light mb-4 shadow-none rounded-3">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small text-uppercase fw-semibold d-block">Amount To Settle</span>
                            <h3 class="fw-extrabold text-primary mb-0" id="modalPayableDisplay">₦0.00</h3>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-2" id="modalItemSummary">0 Items</span>
                        </div>
                    </div>
                </div>

                {{-- PAYMENT METHOD SELECTION --}}
                <label class="form-label fw-bold small text-uppercase text-muted">Select Payment Method</label>
                <div class="row g-2 mb-4">
                    <div class="col-4">
                        <input type="radio" class="btn-check" name="payMethod" id="payMethodCash" value="Cash" checked onchange="onPaymentMethodChanged()">
                        <label class="payMethodOption w-100 pay-method-option" for="payMethodCash">
                            <i class="bx bx-money font-size-24 d-block mb-1 text-success"></i>
                            <span class="fw-bold">Cash</span>
                        </label>
                    </div>
                    <div class="col-4">
                        <input type="radio" class="btn-check" name="payMethod" id="payMethodTransfer" value="Transfer" onchange="onPaymentMethodChanged()">
                        <label class="payMethodOption w-100 pay-method-option" for="payMethodTransfer">
                            <i class="bx bx-transfer font-size-24 d-block mb-1 text-info"></i>
                            <span class="fw-bold">Bank Transfer</span>
                        </label>
                    </div>
                    <div class="col-4">
                        <input type="radio" class="btn-check" name="payMethod" id="payMethodCard" value="Card" onchange="onPaymentMethodChanged()">
                        <label class="payMethodOption w-100 pay-method-option" for="payMethodCard">
                            <i class="bx bxs-credit-card font-size-24 d-block mb-1 text-warning"></i>
                            <span class="fw-bold">Card / POS</span>
                        </label>
                    </div>
                </div>

                {{-- CASH TENDERED & CHANGE CALCULATOR (VISIBLE FOR CASH) --}}
                <div id="cashCalculatorSection" class="p-3 bg-light rounded-3 mb-3 border">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-0">Cash Tendered by Customer</label>
                        <span class="small text-muted">Quick Denominations:</span>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <button type="button" class="denom-pill" onclick="setExactTender()">Exact Amount</button>
                        <button type="button" class="denom-pill" onclick="addTender(500)">+₦500</button>
                        <button type="button" class="denom-pill" onclick="addTender(1000)">+₦1,000</button>
                        <button type="button" class="denom-pill" onclick="addTender(2000)">+₦2,000</button>
                        <button type="button" class="denom-pill" onclick="addTender(5000)">+₦5,000</button>
                        <button type="button" class="denom-pill" onclick="addTender(10000)">+₦10,000</button>
                    </div>

                    <div class="row g-2 align-items-center">
                        <div class="col-sm-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white fw-bold">₦</span>
                                <input type="number" id="cashTenderedInput" class="form-control form-control-lg fw-bold" placeholder="0.00" oninput="calculateChange()">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div id="changeDueBox" class="p-2 rounded text-center fw-bold bg-white border">
                                <span class="small text-muted d-block">Change to Return:</span>
                                <span class="h5 mb-0 text-success fw-extrabold" id="changeAmountLabel">₦0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SALE NOTES & ORDER TAGS --}}
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted text-uppercase">Transaction Notes / Reference (Optional)</label>
                    <input type="text" id="saleNotesInput" class="form-control bg-light border-0" placeholder="e.g. POS terminal ref, transfer session ID, or order instructions...">
                </div>
            </div>

            <div class="modal-footer border-top px-4 py-3 bg-light">
                <button type="button" class="btn btn-secondary px-4 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success px-4 fw-bold" id="confirmSaleBtn" onclick="executeSaleProcess()">
                    <i class="bx bx-check-circle me-1"></i> Complete & Print Receipt
                </button>
            </div>
        </div>
    </div>
</div>

{{-- POST-SALE RECEIPT MODAL --}}
<div class="modal fade" id="receiptModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom px-4 py-3 bg-success text-white">
                <div class="d-flex align-items-center gap-2">
                    <i class="bx bx-check-double font-size-22"></i>
                    <h5 class="modal-title fw-bold text-white mb-0">Sale Completed Successfully</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4 bg-light">
                {{-- THERMAL RECEIPT PREVIEW --}}
                <div class="thermal-paper shadow-sm border" id="printableReceiptArea">
                    <div style="text-align: center; margin-bottom: 8px;">
                        <h5 style="font-weight: 900; margin-bottom: 2px; text-transform: uppercase;">{{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->site_name : "MOSHEL FOOD PRODUCTION" }}</h5>
                        <div style="font-size: 11px; color: #444; line-height: 1.3;">
                            {{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->address : "Sales & Retail Operations" }}<br>
                            Tel: {{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->phone : "+234 800 000 0000" }}
                        </div>
                    </div>

                    <div class="receipt-dashed-line"></div>

                    {{-- RECEIPT TITLE & BARCODE --}}
                    <div style="text-align: center;">
                        <div style="font-weight: 800; font-size: 13px; letter-spacing: 1px;">OFFICIAL SALES RECEIPT</div>
                        <div class="receipt-barcode"></div>
                        <div style="font-size: 12px; font-weight: bold; letter-spacing: 0.5px;" id="rcptReference">REC-00000000</div>
                    </div>

                    <div class="receipt-dashed-line"></div>

                    <div style="font-size: 11px; line-height: 1.5;">
                        <div style="display: flex; justify-content: space-between;">
                            <span>DATE & TIME:</span>
                            <strong id="rcptDate">--</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>CASHIER:</span>
                            <strong id="rcptCashier">Staff</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>PAYMENT METHOD:</span>
                            <strong id="rcptMethod">Cash</strong>
                        </div>
                    </div>

                    <div class="receipt-dashed-line"></div>

                    <table style="width: 100%; border-collapse: collapse; font-size: 11.5px; margin-bottom: 6px;">
                        <thead>
                            <tr style="border-bottom: 1px dashed #000;">
                                <th style="text-align: left; padding-bottom: 4px;">ITEM</th>
                                <th style="text-align: center; padding-bottom: 4px;">QTY</th>
                                <th style="text-align: right; padding-bottom: 4px;">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody id="rcptItemsBody"></tbody>
                    </table>

                    <div class="receipt-dashed-line"></div>

                    <div style="font-size: 12px; line-height: 1.5;">
                        <div style="display: flex; justify-content: space-between;">
                            <span>SUBTOTAL:</span>
                            <span id="rcptSubtotal">₦0.00</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; color: #b91c1c;" id="rcptDiscountRow">
                            <span>DISCOUNT:</span>
                            <span id="rcptDiscount">-₦0.00</span>
                        </div>
                        <div class="receipt-double-line"></div>
                        <div style="display: flex; justify-content: space-between; font-size: 15px; font-weight: 900;">
                            <span>TOTAL PAID:</span>
                            <span id="rcptTotal">₦0.00</span>
                        </div>
                    </div>

                    <div class="receipt-dashed-line"></div>

                    <div style="font-size: 11px; line-height: 1.5;">
                        <div style="display: flex; justify-content: space-between;" id="rcptTenderRow">
                            <span>CASH TENDERED:</span>
                            <span id="rcptTendered">₦0.00</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-weight: bold;" id="rcptChangeRow">
                            <span>CHANGE RETURNED:</span>
                            <span id="rcptChange">₦0.00</span>
                        </div>
                    </div>

                    <div class="receipt-dashed-line"></div>
                    <div style="text-align: center; font-size: 11px; color: #333; line-height: 1.4;">
                        <p style="margin-bottom: 2px; font-weight: 700;">Thank you for your patronage!</p>
                        <p style="margin-bottom: 0;">Freshly baked & produced with excellence.</p>
                        <small style="color: #666;">Retain this receipt for audit verification.</small>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-top px-4 py-3 bg-white">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary px-4 fw-bold" onclick="printReceiptTicket()">
                    <i class="bx bx-printer me-1"></i> Print Receipt
                </button>
                <button type="button" class="btn btn-success px-4 fw-bold" onclick="startFreshSale()">
                    <i class="bx bx-plus me-1"></i> New Order
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    /* ==========================================================================
       MOSHEL POS TERMINAL SCRIPT ENGINE (STAFF SUITE)
       ========================================================================== */
    let cart = [];
    let currentFilter = 'all';
    let currentUnitFilter = null;
    let isSoundMuted = false;
    let lastCompletedSale = null;

    // Process Sale Endpoint
    const PROCESS_SALE_ENDPOINT = '{{ url("/staff/processSale") }}';

    // 1. Digital Clock
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const clockEl = document.getElementById('posLiveClock');
        if (clockEl) clockEl.innerText = timeString;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // 2. Audio Chime (Synthesizer via Web Audio API)
    function playChime(type = 'add') {
        if (isSoundMuted) return;
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);

            if (type === 'add') {
                osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
                osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.08); // A5
                gain.gain.setValueAtTime(0.12, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.12);
                osc.start();
                osc.stop(ctx.currentTime + 0.13);
            } else if (type === 'success') {
                osc.frequency.setValueAtTime(523.25, ctx.currentTime); // C5
                osc.frequency.exponentialRampToValueAtTime(1046.50, ctx.currentTime + 0.2); // C6
                gain.gain.setValueAtTime(0.18, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
                osc.start();
                osc.stop(ctx.currentTime + 0.32);
            } else if (type === 'warn') {
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(220, ctx.currentTime);
                gain.gain.setValueAtTime(0.15, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.18);
                osc.start();
                osc.stop(ctx.currentTime + 0.2);
            }
        } catch (e) {}
    }

    function toggleMute() {
        isSoundMuted = !isSoundMuted;
        const icon = document.getElementById('soundIcon');
        if (icon) {
            icon.className = isSoundMuted ? 'bx bx-volume-mute text-danger font-size-16' : 'bx bx-volume-full font-size-16';
        }
    }

    // 3. Search & Filtering Engine
    document.getElementById('posSearchInput').addEventListener('input', function() {
        filterProductCards();
    });

    function setCatalogFilter(filter, btn) {
        currentFilter = filter;
        currentUnitFilter = null;
        document.querySelectorAll('.pos-filter-chip').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');
        filterProductCards();
    }

    function setUnitFilter(unit, btn) {
        currentFilter = 'unit';
        currentUnitFilter = unit;
        document.querySelectorAll('.pos-filter-chip').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');
        filterProductCards();
    }

    function filterProductCards() {
        const query = (document.getElementById('posSearchInput').value || '').trim().toLowerCase();
        const cards = document.querySelectorAll('.product-card-wrapper');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.dataset.name;
            const stock = parseFloat(card.dataset.stock);
            const unit = card.dataset.unit;

            let matchesQuery = !query || name.includes(query);
            let matchesCategory = true;

            if (currentFilter === 'instock') {
                matchesCategory = stock > 0;
            } else if (currentFilter === 'lowstock') {
                matchesCategory = stock > 0 && stock <= 10;
            } else if (currentFilter === 'unit' && currentUnitFilter) {
                matchesCategory = unit === currentUnitFilter;
            }

            if (matchesQuery && matchesCategory) {
                card.classList.remove('d-none');
                visibleCount++;
            } else {
                card.classList.add('d-none');
            }
        });

        const noMatchEl = document.getElementById('noSearchMatch');
        if (noMatchEl) {
            if (visibleCount === 0 && cards.length > 0) {
                noMatchEl.classList.remove('d-none');
            } else {
                noMatchEl.classList.add('d-none');
            }
        }

        const countBadge = document.getElementById('productsCountBadge');
        if (countBadge) countBadge.innerText = `${visibleCount} products`;
    }

    function applySorting() {
        const sortVal = document.getElementById('sortSelect').value;
        const grid = document.getElementById('productsGrid');
        const cards = Array.from(grid.querySelectorAll('.product-card-wrapper'));

        cards.sort((a, b) => {
            if (sortVal === 'name_asc') return a.dataset.name.localeCompare(b.dataset.name);
            if (sortVal === 'name_desc') return b.dataset.name.localeCompare(a.dataset.name);
            if (sortVal === 'price_low') return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
            if (sortVal === 'price_high') return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
            if (sortVal === 'stock_high') return parseFloat(b.dataset.stock) - parseFloat(a.dataset.stock);
            return 0;
        });

        cards.forEach(card => grid.appendChild(card));
    }

    // 4. Cart Operations
    function handleProductClick(id, name, price, stock, unit) {
        if (stock <= 0) {
            playChime('warn');
            Swal.fire({
                icon: 'warning',
                title: 'Out of Stock',
                text: `${name} is currently out of stock. Record stock-in or production before selling.`,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500
            });
            return;
        }

        let existing = cart.find(i => i.id === id);
        if (existing) {
            if (existing.qty + 1 > stock) {
                playChime('warn');
                Swal.fire({
                    icon: 'warning',
                    title: 'Stock Limit Reached',
                    text: `Cannot add more than ${stock} ${unit}s of ${name}.`,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500
                });
                return;
            }
            existing.qty++;
        } else {
            cart.push({ id, name, price, stock, unit, qty: 1 });
        }

        playChime('add');
        renderCart();
    }

    function changeCartItemQty(index, newQty) {
        if (newQty <= 0) {
            cart.splice(index, 1);
        } else {
            const item = cart[index];
            if (newQty > item.stock) {
                playChime('warn');
                Swal.fire({
                    icon: 'warning',
                    title: 'Stock Limit Reached',
                    text: `Available stock for ${item.name} is ${item.stock} ${item.unit}.`,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500
                });
                item.qty = item.stock;
            } else {
                item.qty = newQty;
            }
        }
        renderCart();
    }

    function removeCartItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function confirmResetCart() {
        if (cart.length === 0) return;
        Swal.fire({
            title: 'Clear Active Ticket?',
            text: 'Are you sure you want to discard all items currently in this order?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f46a6a',
            cancelButtonColor: '#74788d',
            confirmButtonText: 'Yes, Clear'
        }).then((result) => {
            if (result.isConfirmed) {
                cart = [];
                document.getElementById('discountAmountInput').value = 0;
                document.getElementById('customerRefInput').value = '';
                renderCart();
            }
        });
    }

    // 5. Cart Rendering & In-Cart Badges
    function renderCart() {
        const listEl = document.getElementById('cartItemsList');
        const emptyEl = document.getElementById('emptyCartView');
        const checkoutBtn = document.getElementById('checkoutBtn');
        const countBadge = document.getElementById('cartItemCountBadge');

        // Update In-Cart Badges & Selection state on Catalog Cards
        document.querySelectorAll('.pos-product-card').forEach(c => c.classList.remove('is-selected'));
        document.querySelectorAll('.pos-cart-in-badge').forEach(b => {
            b.style.display = 'none';
            b.querySelector('.count').innerText = '0';
        });
        document.querySelectorAll('.pos-thumb-overlay').forEach(o => o.style.display = 'none');

        cart.forEach(item => {
            const badge = document.getElementById(`badge-prod-${item.id}`);
            const overlay = document.getElementById(`overlay-prod-${item.id}`);
            const cardWrapper = document.querySelector(`.product-card-wrapper[data-id="${item.id}"]`);
            if (badge) {
                badge.style.display = 'inline-flex';
                badge.querySelector('.count').innerText = item.qty;
            }
            if (overlay) {
                overlay.style.display = 'block';
            }
            if (cardWrapper) {
                const card = cardWrapper.querySelector('.pos-product-card');
                if (card) card.classList.add('is-selected');
            }
        });

        const totalItemsCount = cart.reduce((acc, i) => acc + i.qty, 0);
        countBadge.innerText = `${totalItemsCount} ${totalItemsCount === 1 ? 'item' : 'items'}`;

        if (cart.length === 0) {
            emptyEl.style.display = 'block';
            listEl.innerHTML = '';
            checkoutBtn.disabled = true;
        } else {
            emptyEl.style.display = 'none';
            checkoutBtn.disabled = false;

            let html = '';
            cart.forEach((item, index) => {
                const lineTotal = item.price * item.qty;
                html += `
                <div class="pos-cart-item">
                    <div class="pos-cart-item-info">
                        <div>
                            <div class="pos-cart-item-name">${item.name}</div>
                            <span class="pos-cart-item-rate">₦${item.price.toLocaleString(undefined, {minimumFractionDigits: 2})} / ${item.unit}</span>
                        </div>
                        <div class="pos-cart-item-subtotal">₦${lineTotal.toLocaleString(undefined, {minimumFractionDigits: 2})}</div>
                    </div>
                    <div class="pos-cart-item-actions">
                        <div class="pos-qty-stepper">
                            <button type="button" class="pos-qty-btn ${item.qty === 1 ? 'del' : ''}" onclick="changeCartItemQty(${index}, ${item.qty - 1})">
                                <i class="bx ${item.qty === 1 ? 'bx-trash' : 'bx-minus'} font-size-14"></i>
                            </button>
                            <input type="number" class="pos-qty-input" value="${item.qty}" min="1" max="${item.stock}" onchange="changeCartItemQty(${index}, parseInt(this.value) || 1)">
                            <button type="button" class="pos-qty-btn" onclick="changeCartItemQty(${index}, ${item.qty + 1})" ${item.qty >= item.stock ? 'disabled' : ''}>
                                <i class="bx bx-plus font-size-14"></i>
                            </button>
                        </div>
                        <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2" onclick="removeCartItem(${index})" title="Remove item">
                            <i class="bx bx-x font-size-18"></i>
                        </button>
                    </div>
                </div>`;
            });
            listEl.innerHTML = html;
        }

        recalculateTotals();
    }

    // 6. Financial Calculations & Discounts
    function recalculateTotals() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        let discount = parseFloat(document.getElementById('discountAmountInput').value) || 0;

        if (discount > subtotal) {
            discount = subtotal;
            document.getElementById('discountAmountInput').value = discount;
        }

        const payable = Math.max(0, subtotal - discount);

        document.getElementById('summarySubtotal').innerText = '₦' + subtotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('discountDeductionLabel').innerText = '-₦' + discount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('summaryTotal').innerText = '₦' + payable.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('checkoutBtnTotal').innerText = '₦' + payable.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function applyPresetDiscount(val, btn, isFlat = false) {
        document.querySelectorAll('.pos-discount-pill').forEach(p => p.classList.remove('active'));
        if (btn) btn.classList.add('active');

        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        let discount = 0;

        if (isFlat) {
            discount = Math.min(subtotal, val);
        } else {
            discount = Math.round((subtotal * (val / 100)) * 100) / 100;
        }

        document.getElementById('discountAmountInput').value = discount;
        recalculateTotals();
    }

    function onCustomDiscountInput() {
        document.querySelectorAll('.pos-discount-pill').forEach(p => p.classList.remove('active'));
        recalculateTotals();
    }

    // 7. Checkout Modal Handling
    function openCheckoutModal() {
        if (cart.length === 0) return;

        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        const discount = parseFloat(document.getElementById('discountAmountInput').value) || 0;
        const payable = Math.max(0, subtotal - discount);
        const totalItems = cart.reduce((sum, i) => sum + i.qty, 0);

        document.getElementById('modalPayableDisplay').innerText = '₦' + payable.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('modalItemSummary').innerText = `${cart.length} Products • ${totalItems} Total Units`;

        // Default tender to exact amount
        document.getElementById('cashTenderedInput').value = payable;
        calculateChange();

        // Prefill Customer Note if customerRef was entered
        const custRef = document.getElementById('customerRefInput').value;
        if (custRef && !document.getElementById('saleNotesInput').value) {
            document.getElementById('saleNotesInput').value = 'Customer/Ref: ' + custRef;
        }

        new bootstrap.Modal(document.getElementById('checkoutModal')).show();
    }

    function onPaymentMethodChanged() {
        const method = document.querySelector('input[name="payMethod"]:checked').value;
        const cashSection = document.getElementById('cashCalculatorSection');
        if (cashSection) {
            cashSection.style.display = (method === 'Cash') ? 'block' : 'none';
        }
    }

    function setExactTender() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        const discount = parseFloat(document.getElementById('discountAmountInput').value) || 0;
        const payable = Math.max(0, subtotal - discount);
        document.getElementById('cashTenderedInput').value = payable;
        calculateChange();
    }

    function addTender(amount) {
        let current = parseFloat(document.getElementById('cashTenderedInput').value) || 0;
        document.getElementById('cashTenderedInput').value = current + amount;
        calculateChange();
    }

    function calculateChange() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        const discount = parseFloat(document.getElementById('discountAmountInput').value) || 0;
        const payable = Math.max(0, subtotal - discount);
        const tendered = parseFloat(document.getElementById('cashTenderedInput').value) || 0;
        const change = tendered - payable;

        const changeBox = document.getElementById('changeDueBox');
        const label = document.getElementById('changeAmountLabel');

        if (change >= 0) {
            changeBox.className = 'p-2 rounded text-center fw-bold bg-white border border-success';
            label.className = 'h5 mb-0 text-success fw-extrabold';
            label.innerText = '₦' + change.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        } else {
            changeBox.className = 'p-2 rounded text-center fw-bold bg-white border border-danger';
            label.className = 'h5 mb-0 text-danger fw-extrabold';
            label.innerText = 'Short: -₦' + Math.abs(change).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }

    // 8. Execute Sale Request
    async function executeSaleProcess() {
        const confirmBtn = document.getElementById('confirmSaleBtn');
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing Sale...';

        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        const discount = parseFloat(document.getElementById('discountAmountInput').value) || 0;
        const payable = Math.max(0, subtotal - discount);
        const method = document.querySelector('input[name="payMethod"]:checked').value;
        const notes = document.getElementById('saleNotesInput').value;
        const tendered = parseFloat(document.getElementById('cashTenderedInput').value) || payable;

        const payload = {
            payment_method: method,
            cart_items: cart.map(i => ({ product_id: i.id, quantity: i.qty, price: i.price })),
            total_amount: subtotal,
            discount_amount: discount,
            payable_amount: payable,
            notes: notes
        };

        try {
            const response = await fetch(PROCESS_SALE_ENDPOINT, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (data.success) {
                playChime('success');

                // Dismiss Checkout Modal
                const checkoutModalEl = document.getElementById('checkoutModal');
                const checkoutModalInst = bootstrap.Modal.getInstance(checkoutModalEl);
                if (checkoutModalInst) checkoutModalInst.hide();

                // Store completed transaction receipt data
                lastCompletedSale = {
                    reference_no: data.reference_no,
                    date: data.sale?.created_at || (new Date()).toLocaleString(),
                    seller: data.sale?.seller_name || 'Staff',
                    items: [...cart],
                    subtotal: subtotal,
                    discount: discount,
                    total: payable,
                    method: method,
                    tendered: tendered,
                    change: Math.max(0, tendered - payable)
                };

                // Deduct local card stocks immediately
                cart.forEach(item => {
                    const card = document.querySelector(`.product-card-wrapper[data-id="${item.id}"]`);
                    if (card) {
                        let newStock = parseFloat(card.dataset.stock) - item.qty;
                        card.dataset.stock = newStock;
                        const badge = card.querySelector('.pos-stock-badge');
                        if (badge) {
                            if (newStock <= 0) {
                                badge.className = 'pos-stock-badge out-stock';
                                badge.innerText = 'Out of Stock';
                                card.querySelector('.pos-product-card').classList.add('out-of-stock');
                            } else if (newStock <= 10) {
                                badge.className = 'pos-stock-badge low-stock';
                                badge.innerText = `${newStock} left`;
                            } else {
                                badge.className = 'pos-stock-badge in-stock';
                                badge.innerText = `${newStock} in stock`;
                            }
                        }
                    }
                });

                // Display Receipt Modal
                populateReceiptModal(lastCompletedSale);
                new bootstrap.Modal(document.getElementById('receiptModal')).show();

                // Reset Active Ticket
                cart = [];
                document.getElementById('discountAmountInput').value = 0;
                document.getElementById('customerRefInput').value = '';
                document.getElementById('saleNotesInput').value = '';
                renderCart();

            } else {
                playChime('warn');
                Swal.fire({
                    icon: 'error',
                    title: 'Sale Failed',
                    text: data.message || 'Unable to process transaction.'
                });
            }
        } catch (error) {
            playChime('warn');
            Swal.fire({
                icon: 'error',
                title: 'Network / Server Error',
                text: 'An error occurred while communicating with the POS service.'
            });
        } finally {
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="bx bx-check-circle me-1"></i> Complete & Print Receipt';
        }
    }

    // 9. Receipt Presentation & Printing
    function populateReceiptModal(sale) {
        document.getElementById('rcptReference').innerText = sale.reference_no;
        document.getElementById('rcptDate').innerText = sale.date;
        document.getElementById('rcptCashier').innerText = sale.seller;
        document.getElementById('rcptSubtotal').innerText = '₦' + sale.subtotal.toLocaleString(undefined, { minimumFractionDigits: 2 });

        if (sale.discount > 0) {
            document.getElementById('rcptDiscountRow').style.display = 'flex';
            document.getElementById('rcptDiscount').innerText = '-₦' + sale.discount.toLocaleString(undefined, { minimumFractionDigits: 2 });
        } else {
            document.getElementById('rcptDiscountRow').style.display = 'none';
        }

        document.getElementById('rcptTotal').innerText = '₦' + sale.total.toLocaleString(undefined, { minimumFractionDigits: 2 });
        document.getElementById('rcptMethod').innerText = sale.method;

        if (sale.method === 'Cash') {
            document.getElementById('rcptTenderRow').style.display = 'flex';
            document.getElementById('rcptChangeRow').style.display = 'flex';
            document.getElementById('rcptTendered').innerText = '₦' + sale.tendered.toLocaleString(undefined, { minimumFractionDigits: 2 });
            document.getElementById('rcptChange').innerText = '₦' + sale.change.toLocaleString(undefined, { minimumFractionDigits: 2 });
        } else {
            document.getElementById('rcptTenderRow').style.display = 'none';
            document.getElementById('rcptChangeRow').style.display = 'none';
        }

        let itemsHtml = '';
        sale.items.forEach(item => {
            const line = item.price * item.qty;
            itemsHtml += `
            <tr>
                <td>${item.name}</td>
                <td class="text-center">${item.qty}</td>
                <td class="text-end">₦${line.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
            </tr>`;
        });
        document.getElementById('rcptItemsBody').innerHTML = itemsHtml;
    }

    function printReceiptTicket() {
        const receiptHtml = document.getElementById('printableReceiptArea').outerHTML;
        const printWindow = window.open('', '_blank', 'width=420,height=600');
        printWindow.document.write(`
            <html>
                <head>
                    <title>POS Receipt - ${document.getElementById('rcptReference').innerText}</title>
                    <style>
                        body { font-family: 'Courier New', Courier, monospace; width: 80mm; margin: 0 auto; padding: 10px; color: #000; }
                        table { width: 100%; border-collapse: collapse; }
                        .text-center { text-align: center; }
                        .text-end { text-align: right; }
                        .d-flex { display: flex; }
                        .justify-content-between { justify-content: space-between; }
                        .small { font-size: 12px; }
                        .fw-bold { font-weight: bold; }
                        @media print {
                            @page { margin: 0; size: 80mm auto; }
                            body { margin: 0; padding: 6px; }
                        }
                    </style>
                </head>
                <body>
                    ${receiptHtml}
                    <script>
                        window.onload = function() {
                            window.print();
                            setTimeout(() => { window.close(); }, 600);
                        };
                    <\/script>
                </body>
            </html>
        `);
        printWindow.document.close();
    }

    function startFreshSale() {
        const receiptModalEl = document.getElementById('receiptModal');
        const inst = bootstrap.Modal.getInstance(receiptModalEl);
        if (inst) inst.hide();
        document.getElementById('posSearchInput').focus();
    }

    // 10. Global Keyboard Shortcuts
    document.addEventListener('keydown', function(e) {
        // [F2] or [/] Focus Product Search
        if (e.key === 'F2' || (e.key === '/' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA')) {
            e.preventDefault();
            const searchInput = document.getElementById('posSearchInput');
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        }

        // [F4] Open Checkout
        if (e.key === 'F4') {
            e.preventDefault();
            const checkoutModalEl = document.getElementById('checkoutModal');
            const isModalOpen = checkoutModalEl && checkoutModalEl.classList.contains('show');
            if (!isModalOpen && cart.length > 0) {
                openCheckoutModal();
            }
        }
    });
</script>
@endsection
