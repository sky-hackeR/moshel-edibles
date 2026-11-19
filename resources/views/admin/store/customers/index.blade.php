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
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Total Customers"
            :value="$totalCustomers"
            subtitle="Storefront Accounts"
            subicon="bx bx-user-check"
            icon="bx bx-user"
            color="success"
        />
    </div>

    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="New This Month"
            :value="$newThisMonth"
            :subtitle="now()->format('F Y')"
            subicon="bx bx-calendar"
            icon="bx bx-user-plus"
            color="info"
        />
    </div>

    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Awaiting Verification"
            :value="$awaitingVerification"
            subtitle="Pending Activation"
            subicon="bx bx-envelope"
            icon="bx bx-mail-send"
            color="warning"
        />
    </div>

    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Confirmed Spend"
            :value="'₦' . number_format($totalConfirmedSpend, 2)"
            subtitle="Lifetime Paid Sales"
            subicon="bx bx-wallet"
            icon="bx bx-credit-card"
            color="primary"
        />
    </div>
</div>

{{-- =====================================================
     MAIN CUSTOMER DIRECTORY CARD
     ===================================================== --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
    
    <div class="card-header bg-white border-bottom py-3 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
            <h5 class="card-title mb-0 font-size-15 fw-bold text-dark">
                <i class="bx bx-id-card text-primary me-1"></i> Customer Directory
            </h5>
            <span class="badge bg-light text-dark font-size-11 border px-2 py-0.5 rounded-pill">
                {{ $customers->count() }} registered
            </span>
        </div>
        <div class="text-muted small">
            Click on any customer name to inspect orders and account history
        </div>
    </div>

    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-hover align-middle dt-responsive nowrap w-100 mb-0 font-size-13">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 40px;">#</th>
                        <th style="min-width: 220px;">Customer Profile</th>
                        <th style="min-width: 170px;">Email</th>
                        <th style="min-width: 120px;">Phone</th>
                        <th style="min-width: 140px;">Status</th>
                        <th style="min-width: 90px;" class="text-center">Orders</th>
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
                                            {{ ucfirst($customer->status) }}
                                        </span>
                                    @endif

                                    @if($customer->email_verified_at)
                                        <span class="badge bg-light text-success border font-size-10 px-1.5 py-0.5 rounded ms-1" title="Email verified at {{ $customer->email_verified_at }}">
                                            <i class="bx bx-check-circle"></i>
                                        </span>
                                    @else
                                        <span class="badge bg-light text-warning border font-size-10 px-1.5 py-0.5 rounded ms-1" title="Email unverified">
                                            <i class="bx bx-time"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- TOTAL ORDERS --}}
                            <td class="text-center">
                                @if($customer->sales_count > 0)
                                    <a href="{{ route('customers.orders', $customer) }}" class="badge bg-light text-primary border font-size-12 px-2 py-1 text-decoration-none">
                                        {{ $customer->sales_count }}
                                    </a>
                                @else
                                    <span class="text-muted font-size-12">0</span>
                                @endif
                            </td>

                            {{-- CONFIRMED SPEND --}}
                            <td>
                                <strong class="text-dark font-size-13">
                                    ₦{{ number_format((float) $customer->confirmed_order_total, 2) }}
                                </strong>
                            </td>

                            {{-- REGISTRATION DATE --}}
                            <td class="text-muted font-size-12">
                                {{ optional($customer->created_at)->format('d M, Y') }}
                            </td>

                            {{-- ACTIONS --}}
                            <td class="text-center">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-0.5 font-size-11"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewCustomer{{ $customer->id }}"
                                            title="View customer details">
                                        <i class="bx bx-show me-0.5"></i> Profile
                                    </button>
                                    <a href="{{ route('customers.orders', $customer) }}"
                                       class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-0.5 font-size-11"
                                       title="View customer order history">
                                        <i class="bx bx-receipt me-0.5"></i> Orders
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger rounded-pill px-2 py-0.5 font-size-11"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteCustomer{{ $customer->id }}"
                                            title="Remove customer account">
                                        <i class="bx bx-trash"></i>
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

{{-- MODALS: VIEW CUSTOMER & DELETE CONFIRMATION --}}
@include('admin.store.customers.partials.customer-modals', ['customers' => $customers])

@endsection
