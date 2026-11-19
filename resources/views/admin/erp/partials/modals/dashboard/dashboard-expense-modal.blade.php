{{-- =====================================================
     OPERATING POSITION / EXPENSE BREAKDOWN MODAL
     ===================================================== --}}
<div class="modal fade" id="expenseBreakdownModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title font-size-16">Operating Position Breakdown</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p class="text-muted mb-0">Today's paid revenue</p>
                    <h5 class="text-dark mb-0">₦{{ number_format($todayRevenue, 2) }}</h5>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p class="text-muted mb-0">Today's production use</p>
                    <h5 class="text-danger mb-0">- ₦{{ number_format($todayProductionCost, 2) }}</h5>
                </div>
                <hr>
                <div class="p-3 rounded {{ $todayProfit >= 0 ? 'bg-soft-success' : 'bg-soft-danger' }} mb-3">
                    <p class="mb-1 small text-uppercase fw-bold">Today's operating estimate</p>
                    <h3 class="{{ $todayProfit >= 0 ? 'text-success' : 'text-danger' }} mb-0">
                        ₦{{ number_format($todayProfit, 2) }}
                    </h3>
                </div>

                <p class="text-muted small mb-4">{{ $dailyPositionExplanation }}</p>

                <div class="border rounded p-3 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Last 7 days</strong>
                        <span class="fw-bold {{ $weeklyOperatingBalance >= 0 ? 'text-success' : 'text-danger' }}">₦{{ number_format($weeklyOperatingBalance, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between small text-muted"><span>Paid revenue</span><span>₦{{ number_format($weeklyRevenue, 2) }}</span></div>
                    <div class="d-flex justify-content-between small text-muted"><span>Production use</span><span>− ₦{{ number_format($weeklyProductionCost, 2) }}</span></div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center small">
                        <span class="text-muted">Previous 7-day position</span>
                        <span class="fw-semibold">₦{{ number_format($previousWeeklyOperatingBalance, 2) }}</span>
                    </div>
                    <small class="d-block mt-2 text-muted">This rolling view helps account for production and payment activity landing on different days.</small>
                </div>
                
                <div class="border-top pt-3 mt-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Stock Purchases</h6>
                            <small class="text-muted">Total inventory bought today</small>
                        </div>
                        <span class="text-primary fw-bold">₦{{ number_format($todayPurchases, 2) }}</span>
                    </div>
                    <p class="mt-2 text-muted small italic" style="font-size: 0.75rem;">
                        * Purchases are considered inventory assets and are only deducted from profit as they are <b>produced/used</b>.
                    </p>
                </div>

                <div class="border rounded p-3 mt-3 bg-light-subtle">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong class="font-size-13 text-dark">Total Inventory Asset Valuation</strong>
                        <span class="fw-bold text-success font-size-14">₦{{ number_format($totalInventoryValuation, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between small text-muted font-size-12">
                        <span>Raw Bakery Materials:</span>
                        <span>₦{{ number_format($rawMaterialsValuation, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between small text-muted font-size-12">
                        <span>Commercial Finished Goods:</span>
                        <span>₦{{ number_format($finishedGoodsValuation, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
