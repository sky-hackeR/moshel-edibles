{{-- RIGHT COLUMN: COUNTER READINESS & SHIFT INSIGHTS (col-xl-4) --}}
<div class="col-xl-4">

    {{-- 1. COUNTER SELLING READINESS (READY FOR SALE IN POS) --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden"
         style="border: 1px solid rgba(226, 232, 240, 0.9) !important; border-top: 3px solid #3b82f6 !important;">
        
        <div class="card-header bg-white border-bottom border-light py-3 px-3.5 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <div class="avatar-xs rounded-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                    <i class="bx bx-package font-size-16"></i>
                </div>
                <h6 class="card-title mb-0 fw-bold text-dark font-size-14">Counter Stock Ready</h6>
            </div>
            <a href="{{ url('staff/pos') }}" class="btn btn-outline-primary btn-sm rounded-pill font-size-11 px-2.5 py-0.5">
                Sell Now
            </a>
        </div>

        <div class="card-body p-3">
            <div class="d-flex flex-column gap-2 mb-2">
                @forelse($availableProducts as $prod)
                <div class="p-2.5 rounded-3 bg-light border d-flex align-items-center justify-content-between">
                    <div>
                        <strong class="text-dark font-size-13 d-block">{{ $prod->name }}</strong>
                        <span class="text-muted font-size-11">Price: ₦{{ number_format($prod->selling_price, 2) }}</span>
                    </div>
                    <div class="text-end">
                        <span class="badge {{ $prod->stock_on_hand > 5 ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} border rounded-pill font-size-11 px-2.5 py-1">
                            {{ number_format($prod->stock_on_hand, 0) }} {{ $prod->sales_unit }} left
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-3 text-muted font-size-12">
                    No products currently recorded in stock.
                </div>
                @endforelse
            </div>

            @if($outOfStockProductsCount > 0)
            <div class="p-2 rounded-2 bg-danger-subtle border border-danger-subtle d-flex align-items-center gap-2 font-size-11 text-danger mt-2">
                <i class="bx bx-error-circle font-size-16"></i>
                <span><strong>{{ $outOfStockProductsCount }} products</strong> currently out of stock. Notify the bakery team to produce!</span>
            </div>
            @endif
        </div>
    </div>


    {{-- 2. SHIFT PAYMENT BREAKDOWN (CASH DRAWER TALLY) --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden"
         style="border: 1px solid rgba(226, 232, 240, 0.9) !important; border-top: 3px solid #10b981 !important;">
        
        <div class="card-header bg-white border-bottom border-light py-3 px-3.5 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <div class="avatar-xs rounded-2 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                    <i class="bx bx-wallet font-size-16"></i>
                </div>
                <h6 class="card-title mb-0 fw-bold text-dark font-size-14">My Shift Register Tally</h6>
            </div>
            <span class="badge bg-light text-muted border font-size-10">Cashout Aid</span>
        </div>

        <div class="card-body p-3">
            <div class="d-flex flex-column gap-2 mb-3">
                @forelse($myPaymentBreakdown as $pay)
                @php
                    $method = strtolower($pay->payment_method ?: 'cash');
                    $icon = match($method) {
                        'cash' => 'bx-money text-success',
                        'card', 'pos' => 'bx-credit-card text-primary',
                        'transfer' => 'bx-transfer text-info',
                        default => 'bx-wallet text-secondary'
                    };
                @endphp
                <div class="d-flex align-items-center justify-content-between p-2 rounded-2 bg-light border font-size-12">
                    <span class="d-flex align-items-center gap-2 text-dark fw-medium text-capitalize">
                        <i class="bx {{ $icon }} font-size-16"></i> {{ $pay->payment_method ?: 'Cash' }}
                        <small class="text-muted fw-normal">({{ $pay->count }} orders)</small>
                    </span>
                    <strong class="text-dark">₦{{ number_format($pay->total, 2) }}</strong>
                </div>
                @empty
                <div class="text-center py-3 text-muted font-size-12">
                    No payments processed yet today.
                </div>
                @endforelse
            </div>

            <div class="p-2.5 rounded-3 bg-success-subtle border border-success-subtle d-flex align-items-center justify-content-between">
                <span class="font-size-12 text-success fw-bold">My Total Sales Tally:</span>
                <h5 class="mb-0 fw-bold text-success font-size-15">₦{{ number_format($mySalesTotal, 2) }}</h5>
            </div>
        </div>
    </div>


    {{-- 3. RAW INGREDIENTS BUFFER ALERT (CHECK BEFORE YOU BAKE) --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden"
         style="border: 1px solid rgba(226, 232, 240, 0.9) !important; border-top: 3px solid #f59e0b !important;">
        
        <div class="card-header bg-white border-bottom border-light py-3 px-3.5 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <div class="avatar-xs rounded-2 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                    <i class="bx bx-layer font-size-16"></i>
                </div>
                <h6 class="card-title mb-0 fw-bold text-dark font-size-14">Kitchen Stock Buffer</h6>
            </div>
            <a href="{{ url('staff/inventory') }}" class="btn btn-outline-secondary btn-sm rounded-pill font-size-11 px-2 py-0.5">
                Inventory
            </a>
        </div>

        <div class="card-body p-3">
            @if($lowStockIngredients->count() > 0)
                <div class="d-flex flex-column gap-2 mb-2">
                    @foreach($lowStockIngredients as $inv)
                    @php
                        $ing = $inv->ingredient;
                        $unitSymbol = $ing->baseUnit->symbol ?? '';
                        $threshold = $ing ? (float) $ing->effective_reorder_level : 0;
                    @endphp
                    <div class="p-2 rounded-2 bg-light border d-flex align-items-center justify-content-between font-size-12">
                        <div>
                            <strong class="text-dark d-block">{{ $ing->name ?? 'Ingredient' }}</strong>
                            <small class="text-muted font-size-11">Buffer: {{ number_format($threshold, 1) }} {{ $unitSymbol }}</small>
                        </div>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle font-size-11 px-2 py-1">
                            {{ number_format($inv->quantity, 1) }} {{ $unitSymbol }} left
                        </span>
                    </div>
                    @endforeach
                </div>
                <small class="text-muted font-size-11 d-block mt-2">
                    <i class="bx bx-info-circle text-warning me-0.5"></i> Verify recipe feasibility before starting batches.
                </small>
            @else
                <div class="p-3 rounded-3 bg-success-subtle border border-success-subtle text-center text-success">
                    <i class="bx bx-check-circle font-size-22 mb-1 d-block"></i>
                    <strong class="d-block font-size-13">All Raw Supplies Healthy</strong>
                    <small class="text-muted font-size-11">Pantry ingredients are well-stocked for current baking plans.</small>
                </div>
            @endif
        </div>
    </div>

</div>
