@extends('admin.layout.dashboard')

@section('content')

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h4 class="mb-1">Manage Store Product</h4>

                    <p class="text-muted mb-0">
                        Manage how this product appears on the online storefront.
                    </p>
                </div>

                <div class="d-flex gap-2">

                    <a href="{{ url('/admin/store') }}"
                       class="btn btn-light">

                        <i class="bx bx-arrow-back me-1"></i>
                        Back to Store

                    </a>

                    @if($storeProduct->is_published)

                        <a href="{{ url('/products/' . $product->slug) }}"
                           target="_blank"
                           class="btn btn-primary">

                            <i class="bx bx-show me-1"></i>
                            View Product

                        </a>

                    @endif

                </div>

            </div>
        </div>
    </div>


    {{-- PRODUCT SUMMARY --}}
    <div class="row">

        <div class="col-xl-8">

            {{-- STORE INFORMATION --}}
            <div class="card">

                <div class="card-header">
                    <h4 class="card-title mb-1">
                        Store Information
                    </h4>

                    <p class="text-muted mb-0">
                        Information customers will see when viewing this product.
                    </p>
                </div>

                <div class="card-body">

                    <form action="{{ url('/admin/store/products/' . $product->slug . '/update') }}"
                          method="POST">

                        @csrf

                        @method('PUT')


                        {{-- STORE TITLE --}}
                        <div class="mb-4">

                            <label for="store_title" class="form-label">
                                Store Title
                            </label>

                            <input
                                type="text"
                                name="store_title"
                                id="store_title"
                                class="form-control"
                                value="{{ old('store_title', $storeProduct->store_title ?? $product->name) }}"
                                placeholder="Enter storefront title"
                            >

                            <small class="text-muted">
                                Leave this as the product name if you don't need a different storefront title.
                            </small>

                        </div>


                        {{-- DESCRIPTION --}}
                        <div class="mb-4">

                            <label for="description" class="form-label">
                                Product Description
                            </label>

                            <textarea
                                name="description"
                                id="description"
                                rows="8"
                                class="form-control"
                                placeholder="Describe this product for customers..."
                            >{{ old('description', $storeProduct->description) }}</textarea>

                            <small class="text-muted">
                                This description is storefront-specific and does not affect the ERP product.
                            </small>

                        </div>


                        {{-- SHORT DESCRIPTION --}}
                        <div class="mb-4">

                            <label for="short_description" class="form-label">
                                Short Description
                            </label>

                            <textarea
                                name="short_description"
                                id="short_description"
                                rows="3"
                                class="form-control"
                                maxlength="500"
                                placeholder="A short description for product cards..."
                            >{{ old('short_description', $storeProduct->short_description) }}</textarea>

                            <small class="text-muted">
                                Used for product cards, featured sections and search results.
                            </small>

                        </div>


                        {{-- SEO --}}
                        <div class="border-top pt-4 mt-4">

                            <h5 class="mb-3">
                                Search Engine Information
                            </h5>

                            <div class="mb-3">

                                <label for="meta_title" class="form-label">
                                    Meta Title
                                </label>

                                <input
                                    type="text"
                                    name="meta_title"
                                    id="meta_title"
                                    class="form-control"
                                    maxlength="255"
                                    value="{{ old('meta_title', $storeProduct->meta_title) }}"
                                    placeholder="SEO title"
                                >

                            </div>


                            <div class="mb-3">

                                <label for="meta_description" class="form-label">
                                    Meta Description
                                </label>

                                <textarea
                                    name="meta_description"
                                    id="meta_description"
                                    rows="3"
                                    class="form-control"
                                    maxlength="500"
                                    placeholder="SEO description"
                                >{{ old('meta_description', $storeProduct->meta_description) }}</textarea>

                            </div>

                        </div>


                        {{-- SAVE --}}
                        <div class="border-top pt-4 mt-4">

                            <div class="d-flex justify-content-end">

                                <button type="submit"
                                        class="btn btn-primary">

                                    <i class="bx bx-save me-1"></i>
                                    Save Changes

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>


            {{-- IMAGES --}}
            <div class="card">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h4 class="card-title mb-1">
                                Product Images
                            </h4>

                            <p class="text-muted mb-0">
                                Images displayed on the storefront.
                            </p>
                        </div>

                        <button type="button"
                                class="btn btn-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#addImageModal">

                            <i class="bx bx-plus me-1"></i>
                            Add Image

                        </button>

                    </div>

                </div>


                <div class="card-body">

                    @if($storeProduct->images->count())

                        <div class="row">

                            @foreach($storeProduct->images as $image)

                                <div class="col-xl-3 col-md-4 col-sm-6 mb-4">

                                    <div class="card border mb-0">

                                        <div class="position-relative">

                                            <img
                                                src="{{ asset($image->image_path) }}"
                                                alt="{{ $image->alt_text ?? $product->name }}"
                                                class="card-img-top"
                                                style="height: 180px; object-fit: cover;"
                                            >

                                            @if($image->is_primary)

                                                <span class="badge bg-primary position-absolute top-0 start-0 m-2">
                                                    Primary
                                                </span>

                                            @endif

                                        </div>


                                        <div class="card-body p-2">

                                            <div class="d-flex justify-content-between align-items-center">

                                                @if(!$image->is_primary)

                                                    <form
                                                        action="{{ url('/admin/store/products/' . $product->slug . '/images/' . $image->id . '/primary') }}"
                                                        method="POST"
                                                    >

                                                        @csrf

                                                        @method('PATCH')

                                                        <button
                                                            type="submit"
                                                            class="btn btn-sm btn-outline-primary"
                                                        >
                                                            <i class="bx bx-star"></i>
                                                            Primary
                                                        </button>

                                                    </form>

                                                @else

                                                    <span class="text-muted small">
                                                        Primary image
                                                    </span>

                                                @endif


                                                <form
                                                    action="{{ url('/admin/store/products/' . $product->slug . '/images/' . $image->id . '/delete') }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this image?');"
                                                >

                                                    @csrf

                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                    >

                                                        <i class="bx bx-trash"></i>

                                                    </button>

                                                </form>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="text-center py-5">

                            <div class="mb-3">

                                <i class="bx bx-image-alt text-muted"
                                   style="font-size: 48px;">
                                </i>

                            </div>

                            <h5>
                                No product images
                            </h5>

                            <p class="text-muted mb-3">
                                Add images so customers can see this product.
                            </p>

                            <button
                                type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#addImageModal"
                            >
                                <i class="bx bx-plus me-1"></i>
                                Add First Image
                            </button>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- RIGHT SIDEBAR --}}
        <div class="col-xl-4">


            {{-- ERP PRODUCT --}}
            <div class="card">

                <div class="card-header">

                    <h4 class="card-title mb-0">
                        ERP Product
                    </h4>

                </div>

                <div class="card-body">

                    <div class="d-flex align-items-center mb-4">
                        @if($product->storeProduct?->primaryImage)
                            <img
                                src="{{ asset($product->storeProduct->primaryImage->image_path) }}"
                                alt="{{ $product->name }}"
                                class="rounded me-3"
                                style="width: 45px; height: 45px; object-fit: cover;"
                            >
                        @else
                            <div
                                class="avatar-sm bg-light rounded d-flex align-items-center justify-content-center"
                            >
                                <i class="bx bx-package font-size-20 text-primary"></i>
                            </div>
                        @endif

                        <div class="ms-3">

                            <h5 class="mb-1">
                                {{ $product->name }}
                            </h5>

                            <span class="text-muted">
                                Product #{{ $product->id }}
                            </span>

                        </div>

                    </div>


                    <div class="border-top pt-3">

                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Price
                            </span>

                            <strong>
                                ₦{{ number_format($product->selling_price, 2) }}
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Stock
                            </span>

                            <strong>
                                {{ number_format($product->stock_on_hand, 2) }}
                                {{ $product->sales_unit }}
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                ERP Status
                            </span>

                            @if($product->is_active)

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Inactive
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- STORE STATUS --}}
            <div class="card">

                <div class="card-header">

                    <h4 class="card-title mb-0">
                        Store Status
                    </h4>

                </div>

                <div class="card-body">

                    <form
                        action="{{ url('/admin/store/products/' . $product->slug . '/status') }}"
                        method="POST"
                    >

                        @csrf

                        @method('PATCH')


                        {{-- PUBLISHED --}}
                        <div class="form-check form-switch mb-4">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="is_published"
                                value="1"
                                id="is_published"
                                {{ $storeProduct->is_published ? 'checked' : '' }}
                            >

                            <label class="form-check-label"
                                   for="is_published">

                                <strong>
                                    Published
                                </strong>

                                <small class="d-block text-muted">
                                    Make this product visible to customers.
                                </small>

                            </label>

                        </div>


                        {{-- FEATURED --}}
                        <div class="form-check form-switch mb-4">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="is_featured"
                                value="1"
                                id="is_featured"
                                {{ $storeProduct->is_featured ? 'checked' : '' }}
                            >

                            <label class="form-check-label"
                                   for="is_featured">

                                <strong>
                                    Featured Product
                                </strong>

                                <small class="d-block text-muted">
                                    Highlight this product on the storefront.
                                </small>

                            </label>

                        </div>


                        <button type="submit"
                                class="btn btn-primary w-100">

                            <i class="bx bx-save me-1"></i>
                            Update Store Status

                        </button>

                    </form>

                </div>

            </div>


            {{-- STORE URL --}}
            <div class="card">

                <div class="card-header">

                    <h4 class="card-title mb-0">
                        Store URL
                    </h4>

                </div>

                <div class="card-body">

                    <div class="input-group">

                        <input
                            type="text"
                            class="form-control"
                            value="{{ url('/products/' . $product->slug) }}"
                            readonly
                        >

                        <a
                            href="{{ url('/products/' . $product->slug) }}"
                            target="_blank"
                            class="btn btn-outline-primary"
                        >
                            <i class="bx bx-link-external"></i>
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ADD IMAGE MODAL --}}
<div class="modal fade"
     id="addImageModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                action="{{ url('/admin/store/products/' . $product->slug . '/images/add') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        Add Product Image
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>

                </div>


                <div class="modal-body">

                    <div class="mb-3">

                        <label for="image" class="form-label">
                            Image
                        </label>

                        <input
                            type="file"
                            name="image"
                            id="image"
                            class="form-control"
                            accept="image/jpeg,image/png,image/webp"
                            required
                        >

                        <small class="text-muted">
                            JPG, PNG or WebP.
                        </small>

                    </div>


                    <div class="mb-3">

                        <label for="alt_text" class="form-label">
                            Alt Text
                        </label>

                        <input
                            type="text"
                            name="alt_text"
                            id="alt_text"
                            class="form-control"
                            maxlength="255"
                            placeholder="Describe the image"
                        >

                        <small class="text-muted">
                            Useful for accessibility and search engines.
                        </small>

                    </div>


                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="is_primary"
                            value="1"
                            id="is_primary"
                        >

                        <label class="form-check-label"
                               for="is_primary">

                            Set as primary image

                        </label>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bx bx-upload me-1"></i>
                        Upload Image
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection