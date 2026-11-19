@foreach($customers as $customer)

    {{-- VIEW MODAL --}}
    <div class="modal fade" id="viewCustomer{{ $customer->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
                
                <div class="modal-header border-bottom py-3 px-4 bg-white">
                    <div>
                        <small class="text-muted text-uppercase font-size-11 fw-semibold">Customer profile</small>
                        <h5 class="modal-title font-size-16 fw-bold text-dark mt-0.5">Account overview</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    {{-- HERO PROFILE BANNER --}}
                    <div class="d-flex align-items-center gap-3 bg-light rounded p-3 mb-4 border border-light">
                        <div class="avatar-lg flex-shrink-0" style="width: 60px; height: 60px;">
                            <span class="avatar-title rounded-circle font-size-22 fw-bold text-white shadow-sm"
                                  style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                                {{ $customer->initials }}
                            </span>
                        </div>
                        <div class="min-w-0 flex-grow-1">
                            <h4 class="mb-1 fw-bold text-dark font-size-18">{{ $customer->name }}</h4>
                            <div class="d-flex align-items-center gap-1 flex-wrap">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill font-size-10 px-2 py-0.5">
                                    Storefront customer
                                </span>
                                <span class="badge bg-{{ $customer->status === 'active' ? 'success' : 'warning' }}-subtle text-{{ $customer->status === 'active' ? 'success' : 'warning' }} border border-{{ $customer->status === 'active' ? 'success' : 'warning' }}-subtle rounded-pill font-size-10 px-2 py-0.5">
                                    {{ ucfirst($customer->status) }}
                                </span>
                                <span class="badge bg-{{ $customer->email_verified_at ? 'success' : 'warning' }}-subtle text-{{ $customer->email_verified_at ? 'success' : 'warning' }} border border-{{ $customer->email_verified_at ? 'success' : 'warning' }}-subtle rounded-pill font-size-10 px-2 py-0.5">
                                    {{ $customer->email_verified_at ? 'Email verified' : 'Email unverified' }}
                                </span>
                            </div>
                            <div class="d-flex flex-wrap gap-3 mt-2 font-size-12 text-muted">
                                <a href="mailto:{{ $customer->email }}" class="text-reset text-decoration-none">
                                    <i class="bx bx-envelope me-1 text-secondary"></i>{{ $customer->email }}
                                </a>
                                @if($customer->phone)
                                    <a href="tel:{{ $customer->phone }}" class="text-reset text-decoration-none">
                                        <i class="bx bx-phone me-1 text-secondary"></i>{{ $customer->phone }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- 5 SUMMARY ORDER METRIC TILES --}}
                    <div class="row g-2 mb-4">
                        <div class="col-6 col-md">
                            <div class="border rounded p-3 h-100 bg-white">
                                <small class="text-muted d-block font-size-11 text-uppercase fw-semibold mb-1">All orders</small>
                                <strong class="h4 mb-0 fw-bold text-dark">{{ $customer->sales_count }}</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md">
                            <div class="border rounded p-3 h-100 bg-white">
                                <small class="text-muted d-block font-size-11 text-uppercase fw-semibold mb-1">Paid</small>
                                <strong class="h4 mb-0 fw-bold text-success">{{ $customer->paid_orders_count }}</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md">
                            <div class="border rounded p-3 h-100 bg-white">
                                <small class="text-muted d-block font-size-11 text-uppercase fw-semibold mb-1">Pending</small>
                                <strong class="h4 mb-0 fw-bold text-warning">{{ $customer->pending_orders_count }}</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md">
                            <div class="border rounded p-3 h-100 bg-white">
                                <small class="text-muted d-block font-size-11 text-uppercase fw-semibold mb-1">Needs attention</small>
                                <strong class="h4 mb-0 fw-bold text-danger">{{ $customer->attention_orders_count }}</strong>
                            </div>
                        </div>
                        <div class="col-12 col-md">
                            <div class="border rounded p-3 h-100 bg-white">
                                <small class="text-muted d-block font-size-11 text-uppercase fw-semibold mb-1">Confirmed spend</small>
                                <strong class="h5 mb-0 fw-bold text-dark">₦{{ number_format((float) $customer->confirmed_order_total, 2) }}</strong>
                            </div>
                        </div>
                    </div>

                    {{-- TWO COLUMN CONTACT & LATEST ORDER DETAILS --}}
                    <div class="row g-4">
                        <div class="col-lg-5">
                            <h6 class="text-uppercase text-muted font-size-11 fw-bold mb-3">Contact &amp; account</h6>
                            <dl class="row mb-0 font-size-12 gy-2">
                                <dt class="col-5 text-muted">Account status</dt>
                                <dd class="col-7 fw-medium text-dark">{{ ucfirst($customer->status) }}</dd>

                                <dt class="col-5 text-muted">Email verified</dt>
                                <dd class="col-7 text-dark">{{ $customer->email_verified_at ? $customer->email_verified_at->format('d M, Y H:i') : 'Not verified' }}</dd>

                                <dt class="col-5 text-muted">Phone number</dt>
                                <dd class="col-7 text-dark">{{ $customer->phone ?: 'Not provided' }}</dd>

                                <dt class="col-5 text-muted">Saved address</dt>
                                <dd class="col-7 text-dark">{{ $customer->address ?: 'Not provided' }}</dd>

                                <dt class="col-5 text-muted">Customer since</dt>
                                <dd class="col-7 text-dark">{{ optional($customer->created_at)->format('d M, Y H:i') }}</dd>
                            </dl>
                        </div>

                        <div class="col-lg-7">
                            <h6 class="text-uppercase text-muted font-size-11 fw-bold mb-3">Latest order</h6>
                            @if($customer->latest_order_reference)
                                <div class="bg-light rounded p-3 border border-light">
                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                                        <strong class="font-size-14 text-dark">{{ $customer->latest_order_reference }}</strong>
                                        <strong class="font-size-14 text-dark">₦{{ number_format((float) $customer->latest_order_total, 2) }}</strong>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill font-size-10 px-2 py-0.5">
                                            {{ ucfirst(str_replace('_', ' ', $customer->latest_order_payment_status)) }}
                                        </span>
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill font-size-10 px-2 py-0.5">
                                            {{ ucfirst(str_replace('_', ' ', $customer->latest_order_status)) }}
                                        </span>
                                    </div>
                                    <small class="text-muted d-block font-size-11">
                                        <i class="bx bx-calendar me-1"></i>{{ \Illuminate\Support\Carbon::parse($customer->latest_order_date)->format('d M, Y H:i') }}
                                    </small>
                                    @if($customer->latest_delivery_address)
                                        <small class="d-block mt-2 font-size-11 text-dark">
                                            <i class="bx bx-map-pin text-secondary me-1"></i>{{ $customer->latest_delivery_address }}
                                        </small>
                                    @endif
                                    @if($customer->latest_delivery_phone)
                                        <small class="d-block font-size-11 text-dark">
                                            <i class="bx bx-phone text-secondary me-1"></i>{{ $customer->latest_delivery_phone }}
                                        </small>
                                    @endif
                                </div>
                            @else
                                <div class="bg-light rounded p-3 text-muted font-size-12">
                                    This customer has not placed an order yet.
                                </div>
                            @endif

                            <div class="mt-3">
                                <a href="{{ route('customers.orders', $customer) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1 font-size-12 fw-medium">
                                    <i class="bx bx-receipt me-1"></i> View all orders
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top py-2.5 px-4">
                    <button type="button" class="btn btn-dark btn-sm rounded-pill px-3" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    {{-- DELETE MODAL --}}
    <div class="modal fade" id="deleteCustomer{{ $customer->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ url('admin/deleteCustomer') }}" class="w-100">
                @csrf
                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
                    <div class="modal-body text-center p-4">
                        <div class="avatar-lg mx-auto mb-3 text-danger d-flex align-items-center justify-content-center bg-danger-subtle rounded-circle" style="width: 56px; height: 56px;">
                            <i class="bx bx-trash font-size-28"></i>
                        </div>
                        <h5 class="fw-bold text-danger mb-1 font-size-16">Remove Customer?</h5>
                        <p class="text-muted font-size-13 mb-4">
                            You are about to remove <strong>{{ $customer->name }}</strong>'s storefront account.
                        </p>
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-light btn-sm rounded-pill px-4 w-50" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger btn-sm rounded-pill px-4 w-50 fw-semibold">Confirm Deletion</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endforeach
