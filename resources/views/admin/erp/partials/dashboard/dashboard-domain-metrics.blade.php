{{-- =====================================================
     OMNICHANNEL, FULFILLMENT & INVENTORY VALUATION GRID
     ===================================================== --}}
<div class="row g-3 mt-1 align-items-stretch" data-reveal>
    {{-- 1. Bakery Production Intelligence (Kitchen & Manufacturing Core) --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-sm domain-card h-100 border-0">
            <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="domain-tag tag-kitchen">
                            <i class="bx bx-dish me-1"></i> Bakery Production
                        </span>
                        @if($weeklyProducedUnits > 0 && $weeklyProductionProfit < 0)
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle font-size-10 px-2 py-0.5"
                                  data-bs-toggle="tooltip"
                                  title="Ingredient cost (₦{{ number_format($weeklyProductionCost, 0) }}) exceeded expected retail sales value (₦{{ number_format($weeklyExpectedRevenue, 0) }}). Review recipe quantities or product selling price.">
                                <i class="bx bx-error-circle me-0.5"></i> Cost &gt; Revenue
                            </span>
                        @else
                            <span class="badge bg-light text-muted border font-size-10">{{ $totalRecipesCount }} Recipes</span>
                        @endif
                    </div>
                    <h5 class="fw-bold mb-1 font-size-16 text-dark">
                        {{ number_format($weeklyProducedUnits, 0) }} Units
                    </h5>
                    <small class="text-muted font-size-11 d-block mb-3">7-day production output ({{ $weeklyProductionBatches }} batches)</small>

                    <div class="d-flex align-items-center justify-content-between font-size-12 mb-1.5">
                        <span class="text-muted">Today's Batches:</span>
                        <strong class="text-dark">{{ $todayProductionBatches }} ({{ number_format($todayProducedUnits, 0) }} units)</strong>
                    </div>
                    <div class="d-flex align-items-center justify-content-between font-size-12 mb-1.5">
                        <span class="text-muted">Ingredient Cost:</span>
                        <strong class="text-danger">₦{{ number_format($weeklyProductionCost, 0) }}</strong>
                    </div>
                    <div class="d-flex align-items-center justify-content-between font-size-12 mb-1.5">
                        <span class="text-muted">Expected Output:</span>
                        <strong class="text-dark">₦{{ number_format($weeklyExpectedRevenue, 0) }}</strong>
                    </div>
                </div>
                <div class="pt-2 mt-2 border-top border-light d-flex align-items-center justify-content-between font-size-11 text-muted">
                    @if($weeklyProducedUnits == 0)
                        <span>Est. Gross Margin:</span>
                        <span class="text-muted">No production this week</span>
                    @elseif($weeklyProductionProfit >= 0)
                        <span>Est. Gross Margin:</span>
                        <strong class="text-success">
                            <i class="bx bx-trending-up me-0.5"></i> +{{ $productionMarginPercent }}% (+₦{{ number_format($weeklyProductionProfit, 0) }})
                        </strong>
                    @else
                        <span class="text-danger fw-semibold">Est. Production Deficit:</span>
                        <strong class="text-danger" data-bs-toggle="tooltip" title="Ingredient cost exceeded expected retail sales value. Review ingredient quantities used or product pricing.">
                            <i class="bx bx-trending-down me-0.5"></i> {{ $productionMarginPercent }}% (-₦{{ number_format(abs($weeklyProductionProfit), 0) }})
                        </strong>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Sales Channels (Commercial Monetization: POS vs Online) --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-sm domain-card h-100 border-0">
            <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="domain-tag tag-commerce">
                            <i class="bx bx-store-alt me-1"></i> Sales Channels
                        </span>
                        <span class="text-muted font-size-11">Last 7 Days</span>
                    </div>
                    <h5 class="fw-bold mb-1 font-size-16 text-dark">
                        ₦{{ number_format($weeklyRevenue, 2) }}
                    </h5>
                    <small class="text-muted font-size-11 d-block mb-3">Omnichannel paid revenue</small>

                    <div class="channel-split-bar mb-3">
                        <div class="split-pos" style="width: {{ max(4, $posRevenueShare) }}%" title="In-Store POS: {{ $posRevenueShare }}%"></div>
                        <div class="split-online" style="width: {{ max(4, $onlineRevenueShare) }}%" title="Online Store: {{ $onlineRevenueShare }}%"></div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between font-size-12 mb-1.5">
                        <span class="text-muted d-flex align-items-center gap-1">
                            <span class="channel-dot" style="background: #4f46e5;"></span> In-Store (POS)
                        </span>
                        <span class="fw-bold text-dark">₦{{ number_format($posPaidRevenue, 0) }} <small class="text-muted fw-normal">({{ $posPaidOrdersCount }})</small></span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between font-size-12">
                        <span class="text-muted d-flex align-items-center gap-1">
                            <span class="channel-dot" style="background: #06b6d4;"></span> Online Store
                        </span>
                        <span class="fw-bold text-dark">₦{{ number_format($onlinePaidRevenue, 0) }} <small class="text-muted fw-normal">({{ $onlinePaidOrdersCount }})</small></span>
                    </div>
                </div>
                <div class="pt-2 mt-2 border-top border-light d-flex align-items-center justify-content-between font-size-11 text-muted">
                    <span>POS Share: <strong class="text-primary">{{ $posRevenueShare }}%</strong></span>
                    <span>Online: <strong class="text-info">{{ $onlineRevenueShare }}%</strong></span>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Order Fulfillment Pipeline (Live Order Workflow) --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-sm domain-card h-100 border-0">
            <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="domain-tag tag-fulfillment">
                            <i class="bx bx-package me-1"></i> Order Pipeline
                        </span>
                        <span class="badge bg-light text-muted border font-size-10">Live Status</span>
                    </div>
                    <h5 class="fw-bold mb-1 font-size-16 text-dark">
                        {{ number_format($completedOrdersCount + $processingOrdersCount + $pendingOrdersCount) }} Orders
                    </h5>
                    <small class="text-muted font-size-11 d-block mb-3">All-time fulfillment tracking</small>

                    <div class="pipeline-list d-flex flex-column gap-2 mb-2">
                        <div class="d-flex align-items-center justify-content-between font-size-12">
                            <span class="text-muted d-flex align-items-center gap-1">
                                <i class="bx bx-check-circle text-success font-size-14"></i> Completed / Delivered
                            </span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-size-11 px-2">
                                {{ $completedOrdersCount }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between font-size-12">
                            <span class="text-muted d-flex align-items-center gap-1">
                                <i class="bx bx-loader-alt text-warning font-size-14"></i> In Kitchen / Processing
                            </span>
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill font-size-11 px-2">
                                {{ $processingOrdersCount }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between font-size-12">
                            <span class="text-muted d-flex align-items-center gap-1">
                                <i class="bx bx-time-five text-secondary font-size-14"></i> Pending Payment / Review
                            </span>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill font-size-11 px-2">
                                {{ $pendingOrdersCount }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="pt-2 mt-2 border-top border-light d-flex align-items-center justify-content-between font-size-11 text-muted">
                    <span>Payment Failures (7D):</span>
                    <strong class="{{ $failedOrdersCount > 0 ? 'text-danger' : 'text-success' }}">{{ $failedOrdersCount }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. Total Inventory Asset Valuation (Balance Sheet Capital) --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-sm domain-card h-100 border-0">
            <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="domain-tag tag-valuation">
                            <i class="bx bx-shield-quarter me-1"></i> Inventory Assets
                        </span>
                        <span class="badge bg-light text-muted border font-size-10">Balance Sheet</span>
                    </div>
                    <h5 class="fw-bold mb-1 font-size-16 text-dark">
                        ₦{{ number_format($totalInventoryValuation, 2) }}
                    </h5>
                    <small class="text-muted font-size-11 d-block mb-3">Combined stock asset valuation</small>

                    <div class="d-flex align-items-center justify-content-between font-size-12 mb-1.5">
                        <span class="text-muted">Raw Ingredients:</span>
                        <strong class="text-dark">₦{{ number_format($rawMaterialsValuation, 0) }}</strong>
                    </div>
                    <div class="d-flex align-items-center justify-content-between font-size-12 mb-1.5">
                        <span class="text-muted">Finished Goods (Retail):</span>
                        <strong class="text-dark">₦{{ number_format($finishedGoodsValuation, 0) }}</strong>
                    </div>
                    <div class="d-flex align-items-center justify-content-between font-size-12">
                        <span class="text-muted">Procurement Spend (7D):</span>
                        <strong class="text-primary">₦{{ number_format($weeklyPurchases, 0) }}</strong>
                    </div>
                </div>
                <div class="pt-2 mt-2 border-top border-light d-flex align-items-center justify-content-between font-size-11 text-muted">
                    <span>Raw Stock Alerts:</span>
                    <strong class="{{ $lowStockIngredientsCount > 0 ? 'text-danger' : 'text-success' }}">
                        {{ $lowStockIngredientsCount }} need reorder
                    </strong>
                </div>
            </div>
        </div>
    </div>
</div>
