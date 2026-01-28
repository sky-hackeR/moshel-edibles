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
        <div class="card">

            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">All Products</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProduct">
                    Add Product
                </button>
            </div>

            <div class="card-body">
                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Selling Price</th>
                            <th>Status</th>
                            <th width="140">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $product->name }}</td>
                                <td>₦{{ number_format($product->selling_price, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $product->is_active ? 'success' : 'danger' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-info m-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editProduct{{ $product->id }}">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>

                                    <button class="btn btn-danger m-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteProduct{{ $product->id }}">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </td>
                            </tr>

                            {{-- EDIT MODAL --}}
                            <div class="modal fade" id="editProduct{{ $product->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <form method="POST" action="{{ url('admin/updateProduct') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Product</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body row g-3">
                                                <div class="col-md-12">
                                                    <label>Name *</label>
                                                    <input type="text" name="name" class="form-control"
                                                            value="{{ $product->name }}" required>
                                                </div>

                                                <div class="col-md-12">
                                                    <label>Selling Price (₦) *</label>
                                                    <input type="number" step="0.01" name="selling_price"
                                                        value="{{ $product->selling_price }}"
                                                        class="form-control" required>
                                                </div>


                                                <div class="col-md-12">
                                                    <label>
                                                        <input type="checkbox" name="is_active"
                                                                {{ $product->is_active ? 'checked' : '' }}>
                                                        Active
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button class="btn btn-success">Save Changes</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {{-- DELETE MODAL --}}
                            <div class="modal fade" id="deleteProduct{{ $product->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <form method="POST" action="{{ url('admin/deleteProduct') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                                        <div class="modal-content">
                                            <div class="modal-body text-center p-5">
                                                <button type="button" class="btn-close float-end" data-bs-dismiss="modal"></button>

                                                <h4 class="mt-4">Delete Product?</h4>
                                                <p class="text-muted">{{ $product->name }}</p>

                                                @if($product->recipe)
                                                    <div class="alert alert-warning mt-3">
                                                        This product has a recipe attached and cannot be deleted.
                                                    </div>
                                                @else
                                                    <button class="btn btn-danger w-100 mt-3">
                                                        Yes, Delete
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No product records found
                                </td>
                            </tr>

                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

{{-- ADD PRODUCT MODAL --}}
<div class="modal fade" id="addProduct" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="{{ url('admin/newProduct') }}">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row g-3">
                    <div class="col-md-12">
                        <label>Name *</label>
                        <input type="text" name="name" class="form-control"
                               placeholder="e.g. Bread" required>
                    </div>

                    <div class="col-md-12">
                        <label>Selling Price (₦) *</label>
                        <input type="number" step="0.01" name="selling_price"
                            class="form-control" required>
                    </div>

                    <div class="col-md-12">
                        <label>
                            <input type="checkbox" name="is_active" checked>
                            Active
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Save Product</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
