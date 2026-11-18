@extends('admin.layout.dashboard')

@section('content')

@php
    $totalCustomers = $customers->count();
    $newThisMonth = $customers->where('created_at', '>=', now()->startOfMonth())->count();
    $awaitingVerification = $customers->filter(fn ($customer) => $customer->status !== 'active' || !$customer->email_verified_at)->count();
    $totalConfirmedSpend = (float) $customers->sum('confirmed_order_total');
@endphp

{{-- =====================================================
     PAGE HEADER & ACTION BAR
     ===================================================== --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-sm-0 font-size-18 text-primary fw-bold">
                    <i class="bx bx-group me-1 align-middle" style="color: #059669;"></i> Customers
                </h4>
                <p class="text-muted font-size-12 mb-0">
                    View registered storefront customers, account activity, contact details, and order history.
                </p>
            </div>
            <div class="page-title-right d-flex align-items-center gap-2">
                <a href="{{ url('/admin/salesHistory') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm font-size-12">
                    <i class="bx bx-receipt me-1 align-middle"></i> Sales &amp; Order History
                </a>
                <a href="{{ url('/admin/pos') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm font-size-12 fw-semibold">
                    <i class="bx bx-calculator me-1 align-middle"></i> POS Terminal
                </a>
                <ol class="breadcrumb m-0 ms-2 d-none d-md-flex">
                    <li class="breadcrumb-item active">Customers</li>
                </ol>
            </div>
        </div>
    </div>
</div>


{{-- =====================================================
     EXECUTIVE KPI SUMMARY CARDS
     ===================================================== --}}
<div class="row g-3 mb-4">

    {{-- Card 1: Total Customers --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #059669 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Total Customers</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ $totalCustomers }}</h4>
                        <small class="text-success font-size-11 fw-medium">
                            <i class="bx bx-user-check me-1"></i>Storefront Accounts
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-success-subtle text-success">
                            <i class="bx bx-user"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: New This Month --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">New This Month</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ $newThisMonth }}</h4>
                        <small class="text-info font-size-11 fw-medium">
                            <i class="bx bx-calendar me-1"></i>{{ now()->format('F Y') }}
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-info-subtle text-info">
                            <i class="bx bx-user-plus"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 3: Awaiting Verification --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Awaiting Verification</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ $awaitingVerification }}</h4>
                        <small class="text-warning font-size-11 fw-semibold">
                            <i class="bx bx-envelope me-1"></i>Pending Activation
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-warning-subtle text-warning">
                            <i class="bx bx-mail-send"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 4: Confirmed Total Spend --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #4f46e5 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div style="min-width: 0;">
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Confirmed Spend</p>
                        <h4 class="mb-0 fw-bold text-dark text-truncate" title="₦{{ number_format($totalConfirmedSpend, 2) }}">
                            ₦{{ number_format($totalConfirmedSpend, 2) }}
                        </h4>
                        <small class="text-primary font-size-11 fw-medium">
                            <i class="bx bx-wallet me-1"></i>Lifetime Paid Sales
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0 ms-2">
                        <span class="avatar-title rounded-circle font-size-20 bg-primary-subtle text-primary">
                            <i class="bx bx-credit-card"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


{{-- =====================================================
     MAIN CUSTOMER DIRECTORY CARD
     ===================================================== --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
    
    <div class="card-header bg-white border-bottom py-3 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h5 class="card-title mb-0 fw-bold text-dark">
                <i class="bx bx-user-circle me-1 text-primary"></i> Customer Directory
            </h5>
            <small class="text-muted">View registered storefront customers and their account activity.</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill bg-light text-dark border px-3 py-1 font-size-11">
                {{ $totalCustomers }} {{ Str::plural('Customer', $totalCustomers) }} Registered
            </span>
        </div>
    </div>

    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-hover align-middle dt-responsive nowrap w-100 font-size-13 mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">S/N</th>
                        <th style="min-width: 200px;">Customer</th>
                        <th style="min-width: 180px;">Email Address</th>
                        <th style="min-width: 130px;">Phone</th>
                        <th style="min-width: 140px;">Account Status</th>
                        <th class="text-center" style="min-width: 80px;">Orders</th>
                        <th style="min-width: 140px;">Confirmed Spend</th>
                        <th style="min-width: 110px;">Joined</th>
                        <th class="text-center" style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            {{-- S/N --}}
                            <td class="text-center text-muted fw-semibold">
                                {{ $loop->iteration }}
                            </td>

                            {{-- CUSTOMER PROFILE --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3 flex-shrink-0">
                                        <span class="avatar-title rounded-circle font-size-13 fw-bold shadow-sm"
                                              style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: #ffffff;">
                                            {{ $customer->initials }}
                                        </span>
                                    </div>
                                    <div class="overflow-hidden">
                                        <a href="javascript:void(0);"
                                           class="font-size-14 mb-0 fw-bold text-dark text-decoration-none d-block text-truncate"
                                           data-bs-toggle="modal"
                                           data-bs-target="#viewCustomer{{ $customer->id }}">
                                            {{ $customer->name }}
                                        </a>
                                        <small class="text-muted font-size-11">
                                            @if($customer->address)
                                                <i class="bx bx-map-pin me-0.5"></i>{{ Str::limit($customer->address, 24) }}
                                            @else
                                                Customer account
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </td>

                            {{-- EMAIL ADDRESS --}}
                            <td>
                                <div class="d-flex align-items-center gap-1.5">
                                    <span class="font-monospace text-dark font-size-12 text-truncate" style="max-width: 170px;">
                                        {{ $customer->email }}
                                    </span>
                                    <button type="button" 
                                            class="btn btn-sm btn-link text-muted p-0" 
                                            onclick="navigator.clipboard.writeText('{{ $customer->email }}'); this.classList.add('text-success'); setTimeout(() => this.classList.remove('text-success'), 1500);" 
                                            title="Copy email address">
                                        <i class="bx bx-copy font-size-14"></i>
                                    </button>
                                </div>
                            </td>

                            {{-- PHONE --}}
                            <td>
                                <span class="font-size-12 text-dark">
                                    {{ $customer->phone ?: 'Not provided' }}
                                </span>
                            </td>

                            {{-- ACCOUNT STATUS & VERIFICATION --}}
                            <td>
                                <div>
                                    @if($customer->status === 'active')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-size-11 px-2 py-0.5">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill font-size-11 px-2 py-0.5">
                                            Inactive
                                        </span>
                                    @endif

                                    <small class="d-block mt-0.5 text-muted font-size-11">
                                        {{ $customer->email_verified_at ? 'Email verified' : 'Email unverified' }}
                                    </small>
                                </div>
                            </td>

                            {{-- ORDERS COUNT --}}
                            <td class="text-center">
                                @if($customer->sales_count > 0)
                                    <a href="{{ route('customers.orders', $customer) }}"
                                       class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 font-size-11 fw-bold text-decoration-none"
                                       title="View {{ $customer->sales_count }} orders">
                                        {{ $customer->sales_count }}
                                    </a>
                                @else
                                    <span class="badge bg-light text-muted border rounded-pill px-2 py-0.5 font-size-11">
                                        0
                                    </span>
                                @endif
                            </td>

                            {{-- CONFIRMED SPEND --}}
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark font-size-13">
                                        ₦{{ number_format((float) $customer->confirmed_order_total, 2) }}
                                    </span>
                                    @if($customer->latest_order_reference)
                                        <small class="text-muted font-size-11 text-truncate" title="Latest: {{ $customer->latest_order_reference }}">
                                            Ref: {{ $customer->latest_order_reference }}
                                        </small>
                                    @endif
                                </div>
                            </td>

                            {{-- JOINED --}}
                            <td data-order="{{ optional($customer->created_at)->timestamp }}">
                                <span class="text-muted font-size-12">
                                    {{ optional($customer->created_at)->format('d M, Y') }}
                                </span>
                            </td>

                            {{-- ACTIONS --}}
                            <td class="text-center">
                                <div class="d-inline-flex align-items-center gap-1">
                                    {{-- View Customer Overview --}}
                                    <button type="button"
                                            class="btn btn-outline-info btn-sm rounded-circle p-0"
                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewCustomer{{ $customer->id }}"
                                            title="View Customer Profile">
                                        <i class="bx bx-show font-size-15"></i>
                                    </button>

                                    {{-- View Order History --}}
                                    <a href="{{ route('customers.orders', $customer) }}"
                                       class="btn btn-outline-primary btn-sm rounded-circle p-0"
                                       style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                       title="View Order History">
                                        <i class="bx bx-receipt font-size-15"></i>
                                    </a>

                                    {{-- Remove Customer Account --}}
                                    <button type="button"
                                            class="btn btn-outline-danger btn-sm rounded-circle p-0"
                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteCustomer{{ $customer->id }}"
                                            title="Remove Customer">
                                        <i class="bx bx-trash font-size-15"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <div class="avatar-md bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2">
                                    <i class="bx bx-user-x font-size-28 text-secondary opacity-50"></i>
                                </div>
                                <p class="mb-0 font-size-13">No customers have registered yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- =====================================================
     MODALS: VIEW CUSTOMER & DELETE CONFIRMATION
     ===================================================== --}}
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

@endsection
