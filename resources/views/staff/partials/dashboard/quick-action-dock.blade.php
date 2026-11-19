{{-- =====================================================
     3. FAST OPERATIONS DOCK (QUICK ACTION TILES)
     ===================================================== --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <a href="{{ url('staff/pos') }}" class="card border-0 shadow-sm rounded-3 text-decoration-none staff-action-tile h-100">
            <div class="card-body p-3 text-center">
                <div class="avatar-sm rounded-3 bg-primary-subtle text-primary mx-auto mb-2 d-flex align-items-center justify-content-center">
                    <i class="bx bx-shopping-bag font-size-22"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0.5 font-size-14">POS Terminal</h6>
                <small class="text-muted font-size-11 d-block">Process walk-in counter sales</small>
            </div>
        </a>
    </div>

    <div class="col-6 col-md-3">
        <a href="{{ url('staff/production') }}" class="card border-0 shadow-sm rounded-3 text-decoration-none staff-action-tile h-100">
            <div class="card-body p-3 text-center">
                <div class="avatar-sm rounded-3 bg-success-subtle text-success mx-auto mb-2 d-flex align-items-center justify-content-center">
                    <i class="bx bx-plus font-size-22"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0.5 font-size-14">Record Production</h6>
                <small class="text-muted font-size-11 d-block">Log bake batches &amp; usage</small>
            </div>
        </a>
    </div>

    <div class="col-6 col-md-3">
        <a href="{{ url('staff/inventory') }}" class="card border-0 shadow-sm rounded-3 text-decoration-none staff-action-tile h-100">
            <div class="card-body p-3 text-center">
                <div class="avatar-sm rounded-3 bg-warning-subtle text-warning mx-auto mb-2 d-flex align-items-center justify-content-center">
                    <i class="bx bx-list-check font-size-22"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0.5 font-size-14">Raw Inventory</h6>
                <small class="text-muted font-size-11 d-block">Check flour, sugar &amp; stock</small>
            </div>
        </a>
    </div>

    <div class="col-6 col-md-3">
        <a href="{{ url('staff/salesHistory') }}" class="card border-0 shadow-sm rounded-3 text-decoration-none staff-action-tile h-100">
            <div class="card-body p-3 text-center">
                <div class="avatar-sm rounded-3 bg-info-subtle text-info mx-auto mb-2 d-flex align-items-center justify-content-center">
                    <i class="bx bx-receipt font-size-22"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0.5 font-size-14">Sales Receipts</h6>
                <small class="text-muted font-size-11 d-block">Reprint &amp; verify past orders</small>
            </div>
        </a>
    </div>
</div>
