{{-- LEFT COLUMN: MY LIVE SHIFT ACTIVITY (col-xl-8) --}}
<div class="col-xl-8">
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden"
         style="border: 1px solid rgba(226, 232, 240, 0.9) !important;">
        
        {{-- CARD HEADER WITH TABS --}}
        <div class="card-header bg-white border-bottom border-light py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="avatar-xs rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <i class="bx bx-history font-size-18"></i>
                </div>
                <div>
                    <h5 class="card-title mb-0 fw-bold text-dark font-size-15">My Shift Operations Log</h5>
                    <p class="text-muted mb-0 font-size-12">Live activity recorded under your staff account today.</p>
                </div>
            </div>

            {{-- NAV PILL TABS --}}
            <ul class="nav nav-pills staff-activity-pills" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active font-size-12 py-1.5 px-3 rounded-pill"
                       data-bs-toggle="tab"
                       href="#mySalesTab"
                       role="tab">
                        <i class="bx bx-shopping-bag me-1"></i> My Sales ({{ $mySalesCount }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-size-12 py-1.5 px-3 rounded-pill"
                       data-bs-toggle="tab"
                       href="#myProductionTab"
                       role="tab">
                        <i class="bx bx-dish me-1"></i> My Batches ({{ $myProductionCount }})
                    </a>
                </li>
            </ul>
        </div>

        {{-- TAB CONTENT --}}
        <div class="card-body p-0">
            <div class="tab-content">
                {{-- TAB 1: MY SALES TODAY --}}
                <div class="tab-pane fade show active" id="mySalesTab" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 staff-table">
                            <thead class="table-light font-size-11 text-uppercase text-muted">
                                <tr>
                                    <th>Receipt Ref</th>
                                    <th>Items Sold</th>
                                    <th>Method</th>
                                    <th>Amount</th>
                                    <th>Time</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($myRecentSales as $sale)
                                <tr>
                                    <td>
                                        <span class="fw-bold font-monospace text-primary font-size-12">
                                            {{ $sale->reference_no }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark font-size-13 fw-medium">
                                            {{ $sale->items->count() }} {{ Str::plural('item', $sale->items->count()) }}
                                        </span>
                                        @if($sale->items->first())
                                            <small class="text-muted d-block font-size-11">
                                                {{ Str::limit($sale->items->first()->product->name ?? 'Product', 20) }}
                                                @if($sale->items->count() > 1)
                                                    +{{ $sale->items->count() - 1 }} more
                                                @endif
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $method = strtolower($sale->payment_method ?? 'cash');
                                            $methodBadge = match($method) {
                                                'cash' => 'bg-success-subtle text-success border-success-subtle',
                                                'card', 'pos' => 'bg-primary-subtle text-primary border-primary-subtle',
                                                'transfer' => 'bg-info-subtle text-info border-info-subtle',
                                                default => 'bg-light text-muted border'
                                            };
                                        @endphp
                                        <span class="badge {{ $methodBadge }} border font-size-11 px-2 py-0.5 text-uppercase fw-semibold">
                                            {{ $sale->payment_method ?: 'Cash' }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-dark font-size-13">₦{{ number_format($sale->payable_amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="text-muted font-size-12">
                                            {{ $sale->created_at->format('h:i A') }}
                                            <small class="text-muted d-block opacity-75">({{ $sale->created_at->diffForHumans() }})</small>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ url('staff/salesHistory') }}" class="btn btn-outline-secondary btn-sm rounded-pill font-size-11 px-2.5 py-1">
                                            <i class="bx bx-receipt me-0.5"></i> Details
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center text-muted">
                                            <div class="avatar-md rounded-circle bg-light d-flex align-items-center justify-content-center mb-3">
                                                <i class="bx bx-shopping-bag font-size-26 text-secondary"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark mb-1">No Sales Processed by You Today</h6>
                                            <p class="font-size-12 text-muted mb-3" style="max-width: 320px;">
                                                Launch the POS terminal to start ringing up walk-in customers and collecting payments.
                                            </p>
                                            <a href="{{ url('staff/pos') }}" class="btn btn-primary btn-sm rounded-pill px-3 py-1.5 font-size-12">
                                                <i class="bx bx-shopping-bag me-1"></i> Open POS Terminal
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 2: MY RECENT PRODUCTION --}}
                <div class="tab-pane fade" id="myProductionTab" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 staff-table">
                            <thead class="table-light font-size-11 text-uppercase text-muted">
                                <tr>
                                    <th>Product Baked</th>
                                    <th>Batch Quantity</th>
                                    <th>Time Logged</th>
                                    <th class="text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentProductions as $prod)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-xs rounded-2 bg-light border d-flex align-items-center justify-content-center text-primary font-size-14" style="width: 30px; height: 30px;">
                                                <i class="bx bx-cookie"></i>
                                            </div>
                                            <div>
                                                <strong class="text-dark font-size-13">{{ $prod->product->name ?? 'Commercial Product' }}</strong>
                                                <small class="text-muted d-block font-size-11">Sales Unit: {{ $prod->product->sales_unit ?? 'Units' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-success font-size-13">{{ number_format($prod->quantity, 0) }} Units</strong>
                                    </td>
                                    <td>
                                        <span class="text-muted font-size-12">
                                            {{ $prod->produced_at ? \Carbon\Carbon::parse($prod->produced_at)->format('h:i A') : $prod->created_at->format('h:i A') }}
                                            <small class="text-muted d-block opacity-75">({{ $prod->created_at->diffForHumans() }})</small>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-size-11 px-2.5 py-1">
                                            <i class="bx bx-check me-0.5"></i> Baked &amp; Stocked
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center text-muted">
                                            <div class="avatar-md rounded-circle bg-light d-flex align-items-center justify-content-center mb-3">
                                                <i class="bx bx-dish font-size-26 text-secondary"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark mb-1">No Batches Logged Today</h6>
                                            <p class="font-size-12 text-muted mb-3" style="max-width: 320px;">
                                                When you complete a bake, record the batch so inventory deducts ingredients automatically.
                                            </p>
                                            <a href="{{ url('staff/production') }}" class="btn btn-success btn-sm rounded-pill px-3 py-1.5 font-size-12">
                                                <i class="bx bx-plus me-1"></i> Record Batch Now
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD FOOTER --}}
        <div class="card-footer bg-light-subtle border-top border-light py-2.5 px-4 font-size-12 text-muted d-flex align-items-center justify-content-between flex-wrap gap-2">
            <span>
                <i class="bx bx-info-circle text-primary me-1"></i> Showing activity recorded during today's shift.
            </span>
            <a href="{{ url('staff/salesHistory') }}" class="fw-medium text-primary text-decoration-none font-size-12">
                View Complete Sales History <i class="bx bx-right-arrow-alt"></i>
            </a>
        </div>
    </div>
</div>
