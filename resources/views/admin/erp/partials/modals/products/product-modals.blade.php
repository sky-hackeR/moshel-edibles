{{-- =====================================================
     VIEW, EDIT, AND DELETE PRODUCT MODALS
     ===================================================== --}}
@foreach($products as $product)
    @php
        $qty = (float) $product->stock_on_hand;
        $price = (float) $product->selling_price;
        $val = $qty * $price;
    @endphp
    {{-- VIEW PRODUCT SPECIFICATIONS MODAL --}}
    <div class="modal fade" id="viewProduct{{ $product->id }}" tabindex="-1" aria-labelledby="viewProductTitle{{ $product->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                {{-- Header --}}
                <div class="modal-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="viewProductTitle{{ $product->id }}">
                            <i class="bx bx-package me-1 text-primary"></i> Product Specifications
                        </h5>
                        <small class="text-muted">System ID: #{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    {{-- Hero Card --}}
                    <div class="p-3 bg-light rounded-3 border mb-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="product-icon-avatar me-3" style="width: 48px; height: 48px; font-size: 18px;">
                                {{ strtoupper(substr($product->name, 0, 1)) }}
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0 font-size-16">{{ $product->name }}</h5>
                                <span class="text-muted font-monospace font-size-11">{{ $product->slug ?: Str::slug($product->name) }}</span>
                            </div>
                        </div>
                        <div>
                            @if($product->is_active)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 font-size-11">
                                    <i class="mdi mdi-check-circle-outline me-1"></i> Active Item
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 font-size-11">
                                    <i class="mdi mdi-block-helper me-1"></i> Disabled
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Metrics Grid --}}
                    <div class="row g-2 mb-4">
                        <div class="col-sm-6 col-md-3">
                            <div class="p-2.5 bg-light rounded-3 border h-100 text-center">
                                <small class="text-muted font-size-11 text-uppercase fw-semibold d-block">Stock On Hand</small>
                                <span class="fw-bold font-size-14 {{ $qty > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($qty, 0) }} {{ $product->sales_unit }}
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="p-2.5 bg-light rounded-3 border h-100 text-center">
                                <small class="text-muted font-size-11 text-uppercase fw-semibold d-block">Selling Price</small>
                                <span class="fw-bold text-dark font-size-14">
                                    ₦{{ number_format($price, 2) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="p-2.5 bg-light rounded-3 border h-100 text-center">
                                <small class="text-muted font-size-11 text-uppercase fw-semibold d-block">Retail Valuation</small>
                                <span class="fw-bold text-primary font-size-14">
                                    ₦{{ number_format($val, 2) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="p-2.5 bg-light rounded-3 border h-100 text-center">
                                <small class="text-muted font-size-11 text-uppercase fw-semibold d-block">Recipe Status</small>
                                @if($product->recipe)
                                    <span class="fw-bold text-info font-size-13">
                                        <i class="mdi mdi-check-circle me-1"></i> Configured
                                    </span>
                                @else
                                    <span class="fw-bold text-warning font-size-13">
                                        <i class="mdi mdi-alert me-1"></i> Missing
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Key Details Table --}}
                    <div class="table-responsive rounded border mb-0">
                        <table class="table table-sm table-bordered align-middle font-size-12 mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted bg-light" style="width: 35%;">Sales Unit</td>
                                    <td class="fw-bold text-dark">{{ $product->sales_unit }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted bg-light">Online Storefront</td>
                                    <td>
                                        @if($product->storeProduct)
                                            <span class="badge bg-success-subtle text-success">Listed in Online Store</span>
                                            <a href="{{ route('store.product.edit', $product->slug) }}" class="ms-2 font-size-11 text-primary">Edit Store Listing</a>
                                        @else
                                            <span class="text-muted">Not listed on online storefront</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted bg-light">Registered Date</td>
                                    <td class="text-dark">{{ $product->created_at->format('M d, Y h:i A') }} ({{ $product->created_at->diffForHumans() }})</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="modal-footer bg-light border-top px-4 py-2.5 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 font-size-12" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary rounded-pill px-4 font-size-12 fw-medium shadow-sm" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#editProduct{{ $product->id }}">
                        <i class="mdi mdi-pencil me-1"></i> Edit Product
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- EDIT PRODUCT MODAL --}}
    <div class="modal fade" id="editProduct{{ $product->id }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="editProductTitle{{ $product->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" action="{{ route('updateProduct') }}" style="width: 100%">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                    {{-- Header --}}
                    <div class="modal-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0" id="editProductTitle{{ $product->id }}">
                                <i class="bx bx-edit me-1 text-primary"></i> Edit Product: {{ $product->name }}
                            </h5>
                            <small class="text-muted">Update catalog details, sales unit, or retail pricing.</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold font-size-12 text-dark mb-1">Product Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-tag"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0 font-size-13" value="{{ $product->name }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold font-size-12 text-dark mb-1">Sales Unit <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-cube"></i></span>
                                    <input type="text" name="sales_unit" class="form-control border-start-0 font-size-13" value="{{ $product->sales_unit }}" placeholder="Loaf / Pcs / Box" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold font-size-12 text-dark mb-1">Selling Price (₦) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light font-size-13">₦</span>
                                <input type="number" step="0.01" min="0" name="selling_price" value="{{ $product->selling_price }}" class="form-control font-size-13" required>
                            </div>
                            <small class="text-muted font-size-11 mt-1 d-block">
                                <i class="bx bx-info-circle me-1"></i>Any price changes will automatically dispatch an email notice to all administrators.
                            </small>
                        </div>

                        <div class="p-3 bg-light rounded-3 border">
                            <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-between p-0">
                                <div>
                                    <label class="form-check-label fw-bold text-dark d-block font-size-13 mb-0" for="editActive{{ $product->id }}">
                                        Mark as Active
                                    </label>
                                    <small class="text-muted font-size-11">Active products appear on POS terminals and storefronts for commercial sales.</small>
                                </div>
                                <input class="form-check-input ms-3 float-none" type="checkbox" name="is_active" id="editActive{{ $product->id }}" {{ $product->is_active ? 'checked' : '' }} style="cursor: pointer; width: 36px; height: 20px;">
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="modal-footer bg-light border-top px-4 py-2.5 d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary rounded-pill px-4 font-size-12" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 font-size-12 fw-medium shadow-sm">
                            <i class="bx bx-check me-1 font-size-14"></i> Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- DELETE PRODUCT MODAL --}}
    <div class="modal fade" id="deleteProduct{{ $product->id }}" tabindex="-1" aria-labelledby="deleteProductTitle{{ $product->id }}" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <form method="POST" action="{{ route('deleteProduct') }}" style="width: 100%">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                    <div class="modal-body text-center p-4">
                        <div class="avatar-md mx-auto mb-3">
                            <span class="avatar-title rounded-circle bg-danger-subtle text-danger font-size-28">
                                <i class="bx bx-trash"></i>
                            </span>
                        </div>
                        <h5 class="fw-bold text-dark font-size-16 mb-2">Delete Commercial Product?</h5>
                        <p class="text-muted font-size-13 mb-3">
                            You are about to permanently delete <strong>{{ $product->name }}</strong> from the commercial catalog.
                        </p>

                        @if($product->recipe)
                            <div class="alert alert-danger border-0 font-size-12 text-start mb-4 py-2.5 px-3">
                                <i class="bx bx-error-circle me-1 font-size-15 align-middle"></i>
                                <strong>Deletion Blocked:</strong> This product has an active recipe attached. You must remove or detach the recipe formulation before deleting this product.
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="button" class="btn btn-secondary rounded-pill px-4 font-size-12" data-bs-dismiss="modal">Close</button>
                            </div>
                        @else
                            <div class="d-flex justify-content-center gap-2 mt-4">
                                <button type="button" class="btn btn-secondary rounded-pill px-4 font-size-12" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger rounded-pill px-4 font-size-12 fw-medium shadow-sm">
                                    <i class="bx bx-trash me-1"></i> Confirm Deletion
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach
