{{-- =====================================================
     SUPPLY CHAIN, STOREFRONT CATALOG & CUSTOMER HEALTH ROW
     ===================================================== --}}
<div class="row g-3 mt-1 align-items-stretch" data-reveal>
    {{-- LEFT: RAW INGREDIENTS REORDER WATCH (BAKERY SUPPLY CHAIN) --}}
    <div class="col-xl-6 d-flex">
        <div class="card shadow-sm analytics-panel bottom-panel ingredients-panel flex-fill">
            <div class="card-body">
                <div class="section-heading d-flex align-items-start justify-content-between gap-3 mb-3">
                    <div>
                        <span class="section-eyebrow">Bakery Supply Chain</span>
                        <h4 class="card-title mb-1">Raw Ingredients Reorder Watch</h4>
                        <small class="text-muted">{{ $lowStockIngredientsCount }} raw baking ingredients at or below reorder threshold</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="stock-summary {{ $lowStockIngredientsCount > 0 ? 'stock-summary-alert' : '' }}" title="{{ $lowStockIngredientsCount }} ingredients require procurement restocking">
                            <strong>{{ $lowStockIngredientsCount }}</strong><small>reorder</small>
                        </span>
                        <a href="{{ url('/admin/stockIn') }}" class="btn btn-outline-primary btn-sm rounded-pill font-size-11 px-2.5 py-1" title="Purchase & Restock Ingredients">
                            <i class="bx bx-plus me-1"></i> Stock In
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle insight-table stock-table">
                        <thead>
                            <tr>
                                <th>Ingredient</th>
                                <th>Stock on Hand</th>
                                <th>Reorder Threshold</th>
                                <th class="text-end">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topLowIngredients as $inv)
                                @php
                                    $ing = $inv->ingredient;
                                    $unitSymbol = $ing->baseUnit->symbol ?? '';
                                    $threshold = $ing ? (float) $ing->effective_reorder_level : 0;
                                    $isCritical = $inv->quantity <= ($threshold * 0.4);
                                @endphp
                                <tr>
                                    <td class="product-name">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="ingredient-dot {{ $isCritical ? 'bg-danger' : 'bg-warning' }}"></span>
                                            <div>
                                                <strong class="text-dark">{{ $ing->name ?? 'Unknown' }}</strong>
                                                <small class="text-muted d-block font-size-11">Avg Cost: ₦{{ number_format($inv->average_cost, 2) }}/{{ $unitSymbol }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="{{ $isCritical ? 'text-danger' : 'text-warning' }}">
                                            {{ number_format($inv->quantity, 2) }} {{ $unitSymbol }}
                                        </strong>
                                        <span class="stock-meter d-block mt-1">
                                            <span class="{{ $isCritical ? 'meter-critical' : 'meter-low' }}"
                                                  style="width: {{ $threshold > 0 ? min(100, max(6, ((float) $inv->quantity / $threshold) * 100)) : 10 }}%"></span>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted font-size-12">{{ number_format($threshold, 2) }} {{ $unitSymbol }}</span>
                                    </td>
                                    <td class="text-end">
                                        <span class="stock-badge {{ $isCritical ? 'critical' : 'low' }}">
                                            {{ $isCritical ? 'Critical Low' : 'Reorder Now' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-insight stock-healthy">
                                            <i class="bx bx-check-double text-success"></i>
                                            <span>Raw Material Stocks Are Healthy</span>
                                            <small>All pantry and bakery ingredients currently meet or exceed minimum reorder buffers.</small>
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

    {{-- RIGHT: STOREFRONT CATALOG & CUSTOMER RELATIONSHIP INTELLIGENCE --}}
    <div class="col-xl-6 d-flex">
        <div class="card shadow-sm analytics-panel bottom-panel storefront-panel flex-fill">
            <div class="card-body">
                <div class="section-heading d-flex align-items-start justify-content-between gap-3 mb-3">
                    <div>
                        <span class="section-eyebrow">Commerce &amp; Audience</span>
                        <h4 class="card-title mb-1">Storefront &amp; Customer Intelligence</h4>
                        <small class="text-muted">Public digital storefront status and customer engagement metrics</small>
                    </div>
                    <a href="{{ url('/admin/customers') }}" class="btn btn-outline-secondary btn-sm rounded-pill font-size-11 px-2.5 py-1" title="View Customer Directory">
                        <i class="bx bx-user me-1"></i> Customers
                    </a>
                </div>

                {{-- STOREFRONT CATALOG SUMMARY ROW --}}
                <div class="row g-2 mb-3">
                    <div class="col-6 col-md-3">
                        <div class="p-2.5 rounded-3 bg-light border text-center">
                            <small class="text-muted font-size-11 d-block mb-1">Catalog Listed</small>
                            <h5 class="mb-0 fw-bold text-dark font-size-15">{{ $totalStoreProducts }}</h5>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-2.5 rounded-3 bg-success-subtle border border-success-subtle text-center">
                            <small class="text-success font-size-11 d-block mb-1">Published Live</small>
                            <h5 class="mb-0 fw-bold text-success font-size-15">{{ $publishedStoreProducts }}</h5>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-2.5 rounded-3 bg-warning-subtle border border-warning-subtle text-center">
                            <small class="text-warning font-size-11 d-block mb-1">Draft Mode</small>
                            <h5 class="mb-0 fw-bold text-warning font-size-15">{{ $draftStoreProducts }}</h5>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-2.5 rounded-3 bg-danger-subtle border border-danger-subtle text-center">
                            <small class="text-danger font-size-11 d-block mb-1">Out of Stock</small>
                            <h5 class="mb-0 fw-bold text-danger font-size-15">{{ $outOfStockStoreProducts }}</h5>
                        </div>
                    </div>
                </div>

                {{-- CUSTOMER METRICS & TEAM PILL --}}
                <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-light border mb-3 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-xs rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                            <i class="bx bx-group font-size-16"></i>
                        </div>
                        <div>
                            <span class="fw-bold text-dark font-size-13">{{ number_format($totalCustomersCount) }} Registered Customers</span>
                            <small class="text-muted d-block font-size-11">+{{ $weeklyNewCustomersCount }} joined this week · {{ $customerVerificationRate }}% verified accounts</small>
                        </div>
                    </div>
                    <span class="badge bg-white text-muted border font-size-11 px-2.5 py-1">
                        {{ $staffCount }} Staff Cashiers · {{ $adminsCount }} Admins
                    </span>
                </div>

                {{-- TOP VALUED CUSTOMERS TABLE --}}
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle insight-table">
                        <thead>
                            <tr>
                                <th>Top Customer</th>
                                <th class="text-center">Paid Orders</th>
                                <th class="text-end">Confirmed Spend</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topCustomers as $cust)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="customer-avatar-sm">{{ $cust->initials ?: 'CU' }}</span>
                                            <div>
                                                <strong class="text-dark">{{ $cust->name }}</strong>
                                                <small class="text-muted d-block font-size-11">{{ $cust->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-secondary border font-size-11 px-2">
                                            {{ $cust->paid_orders_count ?? 0 }} orders
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold text-dark">
                                        ₦{{ number_format($cust->total_spent ?? 0, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        <div class="empty-insight" style="min-height: 120px;">
                                            <i class="bx bx-user-x"></i>
                                            <span>No Customer Purchase History Yet</span>
                                            <small>Customer ranking will populate after customers complete store orders.</small>
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
</div>
