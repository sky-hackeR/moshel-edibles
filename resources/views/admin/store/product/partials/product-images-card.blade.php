{{-- =====================================================
     PHOTOGRAPHY & VISUAL MEDIA GALLERY CARD PARTIAL
     ===================================================== --}}
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
