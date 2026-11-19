{{-- =====================================================
     1. SHIFT WELCOME & FAST ACTION BANNER
     ===================================================== --}}
<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden staff-hero-card"
     style="border: 1px solid rgba(226, 232, 240, 0.9) !important; border-top: 3px solid #059669 !important;">
    <div class="card-body p-4 bg-white">
        <div class="row align-items-center g-3">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    {{-- STAFF AVATAR --}}
                    <div class="staff-hero-avatar flex-shrink-0">
                        {{ $initials ?: 'ST' }}
                    </div>

                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <h3 class="mb-0 fw-bold text-dark font-size-20" style="letter-spacing: -0.02em;">
                                Welcome back, {{ $firstName }}!
                            </h3>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 font-size-11 fw-semibold d-inline-flex align-items-center gap-1.5 shadow-xs">
                                <span class="staff-pulse-dot bg-success"></span> Active Shift
                            </span>
                            <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1 font-size-11">
                                Cashier &amp; Bakery Staff
                            </span>
                        </div>

                        <div class="d-flex align-items-center gap-2 text-muted font-size-12 flex-wrap">
                            <span><i class="bx bx-calendar me-1 text-primary"></i> {{ now()->format('l, d F Y') }}</span>
                            <span class="opacity-50">•</span>
                            <span><i class="bx bx-user me-1 text-muted"></i> {{ $fullName }}</span>
                            <span class="opacity-50">•</span>
                            <span class="text-success fw-medium">
                                <i class="bx bx-check-shield me-0.5"></i> POS &amp; Kitchen Operational
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PRIMARY LAUNCHERS --}}
            <div class="col-lg-5 text-lg-end">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ url('staff/production') }}"
                       class="btn btn-outline-success px-3.5 py-2 rounded-pill shadow-xs fw-semibold d-inline-flex align-items-center gap-2 font-size-13">
                        <i class="bx bx-plus-circle font-size-16"></i>
                        <span>Record Batch</span>
                    </a>

                    <a href="{{ url('staff/pos') }}"
                       class="btn btn-primary px-4 py-2 rounded-pill shadow-sm fw-semibold d-inline-flex align-items-center gap-2 font-size-13"
                       style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); border: none;">
                        <i class="bx bx-shopping-bag font-size-16"></i>
                        <span>Open POS Terminal</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
