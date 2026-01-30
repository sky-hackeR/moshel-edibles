@extends('admin.layout.dashboard')

@section('content')

{{-- PAGE HEADER --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Product Management</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Products</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- CONTENT --}}
<div class="row">
    <div class="col-12">
        <div class="card border shadow-none">
            <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="card-title mb-0">All Products</h4>
                    <p class="text-muted mb-0 small">Manage your production catalog and pricing.</p>
                </div>
                <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#addProduct">
                    <i class="mdi mdi-plus me-1"></i> Add Product
                </button>
            </div>

            <div class="card-body">
                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                    <thead class="table">
                        <tr>
                            <th class="text-center" style="width: 50px;">S/N</th>
                            <th>Product Name</th>
                            <th>Sales Unit</th>
                            <th>Stock On Hand</th>
                            <th>Selling Price</th>
                            <th>Recipe Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <h5 class="font-size-14 mb-1"><a href="javascript: void(0);" class="text-dark">{{ $product->name }}</a></h5>
                                    @if($product->is_active)
                                        <span class="badge bg-success small">Active</span>
                                    @else
                                        <span class="badge bg-danger small">Disabled</span>
                                    @endif
                                </td>
                                <td><span class="text-uppercase fw-medium text-muted small">{{ $product->sales_unit }}</span></td>
                                <td>
                                    <span class="fw-bold {{ $product->stock_on_hand > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ (float)$product->stock_on_hand }} {{ $product->sales_unit }}
                                    </span>
                                </td>
                                <td class="fw-bold">₦{{ number_format($product->selling_price, 2) }}</td>
                                <td>
                                    @if($product->recipe)
                                        <span class="badge badge-soft-info"><i class="mdi mdi-book-open-variant me-1"></i> Configured</span>
                                    @else
                                        <span class="badge badge-soft-danger"><i class="mdi mdi-alert-circle-outline me-1"></i> No Recipe</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="javascript:void(0);" 
                                        class="btn btn-soft-info btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editProduct{{ $product->id }}" 
                                        title="Edit Product">
                                            <i class="mdi mdi-pencil-outline font-size-16"></i>
                                        </a>

                                        <a href="javascript:void(0);" 
                                        class="btn btn-soft-danger btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteProduct{{ $product->id }}" 
                                        title="Delete Product">
                                            <i class="mdi mdi-delete-outline font-size-16"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@foreach($products as $product)
    {{-- DELETE MODAL --}}
    <div class="modal fade" id="deleteProduct{{ $product->id }}" tabindex="-1">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <form method="POST" action="{{ url('admin/deleteProduct') }}" style="width: 100%">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="modal-content border-danger">
                    <div class="modal-body text-center p-4">
                        <i class="mdi mdi-alert-circle-outline text-danger display-4"></i>
                        <h4 class="mt-3">Are you sure?</h4>
                        <p class="text-muted">You are about to delete <b>{{ $product->name }}</b>.</p>

                        @if($product->recipe)
                            <div class="alert alert-soft-danger py-2 mb-0">
                                <small><i class="mdi mdi-block-helper"></i> This product has an active recipe and cannot be deleted.</small>
                            </div>
                        @else
                            <div class="d-flex gap-2 mt-4">
                                <button type="button" class="btn btn-light w-50" data-bs-dismiss="modal">Cancel</button>
                                <button class="btn btn-danger w-50">Delete</button>
                            </div>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div class="modal fade" id="editProduct{{ $product->id }}" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" action="{{ url('admin/updateProduct') }}" style="width: 100%">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit: {{ $product->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Product Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Sales Unit (e.g. Loaf, Pcs)</label>
                                <input type="text" name="sales_unit" class="form-control" value="{{ $product->sales_unit }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Selling Price (₦)</label>
                            <div class="input-group">
                                <span class="input-group-text">₦</span>
                                <input type="number" step="0.01" name="selling_price" value="{{ $product->selling_price }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" name="is_active" id="editActive{{ $product->id }}" {{ $product->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="editActive{{ $product->id }}">Mark as Active</label>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary">Update Product</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

{{-- ADD PRODUCT MODAL --}}
<div class="modal fade" id="addProduct" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="{{ url('admin/newProduct') }}" style="width: 100%">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter product name (e.g. Vanilla Cake)" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Sales Unit</label>
                            <input type="text" name="sales_unit" class="form-control" placeholder="Loaf / Pcs / Pack" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Selling Price</label>
                        <div class="input-group">
                            <span class="input-group-text">₦</span>
                            <input type="number" step="0.01" name="selling_price" class="form-control" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="newActive" checked>
                        <label class="form-check-label" for="newActive">Set as Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Discard</button>
                    <button class="btn btn-primary px-4">Create Product</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection