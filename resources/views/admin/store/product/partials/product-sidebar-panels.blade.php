{{-- =====================================================
     STORE PRODUCT SIDEBAR PANELS PARTIAL
     (Visibility Controls, ERP Master Sync, Customer Share Link)
     ===================================================== --}}

{{-- 1. STOREFRONT PUBLICATION & VISIBILITY CARD --}}
<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden store-panel-card"
     style="border: 1px solid rgba(226, 232, 240, 0.8) !important; border-top: 3px solid #10b981 !important;">
    
    <div class="card-header bg-white border-bottom border-light py-3 px-4">
        <div class="d-flex align-items-center gap-2">
            <div class="avatar-xs rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                <i class="bx bx-toggle-right font-size-18"></i>
            </div>
            <h6 class="card-title mb-0 fw-bold text-dark font-size-15">Storefront Visibility Controls</h6>
        </div>
    </div>

    <div class="card-body p-4">
        <form action="{{ url('/admin/store/products/' . $product->slug . '/status') }}"
              method="POST">
            @csrf
            @method('PATCH')

            {{-- PUBLISHED TOGGLE --}}
            <div class="p-3 rounded-3 mb-3 border {{ $storeProduct->is_published ? 'border-success-subtle bg-success-subtle bg-opacity-25' : 'bg-light border-light' }}">
                <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-between ps-0">
                    <label class="form-check-label cursor-pointer pe-3" for="is_published">
                        <strong class="d-block font-size-13 text-dark mb-0.5">
                            Public Storefront Visibility
                        </strong>
                        <small class="text-muted font-size-11 d-block">
                            When published, customers can browse, view details, and add this bake to their online cart.
                        </small>
                    </label>
                    <input class="form-check-input ms-0 cursor-pointer font-size-18"
                           type="checkbox"
                           name="is_published"
                           value="1"
                           id="is_published"
                           {{ $storeProduct->is_published ? 'checked' : '' }}>
                </div>
            </div>

            {{-- FEATURED TOGGLE --}}
            <div class="p-3 rounded-3 mb-4 border {{ $storeProduct->is_featured ? 'border-warning-subtle bg-warning-subtle bg-opacity-25' : 'bg-light border-light' }}">
                <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-between ps-0">
                    <label class="form-check-label cursor-pointer pe-3" for="is_featured">
                        <strong class="d-block font-size-13 text-dark mb-0.5 d-flex align-items-center gap-1">
                            <i class="bx bxs-star text-warning font-size-14"></i>
                            Homepage Featured Spotlight
                        </strong>
                        <small class="text-muted font-size-11 d-block">
                            Highlights this product on the home page hero carousel and featured treats collection.
                        </small>
                    </label>
                    <input class="form-check-input ms-0 cursor-pointer font-size-18"
                           type="checkbox"
                           name="is_featured"
                           value="1"
                           id="is_featured"
                           {{ $storeProduct->is_featured ? 'checked' : '' }}>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 font-size-13 fw-semibold shadow-xs d-inline-flex align-items-center justify-content-center gap-2">
                <i class="bx bx-check font-size-16"></i>
                <span>Update Visibility Status</span>
            </button>
        </form>
    </div>
</div>

{{-- 2. ERP MASTER SYNC CARD --}}
<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden store-panel-card"
     style="border: 1px solid rgba(226, 232, 240, 0.8) !important; border-top: 3px solid #3b82f6 !important;">
    
    <div class="card-header bg-white border-bottom border-light py-3 px-4 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <div class="avatar-xs rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                <i class="bx bx-sync font-size-18"></i>
            </div>
            <h6 class="card-title mb-0 fw-bold text-dark font-size-15">ERP Product Master Synchronization</h6>
        </div>
        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-size-10 px-2.5 py-1 d-inline-flex align-items-center gap-1">
            <span class="store-pulse-dot bg-success" style="width: 6px; height: 6px;"></span> Live Sync
        </span>
    </div>

    <div class="card-body p-4">
        {{-- MASTER PRODUCT AVATAR & BASIC DETAILS --}}
        <div class="d-flex align-items-center gap-3 mb-3.5 p-3 rounded-3 bg-light border border-light">
            @if($storeProduct->primaryImage)
                <img src="{{ asset($storeProduct->primaryImage->image_path) }}"
                     alt="{{ $product->name }}"
                     class="rounded-3 shadow-xs"
                     style="width: 48px; height: 48px; object-fit: cover;">
            @else
                <div class="rounded-3 bg-white border d-flex align-items-center justify-content-center text-primary shadow-xs"
                     style="width: 48px; height: 48px;">
                    <i class="bx bx-package font-size-22"></i>
                </div>
            @endif

            <div class="overflow-hidden">
                <h6 class="mb-0 fw-bold text-dark text-truncate">{{ $product->name }}</h6>
                <span class="text-muted font-size-11 d-block">ERP SKU / ID: #{{ $product->id }}</span>
                <span class="badge bg-white text-secondary border font-size-10 text-uppercase fw-semibold mt-0.5">
                    Unit: {{ $product->sales_unit }}
                </span>
            </div>
        </div>

        {{-- METRICS LIST --}}
        <div class="d-flex flex-column gap-3 divide-y divide-gray-100">
            <div class="d-flex align-items-center justify-content-between pt-1">
                <span class="text-muted font-size-13">Retail Selling Price</span>
                <span class="fw-bold text-dark font-size-17" style="letter-spacing: -0.01em;">
                    ₦{{ number_format($product->selling_price, 2) }}
                </span>
            </div>

            <div class="d-flex align-items-center justify-content-between pt-2">
                <span class="text-muted font-size-13">Stock Available</span>
                <div class="text-end">
                    <span class="fw-bold text-dark font-size-14">
                        {{ number_format($product->stock_on_hand, 2) }}
                    </span>
                    <span class="text-muted font-size-11 text-uppercase">{{ $product->sales_unit }}</span>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between pt-2">
                <span class="text-muted font-size-13">Inventory Status</span>
                @if($product->stock_on_hand <= 0)
                    <span class="badge bg-danger-subtle text-danger font-size-11 rounded-pill px-2.5 py-0.5 border border-danger-subtle">
                        Out of Stock
                    </span>
                @elseif($product->stock_on_hand <= 5)
                    <span class="badge bg-warning-subtle text-warning font-size-11 rounded-pill px-2.5 py-0.5 border border-warning-subtle">
                        Low Stock
                    </span>
                @else
                    <span class="badge bg-success-subtle text-success font-size-11 rounded-pill px-2.5 py-0.5 border border-success-subtle">
                        In Stock
                    </span>
                @endif
            </div>

            <div class="d-flex align-items-center justify-content-between pt-2">
                <span class="text-muted font-size-13">ERP Operational Status</span>
                @if($product->is_active)
                    <span class="badge bg-success-subtle text-success font-size-11 rounded-pill px-2.5 py-0.5 border border-success-subtle">
                        Active in ERP
                    </span>
                @else
                    <span class="badge bg-danger-subtle text-danger font-size-11 rounded-pill px-2.5 py-0.5 border border-danger-subtle">
                        Inactive in ERP
                    </span>
                @endif
            </div>
        </div>

        <div class="mt-3.5 pt-3 border-top border-light font-size-11 text-muted">
            <i class="bx bx-lock-alt me-1 text-secondary"></i>
            Core price and inventory quantities are managed in Bakery ERP inventory and reflect live on the storefront.
        </div>
    </div>
</div>

{{-- 3. STOREFRONT DEEP LINK CARD --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden store-panel-card"
     style="border: 1px solid rgba(226, 232, 240, 0.8) !important; border-top: 3px solid #64748b !important;">
    
    <div class="card-header bg-white border-bottom border-light py-3 px-4">
        <div class="d-flex align-items-center gap-2">
            <div class="avatar-xs rounded-3 bg-secondary-subtle text-secondary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                <i class="bx bx-link font-size-18"></i>
            </div>
            <h6 class="card-title mb-0 fw-bold text-dark font-size-15">Customer Share URL</h6>
        </div>
    </div>

    <div class="card-body p-4">
        <div class="mb-3">
            <div class="input-group">
                <input type="text"
                       id="storeProductDirectUrl"
                       class="form-control form-control-sm rounded-start-3 font-size-12 bg-light border-end-0 py-2 px-3"
                       value="{{ url('/products/' . $product->slug) }}"
                       readonly>
                <button type="button"
                        class="btn btn-primary btn-sm rounded-end-3 px-3 fw-semibold"
                        id="copyStoreLinkBtn"
                        title="Copy link to clipboard">
                    <i class="bx bx-copy font-size-15"></i>
                    <span class="font-size-12 ms-1">Copy</span>
                </button>
            </div>
            <div id="copyFeedback" class="font-size-11 text-success mt-1.5 d-none">
                <i class="bx bx-check me-0.5"></i> Copied to clipboard!
            </div>
        </div>

        <a href="{{ url('/products/' . $product->slug) }}"
           target="_blank"
           rel="noopener noreferrer"
           class="btn btn-light w-100 rounded-pill py-2 font-size-12 fw-semibold border text-dark d-inline-flex align-items-center justify-content-center gap-2 shadow-xs">
            <i class="bx bx-globe font-size-15 text-primary"></i>
            <span>Open Customer View</span>
            <i class="bx bx-link-external font-size-12 opacity-60"></i>
        </a>

        <div class="mt-3 font-size-11 text-muted text-center">
            @if($storeProduct->is_published)
                <span class="text-success fw-medium"><i class="bx bx-check-circle me-1"></i> Publicly accessible to all online shoppers.</span>
            @else
                <span class="text-warning fw-medium"><i class="bx bx-lock-alt me-1"></i> Accessible publicly once published.</span>
            @endif
        </div>
    </div>
</div>
