@extends('staff.layout.dashboard')

@section('content')
<style>
    /* ==========================================================================
       MOSHEL EXECUTIVE STAFF SALES & ORDER HISTORY DESIGN SYSTEM
       ========================================================================== */
    .sales-kpi-card {
        border: 1px solid #e9ecef;
        border-radius: 14px;
        background: #ffffff;
        padding: 20px;
        transition: all 0.25s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        height: 100%;
    }
    .sales-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        border-color: #cbd5e1;
    }
    .sales-kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    /* Thermal Receipt Styling */
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

    /* Filter Pills */
    .history-filter-pill {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        font-weight: 600;
        font-size: 12px;
        padding: 6px 14px;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .history-filter-pill:hover,
    .history-filter-pill.active {
        background: #556ee6;
        color: #ffffff;
        border-color: #556ee6;
    }

    /* Print styles */
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

{{-- PAGE HEADER --}}
<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between pb-2">
            <div>
                <h4 class="mb-sm-0 font-size-18 fw-bold">My Sales & Order History</h4>
                <p class="text-muted small mb-0">Record of transactions processed at your cashier terminal station.</p>
            </div>
            <div class="page-title-right d-flex gap-2">
                <a href="{{ url('/staff/pos') }}" class="btn btn-primary rounded-pill px-3 fw-bold">
                    <i class="bx bx-store me-1"></i> Open POS Terminal
                </a>
            </div>
        </div>
    </div>
</div>

{{-- KPI METRICS CARDS --}}
@php
    $totalOrdersCount = $sales->count();
    $paidSales = $sales->where('payment_status', 'paid');
    $grossRevenue = $paidSales->sum('payable_amount');
    $cashSales = $sales->where('payment_method', 'Cash');
    $cashRevenue = $cashSales->where('payment_status', 'paid')->sum('payable_amount');
    $transferSales = $sales->where('payment_method', 'Transfer');
    $transferRevenue = $transferSales->where('payment_status', 'paid')->sum('payable_amount');
    $cardSales = $sales->where('payment_method', 'Card');
    $cardRevenue = $cardSales->where('payment_status', 'paid')->sum('payable_amount');
@endphp
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="sales-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted text-uppercase fw-semibold small d-block">My Total Settled Sales</span>
                    <h4 class="fw-extrabold text-primary mb-1 mt-1">₦{{ number_format($grossRevenue, 2) }}</h4>
                    <span class="badge bg-soft-success text-success small fw-semibold">
                        <i class="bx bx-check-circle me-1"></i> {{ $paidSales->count() }} Completed
                    </span>
                </div>
                <div class="sales-kpi-icon bg-primary-subtle text-primary">
                    <i class="bx bx-wallet"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="sales-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted text-uppercase fw-semibold small d-block">Cash Collected</span>
                    <h4 class="fw-extrabold text-success mb-1 mt-1">₦{{ number_format($cashRevenue, 2) }}</h4>
                    <span class="text-muted small">{{ $cashSales->count() }} Cash receipts</span>
                </div>
                <div class="sales-kpi-icon bg-success-subtle text-success">
                    <i class="bx bx-money"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="sales-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted text-uppercase fw-semibold small d-block">Bank Transfers</span>
                    <h4 class="fw-extrabold text-info mb-1 mt-1">₦{{ number_format($transferRevenue, 2) }}</h4>
                    <span class="text-muted small">{{ $transferSales->count() }} Direct transfers</span>
                </div>
                <div class="sales-kpi-icon bg-info-subtle text-info">
                    <i class="bx bx-transfer"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="sales-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted text-uppercase fw-semibold small d-block">Card / POS Terminal</span>
                    <h4 class="fw-extrabold text-warning mb-1 mt-1">₦{{ number_format($cardRevenue, 2) }}</h4>
                    <span class="text-muted small">{{ $cardSales->count() }} Card swipes</span>
                </div>
                <div class="sales-kpi-icon bg-warning-subtle text-warning">
                    <i class="bx bx-credit-card"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MAIN SALES TABLE CARD --}}
<div class="row">
    <div class="col-12">
        <div class="card border shadow-sm" style="border-radius: 14px;">
            <div class="card-header bg-transparent border-bottom p-3">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div>
                        <h5 class="card-title fw-bold mb-0">My Station Sales Log</h5>
                        <p class="text-muted small mb-0">Audited receipt register with 1-click thermal receipt reproduction.</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="history-filter-pill active" onclick="filterHistoryTable('all', this)">All Sales</button>
                        <button type="button" class="history-filter-pill" onclick="filterHistoryTable('cash', this)">Cash</button>
                        <button type="button" class="history-filter-pill" onclick="filterHistoryTable('transfer', this)">Transfer</button>
                        <button type="button" class="history-filter-pill" onclick="filterHistoryTable('card', this)">Card / POS</button>
                    </div>
                </div>
            </div>

            <div class="card-body p-3">
                <table id="datatable" class="table table-hover align-middle table-bordered dt-responsive nowrap w-100">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 130px;">Date & Time</th>
                            <th>Reference</th>
                            <th>Cashier Station</th>
                            <th>Items Sold</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 110px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                        @php
                            $methodSlug = strtolower($sale->payment_method);
                            $itemsCount = $sale->items->count();
                            $unitsCount = $sale->items->sum('quantity');

                            $color = ['Cash' => 'success', 'Transfer' => 'info', 'Card' => 'warning'][$sale->payment_method] ?? 'secondary';
                            $methodIcon = ['Cash' => 'bx-money', 'Transfer' => 'bx-transfer', 'Card' => 'bx-credit-card'][$sale->payment_method] ?? 'bx-wallet';
                            $statusColor = $sale->payment_status === 'paid' ? 'success' : 'warning';
                        @endphp
                        <tr data-method="{{ $methodSlug }}">
                            <td>
                                <div class="fw-semibold text-dark">{{ $sale->created_at->format('d M, Y') }}</div>
                                <small class="text-muted"><i class="bx bx-time-five me-1"></i>{{ $sale->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="font-monospace fw-bold text-primary">{{ $sale->reference_no }}</span>
                                    <button type="button" class="btn btn-sm btn-link text-muted p-0" onclick="copyToClipboard('{{ $sale->reference_no }}')" title="Copy Reference">
                                        <i class="bx bx-copy"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-xs flex-shrink-0">
                                        <span class="avatar-title rounded-circle bg-soft-primary text-white fw-bold small">
                                            {{ strtoupper(substr($sale->seller_name ?? 'ST', 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark font-size-13">{{ $sale->seller_name }}</div>
                                        <small class="text-muted">Staff Operator</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark fw-semibold">
                                    <i class="bx bx-package me-1"></i>{{ $itemsCount }} {{ $itemsCount == 1 ? 'item' : 'items' }} ({{ (float)$unitsCount }} units)
                                </span>
                            </td>
                            <td>
                                <div class="fw-extrabold text-dark font-size-14">₦{{ number_format($sale->payable_amount, 2) }}</div>
                                @if($sale->discount_amount > 0)
                                    <small class="text-danger">Disc: -₦{{ number_format($sale->discount_amount, 2) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-soft-{{ $color }} text-{{ $color }} fw-semibold px-2 py-1">
                                    <i class="bx {{ $methodIcon }} me-1"></i>{{ $sale->payment_method }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-soft-{{ $statusColor }} text-{{ $statusColor }} px-2 py-1 fw-bold">
                                    {{ ucfirst($sale->payment_status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-primary rounded px-3 fw-semibold" onclick="viewSaleDetails('{{ addslashes($sale->reference_no) }}')" title="View Thermal Receipt">
                                    <i class="bx bx-receipt me-1"></i> Receipt
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- REDESIGNED THERMAL RECEIPT MODAL --}}
<div class="modal fade" id="saleDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom px-4 py-3 bg-light">
                <div class="d-flex align-items-center gap-2">
                    <span class="avatar-xs">
                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle"><i class="bx bx-receipt font-size-18"></i></span>
                    </span>
                    <h5 class="modal-title fw-bold text-dark mb-0">Audited Sales Receipt</h5>
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
    /* ==========================================================================
       STAFF SALES HISTORY & THERMAL RECEIPT ENGINE
       ========================================================================== */
    function filterHistoryTable(filter, btn) {
        document.querySelectorAll('.history-filter-pill').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');

        const rows = document.querySelectorAll('#datatable tbody tr');
        rows.forEach(row => {
            const method = row.dataset.method;

            if (filter === 'all') {
                row.style.display = '';
            } else if (filter === 'cash' || filter === 'transfer' || filter === 'card') {
                row.style.display = (method === filter) ? '' : 'none';
            }
        });
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: `Reference ${text} copied to clipboard`,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
        });
    }

    async function viewSaleDetails(saleReference) {
        const modal = new bootstrap.Modal(document.getElementById('saleDetailsModal'));
        const content = document.getElementById('saleDetailsContent');
        
        content.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="text-muted small mt-2">Loading receipt details...</p></div>';
        modal.show();

        try {
            const response = await fetch("{{ url('staff/sales/details') }}/" + encodeURIComponent(saleReference));
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
                        {{-- STORE HEADER --}}
                        <div style="text-align: center; margin-bottom: 8px;">
                            <h5 style="font-weight: 900; margin-bottom: 2px; text-transform: uppercase;">{{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->site_name : "MOSHEL FOOD PRODUCTION" }}</h5>
                            <div style="font-size: 11px; color: #444; line-height: 1.3;">
                                {{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->address : "Sales & Retail Operations" }}<br>
                                Tel: {{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->phone : "+234 800 000 0000" }}
                            </div>
                        </div>

                        <div class="receipt-dashed-line"></div>

                        {{-- RECEIPT TITLE & BARCODE --}}
                        <div style="text-align: center;">
                            <div style="font-weight: 800; font-size: 13px; letter-spacing: 1px;">OFFICIAL SALES RECEIPT</div>
                            <div class="receipt-barcode"></div>
                            <div style="font-size: 12px; font-weight: bold; letter-spacing: 0.5px;">${sale.reference_no}</div>
                        </div>

                        <div class="receipt-dashed-line"></div>

                        {{-- TRANSACTION METADATA --}}
                        <div style="font-size: 11px; line-height: 1.5;">
                            <div style="display: flex; justify-content: space-between;">
                                <span>DATE & TIME:</span>
                                <strong>${sale.created_at}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>CASHIER:</span>
                                <strong>${sale.merchant_name || sale.staff_name || 'Staff'}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>PAYMENT METHOD:</span>
                                <strong>${sale.payment_method}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>SETTLEMENT:</span>
                                <strong>${(sale.payment_status || 'PAID').toUpperCase()}</strong>
                            </div>
                            ${sale.notes ? `
                            <div style="display: flex; justify-content: space-between;">
                                <span>NOTES:</span>
                                <span>${sale.notes}</span>
                            </div>` : ''}
                        </div>

                        <div class="receipt-dashed-line"></div>

                        {{-- ITEMS TABLE --}}
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

                        {{-- FINANCIAL TOTALS --}}
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

                        {{-- FOOTER --}}
                        <div class="receipt-dashed-line"></div>
                        <div style="text-align: center; font-size: 11px; color: #333; line-height: 1.4;">
                            <p style="margin-bottom: 2px; font-weight: 700;">Thank you for your patronage!</p>
                            <p style="margin-bottom: 0;">Freshly baked & produced with excellence.</p>
                            <small style="color: #666;">Retain this receipt for audit verification.</small>
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
