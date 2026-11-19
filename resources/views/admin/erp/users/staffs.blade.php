@extends('admin.layout.dashboard')

@section('content')

@php
    $totalStaff = $staffs->count();
    $newThisMonth = $staffs->where('created_at', '>=', now()->startOfMonth())->count();
@endphp

{{-- PAGE HEADER & ACTION BAR --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-sm-0 font-size-18 text-primary fw-bold">
                    <i class="bx bx-group me-1 align-middle" style="color: #10b981;"></i> Operational Staff Directory
                </h4>
                <p class="text-muted font-size-12 mb-0">
                    Manage bakery floor operators, cashiers, and production staff credentials.
                </p>
            </div>
            <div class="page-title-right d-flex align-items-center gap-2">
                <a href="{{ route('pos') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm font-size-12">
                    <i class="bx bx-calculator me-1 align-middle"></i> Launch POS Terminal
                </a>
                <button class="btn btn-success btn-sm rounded-pill px-3 shadow-sm font-size-12 fw-semibold" data-bs-toggle="modal" data-bs-target="#addStaff">
                    <i class="bx bx-user-plus me-1 align-middle"></i> Add Staff Member
                </button>
                <ol class="breadcrumb m-0 ms-2 d-none d-md-flex">
                    <li class="breadcrumb-item active">Staff</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- EXECUTIVE KPI SUMMARY CARDS --}}
<div class="row g-3 mb-4">
    {{-- Card 1: Total Staff --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card 
            label="Active Staff Members" 
            :value="$totalStaff" 
            color="success" 
            border-color="#10b981" 
            icon="bx bx-group" 
            subtitle="Floor & Sales Operations" 
            subicon="bx bx-check-circle" 
            subcolor="text-success" />
    </div>

    {{-- Card 2: New This Month --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card 
            label="New Onboarding" 
            :value="$newThisMonth" 
            color="purple" 
            border-color="#581a62" 
            icon="bx bx-user-plus" 
            :subtitle="now()->format('F Y') . ' Cohort'" 
            subicon="bx bx-calendar" 
            subcolor="text-purple" />
    </div>

    {{-- Card 3: Scope of Authority --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card 
            label="Operational Tier" 
            value="Staff Access" 
            color="info" 
            border-color="#0284c7" 
            icon="bx bx-id-card" 
            subtitle="POS & Production Batches" 
            subicon="bx bx-calculator" 
            subcolor="text-info" />
    </div>

    {{-- Card 4: Security Guardrails --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card 
            label="Security Isolation" 
            value="Restricted ERP" 
            color="warning" 
            border-color="#f59e0b" 
            icon="bx bx-shield-alt" 
            subtitle="No System Deletion Rights" 
            subicon="bx bx-lock-alt" 
            subcolor="text-warning" />
    </div>
</div>

{{-- MAIN STAFF DIRECTORY CARD --}}
<div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h5 class="card-title mb-0 fw-bold text-dark">
                <i class="bx bx-user-check me-1 text-success"></i> Operational Staff Register
            </h5>
            <small class="text-muted">Staff accounts assigned to bakery production floors, stock tracking, and point of sale stations.</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill bg-light text-dark border px-3 py-1 font-size-11">
                {{ $totalStaff }} {{ Str::plural('Staff Member', $totalStaff) }} Enrolled
            </span>
        </div>
    </div>

    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-hover align-middle dt-responsive nowrap w-100 font-size-13 mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">S/N</th>
                        <th>Staff Member</th>
                        <th>Email Address</th>
                        <th>Assigned Workspaces</th>
                        <th>Onboarded Date</th>
                        <th class="text-center" style="width: 130px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staffs as $staff)
                    @php
                        $initial = strtoupper(substr(trim($staff->name), 0, 1)) ?: 'S';
                    @endphp
                    <tr>
                        <td class="text-center text-muted fw-semibold">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-3 flex-shrink-0">
                                    <span class="avatar-title rounded-circle font-size-13 fw-bold shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff;">
                                        {{ $initial }}
                                    </span>
                                </div>
                                <div>
                                    <h6 class="font-size-14 mb-0 fw-bold text-dark">{{ $staff->name }}</h6>
                                    <small class="text-muted font-size-11">Bakery Operations Staff</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="font-monospace text-dark font-size-12">{{ $staff->email }}</span>
                                <button type="button" 
                                        class="btn btn-sm btn-link text-muted p-0" 
                                        onclick="navigator.clipboard.writeText('{{ $staff->email }}'); this.classList.add('text-success'); setTimeout(() => this.classList.remove('text-success'), 1500);" 
                                        title="Copy email address">
                                    <i class="bx bx-copy font-size-14"></i>
                                </button>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill font-size-10 px-2 py-0">
                                    <i class="bx bx-calculator me-1"></i>POS Cashier
                                </span>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-size-10 px-2 py-0">
                                    <i class="bx bx-cookie me-1"></i>Production
                                </span>
                                <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill font-size-10 px-2 py-0">
                                    <i class="bx bx-box me-1"></i>Stock In
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="text-dark fw-medium">{{ $staff->created_at->format('d M, Y') }}</span>
                                <small class="text-muted font-size-11">{{ $staff->created_at->diffForHumans() }}</small>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex align-items-center gap-1">
                                {{-- View Staff Profile --}}
                                <button type="button" 
                                        class="btn btn-sm btn-outline-info rounded-pill px-2 py-1 font-size-11" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#viewStaff{{ $staff->id }}"
                                        title="View Staff Profile">
                                    <i class="bx bx-user me-1"></i>Profile
                                </button>

                                {{-- Revoke Staff Modal Trigger --}}
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1 font-size-11" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteStaff{{ $staff->id }}"
                                        title="Revoke Staff Access">
                                    <i class="bx bx-trash me-1"></i>Revoke
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- =========================================================================
     MODALS SECTION (VIEW PROFILE, DELETE CONFIRMATION, ADD STAFF)
========================================================================= --}}

@foreach($staffs as $staff)
    @php
        $nameParts = preg_split('/\s+/', trim($staff->name), -1, PREG_SPLIT_NO_EMPTY);
        $surname = $nameParts[0] ?? null;
        $firstName = $nameParts[1] ?? null;
        $middleName = count($nameParts) > 2 ? implode(' ', array_slice($nameParts, 2)) : null;
        $initial = strtoupper(substr(trim($staff->name), 0, 1)) ?: 'S';
    @endphp

    {{-- VIEW STAFF PROFILE MODAL --}}
    <div class="modal fade" id="viewStaff{{ $staff->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
                {{-- Header Banner --}}
                <div class="modal-header text-white border-0 py-3 px-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-id-card font-size-22"></i>
                        <h5 class="modal-title text-white fw-bold mb-0">Staff Profile Details</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    {{-- User Hero Summary --}}
                    <div class="d-flex flex-wrap align-items-center gap-3 p-3 bg-light rounded-3 mb-4 border">
                        <div class="avatar-lg flex-shrink-0">
                            <span class="avatar-title rounded-circle font-size-26 fw-bold shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff;">
                                {{ $initial }}
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-0 fw-bold text-dark">{{ $staff->name }}</h4>
                            <p class="text-muted font-size-12 mb-1">
                                <i class="bx bx-envelope me-1 text-success"></i>{{ $staff->email }}
                            </p>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-size-10 px-2 py-0">
                                <i class="bx bx-check-circle me-1"></i>Operational Staff &bull; POS &amp; Bakery Floor
                            </span>
                        </div>
                    </div>

                    {{-- Two-Column Metadata Matrix --}}
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card border bg-white h-100 mb-0 shadow-none rounded-3">
                                <div class="card-header bg-light py-2 px-3 border-bottom">
                                    <span class="text-uppercase fw-bold text-muted font-size-11">Identity Breakdown</span>
                                </div>
                                <div class="card-body p-3 font-size-12">
                                    <div class="d-flex justify-content-between py-1 border-bottom">
                                        <span class="text-muted">Surname:</span>
                                        <span class="fw-bold text-dark">{{ $surname ?? '—' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between py-1 border-bottom">
                                        <span class="text-muted">First Name:</span>
                                        <span class="fw-semibold text-dark">{{ $firstName ?? '—' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between py-1">
                                        <span class="text-muted">Middle Name:</span>
                                        <span class="fw-semibold text-dark">{{ $middleName ?? '—' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border bg-white h-100 mb-0 shadow-none rounded-3">
                                <div class="card-header bg-light py-2 px-3 border-bottom">
                                    <span class="text-uppercase fw-bold text-muted font-size-11">System Account Record</span>
                                </div>
                                <div class="card-body p-3 font-size-12">
                                    <div class="d-flex justify-content-between py-1 border-bottom">
                                        <span class="text-muted">Staff ID:</span>
                                        <span class="font-monospace text-dark fw-bold">#STF-{{ str_pad($staff->id, 4, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between py-1 border-bottom">
                                        <span class="text-muted">Onboarded:</span>
                                        <span class="text-dark fw-medium">{{ $staff->created_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between py-1">
                                        <span class="text-muted">Account Status:</span>
                                        <span class="badge bg-soft-success text-success">Active &bull; Operational</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Staff Role Privileges Overview --}}
                    <div class="card border bg-white mt-3 mb-0 shadow-none rounded-3">
                        <div class="card-header bg-light py-2 px-3 border-bottom d-flex align-items-center justify-content-between">
                            <span class="text-uppercase fw-bold text-muted font-size-11">Functional Permissions &amp; Guardrails</span>
                            <span class="badge bg-success text-white font-size-10">Operations Tier</span>
                        </div>
                        <div class="card-body p-3 font-size-12">
                            <div class="row g-2">
                                <div class="col-sm-6 d-flex align-items-center gap-2">
                                    <i class="bx bx-check-circle text-success font-size-15"></i>
                                    <span>Point of Sale Terminal Transactions</span>
                                </div>
                                <div class="col-sm-6 d-flex align-items-center gap-2">
                                    <i class="bx bx-check-circle text-success font-size-15"></i>
                                    <span>Recording Production Yields &amp; Batches</span>
                                </div>
                                <div class="col-sm-6 d-flex align-items-center gap-2">
                                    <i class="bx bx-check-circle text-success font-size-15"></i>
                                    <span>Stock In Receiving &amp; Quantity Logging</span>
                                </div>
                                <div class="col-sm-6 d-flex align-items-center gap-2">
                                    <i class="bx bx-x-circle text-danger font-size-15"></i>
                                    <span class="text-muted">Cannot Modify Financial Ledgers</span>
                                </div>
                                <div class="col-sm-6 d-flex align-items-center gap-2">
                                    <i class="bx bx-x-circle text-danger font-size-15"></i>
                                    <span class="text-muted">Cannot Delete Core Audit Trails</span>
                                </div>
                                <div class="col-sm-6 d-flex align-items-center gap-2">
                                    <i class="bx bx-x-circle text-danger font-size-15"></i>
                                    <span class="text-muted">Restricted from Stock Danger Zone</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2 px-4 d-flex align-items-center justify-content-between">
                    <span class="text-muted font-size-11">
                        <i class="bx bx-shield me-1 text-success"></i>Managed securely under business security governance.
                    </span>
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Close Profile</button>
                </div>
            </div>
        </div>
    </div>

    {{-- DELETE STAFF CONFIRMATION MODAL --}}
    <div class="modal fade" id="deleteStaff{{ $staff->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ url('admin/deleteStaff') }}" class="w-100">
                @csrf
                <input type="hidden" name="staff_id" value="{{ $staff->id }}">
                <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
                    <div class="modal-body text-center p-4">
                        <div class="avatar-lg mx-auto mb-3">
                            <span class="avatar-title rounded-circle font-size-32 bg-danger-subtle text-danger shadow-sm">
                                <i class="bx bx-user-x"></i>
                            </span>
                        </div>
                        <h4 class="text-danger fw-bold mb-1">Revoke Staff Access?</h4>
                        <p class="text-muted font-size-13 mb-3">
                            You are about to revoke access for <strong class="text-dark">{{ $staff->name }}</strong> (<span class="font-monospace">{{ $staff->email }}</span>).
                        </p>

                        <div class="alert alert-soft-warning border-warning font-size-12 text-start mb-3">
                            <i class="bx bx-alert-triangle me-1 text-warning font-size-15 align-middle"></i>
                            <strong>Account Consequence:</strong> The staff member will be logged out of all POS terminals and floor devices immediately and will no longer be able to log in.
                        </div>

                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm fw-semibold">
                                <i class="bx bx-trash me-1"></i>Confirm Revocation
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

{{-- ADD STAFF MODAL --}}
<div class="modal fade" id="addStaff" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ url('admin/newStaff') }}" class="w-100" id="formAddStaff">
            @csrf
            <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header text-white border-0 py-3 px-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-user-plus font-size-22"></i>
                        <h5 class="modal-title text-white fw-bold mb-0">Enrol New Staff Member</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark font-size-13">
                            Staff Full Name <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bx bx-user font-size-16"></i>
                            </span>
                            <input type="text" 
                                   name="name" 
                                   class="form-control border-start-0 ps-0" 
                                   value="{{ old('name') }}" 
                                   placeholder="e.g. Samuel Adekunle" 
                                   required 
                                   autocomplete="off">
                        </div>
                        <small class="text-muted font-size-11">Legal full name as recognized on operational roster.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark font-size-13">
                            Corporate / Work Email Address <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bx bx-envelope font-size-16"></i>
                            </span>
                            <input type="email" 
                                   name="email" 
                                   class="form-control border-start-0 ps-0" 
                                   value="{{ old('email') }}" 
                                   placeholder="staff@moshedibles.com" 
                                   required 
                                   autocomplete="off">
                        </div>
                        <small class="text-muted font-size-11">A secure password setup invitation will be automatically dispatched to this inbox.</small>
                    </div>

                    <div class="alert alert-soft-success border-success font-size-12 d-flex align-items-center gap-2 mb-0">
                        <i class="bx bx-shield-quarter font-size-18 text-success flex-shrink-0"></i>
                        <div>
                            <strong>Instant Onboarding Invitation:</strong> The staff member will receive an encrypted password setup link to configure their private terminal login.
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2 px-4 d-flex align-items-center justify-content-between">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Discard</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm fw-semibold" id="btnSubmitAddStaff">
                        <i class="bx bx-check-circle me-1"></i>Enrol Staff Member
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const formAddStaff = document.getElementById('formAddStaff');
    const btnSubmit = document.getElementById('btnSubmitAddStaff');

    if (formAddStaff && btnSubmit) {
        formAddStaff.addEventListener('submit', function () {
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Enrolling Staff...';
        });
    }
});
</script>
@endsection
