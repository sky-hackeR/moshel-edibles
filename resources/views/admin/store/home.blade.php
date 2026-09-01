@extends('admin.layout.dashboard')

@section('content')

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h4 class="mb-1">Store Management</h4>
                    <p class="text-muted mb-0">
                        Manage the products and content displayed on your online storefront.
                    </p>
                </div>

                <div>
                    <a href="{{ url('/') }}"
                       target="_blank"
                       class="btn btn-primary">
                        <i class="bx bx-globe me-1"></i>
                        View Store
                    </a>
                </div>

            </div>
        </div>
    </div>
    

    {{-- =====================================================
    STORE STATISTICS
    ===================================================== --}}
    <div class="row">

        {{-- ERP PRODUCTS --}}
        <div class="col-xl col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">
                            <span class="avatar-title bg-primary rounded">
                                <i class="bx bx-package font-size-20"></i>
                            </span>
                        </div>

                        <div class="ms-3">
                            <p class="text-muted mb-1">
                                ERP Products
                            </p>

                            <h4 class="mb-0">
                                {{ $totalErpProducts }}
                            </h4>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- STORE PRODUCTS --}}
        <div class="col-xl col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">
                            <span class="avatar-title bg-info rounded">
                                <i class="bx bx-store font-size-20"></i>
                            </span>
                        </div>

                        <div class="ms-3">
                            <p class="text-muted mb-1">
                                Store Products
                            </p>

                            <h4 class="mb-0">
                                {{ $totalStoreProducts }}
                            </h4>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- PUBLISHED --}}
        <div class="col-xl col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">
                            <span class="avatar-title bg-success rounded">
                                <i class="bx bx-show font-size-20"></i>
                            </span>
                        </div>

                        <div class="ms-3">
                            <p class="text-muted mb-1">
                                Published
                            </p>

                            <h4 class="mb-0">
                                {{ $publishedProducts }}
                            </h4>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- DRAFTS --}}
        <div class="col-xl col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">
                            <span class="avatar-title bg-warning rounded">
                                <i class="bx bx-edit font-size-20"></i>
                            </span>
                        </div>

                        <div class="ms-3">
                            <p class="text-muted mb-1">
                                Drafts
                            </p>

                            <h4 class="mb-0">
                                {{ $draftProducts }}
                            </h4>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- NOT LISTED --}}
        <div class="col-xl col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">
                            <span class="avatar-title bg-secondary rounded">
                                <i class="bx bx-hide font-size-20"></i>
                            </span>
                        </div>

                        <div class="ms-3">
                            <p class="text-muted mb-1">
                                Not Listed
                            </p>

                            <h4 class="mb-0">
                                {{ $notListedProducts }}
                            </h4>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- STORE PRODUCTS --}}
    <div class="row">
        <div class="col-12">

            <div class="card">

                <div class="card-body">

                    {{-- TABLE HEADER --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div>
                            <h4 class="card-title mb-1">
                                Store Products
                            </h4>

                            <p class="text-muted mb-0">
                                Manage which ERP products are displayed on the storefront.
                            </p>
                        </div>

                    </div>


                    {{-- PRODUCTS TABLE --}}
                    <div class="table-responsive">

                        <table id="store-products-table"
                               class="table table-bordered dt-responsive nowrap w-100">

                            <thead>
                                <tr>

                                    <th>Product</th>

                                    <th>Price</th>

                                    <th>Stock</th>

                                    <th>Store Status</th>

                                    <th>Featured</th>

                                    <th class="text-end">
                                        Action
                                    </th>

                                </tr>
                            </thead>

                            <tbody>

                                @forelse($products as $product)

                                    <tr>

                                        {{-- PRODUCT --}}
                                        <td>
                                            <div class="d-flex align-items-center">

                                                @if($product->storeProduct?->primaryImage)
                                                    <img
                                                        src="{{ asset($product->storeProduct->primaryImage->image_path) }}"
                                                        alt="{{ $product->name }}"
                                                        class="rounded me-3"
                                                        style="width: 45px; height: 45px; object-fit: cover;"
                                                    >
                                                @else
                                                    <div
                                                        class="rounded me-3 bg-light d-flex align-items-center justify-content-center"
                                                        style="width: 45px; height: 45px;"
                                                    >
                                                        <i class="bx bx-image-alt text-muted font-size-20"></i>
                                                    </div>
                                                @endif

                                                <div>
                                                    <h6 class="mb-0">
                                                        {{ $product->name }}
                                                    </h6>

                                                    <small class="text-muted">
                                                        {{ strtoupper($product->sales_unit) }}
                                                    </small>
                                                </div>

                                            </div>
                                        </td>


                                        {{-- PRICE --}}
                                        <td>
                                            ₦{{ number_format($product->selling_price, 2) }}
                                        </td>


                                        {{-- STOCK --}}
                                        <td>
                                            {{ number_format($product->stock_on_hand, 2) }}
                                            {{ $product->sales_unit }}
                                        </td>


                                        {{-- STORE STATUS --}}
                                        <td>

                                            @if(!$product->storeProduct)

                                                <span class="badge bg-secondary">
                                                    Not Listed
                                                </span>

                                            @elseif($product->storeProduct->is_published)

                                                <span class="badge bg-success">
                                                    Published
                                                </span>

                                            @else

                                                <span class="badge bg-warning">
                                                    Draft
                                                </span>

                                            @endif

                                        </td>


                                        {{-- FEATURED --}}
                                        <td>

                                            @if($product->storeProduct?->is_featured)

                                                <span class="badge bg-primary">
                                                    <i class="bx bx-star me-1"></i>
                                                    Featured
                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        {{-- ACTION --}}
                                        <td class="text-end">

                                            @if($product->storeProduct)

                                                <a href="{{ url('/admin/store/products/' . $product->slug . '/edit') }}"
                                                   class="btn btn-sm btn-outline-primary">

                                                    <i class="bx bx-edit-alt me-1"></i>
                                                    Manage

                                                </a>

                                            @else

                                                <form action="{{ url('/admin/store/products/' . $product->slug . '/add') }}"
                                                      method="POST"
                                                      class="d-inline">

                                                    @csrf

                                                    <button type="submit"
                                                            class="btn btn-sm btn-primary">

                                                        <i class="bx bx-plus me-1"></i>
                                                        Add to Store

                                                    </button>

                                                </form>

                                            @endif

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="6"
                                            class="text-center py-5">

                                            <div class="text-muted">

                                                <i class="bx bx-package font-size-30"></i>

                                                <p class="mt-2 mb-0">
                                                    No products found.
                                                </p>

                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>
    </div>


    

</div>

@endsection