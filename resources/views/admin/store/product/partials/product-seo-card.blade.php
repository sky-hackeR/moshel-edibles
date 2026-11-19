{{-- =====================================================
     SEARCH ENGINE OPTIMIZATION (SEO) & GOOGLE SERP PREVIEW CARD PARTIAL
     ===================================================== --}}
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
