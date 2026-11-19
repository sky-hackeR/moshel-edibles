{{-- =====================================================
     ADD PRODUCT IMAGE MODAL PARTIAL
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
