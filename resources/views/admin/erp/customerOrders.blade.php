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
    {{-- Card 1: Total Orders --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #059669 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Total Orders</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ $totalOrdersCount }}</h4>
                        <small class="text-success font-size-11 fw-semibold">
                            <i class="bx bx-cart me-1"></i>{{ $paidOrdersCount }} Settled
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-success-subtle text-success">
                            <i class="bx bx-shopping-bag"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Confirmed Total Spend --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #4f46e5 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div style="min-width: 0;">
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Confirmed Spend</p>
                        <h4 class="mb-0 fw-bold text-dark text-truncate" title="₦{{ number_format($confirmedSpend, 2) }}">
                            ₦{{ number_format($confirmedSpend, 2) }}
                        </h4>
                        <small class="text-primary font-size-11 fw-medium">
                            <i class="bx bx-wallet me-1"></i>Lifetime Paid Revenue
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

    {{-- Card 3: Average Order Value --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div style="min-width: 0;">
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Avg. Order Value</p>
                        <h4 class="mb-0 fw-bold text-dark text-truncate" title="₦{{ number_format($avgOrderValue, 2) }}">
                            ₦{{ number_format($avgOrderValue, 2) }}
                        </h4>
                        <small class="text-info font-size-11 fw-semibold">
                            <i class="bx bx-trending-up me-1"></i>Per Paid Checkout
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0 ms-2">
                        <span class="avatar-title rounded-circle font-size-20 bg-info-subtle text-info">
                            <i class="bx bx-pie-chart-alt-2"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 4: Pending / Open Orders --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Pending Orders</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ $pendingOrdersCount }}</h4>
                        <small class="text-warning font-size-11 fw-semibold">
                            <i class="bx bx-time-five me-1"></i>Awaiting Settlement
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-warning-subtle text-warning">
                            <i class="bx bx-loader-alt"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- =====================================================
     MAIN ORDER HISTORY & LEDGER CARD
     ===================================================== --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-white border-bottom py-3 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h5 class="card-title mb-0 fw-bold text-dark">
                <i class="bx bx-receipt me-1 text-primary"></i> Order History & Ledger
            </h5>
            <small class="text-muted">Complete transactional records, delivery routes, and printable 80mm receipts.</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill bg-light text-dark border px-3 py-1 font-size-11">
                {{ $totalOrdersCount }} {{ Str::plural('Order', $totalOrdersCount) }} Recorded
            </span>
        </div>
    </div>

    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-hover align-middle dt-responsive nowrap w-100 font-size-13 mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">S/N</th>
                        <th style="min-width: 140px;">Reference & Date</th>
                        <th style="min-width: 200px;">Items Purchased</th>
                        <th style="min-width: 130px;">Channel & Method</th>
                        <th style="min-width: 140px;">Payment & Status</th>
                        <th style="min-width: 180px;">Delivery Destination</th>
                        <th style="min-width: 130px;">Total Paid</th>
                        <th class="text-center" style="width: 110px;">Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        @php
                            $isOnline = $order->user_type === 'customer';
                            $paymentClass = match($order->payment_status) {
                                'paid' => 'bg-success-subtle text-success',
                                'pending' => 'bg-warning-subtle text-warning',
                                'failed', 'review' => 'bg-danger-subtle text-danger',
                                default => 'bg-secondary-subtle text-secondary'
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

{{-- =====================================================
     THERMAL RECEIPT MODAL
     ===================================================== --}}
<div class="modal fade" id="saleDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom px-4 py-3 bg-light">
                <div class="d-flex align-items-center gap-2">
                    <span class="avatar-xs">
                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle"><i class="bx bx-receipt font-size-18"></i></span>
                    </span>
                    <h5 class="modal-title fw-bold text-dark mb-0">Customer Order Receipt</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4 thermal-receipt-wrapper">
                <div id="saleDetailsContent">
                    {{-- DYNAMIC THERMAL RECEIPT CONTAINER --}}
                </div>
            </div>

            <div class="modal-footer border-top px-4 py-3 bg-white d-flex justify-content-between">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success px-4 fw-bold" onclick="printReceipt()">
                    <i class="bx bx-printer me-1"></i> Print 80mm Receipt
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    async function viewSaleDetails(saleReference) {
        const modal = new bootstrap.Modal(document.getElementById('saleDetailsModal'));
        const content = document.getElementById('saleDetailsContent');
        
        content.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="text-muted small mt-2">Loading receipt details...</p></div>';
        modal.show();

        try {
            const response = await fetch("{{ url('admin/sales/details') }}/" + encodeURIComponent(saleReference));
            const data = await response.json();

            if (data.success) {
                const sale = data.sale;
                let itemsHtml = '';
                sale.items.forEach(item => {
                    const lineTotal = parseFloat(item.quantity) * parseFloat(item.unit_price);
                    itemsHtml += `
                        <tr>
                            <td style="padding: 4px 0;">
                                <div style="font-weight: 700;">${item.product_name}</div>
                                <div style="font-size: 11px; color: #555;">${item.quantity} × ₦${parseFloat(item.unit_price).toLocaleString(undefined, {minimumFractionDigits: 2})}</div>
                            </td>
                            <td style="text-align: right; padding: 4px 0; font-weight: 700;">₦${lineTotal.toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                        </tr>`;
                });

                const subtotal = parseFloat(sale.total_amount || 0);
                const discount = parseFloat(sale.discount_amount || 0);
                const shipping = parseFloat(sale.shipping_fee || 0);
                const payable = parseFloat(sale.payable_amount || 0);

                content.innerHTML = `
                    <div class="thermal-paper" id="thermalPrintContent">
                        <div style="text-align: center; margin-bottom: 8px;">
                            <h5 style="font-weight: 900; margin-bottom: 2px; text-transform: uppercase;">{{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->site_name : "MOSHEL FOOD PRODUCTION" }}</h5>
                            <div style="font-size: 11px; color: #444; line-height: 1.3;">
                                {{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->address : "Sales & Retail Operations" }}<br>
                                Tel: {{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->phone : "+234 800 000 0000" }}
                            </div>
                        </div>

                        <div class="receipt-dashed-line"></div>

                        <div style="text-align: center;">
                            <div style="font-weight: 800; font-size: 13px; letter-spacing: 1px;">CUSTOMER ORDER RECEIPT</div>
                            <div class="receipt-barcode"></div>
                            <div style="font-size: 12px; font-weight: bold; letter-spacing: 0.5px;">${sale.reference_no}</div>
                        </div>

                        <div class="receipt-dashed-line"></div>

                        <div style="font-size: 11px; line-height: 1.5;">
                            <div style="display: flex; justify-content: space-between;">
                                <span>DATE & TIME:</span>
                                <strong>${sale.created_at}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>CUSTOMER:</span>
                                <strong>{{ $customer->name }}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>PAYMENT METHOD:</span>
                                <strong>${sale.payment_method}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>SETTLEMENT:</span>
                                <strong>${(sale.payment_status || 'PAID').toUpperCase()}</strong>
                            </div>
                        </div>

                        <div class="receipt-dashed-line"></div>

                        <table style="width: 100%; border-collapse: collapse; font-size: 11.5px;">
                            <thead>
                                <tr style="border-bottom: 1px dashed #000;">
                                    <th style="text-align: left; padding-bottom: 4px;">ITEM</th>
                                    <th style="text-align: right; padding-bottom: 4px;">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${itemsHtml}
                            </tbody>
                        </table>

                        <div class="receipt-dashed-line"></div>

                        <div style="font-size: 12px; line-height: 1.5;">
                            <div style="display: flex; justify-content: space-between;">
                                <span>SUBTOTAL:</span>
                                <span>₦${subtotal.toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                            </div>
                            ${discount > 0 ? `
                            <div style="display: flex; justify-content: space-between; color: #b91c1c;">
                                <span>DISCOUNT APPLIED:</span>
                                <span>-₦${discount.toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                            </div>` : ''}
                            ${shipping > 0 ? `
                            <div style="display: flex; justify-content: space-between;">
                                <span>SHIPPING FEE:</span>
                                <span>₦${shipping.toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                            </div>` : ''}
                            <div class="receipt-double-line"></div>
                            <div style="display: flex; justify-content: space-between; font-size: 15px; font-weight: 900;">
                                <span>TOTAL PAID:</span>
                                <span>₦${payable.toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                            </div>
                        </div>

                        <div class="receipt-dashed-line"></div>
                        <div style="text-align: center; font-size: 11px; color: #333; line-height: 1.4;">
                            <p style="margin-bottom: 2px; font-weight: 700;">Thank you for your patronage!</p>
                            <p style="margin-bottom: 0;">Freshly baked & produced with excellence.</p>
                        </div>
                    </div>`;
            } else {
                content.innerHTML = `<div class="alert alert-danger m-3">${data.message || 'Transaction details not found.'}</div>`;
            }
        } catch (error) {
            content.innerHTML = `<div class="alert alert-danger m-3">Network error fetching transaction data.</div>`;
        }
    }

    function printReceipt() {
        const printWindow = window.open('', '_blank', 'width=420,height=650');
        const content = document.getElementById('thermalPrintContent').outerHTML;
        
        printWindow.document.write(`
            <!doctype html>
            <html>
                <head>
                    <title>Thermal Receipt</title>
                    <style>
                        body {
                            margin: 0;
                            padding: 10px;
                            background: #ffffff;
                            color: #000000;
                            font-family: 'Courier New', Courier, monospace;
                            width: 80mm;
                            margin: 0 auto;
                        }
                        .thermal-paper {
                            width: 100%;
                            box-shadow: none;
                            border: none;
                            padding: 0;
                        }
                        table { width: 100%; border-collapse: collapse; }
                        @media print {
                            @page {
                                size: 80mm auto;
                                margin: 0;
                            }
                            body {
                                width: 80mm;
                                padding: 6px;
                            }
                        }
                    </style>
                </head>
                <body>
                    ${content}
                    <script>
                        window.onload = function() {
                            window.print();
                            setTimeout(() => { window.close(); }, 600);
                        };
                    <\/script>
                </body>
            </html>
        `);
        printWindow.document.close();
    }
</script>
@endsection
