@extends('admin.layout.dashboard')

@section('content')
<style>
    /* ==========================================================================
       MOSHEL EXECUTIVE CUSTOMER ORDERS & LEDGER STYLES
       ========================================================================== */
    .thermal-receipt-wrapper {
        background: #f8fafc;
        padding: 20px;
        border-radius: 12px;
    }

    .thermal-paper {
        width: 80mm;
        max-width: 100%;
        margin: 0 auto;
        background: #ffffff;
        color: #000000;
        font-family: 'SFMono-Regular', Menlo, Monaco, Consolas, 'Courier New', Courier, monospace;
        padding: 24px 20px;
        border-radius: 6px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        position: relative;
    }

    .receipt-dashed-line {
        border-top: 1px dashed #000000;
        margin: 10px 0;
    }
    .receipt-double-line {
        border-top: 2px solid #000000;
        margin: 10px 0;
    }

    .receipt-barcode {
        height: 36px;
        background: repeating-linear-gradient(
            90deg,
            #000000,
            #000000 2px,
            transparent 2px,
            transparent 4px,
            #000000 4px,
            #000000 7px,
            transparent 7px,
            transparent 9px
        );
        margin: 12px auto 6px;
        width: 75%;
    }

    @media print {
        body * {
            visibility: hidden;
        }
        #thermalPrintContent, #thermalPrintContent * {
            visibility: visible;
        }
        #thermalPrintContent {
            position: absolute;
            left: 0;
            top: 0;
            width: 80mm;
            margin: 0;
            padding: 8px;
            box-shadow: none;
            border: none;
        }
        .modal, .modal-backdrop {
            display: none !important;
        }
    }
</style>

@php
    $totalOrdersCount = $orders instanceof \Illuminate\Pagination\AbstractPaginator ? $orders->total() : $orders->count();
    $orderCollection = $orders instanceof \Illuminate\Pagination\AbstractPaginator ? $orders->getCollection() : $orders;
    $confirmedSpend = (float) $orderCollection->where('payment_status', 'paid')->sum('payable_amount');
    $paidOrdersCount = $orderCollection->where('payment_status', 'paid')->count();
    $pendingOrdersCount = $orderCollection->where('payment_status', 'pending')->count();
    $avgOrderValue = $paidOrdersCount > 0 ? ($confirmedSpend / $paidOrdersCount) : 0;
@endphp

{{-- =====================================================
     BREADCRUMB & HEADER
     ===================================================== --}}
<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between pb-2">
            <div>
                <h4 class="mb-sm-0 font-size-18 fw-bold">Customer Orders & Ledger</h4>
                <ol class="breadcrumb m-0 mt-1">
                    <li class="breadcrumb-item active">{{ $customer->name }}</li>
                </ol>
            </div>
            <a href="{{ route('customers') }}" class="btn btn-outline-secondary rounded-pill px-3 font-size-12 fw-medium shadow-sm">
                <i class="bx bx-arrow-back me-1"></i> Back to customers
            </a>
        </div>
    </div>
</div>

{{-- =====================================================
     CUSTOMER PROFILE BANNER
     ===================================================== --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-lg flex-shrink-0">
                    <span class="avatar-title rounded-circle fw-bold display-6 shadow-sm"
                          style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: #ffffff;">
                        {{ $customer->initials }}
                    </span>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h4 class="mb-0 fw-bold text-dark">{{ $customer->name }}</h4>
                        @if($customer->is_active)
                            <span class="badge bg-success-subtle text-success font-size-11">
                                <i class="bx bx-check-circle me-1"></i>Verified Account
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-warning font-size-11">
                                <i class="bx bx-time-five me-1"></i>Awaiting Verification
                            </span>
                        @endif
                    </div>
                    <div class="d-flex flex-wrap gap-3 small text-muted">
                        <a href="mailto:{{ $customer->email }}" class="text-reset text-decoration-none fw-semibold">
                            <i class="bx bx-envelope me-1 text-primary"></i>{{ $customer->email }}
                        </a>
                        @if($customer->phone)
                            <a href="tel:{{ $customer->phone }}" class="text-reset text-decoration-none fw-semibold">
                                <i class="bx bx-phone me-1 text-success"></i>{{ $customer->phone }}
                            </a>
                        @endif
                        <span><i class="bx bx-calendar me-1 text-muted"></i>Joined {{ $customer->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="mailto:{{ $customer->email }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 font-size-12 fw-medium">
                    <i class="bx bx-envelope me-1"></i> Email Customer
                </a>
                @if($customer->phone)
                    <a href="tel:{{ $customer->phone }}" class="btn btn-sm btn-outline-success rounded-pill px-3 font-size-12 fw-medium">
                        <i class="bx bx-phone-call me-1"></i> Call Customer
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- =====================================================
     EXECUTIVE KPI STATS ROW
     ===================================================== --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Total Orders"
            :value="$totalOrdersCount"
            :subtitle="$paidOrdersCount . ' Settled'"
            subicon="bx bx-cart"
            icon="bx bx-shopping-bag"
            color="success"
        />
    </div>

    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Confirmed Spend"
            :value="'₦' . number_format($confirmedSpend, 2)"
            subtitle="Lifetime Paid Revenue"
            subicon="bx bx-wallet"
            icon="bx bx-credit-card"
            color="primary"
        />
    </div>

    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Avg. Order Value"
            :value="'₦' . number_format($avgOrderValue, 2)"
            subtitle="Per Paid Checkout"
            subicon="bx bx-trending-up"
            icon="bx bx-pie-chart-alt-2"
            color="info"
        />
    </div>

    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Pending Orders"
            :value="$pendingOrdersCount"
            subtitle="Awaiting Settlement"
            subicon="bx bx-time-five"
            icon="bx bx-loader-alt"
            color="warning"
        />
    </div>
</div>

{{-- =====================================================
     ORDERS DIRECTORY TABLE CARD
     ===================================================== --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
    
    <div class="card-header bg-white border-bottom py-3 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
            <h5 class="card-title mb-0 font-size-15 fw-bold text-dark">
                <i class="bx bx-history text-primary me-1"></i> Transaction History
            </h5>
            <span class="badge bg-light text-dark font-size-11 border px-2 py-0.5 rounded-pill">
                {{ $totalOrdersCount }} records
            </span>
        </div>
        <div class="text-muted small">
            Click 'Receipt' to view customer's print-ready sales docket
        </div>
    </div>

    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-hover align-middle dt-responsive nowrap w-100 mb-0 font-size-13">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 40px;">#</th>
                        <th style="min-width: 140px;">Reference &amp; Date</th>
                        <th style="min-width: 220px;">Items Purchased</th>
                        <th style="min-width: 130px;">Channel &amp; Method</th>
                        <th style="min-width: 130px;">Payment Status</th>
                        <th style="min-width: 180px;">Delivery Destination</th>
                        <th style="min-width: 140px;">Total Paid</th>
                        <th class="text-center" style="width: 100px;">Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        @php
                            $isPaid = $order->payment_status === 'paid';
                            $isOnline = (bool) $order->is_online;
                            $paymentClass = match($order->payment_status) {
                                'paid' => 'bg-success-subtle text-success border border-success-subtle',
                                'pending' => 'bg-warning-subtle text-warning border border-warning-subtle',
                                'review' => 'bg-danger-subtle text-danger border border-danger-subtle',
                                default => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                            };
                        @endphp
                        <tr>
                            {{-- S/N --}}
                            <td class="text-center text-muted fw-semibold">
                                {{ $loop->iteration }}
                            </td>

                            {{-- REFERENCE & DATE --}}
                            <td>
                                <strong class="d-block font-monospace text-primary font-size-13">{{ $order->reference_no }}</strong>
                                <small class="text-muted"><i class="bx bx-time-five me-1"></i>{{ optional($order->created_at)->format('d M, Y H:i') }}</small>
                            </td>

                            {{-- ITEMS PURCHASED --}}
                            <td>
                                @forelse($order->items as $item)
                                    <div class="font-size-13 mb-1">
                                        <span class="fw-semibold text-dark">{{ optional(optional($item->product)->storeProduct)->store_title ?? optional($item->product)->name ?? 'Product' }}</span>
                                        <span class="text-muted small">× {{ $item->quantity }}</span>
                                    </div>
                                @empty
                                    <span class="text-muted small">No items recorded</span>
                                @endforelse
                            </td>

                            {{-- CHANNEL & METHOD --}}
                            <td>
                                <span class="badge {{ $isOnline ? 'bg-info-subtle text-info' : 'bg-primary-subtle text-primary' }} font-size-11 mb-1">
                                    <i class="bx {{ $isOnline ? 'bx-globe' : 'bx-store' }} me-1"></i>{{ $isOnline ? 'Storefront' : 'POS Counter' }}
                                </span>
                                <small class="d-block text-muted fw-semibold">{{ $order->payment_method ?: 'Paystack' }}</small>
                            </td>

                            {{-- PAYMENT & ORDER STATUS --}}
                            <td>
                                <span class="badge {{ $paymentClass }} fw-bold px-2 py-1 font-size-11">
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                </span>
                                <small class="d-block text-muted mt-1">{{ ucfirst(str_replace('_', ' ', $order->order_status)) }}</small>
                                @if($order->payment_status === 'review')
                                    <small class="d-block text-danger fw-semibold"><i class="bx bx-error-circle me-1"></i>Review required</small>
                                @endif
                            </td>

                            {{-- DELIVERY DESTINATION --}}
                            <td>
                                <div class="small fw-semibold text-dark text-truncate" style="max-width: 220px;" title="{{ $order->delivery_address ?: 'Store Pickup / Counter Sale' }}">
                                    <i class="bx {{ $order->delivery_address ? 'bx-map-pin text-danger' : 'bx-building-house text-muted' }} me-1"></i>{{ $order->delivery_address ?: 'Store Pickup / Counter Sale' }}
                                </div>
                                @if($order->delivery_phone)
                                    <small class="text-muted d-block mt-0.5"><i class="bx bx-phone me-1"></i>{{ $order->delivery_phone }}</small>
                                @endif
                            </td>

                            {{-- TOTAL PAID --}}
                            <td class="text-nowrap">
                                <strong class="text-dark font-size-14">₦{{ number_format($order->payable_amount, 2) }}</strong>
                                <small class="d-block text-muted font-size-11">Items ₦{{ number_format($order->total_amount, 2) }} · Ship ₦{{ number_format($order->shipping_fee ?? 0, 2) }}</small>
                            </td>

                            {{-- RECEIPT ACTION --}}
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1 font-size-12 fw-medium shadow-sm" onclick="viewSaleDetails('{{ addslashes($order->reference_no) }}')">
                                    <i class="bx bx-receipt me-1"></i> Receipt
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bx bx-receipt text-muted font-size-24 d-block mb-1"></i>
                                This customer has no orders yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION CONTAINER (if paginator is used) --}}
        @if($orders instanceof \Illuminate\Pagination\AbstractPaginator && $orders->hasPages())
            <div class="card-footer bg-white border-top py-3 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3">
                <div class="text-muted small">
                    Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} recorded orders
                </div>
                <div>
                    {{ $orders->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

@include('shared.modals.sales.sale-receipt-modal', [
    'modalTitle' => 'Customer Order Receipt',
    'detailsEndpoint' => url('admin/sales/details'),
    'customerName' => $customer->name ?? null,
])
@endsection
