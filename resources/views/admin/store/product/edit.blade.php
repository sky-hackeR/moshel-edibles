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
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden store-panel-card"
                 style="border: 1px solid rgba(226, 232, 240, 0.8) !important; border-top: 3px solid #8b5cf6 !important;">
                
                <div class="card-header bg-white border-bottom border-light py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-xs rounded-3 d-flex align-items-center justify-content-center text-white"
                             style="width: 34px; height: 34px; background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);">
                            <i class="bx bx-images font-size-18"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold text-dark font-size-15">Product Images &amp; Photography Gallery</h5>
                            <p class="text-muted mb-0 font-size-12">Manage high-definition product imagery, set primary showcase covers, and assign alt text for SEO.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-secondary-subtle text-secondary rounded-pill font-size-11 px-3 py-1.5">
                            {{ $storeProduct->images->count() }} {{ Str::plural('Photo', $storeProduct->images->count()) }}
                        </span>
                        <button type="button"
                                class="btn btn-primary btn-sm rounded-pill px-3.5 py-2 shadow-xs fw-semibold d-inline-flex align-items-center gap-2 font-size-12"
                                data-bs-toggle="modal"
                                data-bs-target="#addImageModal">
                            <i class="bx bx-cloud-upload font-size-16"></i>
                            <span>Upload Photo</span>
                        </button>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if($storeProduct->images->count())
                        <div class="row g-3.5">
                            @foreach($storeProduct->images as $image)
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="card border rounded-4 overflow-hidden h-100 store-image-card shadow-xs position-relative"
                                         style="border-color: {{ $image->is_primary ? '#4f46e5' : 'rgba(226, 232, 240, 0.9)' }} !important;">
                                        
                                        {{-- THUMBNAIL CONTAINER --}}
                                        <div class="position-relative store-image-box" style="height: 190px; background: #f8fafc; overflow: hidden;">
                                            <img src="{{ asset($image->image_path) }}"
                                                 alt="{{ $image->alt_text ?? $product->name }}"
                                                 class="w-100 h-100 object-fit-cover store-gallery-preview-img">

                                            @if($image->is_primary)
                                                <div class="position-absolute top-0 start-0 m-2">
                                                    <span class="badge bg-warning text-dark font-size-11 fw-bold rounded-pill shadow-xs d-inline-flex align-items-center gap-1 px-2.5 py-1">
                                                        <i class="bx bxs-star font-size-12"></i> Primary Cover
                                                    </span>
                                                </div>
                                            @endif

                                            @if($image->alt_text)
                                                <div class="position-absolute bottom-0 start-0 end-0 p-2 bg-dark bg-opacity-75 text-white font-size-11 text-truncate px-2.5"
                                                     title="Alt text: {{ $image->alt_text }}">
                                                    <i class="bx bx-text me-1"></i> {{ $image->alt_text }}
                                                </div>
                                            @endif
                                        </div>

                                        {{-- CARD ACTIONS FOOTER WITH GENEROUS BUTTON SPACING --}}
                                        <div class="card-footer bg-white border-top border-light p-2.5 d-flex align-items-center justify-content-between">
                                            @if(!$image->is_primary)
                                                <form action="{{ url('/admin/store/products/' . $product->slug . '/images/' . $image->uuid . '/primary') }}"
                                                      method="POST"
                                                      class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 font-size-11 fw-semibold d-inline-flex align-items-center gap-1 shadow-xs"
                                                            title="Set as storefront primary cover">
                                                        <i class="bx bx-star font-size-13"></i>
                                                        <span>Set Primary</span>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-primary font-size-11 fw-bold d-inline-flex align-items-center gap-1 px-1">
                                                    <i class="bx bx-check-circle font-size-14 text-success"></i> Storefront Cover
                                                </span>
                                            @endif

                                            <form action="{{ url('/admin/store/products/' . $product->slug . '/images/' . $image->uuid . '/delete') }}"
                                                  method="POST"
                                                  class="d-inline store-delete-image-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger rounded-circle p-1.5 shadow-xs store-delete-img-btn"
                                                        style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                                        title="Delete this image">
                                                    <i class="bx bx-trash font-size-14"></i>
                                                </button>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            @endforeach

                            {{-- QUICK ADD PHOTO TILE --}}
                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="card border-2 border-dashed rounded-4 h-100 d-flex align-items-center justify-content-center p-4 text-center cursor-pointer store-quick-add-tile"
                                     style="border-color: #cbd5e1 !important; min-height: 240px; background: #fafafa; transition: all 0.2s ease;"
                                     data-bs-toggle="modal"
                                     data-bs-target="#addImageModal">
                                    <div class="avatar-md rounded-circle bg-white shadow-xs mx-auto d-flex align-items-center justify-content-center mb-2.5 text-primary" style="width: 48px; height: 48px;">
                                        <i class="bx bx-plus font-size-24"></i>
                                    </div>
                                    <h6 class="fw-bold font-size-13 text-dark mb-1">Add Another Photo</h6>
                                    <p class="text-muted font-size-11 mb-0">Upload multiple angles, crumb textures, or cross-sections</p>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- EMPTY STATE FOR IMAGES --}}
                        <div class="text-center py-5 px-3">
                            <div class="avatar-lg bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3 text-secondary" style="width: 68px; height: 68px;">
                                <i class="bx bx-camera font-size-36 text-secondary opacity-50"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-1">No Product Images Uploaded</h5>
                            <p class="text-muted font-size-13 mb-3 max-w-sm mx-auto" style="max-width: 440px;">
                                High-quality product photos increase customer conversion by up to 80%. Upload your first bake photo to showcase it in your catalog.
                            </p>
                            <button type="button"
                                    class="btn btn-primary rounded-pill px-4 py-2 font-size-13 fw-semibold shadow-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#addImageModal">
                                <i class="bx bx-plus me-1 font-size-16"></i> Add First Product Photo
                            </button>
                        </div>
                    @endif
                </div>

                {{-- CARD FOOTER GUIDANCE --}}
                <div class="card-footer bg-light-subtle border-top border-light py-3 px-4 font-size-12 text-muted d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <span>
                        <i class="bx bx-info-circle text-primary me-1"></i> Recommended aspect ratio: 1:1 (square) or 4:3. Formats: JPG, PNG, WebP (Max 5MB).
                    </span>
                    <span>The primary photo is automatically highlighted on the storefront index and social share snippets.</span>
                </div>
            </div>


            {{-- 3. SEARCH ENGINE OPTIMIZATION (SEO) & GOOGLE SERP PREVIEW CARD --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden store-panel-card"
                 style="border: 1px solid rgba(226, 232, 240, 0.8) !important; border-top: 3px solid #06b6d4 !important;">
                
                <div class="card-header bg-white border-bottom border-light py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-xs rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                            <i class="bx bx-search-alt font-size-18"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold text-dark font-size-15">Search Engine Optimization (SEO)</h5>
                            <p class="text-muted mb-0 font-size-12">Fine-tune Google Search appearance and social media open-graph sharing snippets.</p>
                        </div>
                    </div>
                    <span class="badge bg-info-subtle text-info rounded-pill font-size-11 px-2.5 py-1">Search Engine Information</span>
                </div>

                <div class="card-body p-4">
                    <div class="row g-3.5 mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between mb-1.5">
                                <label for="meta_title" class="form-label font-size-13 fw-semibold text-dark mb-0">
                                    SEO Meta Title
                                </label>
                                <span class="text-muted font-size-11" id="metaTitleCount">
                                    {{ strlen(old('meta_title', $storeProduct->meta_title ?? '')) }}/255
                                </span>
                            </div>
                            <input type="text"
                                   form="storeProductUpdateForm"
                                   name="meta_title"
                                   id="meta_title"
                                   class="form-control rounded-3 py-2 px-3 font-size-13"
                                   maxlength="255"
                                   value="{{ old('meta_title', $storeProduct->meta_title) }}"
                                   placeholder="e.g. Fresh Artisan Sourdough Bread | Moshel">
                            <small class="text-muted font-size-11 d-block mt-1">Recommended length: 50–60 characters</small>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between mb-1.5">
                                <label for="meta_description" class="form-label font-size-13 fw-semibold text-dark mb-0">
                                    SEO Meta Description
                                </label>
                                <span class="text-muted font-size-11" id="metaDescCount">
                                    {{ strlen(old('meta_description', $storeProduct->meta_description ?? '')) }}/500
                                </span>
                            </div>
                            <textarea form="storeProductUpdateForm"
                                      name="meta_description"
                                      id="meta_description"
                                      rows="2"
                                      class="form-control rounded-3 py-2 px-3 font-size-12 no-editor"
                                      data-no-editor="true"
                                      maxlength="500"
                                      placeholder="Summary snippet displayed beneath the title in search engine results...">{{ old('meta_description', $storeProduct->meta_description) }}</textarea>
                            <small class="text-muted font-size-11 d-block mt-1">Recommended length: 120–160 characters</small>
                        </div>
                    </div>

                    {{-- REAL-TIME GOOGLE SERP PREVIEW BOX --}}
                    <div class="p-3.5 rounded-3 bg-light border" style="border-color: rgba(226, 232, 240, 0.9) !important;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-uppercase text-muted fw-bold font-size-11" style="letter-spacing: 0.5px;">
                                <i class="bx bxl-google text-danger me-1"></i> Google Search Result Live Simulator
                            </span>
                            <span class="badge bg-white text-muted border font-size-10 px-2 py-0.5">Desktop &amp; Mobile</span>
                        </div>
                        <div class="google-serp-preview p-3 rounded-3 bg-white border" style="font-family: Arial, sans-serif;">
                            <div class="d-flex align-items-center gap-2 font-size-12 text-muted mb-1" style="color: #202124 !important;">
                                <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center flex-shrink-0" style="width: 18px; height: 18px;">
                                    <i class="bx bx-globe text-primary font-size-11"></i>
                                </div>
                                <span class="text-truncate fw-medium" style="font-size: 13px; color: #202124;">Moshel Edibles</span>
                                <span style="color: #5f6368;">› products › {{ $product->slug }}</span>
                            </div>
                            <div class="google-serp-title fw-medium mb-1.5" id="serpTitlePreview" style="color: #1a0dab; font-size: 18px; line-height: 1.3; cursor: pointer;">
                                {{ $storeProduct->meta_title ?: ($storeProduct->store_title ?: $product->name) }} | Moshel Edibles
                            </div>
                            <div class="google-serp-snippet text-muted" id="serpSnippetPreview" style="color: #4d5156; font-size: 13px; line-height: 1.45;">
                                {{ Str::limit($storeProduct->meta_description ?: ($storeProduct->short_description ?: 'Artisanal treats, celebration cakes, and gourmet bakes made fresh daily in Lagos.'), 155) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        {{-- RIGHT COLUMN: STATUS, ERP SYNC & DEEP LINK (col-xl-4) --}}
        <div class="col-xl-4">

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

        </div>

    </div>

</div>


{{-- =====================================================
     ADD PRODUCT IMAGE MODAL
     ===================================================== --}}
<div class="modal fade"
     id="addImageModal"
     tabindex="-1"
     aria-labelledby="addImageModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <form action="{{ url('/admin/store/products/' . $product->slug . '/images/add') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="storeAddImageForm">
                @csrf

                <div class="modal-header bg-white border-bottom border-light py-3.5 px-4">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="avatar-xs rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="bx bx-camera font-size-20"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-dark font-size-16 mb-0" id="addImageModalLabel">
                                Upload Product Photo
                            </h5>
                            <small class="text-muted font-size-11">Add to storefront photography gallery</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">

                    {{-- DRAG & DROP / FILE INPUT ZONE --}}
                    <div class="mb-4">
                        <label for="image" class="form-label fw-bold text-dark font-size-13 mb-2">
                            Product Image File <span class="text-danger">*</span>
                        </label>
                        <div class="p-4 text-center border-2 border-dashed rounded-4 bg-light cursor-pointer store-upload-dropzone position-relative"
                             id="imageUploadDropzone"
                             style="border-style: dashed; border-color: #cbd5e1 !important; transition: all 0.2s ease;">
                            
                            {{-- PLACEHOLDER STATE --}}
                            <div id="dropzonePrompt">
                                <div class="avatar-md rounded-circle bg-white shadow-xs mx-auto d-flex align-items-center justify-content-center mb-2.5 text-primary" style="width: 52px; height: 52px;">
                                    <i class="bx bx-cloud-upload font-size-26"></i>
                                </div>
                                <h6 class="fw-bold font-size-14 text-dark mb-1">Click to choose image or drag &amp; drop</h6>
                                <p class="text-muted font-size-12 mb-0">High-res JPEG, PNG, or WebP (Max 5MB)</p>
                            </div>

                            {{-- PREVIEW STATE --}}
                            <div id="dropzonePreview" class="d-none">
                                <img src="" id="imagePreviewImg" class="rounded-3 shadow-xs mb-2.5" style="max-height: 160px; max-width: 100%; object-fit: contain;">
                                <div class="font-size-13 fw-semibold text-dark text-truncate" id="imagePreviewName"></div>
                                <span class="badge bg-light text-secondary border font-size-11 mt-1.5 cursor-pointer px-2 py-1" id="changeImageBtn">Change Image</span>
                            </div>

                            <input type="file"
                                   name="image"
                                   id="image"
                                   class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer"
                                   accept="image/jpeg,image/png,image/webp"
                                   required>
                        </div>
                    </div>

                    {{-- ALT TEXT --}}
                    <div class="mb-4">
                        <label for="alt_text" class="form-label fw-bold text-dark font-size-13 mb-1.5">
                            Accessibility Alt Text
                        </label>
                        <input type="text"
                               name="alt_text"
                               id="alt_text"
                               class="form-control rounded-3 py-2.5 px-3 font-size-13"
                               maxlength="255"
                               placeholder="e.g. Golden toasted sourdough loaf with crisp crust">
                        <small class="text-muted font-size-11 d-block mt-1">
                            Essential for visually impaired shoppers and search engines.
                        </small>
                    </div>

                    {{-- IS PRIMARY CHECKBOX --}}
                    <div class="p-3 rounded-3 bg-light border border-light">
                        <div class="form-check mb-0">
                            <input class="form-check-input cursor-pointer font-size-16"
                                   type="checkbox"
                                   name="is_primary"
                                   value="1"
                                   id="modal_is_primary"
                                   {{ $storeProduct->images->count() === 0 ? 'checked' : '' }}>
                            <label class="form-check-label font-size-13 text-dark cursor-pointer fw-semibold ms-1"
                                   for="modal_is_primary">
                                Set as primary cover photo
                            </label>
                            <small class="text-muted font-size-11 d-block ms-1">
                                Automatically displays as the main product thumbnail in catalog cards.
                            </small>
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-light-subtle border-top border-light py-3 px-4 d-flex align-items-center justify-content-between">
                    <button type="button" class="btn btn-light rounded-pill px-3.5 py-2 font-size-12 fw-medium" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 font-size-13 fw-semibold shadow-xs d-inline-flex align-items-center gap-2" id="uploadImageSubmitBtn">
                        <i class="bx bx-upload font-size-16"></i>
                        <span>Upload Photo</span>
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>


{{-- =====================================================
     SCRIPTS FOR INTERACTIVE COUNTERS, SEO PREVIEW & SWEETALERTS
     ===================================================== --}}
<style>
    .store-panel-card {
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .store-image-box:hover .store-gallery-preview-img {
        transform: scale(1.06);
    }
    .store-gallery-preview-img {
        transition: transform 0.25s ease;
    }
    .store-quick-add-tile:hover {
        border-color: #4f46e5 !important;
        background: #f8fafc !important;
        transform: translateY(-2px);
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Character counter helpers
        const setupCounter = (inputId, counterId, max) => {
            const input = document.getElementById(inputId);
            const counter = document.getElementById(counterId);
            if (!input || !counter) return;

            const update = () => {
                const len = input.value.length;
                counter.textContent = `${len}/${max}`;
            };
            input.addEventListener('input', update);
        };

        setupCounter('store_title', 'storeTitleCharCount', 255);
        setupCounter('short_description', 'shortDescCharCount', 500);
        setupCounter('meta_title', 'metaTitleCount', 255);
        setupCounter('meta_description', 'metaDescCount', 500);

        // Real-time Google SERP Snippet Preview Updater
        const storeTitleInput = document.getElementById('store_title');
        const metaTitleInput = document.getElementById('meta_title');
        const shortDescInput = document.getElementById('short_description');
        const metaDescInput = document.getElementById('meta_description');

        const serpTitlePreview = document.getElementById('serpTitlePreview');
        const serpSnippetPreview = document.getElementById('serpSnippetPreview');

        const fallbackTitle = "{{ $product->name }}";
        const fallbackSnippet = "Artisanal treats, celebration cakes, and gourmet bakes made fresh daily in Lagos.";

        function updateSerp() {
            if (serpTitlePreview) {
                const title = metaTitleInput?.value.trim() || storeTitleInput?.value.trim() || fallbackTitle;
                serpTitlePreview.textContent = title.includes('Moshel') ? title : `${title} | Moshel Edibles`;
            }

            if (serpSnippetPreview) {
                const snippet = metaDescInput?.value.trim() || shortDescInput?.value.trim() || fallbackSnippet;
                serpSnippetPreview.textContent = snippet.length > 155 ? snippet.substring(0, 155) + '...' : snippet;
            }
        }

        [storeTitleInput, metaTitleInput, shortDescInput, metaDescInput].forEach(el => {
            if (el) el.addEventListener('input', updateSerp);
        });

        // Copy direct link to clipboard with Toast
        const copyBtn = document.getElementById('copyStoreLinkBtn');
        const urlInput = document.getElementById('storeProductDirectUrl');
        const feedback = document.getElementById('copyFeedback');

        if (copyBtn && urlInput) {
            copyBtn.addEventListener('click', function () {
                urlInput.select();
                urlInput.setSelectionRange(0, 99999);
                navigator.clipboard.writeText(urlInput.value).then(() => {
                    if (feedback) {
                        feedback.classList.remove('d-none');
                        setTimeout(() => feedback.classList.add('d-none'), 2500);
                    }
                    if (window.Swal) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'Customer share URL copied to clipboard!'
                        });
                    }
                });
            });
        }

        // Live image file preview in modal dropzone
        const fileInput = document.getElementById('image');
        const dropzonePrompt = document.getElementById('dropzonePrompt');
        const dropzonePreview = document.getElementById('dropzonePreview');
        const previewImg = document.getElementById('imagePreviewImg');
        const previewName = document.getElementById('imagePreviewName');

        if (fileInput) {
            fileInput.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImg.src = e.target.result;
                        previewName.textContent = file.name;
                        dropzonePrompt.classList.add('d-none');
                        dropzonePreview.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // SweetAlert enhancement for image deletion
        document.querySelectorAll('.store-delete-image-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                if (window.Swal) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Delete this Photo?',
                        text: 'This image will be permanently removed from the storefront gallery.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Delete',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#ef4444',
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
@endpush

@endsection