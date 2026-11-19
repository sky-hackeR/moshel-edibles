@extends('admin.layout.dashboard')

@section('content')

<div class="container-fluid py-3">

    {{-- =====================================================
         EXECUTIVE HERO & BREADCRUMB CARD
         ===================================================== --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="border: 1px solid rgba(226, 232, 240, 0.8) !important;">
        <div class="card-body p-4 bg-white">
            <div class="row align-items-center g-3">
                <div class="col-lg-7">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        {{-- PRODUCT THUMBNAIL AVATAR --}}
                        <div class="store-product-hero-thumb flex-shrink-0">
                            @if($storeProduct->primaryImage)
                                <img src="{{ asset($storeProduct->primaryImage->image_path) }}"
                                     alt="{{ $product->name }}"
                                     class="rounded-3 shadow-xs border"
                                     style="width: 58px; height: 58px; object-fit: cover;">
                            @else
                                <div class="rounded-3 bg-light border d-flex align-items-center justify-content-center text-primary shadow-xs"
                                     style="width: 58px; height: 58px;">
                                    <i class="bx bx-package font-size-28"></i>
                                </div>
                            @endif
                        </div>

                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                <h3 class="mb-0 fw-bold text-dark font-size-20" style="letter-spacing: -0.02em;">
                                    {{ $storeProduct->store_title ?: $product->name }}
                                </h3>

                                @if($storeProduct->is_published)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 font-size-11 fw-semibold d-inline-flex align-items-center gap-1.5 shadow-xs">
                                        <span class="store-pulse-dot bg-success"></span> Published Live
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1 font-size-11 fw-semibold d-inline-flex align-items-center gap-1 shadow-xs">
                                        <i class="bx bx-time-five font-size-12"></i> Draft Mode
                                    </span>
                                @endif

                                @if($storeProduct->is_featured)
                                    <span class="badge bg-amber-subtle text-amber border border-warning-subtle rounded-pill px-2.5 py-1 font-size-11 fw-semibold d-inline-flex align-items-center gap-1"
                                          style="background: #fef3c7; color: #b45309; border-color: #fde68a !important;">
                                        <i class="bx bxs-star font-size-12 text-warning"></i> Featured
                                    </span>
                                @endif
                            </div>

                            <div class="d-flex align-items-center gap-2 text-muted font-size-12 flex-wrap">
                                <span>ERP Name: <strong class="text-dark">{{ $product->name }}</strong></span>
                                <span class="opacity-50">•</span>
                                <span>SKU/ID: <strong class="text-dark">#{{ $product->id }}</strong></span>
                                <span class="opacity-50">•</span>
                                <span class="badge bg-light text-secondary border font-size-10 text-uppercase fw-semibold px-2">
                                    {{ $product->sales_unit }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TOP ACTIONS BAR WITH GENEROUS SPACING --}}
                <div class="col-lg-5 text-lg-end">
                    <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                        <a href="{{ url('/admin/store') }}"
                           class="btn btn-outline-secondary px-3.5 py-2 rounded-pill shadow-xs fw-medium d-inline-flex align-items-center gap-2 font-size-13">
                            <i class="bx bx-arrow-back font-size-16"></i>
                            <span>Back to Store</span>
                        </a>

                        @if($storeProduct->is_published)
                            <a href="{{ url('/products/' . $product->slug) }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="btn btn-outline-primary px-3 py-2 rounded-pill shadow-xs fw-semibold d-inline-flex align-items-center gap-2 font-size-13">
                                <i class="bx bx-globe font-size-16"></i>
                                <span>View Live Page</span>
                                <i class="bx bx-link-external font-size-12 opacity-75"></i>
                            </a>
                        @endif

                        <button type="button"
                                class="btn btn-primary px-4 py-2 rounded-pill shadow-sm fw-semibold d-inline-flex align-items-center gap-2 font-size-13"
                                onclick="document.getElementById('storeProductUpdateForm').requestSubmit();">
                            <i class="bx bx-save font-size-16"></i>
                            <span>Save Changes</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- =====================================================
         TWO-COLUMN MASTER-DETAIL LAYOUT
         ===================================================== --}}
    <div class="row g-4">

        {{-- LEFT COLUMN: COPYWRITING, MEDIA GALLERY & SEO (col-xl-8) --}}
        <div class="col-xl-8">

            {{-- 1. STOREFRONT MERCHANDISING COPY CARD --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden store-panel-card"
                 style="border: 1px solid rgba(226, 232, 240, 0.8) !important; border-top: 3px solid #4f46e5 !important;">
                
                <div class="card-header bg-white border-bottom border-light py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-xs rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                            <i class="bx bx-edit font-size-18"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold text-dark font-size-15">Storefront Merchandising Copy</h5>
                            <p class="text-muted mb-0 font-size-12">Customer-facing titles and rich editorial descriptions shown in your public catalog.</p>
                        </div>
                    </div>
                    <span class="badge bg-light text-muted border font-size-11 px-2.5 py-1">Store Information</span>
                </div>

                <div class="card-body p-4">
                    <form action="{{ url('/admin/store/products/' . $product->slug . '/update') }}"
                          method="POST"
                          id="storeProductUpdateForm">
                        @csrf
                        @method('PUT')

                        {{-- STORE TITLE --}}
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label for="store_title" class="form-label fw-bold text-dark font-size-13 mb-0">
                                    Storefront Display Title <span class="text-danger">*</span>
                                </label>
                                <span class="badge bg-light text-muted border font-size-11" id="storeTitleCharCount">
                                    {{ strlen(old('store_title', $storeProduct->store_title ?? $product->name)) }}/255
                                </span>
                            </div>
                            <input type="text"
                                   name="store_title"
                                   id="store_title"
                                   class="form-control rounded-3 py-2.5 px-3.5 font-size-14 @error('store_title') is-invalid @enderror"
                                   value="{{ old('store_title', $storeProduct->store_title ?? $product->name) }}"
                                   placeholder="Enter storefront title (e.g. Artisanal Sourdough Country Loaf)"
                                   required
                                   maxlength="255">
                            @error('store_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="d-flex align-items-center justify-content-between mt-1.5 text-muted font-size-12">
                                <span>Customizes customer presentation without altering master ERP inventory title (<strong>{{ $product->name }}</strong>).</span>
                            </div>
                        </div>

                        {{-- SHORT DESCRIPTION --}}
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label for="short_description" class="form-label fw-bold text-dark font-size-13 mb-0">
                                    Short Summary / Teaser Hook
                                </label>
                                <span class="badge bg-light text-muted border font-size-11" id="shortDescCharCount">
                                    {{ strlen(old('short_description', $storeProduct->short_description ?? '')) }}/500
                                </span>
                            </div>
                            <textarea name="short_description"
                                      id="short_description"
                                      rows="3"
                                      class="form-control rounded-3 py-2.5 px-3.5 font-size-13 no-editor @error('short_description') is-invalid @enderror"
                                      data-no-editor="true"
                                      maxlength="500"
                                      placeholder="A crisp, mouthwatering 1-2 sentence hook for catalog cards, search listings, and cart previews...">{{ old('short_description', $storeProduct->short_description) }}</textarea>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted font-size-12 mt-1.5 d-block">
                                Displayed on catalog cards, homepage hero highlights, search snippets, and cart drawer previews.
                            </small>
                        </div>

                        {{-- FULL DESCRIPTION --}}
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label for="description" class="form-label fw-bold text-dark font-size-13 mb-0">
                                    Full Product Story &amp; Tasting Notes
                                </label>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-size-10 px-2">Single Product Page</span>
                            </div>
                            <textarea name="description"
                                      id="description"
                                      rows="7"
                                      class="form-control rounded-3 py-2.5 px-3.5 font-size-13 hugerte-editor @error('description') is-invalid @enderror"
                                      placeholder="Describe the crust, crumb, premium European butter, allergen notices, shelf-life, and serving recommendations...">{{ old('description', $storeProduct->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted font-size-12 mt-1.5 d-block">
                                Displayed in full on the customer product details page under the story and description section.
                            </small>
                        </div>

                        {{-- SAVE BUTTON INSIDE CARD --}}
                        <div class="pt-3 border-top border-light d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <span class="text-muted font-size-12">
                                <i class="bx bx-check-circle text-success me-1"></i> Changes take effect immediately across live catalog.
                            </span>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 font-size-13 fw-semibold shadow-xs d-inline-flex align-items-center gap-2">
                                <i class="bx bx-save font-size-16"></i>
                                <span>Save Merchandising Copy</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>


            {{-- 2. PHOTOGRAPHY & VISUAL MEDIA GALLERY CARD --}}
            @include('admin.store.product.partials.product-images-card')

            {{-- 3. SEARCH ENGINE OPTIMIZATION (SEO) & GOOGLE SERP PREVIEW CARD --}}
            @include('admin.store.product.partials.product-seo-card')

        </div>


        {{-- RIGHT COLUMN: STATUS, ERP SYNC & DEEP LINK (col-xl-4) --}}
        <div class="col-xl-4">
            @include('admin.store.product.partials.product-sidebar-panels')
        </div>

    </div>

</div>


{{-- =====================================================
     ADD PRODUCT IMAGE MODAL
     ===================================================== --}}
@include('admin.store.product.partials.add-image-modal')

{{-- =====================================================
     SCRIPTS & STYLES FOR PRODUCT EDIT
     ===================================================== --}}
@include('admin.store.product.partials.product-edit-scripts')

@endsection