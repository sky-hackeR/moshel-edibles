{{-- =====================================================
     PAID REVENUE TREND & PAYMENT METHODS CHARTS
     ===================================================== --}}
<div class="row mt-4 g-3 align-items-stretch">
    <div class="col-xl-8 d-flex" data-reveal>
        <div class="card shadow-sm analytics-panel chart-panel flex-fill">
            <div class="card-body">
                <h4 class="card-title mb-1">Paid Revenue Trend <button type="button" class="metric-help text-muted" data-bs-toggle="tooltip" title="Daily paid revenue this week compared with the matching days in the previous week."><i class="bx bx-info-circle"></i><span class="visually-hidden">Revenue trend details</span></button></h4>
                <p class="text-muted small mb-3">This 7 days compared with the previous 7 days</p>
                <div id="revenue_chart"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 d-flex" data-reveal>
        <div class="card shadow-sm analytics-panel chart-panel flex-fill">
            <div class="card-body">
                <h4 class="card-title mb-4">Payment Methods <button type="button" class="metric-help text-muted" data-bs-toggle="tooltip" title="Distribution of all recorded orders by selected payment method."><i class="bx bx-info-circle"></i><span class="visually-hidden">Payment methods details</span></button></h4>
                <div id="payment_chart"></div>
            </div>
        </div>
    </div>
</div>
