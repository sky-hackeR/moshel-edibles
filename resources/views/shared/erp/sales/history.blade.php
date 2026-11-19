@php
    $portal = $portal ?? 'admin';
    $isAdmin = ($portal === 'admin');

    $totalOrdersCount = $sales->count();
    $paidSales = $sales->where('payment_status', 'paid');
    $grossRevenue = $paidSales->sum('payable_amount');

    if ($isAdmin) {
        $posSales = $sales->where('user_type', '!=', 'customer');
        $posRevenue = $posSales->where('payment_status', 'paid')->sum('payable_amount');
        $onlineOrders = $sales->where('user_type', 'customer');
        $onlineRevenue = $onlineOrders->where('payment_status', 'paid')->sum('payable_amount');
    } else {
        $cashSales = $sales->where('payment_method', 'Cash');
        $cashRevenue = $cashSales->where('payment_status', 'paid')->sum('payable_amount');
        $transferSales = $sales->where('payment_method', 'Transfer');
        $transferRevenue = $transferSales->where('payment_status', 'paid')->sum('payable_amount');
        $cardSales = $sales->where('payment_method', 'Card');
        $cardRevenue = $cardSales->where('payment_status', 'paid')->sum('payable_amount');
    }
@endphp

<style>
    /* ==========================================================================
       MOSHEL EXECUTIVE SALES & ORDER HISTORY DESIGN SYSTEM
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
                @if($isAdmin)
                    <h4 class="mb-sm-0 font-size-18 fw-bold">Sales & Order History</h4>
                    <p class="text-muted small mb-0">Audited transaction ledger for in-store POS retail orders and storefront purchases.</p>
                @else
                    <h4 class="mb-sm-0 font-size-18 fw-bold">My Sales & Order History</h4>
                    <p class="text-muted small mb-0">Record of transactions processed at your cashier terminal station.</p>
                @endif
            </div>
            <div class="page-title-right d-flex gap-2">
                <a href="{{ url('/' . $portal . '/pos') }}" class="btn btn-primary rounded-pill px-3 fw-bold">
                    <i class="bx bx-store me-1"></i> Open POS Terminal
                </a>
            </div>
        </div>
    </div>
</div>

{{-- KPI METRICS CARDS --}}
<div class="row g-3 mb-4">
    @if($isAdmin)
        <div class="col-sm-6 col-xl-3">
            <div class="sales-kpi-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted text-uppercase fw-semibold small d-block">Gross Paid Sales</span>
                        <h4 class="fw-extrabold text-primary mb-1 mt-1">₦{{ number_format($grossRevenue, 2) }}</h4>
                        <span class="badge bg-soft-success text-success small fw-semibold">
                            <i class="bx bx-check-circle me-1"></i> {{ $paidSales->count() }} Settled Orders
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
                        <span class="text-muted text-uppercase fw-semibold small d-block">Total Orders Logged</span>
                        <h4 class="fw-extrabold text-dark mb-1 mt-1">{{ number_format($totalOrdersCount) }}</h4>
                        <span class="text-muted small">All channels combined</span>
                    </div>
                    <div class="sales-kpi-icon bg-info-subtle text-info">
                        <i class="bx bx-receipt"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="sales-kpi-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted text-uppercase fw-semibold small d-block">Counter POS Sales</span>
                        <h4 class="fw-extrabold text-success mb-1 mt-1">₦{{ number_format($posRevenue, 2) }}</h4>
                        <span class="badge bg-soft-primary text-primary small fw-semibold">
                            <i class="bx bx-store me-1"></i> {{ $posSales->count() }} Counter Tickets
                        </span>
                    </div>
                    <div class="sales-kpi-icon bg-success-subtle text-success">
                        <i class="bx bx-shopping-bag"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="sales-kpi-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted text-uppercase fw-semibold small d-block">Online Store Orders</span>
                        <h4 class="fw-extrabold text-info mb-1 mt-1">₦{{ number_format($onlineRevenue, 2) }}</h4>
                        <span class="badge bg-soft-info text-info small fw-semibold">
                            <i class="bx bx-globe me-1"></i> {{ $onlineOrders->count() }} Customer Orders
                        </span>
                    </div>
                    <div class="sales-kpi-icon bg-warning-subtle text-warning">
                        <i class="bx bx-globe"></i>
                    </div>
                </div>
            </div>
        </div>
    @else
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
    @endif
</div>

{{-- MAIN SALES TABLE CARD --}}
<div class="row">
    <div class="col-12">
        <div class="card border shadow-sm" style="border-radius: 14px;">
            <div class="card-header bg-transparent border-bottom p-3">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div>
                        @if($isAdmin)
                            <h5 class="card-title fw-bold mb-0">Transaction Register</h5>
                            <p class="text-muted small mb-0">Audited receipt stream with instant thermal print and transaction lookup.</p>
                        @else
                            <h5 class="card-title fw-bold mb-0">My Station Sales Log</h5>
                            <p class="text-muted small mb-0">Audited receipt register with 1-click thermal receipt reproduction.</p>
                        @endif
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @if($isAdmin)
                            <button type="button" class="history-filter-pill active" onclick="filterHistoryTable('all', this)">All Orders</button>
                            <button type="button" class="history-filter-pill" onclick="filterHistoryTable('pos', this)">POS Counter</button>
                            <button type="button" class="history-filter-pill" onclick="filterHistoryTable('online', this)">Online Orders</button>
                            <button type="button" class="history-filter-pill" onclick="filterHistoryTable('cash', this)">Cash</button>
                            <button type="button" class="history-filter-pill" onclick="filterHistoryTable('transfer', this)">Transfer</button>
                            <button type="button" class="history-filter-pill" onclick="filterHistoryTable('card', this)">Card / POS</button>
                        @else
                            <button type="button" class="history-filter-pill active" onclick="filterHistoryTable('all', this)">All Sales</button>
                            <button type="button" class="history-filter-pill" onclick="filterHistoryTable('cash', this)">Cash</button>
                            <button type="button" class="history-filter-pill" onclick="filterHistoryTable('transfer', this)">Transfer</button>
                            <button type="button" class="history-filter-pill" onclick="filterHistoryTable('card', this)">Card / POS</button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body p-3">
                <table id="datatable" class="table table-hover align-middle table-bordered dt-responsive nowrap w-100">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 130px;">Date & Time</th>
                            @if($isAdmin)
                                <th>Reference & Channel</th>
                                <th>Operator / Customer</th>
                                <th>Items Summary</th>
                                <th>Payable Amount</th>
                                <th>Method</th>
                                <th>Payment & Status</th>
                                <th class="text-center" style="width: 140px;">Actions</th>
                            @else
                                <th>Reference</th>
                                <th>Cashier Station</th>
                                <th>Items Sold</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th class="text-center" style="width: 110px;">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                        @php
                            $isOnline = $sale->user_type === 'customer';
                            $channelType = $isOnline ? 'online' : 'pos';
                            $methodSlug = strtolower($sale->payment_method);
                            $itemsCount = $sale->items->count();
                            $unitsCount = $sale->items->sum('quantity');

                            $color = ['Cash' => 'success', 'Transfer' => 'info', 'Card' => 'warning'][$sale->payment_method] ?? 'secondary';
                            $methodIcon = ['Cash' => 'bx-money', 'Transfer' => 'bx-transfer', 'Card' => 'bx-credit-card'][$sale->payment_method] ?? 'bx-wallet';
                            
                            if ($isAdmin) {
                                $statusColor = $sale->payment_status === 'paid' && $sale->order_status !== 'payment_review' ? 'success' : ($sale->payment_status === 'failed' ? 'danger' : 'warning');
                            } else {
                                $statusColor = $sale->payment_status === 'paid' ? 'success' : 'warning';
                            }
                        @endphp
                        <tr @if($isAdmin) data-channel="{{ $channelType }}" @endif data-method="{{ $methodSlug }}">
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
                                @if($isAdmin)
                                    <div>
                                        @if($isOnline)
                                            <span class="badge bg-soft-info text-info small"><i class="bx bx-globe me-1"></i>Online Store</span>
                                        @else
                                            <span class="badge bg-soft-primary text-primary small"><i class="bx bx-store me-1"></i>POS Counter</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-xs flex-shrink-0">
                                        @if($isAdmin)
                                            <span class="avatar-title rounded-circle {{ $isOnline ? 'bg-soft-info text-white' : 'bg-soft-primary text-white' }} fw-bold small">
                                                {{ strtoupper(substr($sale->seller_name ?? 'OP', 0, 2)) }}
                                            </span>
                                        @else
                                            <span class="avatar-title rounded-circle bg-soft-primary text-white fw-bold small">
                                                {{ strtoupper(substr($sale->seller_name ?? 'ST', 0, 2)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark font-size-13">{{ $sale->seller_name }}</div>
                                        <small class="text-muted">{{ $isAdmin ? ucfirst($sale->user_type ?? 'staff') : 'Staff Operator' }}</small>
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
                                @if($isAdmin)
                                    <div>
                                        <span class="badge bg-soft-{{ $statusColor }} text-{{ $statusColor }} px-2 py-1 fw-bold">
                                            {{ ucfirst($sale->payment_status) }}
                                        </span>
                                    </div>
                                    <small class="text-muted d-block mt-1">{{ ucfirst(str_replace('_', ' ', $sale->order_status)) }}</small>
                                    @if($sale->payment_status === 'review')
                                        <small class="d-block text-danger fw-semibold">Payment mismatch review</small>
                                    @endif
                                @else
                                    <span class="badge bg-soft-{{ $statusColor }} text-{{ $statusColor }} px-2 py-1 fw-bold">
                                        {{ ucfirst($sale->payment_status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($isAdmin)
                                    <div class="d-flex justify-content-center gap-1">
                                        <button type="button" class="btn btn-sm btn-primary rounded px-2" onclick="viewSaleDetails('{{ addslashes($sale->reference_no) }}')" title="View Thermal Receipt">
                                            <i class="bx bx-receipt me-1"></i> Receipt
                                        </button>
                                        @if(!$isOnline)
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded px-2" onclick="confirmVoid({{ $sale->id }}, '{{ $sale->reference_no }}')" title="Void Transaction">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    <button type="button" class="btn btn-sm btn-primary rounded px-3 fw-semibold" onclick="viewSaleDetails('{{ addslashes($sale->reference_no) }}')" title="View Thermal Receipt">
                                        <i class="bx bx-receipt me-1"></i> Receipt
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- SHARED THERMAL RECEIPT MODAL --}}
@include('shared.modals.sales.sale-receipt-modal', [
    'modalTitle' => 'Audited Sales Receipt',
    'detailsEndpoint' => url($portal . '/sales/details')
])

{{-- VOID TRANSACTION MODAL (ADMIN ONLY) --}}
@if($isAdmin)
    @include('admin.erp.partials.modals.sales.void-sale-modal')
@endif

<script>
    /* ==========================================================================
       SALES HISTORY & THERMAL RECEIPT ENGINE
       ========================================================================== */
    function filterHistoryTable(filter, btn) {
        document.querySelectorAll('.history-filter-pill').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');

        const rows = document.querySelectorAll('#datatable tbody tr');
        rows.forEach(row => {
            const channel = row.dataset.channel;
            const method = row.dataset.method;

            if (filter === 'all') {
                row.style.display = '';
            } else if (filter === 'pos') {
                row.style.display = (channel === 'pos') ? '' : 'none';
            } else if (filter === 'online') {
                row.style.display = (channel === 'online') ? '' : 'none';
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

    @if($isAdmin)
    function confirmVoid(id, ref) {
        document.getElementById('void_sale_id').value = id;
        document.getElementById('void_ref_display').innerText = ref;
        new bootstrap.Modal(document.getElementById('voidSaleModal')).show();
    }
    @endif
</script>
