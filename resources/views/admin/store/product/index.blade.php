@extends('admin.layout.dashboard')

@section('content')

<div class="container-fluid py-3">

    {{-- =====================================================
         PAGE HEADER & EXECUTIVE ACTIONS
         ===================================================== --}}
    <div class="row align-items-center mb-4 g-3">
        <div class="col-lg-7">
            <div class="d-flex align-items-center gap-3">
                <div class="store-hero-avatar flex-shrink-0">
                    <div class="rounded-4 text-white shadow-sm d-flex align-items-center justify-content-center"
                         style="width: 56px; height: 56px; background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                        <i class="bx bx-store-alt font-size-28"></i>
                    </div>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                        <h4 class="mb-0 fw-bold text-dark" style="letter-spacing: -0.02em;">Storefront Merchandising</h4>
                    </div>
                    <p class="text-muted mb-0 font-size-13">
                        Curate your public bakery catalog, manage high-resolution photography, copywriting, and live customer visibility.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-5 text-md-end">
            <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                <a href="{{ url('/admin/pos') }}" class="btn btn-outline-secondary px-3.5 py-2 rounded-pill shadow-xs fw-medium d-inline-flex align-items-center gap-2">
                    <i class="bx bx-calculator font-size-16"></i>
                    <span>POS Terminal</span>
                </a>

                <a href="{{ url('/') }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="btn btn-primary px-2 py-2 rounded-pill shadow-sm fw-semibold d-inline-flex align-items-center gap-2">
                    <i class="bx bx-globe font-size-14"></i>
                    <span>View Live Store</span>
                    <i class="bx bx-link-external font-size-12 opacity-75"></i>
                </a>
            </div>
        </div>
    </div>


    {{-- =====================================================
         METRIC KPI CARDS
         ===================================================== --}}
    <div class="row g-3 mb-4">

        {{-- 1. TOTAL ERP PRODUCTS --}}
        <div class="col-xxl col-lg-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 store-kpi-card" style="background: #ffffff; border: 1px solid rgba(226, 232, 240, 0.8) !important;">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted text-uppercase fw-semibold font-size-11" style="letter-spacing: 0.5px;">Master ERP Catalog</span>
                        <div class="avatar-xs rounded-3 d-flex align-items-center justify-content-center text-primary" style="background: rgba(79, 70, 229, 0.1); width: 34px; height: 34px;">
                            <i class="bx bx-package font-size-18"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <h3 class="mb-0 fw-bold text-dark">{{ number_format($totalErpProducts) }}</h3>
                        <span class="text-muted font-size-12">Items</span>
                    </div>
                    <div class="mt-2 pt-2 border-top border-light d-flex align-items-center justify-content-between font-size-11 text-muted">
                        <span>Internal bakery skus</span>
                        <span class="fw-medium text-dark">Base items</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. PUBLISHED PRODUCTS --}}
        <div class="col-xxl col-lg-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 store-kpi-card" style="background: #ffffff; border: 1px solid rgba(226, 232, 240, 0.8) !important;">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted text-uppercase fw-semibold font-size-11" style="letter-spacing: 0.5px;">Active Online</span>
                        <div class="avatar-xs rounded-3 d-flex align-items-center justify-content-center text-success" style="background: rgba(16, 185, 129, 0.12); width: 34px; height: 34px;">
                            <i class="bx bx-show font-size-18"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <h3 class="mb-0 fw-bold text-success">{{ number_format($publishedProducts) }}</h3>
                        @php
                            $publishedPct = $totalErpProducts > 0 ? round(($publishedProducts / $totalErpProducts) * 100) : 0;
                        @endphp
                        <span class="badge bg-success-subtle text-success rounded-pill font-size-11 px-2">
                            {{ $publishedPct }}% of catalog
                        </span>
                    </div>
                    <div class="mt-2 pt-2 border-top border-light d-flex align-items-center justify-content-between font-size-11 text-muted">
                        <span class="d-inline-flex align-items-center gap-1 text-success">
                            <span class="store-pulse-dot bg-success"></span> Visible to shoppers
                        </span>
                        <span class="fw-semibold text-success">Live</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. DRAFTS --}}
        <div class="col-xxl col-lg-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 store-kpi-card" style="background: #ffffff; border: 1px solid rgba(226, 232, 240, 0.8) !important;">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted text-uppercase fw-semibold font-size-11" style="letter-spacing: 0.5px;">Store Drafts</span>
                        <div class="avatar-xs rounded-3 d-flex align-items-center justify-content-center text-warning" style="background: rgba(245, 158, 11, 0.12); width: 34px; height: 34px;">
                            <i class="bx bx-edit font-size-18"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <h3 class="mb-0 fw-bold text-warning">{{ number_format($draftProducts) }}</h3>
                        <span class="text-muted font-size-12">Unpublished</span>
                    </div>
                    <div class="mt-2 pt-2 border-top border-light d-flex align-items-center justify-content-between font-size-11 text-muted">
                        <span>Prepared in backend</span>
                        <span class="badge bg-warning-subtle text-warning font-size-10">Hidden</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. FEATURED PRODUCTS --}}
        <div class="col-xxl col-lg-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 store-kpi-card" style="background: #ffffff; border: 1px solid rgba(226, 232, 240, 0.8) !important;">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted text-uppercase fw-semibold font-size-11" style="letter-spacing: 0.5px;">Featured Showcase</span>
                        <div class="avatar-xs rounded-3 d-flex align-items-center justify-content-center text-amber" style="background: rgba(217, 119, 6, 0.12); width: 34px; height: 34px; color: #d97706;">
                            <i class="bx bxs-star font-size-18"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <h3 class="mb-0 fw-bold" style="color: #d97706;">{{ number_format($featuredProducts) }}</h3>
                        <span class="text-muted font-size-12">Spotlights</span>
                    </div>
                    <div class="mt-2 pt-2 border-top border-light d-flex align-items-center justify-content-between font-size-11 text-muted">
                        <span>Homepage highlight bakes</span>
                        <span class="fw-semibold text-warning">VIP</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 5. NOT LISTED --}}
        <div class="col-xxl col-lg-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 store-kpi-card" style="background: #ffffff; border: 1px solid rgba(226, 232, 240, 0.8) !important;">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted text-uppercase fw-semibold font-size-11" style="letter-spacing: 0.5px;">Unlisted ERP Items</span>
                        <div class="avatar-xs rounded-3 d-flex align-items-center justify-content-center text-secondary" style="background: rgba(100, 116, 139, 0.12); width: 34px; height: 34px;">
                            <i class="bx bx-hide font-size-18"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <h3 class="mb-0 fw-bold text-secondary">{{ number_format($notListedProducts) }}</h3>
                        <span class="text-muted font-size-12">Available to add</span>
                    </div>
                    <div class="mt-2 pt-2 border-top border-light d-flex align-items-center justify-content-between font-size-11 text-muted">
                        <span>Not in storefront DB</span>
                        <span class="fw-medium text-primary">Quick Add</span>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- =====================================================
         MAIN CATALOG CARD WITH STANDARD TABLE STYLE & PROPER PAGINATION
         ===================================================== --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="border: 1px solid rgba(226, 232, 240, 0.8) !important;">
                
                {{-- CARD HEADER WITH FILTER TABS --}}
                <div class="card-header bg-white border-bottom border-light py-3.5 px-4">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div>
                            <h5 class="card-title mb-1 fw-bold text-dark">Bakery Merchandising Registry</h5>
                            <p class="text-muted mb-0 font-size-12">
                                Manage individual product visibility, high-resolution media, pricing overrides, and online store publication.
                            </p>
                        </div>

                        {{-- QUICK FILTER PILLS WITH PROPER SPACING AND FUNCTIONAL ONCLICK --}}
                        <div class="store-filter-pills-wrap d-flex align-items-center gap-2 flex-wrap">
                            <button type="button" class="btn store-filter-btn rounded-pill px-3.5 py-1.5 font-size-12 fw-medium active" data-filter="all" onclick="filterStoreProducts('all', this)">
                                All Products <span class="badge bg-secondary-subtle text-dark rounded-pill ms-1.5 font-size-10">{{ $products->count() }}</span>
                            </button>
                            <button type="button" class="btn store-filter-btn rounded-pill px-3.5 py-1.5 font-size-12 fw-medium" data-filter="published" onclick="filterStoreProducts('published', this)">
                                <i class="bx bxs-circle me-1 font-size-8 text-success"></i>Published <span class="badge bg-success-subtle text-success rounded-pill ms-1.5 font-size-10">{{ $publishedProducts }}</span>
                            </button>
                            <button type="button" class="btn store-filter-btn rounded-pill px-3.5 py-1.5 font-size-12 fw-medium" data-filter="draft" onclick="filterStoreProducts('draft', this)">
                                <i class="bx bx-edit-alt me-1 font-size-11 text-warning"></i>Drafts <span class="badge bg-warning-subtle text-warning rounded-pill ms-1.5 font-size-10">{{ $draftProducts }}</span>
                            </button>
                            <button type="button" class="btn store-filter-btn rounded-pill px-3.5 py-1.5 font-size-12 fw-medium" data-filter="not-listed" onclick="filterStoreProducts('not-listed', this)">
                                <i class="bx bx-hide me-1 font-size-11 text-secondary"></i>Not Listed <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1.5 font-size-10">{{ $notListedProducts }}</span>
                            </button>
                            <button type="button" class="btn store-filter-btn rounded-pill px-3.5 py-1.5 font-size-12 fw-medium" data-filter="featured" onclick="filterStoreProducts('featured', this)">
                                <i class="bx bxs-star me-1 font-size-11 text-warning"></i>Featured <span class="badge bg-amber-subtle text-dark rounded-pill ms-1.5 font-size-10" style="background:#fef3c7;">{{ $featuredProducts }}</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- TABLE CONTAINER WITH STANDARD METRONIC/SKOTE DATATABLE STYLE --}}
                <div class="card-body p-3.5">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover align-middle table-bordered dt-responsive nowrap w-100 store-datatable">
                            <thead class="table-light text-muted text-uppercase font-size-11" style="letter-spacing: 0.5px;">
                                <tr>
                                    <th class="ps-3.5 py-3" style="min-width: 270px;">Product & Classification</th>
                                    <th class="py-3" style="min-width: 130px;">Selling Price</th>
                                    <th class="py-3" style="min-width: 140px;">Stock on Hand</th>
                                    <th class="py-3 text-center" style="min-width: 130px;">Store Status</th>
                                    <th class="py-3 text-center" style="min-width: 110px;">Showcase</th>
                                    <th class="pe-3.5 py-3 text-end" style="min-width: 160px;">Management</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    @php
                                        $storeProduct = $product->storeProduct;
                                        $isListed = !empty($storeProduct);
                                        $isPublished = $isListed && $storeProduct->is_published;
                                        $isDraft = $isListed && !$storeProduct->is_published;
                                        $isFeatured = $isListed && $storeProduct->is_featured;
                                        $primaryImg = $storeProduct?->primaryImage;
                                        
                                        $statusSlug = 'not-listed';
                                        $statusText = 'Not Listed';
                                        if ($isPublished) {
                                            $statusSlug = 'published';
                                            $statusText = 'Published';
                                        } elseif ($isDraft) {
                                            $statusSlug = 'draft';
                                            $statusText = 'Draft';
                                        }

                                        $featuredSlug = $isFeatured ? 'featured' : 'standard';
                                        $stockQty = (float) $product->stock_on_hand;
                                    @endphp
                                    <tr data-status="{{ $statusSlug }}" data-featured="{{ $featuredSlug }}" class="store-row-item">
                                        
                                        {{-- 1. PRODUCT & CLASSIFICATION --}}
                                        <td class="ps-3.5 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="position-relative store-product-thumb-container flex-shrink-0">
                                                    @if($primaryImg && file_exists(public_path($primaryImg->image_path)))
                                                        <img src="{{ asset($primaryImg->image_path) }}"
                                                             alt="{{ $product->name }}"
                                                             class="rounded-3 shadow-xs store-product-thumb"
                                                             style="width: 48px; height: 48px; object-fit: cover; border: 1px solid rgba(203, 213, 225, 0.8);">
                                                    @elseif($primaryImg)
                                                        <img src="{{ asset($primaryImg->image_path) }}"
                                                             alt="{{ $product->name }}"
                                                             class="rounded-3 shadow-xs store-product-thumb"
                                                             style="width: 48px; height: 48px; object-fit: cover; border: 1px solid rgba(203, 213, 225, 0.8);">
                                                    @else
                                                        <div class="rounded-3 shadow-xs bg-light d-flex align-items-center justify-content-center text-muted border"
                                                             style="width: 48px; height: 48px;">
                                                            <i class="bx bx-image-alt font-size-24 text-secondary opacity-50"></i>
                                                        </div>
                                                    @endif

                                                    @if($isFeatured)
                                                        <span class="position-absolute -top-1 -end-1 badge rounded-circle bg-warning p-1 shadow-xs" title="Featured Bake" style="top: -5px; right: -5px; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center;">
                                                            <i class="bx bxs-star font-size-11 text-white"></i>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="overflow-hidden">
                                                    <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                                        <a href="{{ $isListed ? url('/admin/store/products/' . $product->slug . '/edit') : '#' }}"
                                                           class="fw-bold text-dark text-decoration-none store-product-title-link mb-0 font-size-14">
                                                            {{ $storeProduct?->store_title ?: $product->name }}
                                                        </a>
                                                        @if($storeProduct && $storeProduct->store_title && $storeProduct->store_title !== $product->name)
                                                            <span class="badge bg-light text-muted font-size-10 border" title="ERP Internal Title: {{ $product->name }}">
                                                                ERP: {{ Str::limit($product->name, 16) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mt-0.5 text-muted font-size-12">
                                                        <span class="badge bg-light text-secondary border font-size-10 text-uppercase fw-semibold px-2 py-0.5">
                                                            {{ strtoupper($product->sales_unit) }}
                                                        </span>
                                                        <span class="font-size-11 text-muted">ID: #{{ $product->id }}</span>
                                                        @if($product->slug)
                                                            <span class="text-muted font-size-11 d-none d-sm-inline opacity-75">/{{ Str::limit($product->slug, 18) }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- 2. PRICE --}}
                                        <td class="py-3">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark font-size-15" style="letter-spacing: -0.01em;">
                                                    ₦{{ number_format($product->selling_price, 2) }}
                                                </span>
                                                <span class="text-muted font-size-11">
                                                    per {{ strtolower($product->sales_unit) }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- 3. STOCK ON HAND --}}
                                        <td class="py-3">
                                            <div class="d-flex flex-column">
                                                <div class="d-flex align-items-center gap-1">
                                                    <span class="fw-semibold text-dark font-size-13">
                                                        {{ number_format($stockQty, 2) }}
                                                    </span>
                                                    <span class="text-muted font-size-11 text-uppercase">{{ $product->sales_unit }}</span>
                                                </div>
                                                @if($stockQty <= 0)
                                                    <span class="badge bg-danger-subtle text-danger font-size-10 rounded-pill px-2 py-0.5 mt-1 align-self-start border border-danger-subtle">
                                                        Out of Stock
                                                    </span>
                                                @elseif($stockQty <= 5)
                                                    <span class="badge bg-warning-subtle text-warning font-size-10 rounded-pill px-2 py-0.5 mt-1 align-self-start border border-warning-subtle">
                                                        Low Stock ({{ number_format($stockQty, 0) }})
                                                    </span>
                                                @else
                                                    <span class="badge bg-success-subtle text-success font-size-10 rounded-pill px-2 py-0.5 mt-1 align-self-start border border-success-subtle">
                                                        In Stock
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- 4. STORE STATUS --}}
                                        <td class="py-3 text-center">
                                            @if(!$isListed)
                                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2.5 py-1 font-size-11 border border-secondary-subtle d-inline-flex align-items-center gap-1">
                                                    <i class="bx bx-minus-circle font-size-12"></i> Not Listed
                                                </span>
                                            @elseif($isPublished)
                                                <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1 font-size-11 border border-success-subtle d-inline-flex align-items-center gap-1.5 shadow-xs">
                                                    <span class="store-pulse-dot bg-success"></span> Published
                                                </span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning rounded-pill px-2.5 py-1 font-size-11 border border-warning-subtle d-inline-flex align-items-center gap-1">
                                                    <i class="bx bx-time-five font-size-12"></i> Draft
                                                </span>
                                            @endif
                                        </td>

                                        {{-- 5. FEATURED / SHOWCASE --}}
                                        <td class="py-3 text-center">
                                            @if($isFeatured)
                                                <span class="badge bg-amber-subtle text-amber rounded-pill px-2.5 py-1 font-size-11 border border-warning-subtle d-inline-flex align-items-center gap-1"
                                                      style="background: #fef3c7; color: #b45309; border-color: #fde68a !important;">
                                                    <i class="bx bxs-star font-size-12 text-warning"></i> Featured
                                                </span>
                                            @else
                                                <span class="text-muted font-size-12 opacity-50">Standard</span>
                                            @endif
                                        </td>

                                        {{-- 6. MANAGEMENT ACTIONS WITH GENEROUS SPACING --}}
                                        <td class="pe-3.5 py-3 text-end">
                                            @if($isListed)
                                                <div class="d-inline-flex align-items-center gap-2">
                                                    <a href="{{ url('/admin/store/products/' . $product->slug . '/edit') }}"
                                                       class="btn btn-sm btn-primary rounded-pill px-3.5 py-1.5 font-size-12 fw-semibold shadow-xs d-inline-flex align-items-center gap-1">
                                                        <i class="bx bx-slider-alt font-size-15"></i>
                                                        <span>Manage</span>
                                                    </a>

                                                    @if($isPublished)
                                                        <a href="{{ url('/products/' . $product->slug) }}"
                                                           target="_blank"
                                                           rel="noopener noreferrer"
                                                           class="btn btn-sm btn-outline-secondary rounded-circle shadow-xs"
                                                           style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                                           title="Open Live Product Page">
                                                            <i class="bx bx-link-external font-size-14"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            @else
                                                <form action="{{ url('/admin/store/products/' . $product->slug . '/add') }}"
                                                      method="POST"
                                                      class="d-inline store-add-product-form">
                                                    @csrf
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-primary rounded-pill px-3.5 py-1.5 font-size-12 fw-semibold shadow-xs d-inline-flex align-items-center gap-1.5">
                                                        <i class="bx bx-plus font-size-15"></i>
                                                        <span>Add to Store</span>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- CARD FOOTER SUMMARY --}}
                <div class="card-footer bg-light-subtle border-top border-light py-3 px-4 font-size-12 text-muted d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-info-circle text-primary font-size-15"></i>
                        <span>Changes made to prices or stock inside Bakery ERP automatically synchronize to your storefront live.</span>
                    </div>
                    <div class="text-muted">
                        Total Catalog: <strong class="text-dark">{{ $products->count() }}</strong> products
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

{{-- =====================================================
     CUSTOM STYLES & INTERACTIVE FILTER LOGIC
     ===================================================== --}}
<style>
    .store-hero-avatar {
        flex-shrink: 0;
    }
    .store-kpi-card {
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .store-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.06) !important;
    }
    .store-product-thumb {
        transition: transform 0.18s ease;
    }
    .store-row-item:hover .store-product-thumb {
        transform: scale(1.08);
    }
    .store-product-title-link:hover {
        color: #4f46e5 !important;
    }
    .store-filter-btn {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
        transition: all 0.15s ease;
        padding-top: 0.45rem !important;
        padding-bottom: 0.45rem !important;
    }
    .store-filter-btn:hover {
        background: #f1f5f9;
        color: #1e293b;
    }
    .store-filter-btn.active {
        background: #4f46e5 !important;
        border-color: #4f46e5 !important;
        color: #ffffff !important;
    }
    .store-filter-btn.active .badge {
        background: rgba(255, 255, 255, 0.25) !important;
        color: #ffffff !important;
    }
    .store-pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: storePulse 1.8s infinite cubic-bezier(0.66, 0, 0, 1);
    }
    @keyframes storePulse {
        to {
            box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
        }
    }
</style>

{{-- SCRIPT LOADED DIRECTLY AND VIA @section('script') FOR MAXIMUM RELIABILITY --}}
<script>
    function filterStoreProducts(filter, btn) {
        document.querySelectorAll('.store-filter-btn').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');

        // Check if DataTable instance is available
        if (window.$ && $.fn && $.fn.DataTable) {
            try {
                const dt = $('#datatable').DataTable();
                if (filter === 'all') {
                    dt.search('').columns().search('').draw();
                } else if (filter === 'published') {
                    dt.columns().search('');
                    dt.column(3).search('Published').draw();
                } else if (filter === 'draft') {
                    dt.columns().search('');
                    dt.column(3).search('Draft').draw();
                } else if (filter === 'not-listed') {
                    dt.columns().search('');
                    dt.column(3).search('Not Listed').draw();
                } else if (filter === 'featured') {
                    dt.columns().search('');
                    dt.column(4).search('Featured').draw();
                }
                return;
            } catch (e) {
                console.warn('DataTable search fallback:', e);
            }
        }

        // DOM Fallback
        const rows = document.querySelectorAll('#datatable tbody tr');
        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            const featured = row.getAttribute('data-featured');

            if (filter === 'all') {
                row.style.display = '';
            } else if (filter === 'featured') {
                row.style.display = (featured === 'featured') ? '' : 'none';
            } else {
                row.style.display = (status === filter) ? '' : 'none';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        // SweetAlert enhancement for Add to Store form
        document.querySelectorAll('.store-add-product-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                if (window.Swal) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Add to Storefront?',
                        text: 'This will register the product on your online store catalog in Draft mode so you can add photos and copywriting.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Add Product',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#4f46e5',
                        cancelButtonColor: '#64748b',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        });
    });
</script>

@section('script')
<script>
    // Ensure DataTable styles select dropdown nicely
    $(document).ready(function() {
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#datatable')) {
            $('.dataTables_length select').addClass('form-select form-select-sm');
        }
    });
</script>
@endsection

@endsection
