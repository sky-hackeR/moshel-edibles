{{-- =====================================================
     BEST SELLERS & STOCK ATTENTION INSIGHTS ROW
     ===================================================== --}}
<div class="row g-3 mt-1 align-items-stretch">
    <div class="col-xl-6 d-flex" data-reveal>
        <div class="card shadow-sm analytics-panel bottom-panel best-sellers-panel flex-fill">
            <div class="card-body">
                <div class="section-heading d-flex align-items-start justify-content-between gap-3 mb-3">
                    <div>
                        <span class="section-eyebrow">Sales leaders</span>
                        <h4 class="card-title mb-1">Top 5 Best Sellers</h4>
                        <small class="text-muted">Ranked by units sold across paid orders</small>
                    </div>
                    <span class="section-icon sales-icon"><i class="bx bx-trophy"></i></span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle insight-table">
                        <thead>
                            <tr><th class="rank-column">#</th><th>Product</th><th>Units sold</th><th class="text-end">Revenue</th></tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $item)
                            <tr>
                                <td class="rank-column"><span class="product-rank rank-{{ $loop->iteration }}">{{ sprintf('%02d', $loop->iteration) }}</span></td>
                                <td class="product-name">{{ $item->product->name ?? 'Deleted Product' }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-semibold">{{ number_format($item->total_qty, 0) }}</span>
                                        <span class="sales-meter" aria-hidden="true"><span style="width: {{ min(100, ((float) $item->total_qty / $topProductMaxQty) * 100) }}%"></span></span>
                                    </div>
                                </td>
                                <td class="text-end fw-semibold">₦{{ number_format($item->total_revenue, 0) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4"><div class="empty-insight"><i class="bx bx-bar-chart-alt-2"></i><span>No paid product sales yet</span><small>Product rankings will appear after the first completed payment.</small></div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 d-flex" data-reveal>
        <div class="card shadow-sm analytics-panel bottom-panel inventory-panel flex-fill">
            <div class="card-body">
                <div class="section-heading d-flex align-items-start justify-content-between gap-3 mb-3">
                    <div>
                        <span class="section-eyebrow">Inventory watch</span>
                        <h4 class="card-title mb-1">Stock needing attention</h4>
                        <small class="text-muted">{{ $lowStockCount }} products at or below reorder level</small>
                    </div>
                    <span class="stock-summary {{ $criticalStockCount > 0 ? 'stock-summary-alert' : '' }}" title="{{ $criticalStockCount }} products have 3 or fewer units remaining">
                        <strong>{{ $criticalStockCount }}</strong><small>critical</small>
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle insight-table stock-table">
                        <thead>
                            <tr><th>Product</th><th>On hand</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts as $product)
                            <tr>
                                <td class="product-name">{{ $product->name }}</td>
                                <td>
                                    <strong class="{{ $product->stock_on_hand <= 3 ? 'text-danger' : 'text-warning' }}">{{ number_format($product->stock_on_hand, 0) }}</strong>
                                    <span class="stock-meter d-block mt-1"><span class="{{ $product->stock_on_hand <= 3 ? 'meter-critical' : 'meter-low' }}" style="width: {{ min(100, max(4, ((float) $product->stock_on_hand / 10) * 100)) }}%"></span></span>
                                </td>
                                <td><span class="stock-badge {{ $product->stock_on_hand <= 3 ? 'critical' : 'low' }}">{{ $product->stock_on_hand <= 3 ? 'Critical' : 'Reorder' }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="3"><div class="empty-insight stock-healthy"><i class="bx bx-check-shield"></i><span>Stock levels are healthy</span><small>No active products are at or below the 10-unit reorder threshold.</small></div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
