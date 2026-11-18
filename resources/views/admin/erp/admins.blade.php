@extends('admin.layout.dashboard')

@section('content')

@php
    $totalAdmins = $admins->count();
    $newThisMonth = $admins->where('created_at', '>=', now()->startOfMonth())->count();
    $currentAdmin = auth('admin')->user();
@endphp

{{-- PAGE HEADER & ACTION BAR --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-sm-0 font-size-18 text-primary fw-bold">
                    <i class="bx bx-shield-quarter me-1 align-middle" style="color: #581a62;"></i> System Administrators
                </h4>
                <p class="text-muted font-size-12 mb-0">
                    Manage executive user accounts, system configuration privileges, and security credentials.
                </p>
            </div>
            <div class="page-title-right d-flex align-items-center gap-2">
                <a href="{{ route('admin.auditLogs.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm font-size-12">
                    <i class="bx bx-history me-1 align-middle"></i> Audit Log Trail
                </a>
                <button class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm font-size-12 fw-semibold" data-bs-toggle="modal" data-bs-target="#addAdmin">
                    <i class="bx bx-plus-circle me-1 align-middle"></i> Add Administrator
                </button>
                <ol class="breadcrumb m-0 ms-2 d-none d-md-flex">
                    <li class="breadcrumb-item active">Admins</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- EXECUTIVE KPI SUMMARY CARDS --}}
<div class="row g-3 mb-4">
    {{-- Card 1: Total Admins --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #581a62 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Total Administrators</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ $totalAdmins }}</h4>
                        <small class="text-success font-size-11 fw-medium">
                            <i class="bx bx-check-shield me-1"></i>Full System Authority
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20" style="background-color: #f7eef8; color: #581a62;">
                            <i class="bx bx-shield-quarter"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Security & Encryption --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #10b981 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Security Standard</p>
                        <h4 class="mb-0 fw-bold text-dark">High Entropy</h4>
                        <small class="text-success font-size-11 fw-semibold">
                            <i class="bx bx-lock-alt me-1"></i>Tokenized Reset Links
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-success-subtle text-success">
                            <i class="bx bx-key"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 3: Provisioned This Month --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">New This Month</p>
                        <h4 class="mb-0 fw-bold text-dark">{{ $newThisMonth }}</h4>
                        <small class="text-info font-size-11 fw-medium">
                            <i class="bx bx-calendar me-1"></i>{{ now()->format('F Y') }}
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-info-subtle text-info">
                            <i class="bx bx-user-plus"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 4: Current Session --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div style="min-width: 0;">
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Active Session</p>
                        <h4 class="mb-0 fw-bold text-dark text-truncate" title="{{ $currentAdmin?->name }}">{{ $currentAdmin?->name ?? 'Admin' }}</h4>
                        <small class="text-warning font-size-11 fw-semibold">
                            <i class="bx bx-radio-circle-marked me-1"></i>Authenticated Officer
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0 ms-2">
                        <span class="avatar-title rounded-circle font-size-20 bg-warning-subtle text-warning">
                            <i class="bx bx-user-check"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MAIN ADMINISTRATOR DIRECTORY CARD --}}
<div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h5 class="card-title mb-0 fw-bold text-dark">
                <i class="bx bx-user-circle me-1 text-primary"></i> Administrator Directory
            </h5>
            <small class="text-muted">Registered executive officers holding full read/write permissions.</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill bg-light text-dark border px-3 py-1 font-size-11">
                {{ $totalAdmins }} {{ Str::plural('Account', $totalAdmins) }} Enrolled
            </span>
        </div>
    </div>

    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-hover align-middle dt-responsive nowrap w-100 font-size-13 mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">S/N</th>
                        <th>Administrator Name</th>
                        <th>Email Address</th>
                        <th>Access Scope</th>
                        <th>Enrolled Date</th>
                        <th class="text-center" style="width: 130px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                    @php
                        $isCurrentAdmin = ($currentAdmin && $admin->id === $currentAdmin->id);
                        $initial = strtoupper(substr(trim($admin->name), 0, 1)) ?: 'A';
                    @endphp
                    <tr>
                        <td class="text-center text-muted fw-semibold">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-3 flex-shrink-0">
                                    <span class="avatar-title rounded-circle font-size-13 fw-bold shadow-sm" style="background: linear-gradient(135deg, #581a62 0%, #7b2284 100%); color: #ffffff;">
                                        {{ $initial }}
                                    </span>
                                </div>
                                <div>
                                    <h6 class="font-size-14 mb-0 fw-bold text-dark">
                                        {{ $admin->name }}
                                        @if($isCurrentAdmin)
                                            <span class="badge bg-soft-primary text-primary ms-1 font-size-10 rounded-pill px-2 py-0 border border-primary-subtle">
                                                <i class="bx bx-check-circle me-1"></i>You
                                            </span>
                                        @endif
                                    </h6>
                                    <small class="text-muted font-size-11">Executive Administrator</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="font-monospace text-dark font-size-12">{{ $admin->email }}</span>
                                <button type="button" 
                                        class="btn btn-sm btn-link text-muted p-0" 
                                        onclick="navigator.clipboard.writeText('{{ $admin->email }}'); this.classList.add('text-success'); setTimeout(() => this.classList.remove('text-success'), 1500);" 
                                        title="Copy email address">
                                    <i class="bx bx-copy font-size-14"></i>
                                </button>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-size-11 px-2 py-1">
                                <i class="bx bx-check-shield me-1"></i>Full Admin Rights
                            </span>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="text-dark fw-medium">{{ $admin->created_at->format('d M, Y') }}</span>
                                <small class="text-muted font-size-11">{{ $admin->created_at->diffForHumans() }}</small>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex align-items-center gap-1">
                                {{-- View Profile Modal Trigger --}}
                                <button type="button" 
                                        class="btn btn-sm btn-outline-info rounded-pill px-2 py-1 font-size-11" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#viewAdmin{{ $admin->id }}"
                                        title="View Administrator Details">
                                    <i class="bx bx-user me-1"></i>Profile
                                </button>

                                {{-- Delete Admin Trigger (Disabled for Self) --}}
                                @if($isCurrentAdmin)
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-1 font-size-11 disabled opacity-50" 
                                            title="Cannot delete your active administrator session"
                                            disabled>
                                        <i class="bx bx-trash me-1"></i>Revoke
                                    </button>
                                @else
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1 font-size-11" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteAdmin{{ $admin->id }}"
                                            title="Revoke Administrator Access">
                                        <i class="bx bx-trash me-1"></i>Revoke
                                    </button>
                                @endif
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
     MODALS SECTION (VIEW PROFILE, DELETE CONFIRMATION, ADD ADMINISTRATOR)
========================================================================= --}}

@foreach($admins as $admin)
    @php
        $isCurrentAdmin = ($currentAdmin && $admin->id === $currentAdmin->id);
        $nameParts = preg_split('/\s+/', trim($admin->name), -1, PREG_SPLIT_NO_EMPTY);
        $surname = $nameParts[0] ?? null;
        $firstName = $nameParts[1] ?? null;
        $middleName = count($nameParts) > 2 ? implode(' ', array_slice($nameParts, 2)) : null;
        $initial = strtoupper(substr(trim($admin->name), 0, 1)) ?: 'A';
    @endphp

    {{-- VIEW ADMIN PROFILE MODAL --}}
    <div class="modal fade" id="viewAdmin{{ $admin->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
                {{-- Header Banner --}}
                <div class="modal-header text-white border-0 py-3 px-4" style="background: linear-gradient(135deg, #581a62 0%, #7b2284 100%);">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-shield-quarter font-size-22"></i>
                        <h5 class="modal-title text-white fw-bold mb-0">Administrator Profile</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    {{-- User Hero Summary --}}
                    <div class="d-flex flex-wrap align-items-center gap-3 p-3 bg-light rounded-3 mb-4 border">
                        <div class="avatar-lg flex-shrink-0">
                            <span class="avatar-title rounded-circle font-size-26 fw-bold shadow-sm" style="background: linear-gradient(135deg, #581a62 0%, #7b2284 100%); color: #ffffff;">
                                {{ $initial }}
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2">
                                <h4 class="mb-0 fw-bold text-dark">{{ $admin->name }}</h4>
                                @if($isCurrentAdmin)
                                    <span class="badge bg-soft-primary text-primary font-size-11 rounded-pill px-2">Current Active Session</span>
                                @endif
                            </div>
                            <p class="text-muted font-size-12 mb-1">
                                <i class="bx bx-envelope me-1 text-primary"></i>{{ $admin->email }}
                            </p>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-size-10 px-2 py-0">
                                <i class="bx bx-check-double me-1"></i>System Administrator &bull; Tier 1 Access
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
                                    <span class="text-uppercase fw-bold text-muted font-size-11">Account Credential Details</span>
                                </div>
                                <div class="card-body p-3 font-size-12">
                                    <div class="d-flex justify-content-between py-1 border-bottom">
                                        <span class="text-muted">System ID:</span>
                                        <span class="font-monospace text-dark fw-bold">#ADM-{{ str_pad($admin->id, 4, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between py-1 border-bottom">
                                        <span class="text-muted">Onboarded:</span>
                                        <span class="text-dark fw-medium">{{ $admin->created_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between py-1">
                                        <span class="text-muted">Password Security:</span>
                                        <span class="badge bg-soft-success text-success">Bcrypt Hash (60 chars)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Authority & Privileges Scope --}}
                    <div class="card border bg-white mt-3 mb-0 shadow-none rounded-3">
                        <div class="card-header bg-light py-2 px-3 border-bottom d-flex align-items-center justify-content-between">
                            <span class="text-uppercase fw-bold text-muted font-size-11">Authorized Privilege Matrix</span>
                            <span class="badge bg-primary text-white font-size-10">Unrestricted</span>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-2 font-size-12">
                                <div class="col-sm-6 d-flex align-items-center gap-2">
                                    <i class="bx bx-check-circle text-success font-size-15"></i>
                                    <span>Financial Reports &amp; POS Ledgers</span>
                                </div>
                                <div class="col-sm-6 d-flex align-items-center gap-2">
                                    <i class="bx bx-check-circle text-success font-size-15"></i>
                                    <span>Production Recipes &amp; Cost Matrices</span>
                                </div>
                                <div class="col-sm-6 d-flex align-items-center gap-2">
                                    <i class="bx bx-check-circle text-success font-size-15"></i>
                                    <span>Staff &amp; Admin Account Provisioning</span>
                                </div>
                                <div class="col-sm-6 d-flex align-items-center gap-2">
                                    <i class="bx bx-check-circle text-success font-size-15"></i>
                                    <span>Audit Trail &amp; Forensic Security Logs</span>
                                </div>
                                <div class="col-sm-6 d-flex align-items-center gap-2">
                                    <i class="bx bx-check-circle text-success font-size-15"></i>
                                    <span>Inventory Danger Zone &amp; Write-Offs</span>
                                </div>
                                <div class="col-sm-6 d-flex align-items-center gap-2">
                                    <i class="bx bx-check-circle text-success font-size-15"></i>
                                    <span>Global System Branding &amp; Storefront</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2 px-4 d-flex align-items-center justify-content-between">
                    <span class="text-muted font-size-11">
                        <i class="bx bx-info-circle me-1"></i>Modifications require authorized root access.
                    </span>
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Close Profile</button>
                </div>
            </div>
        </div>
    </div>

    {{-- DELETE / REVOKE CONFIRMATION MODAL --}}
    @if(!$isCurrentAdmin)
    <div class="modal fade" id="deleteAdmin{{ $admin->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ url('admin/deleteAdmin') }}" class="w-100">
                @csrf
                <input type="hidden" name="admin_id" value="{{ $admin->id }}">
                <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
                    <div class="modal-body text-center p-4">
                        <div class="avatar-lg mx-auto mb-3">
                            <span class="avatar-title rounded-circle font-size-32 bg-danger-subtle text-danger shadow-sm">
                                <i class="bx bx-error-circle"></i>
                            </span>
                        </div>
                        <h4 class="text-danger fw-bold mb-1">Revoke Administrator Privileges?</h4>
                        <p class="text-muted font-size-13 mb-3">
                            You are about to permanently remove <strong class="text-dark">{{ $admin->name }}</strong> (<span class="font-monospace">{{ $admin->email }}</span>).
                        </p>

                        <div class="alert alert-soft-warning border-warning font-size-12 text-start mb-3">
                            <i class="bx bx-shield-x me-1 text-warning font-size-15 align-middle"></i>
                            <strong>Security Consequence:</strong> All active sessions will be terminated immediately. The user will lose access to financials, production, and system configurations.
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
    @endif
@endforeach

{{-- ADD ADMINISTRATOR MODAL --}}
<div class="modal fade" id="addAdmin" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ url('admin/newAdmin') }}" class="w-100" id="formAddAdmin">
            @csrf
            <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header text-white border-0 py-3 px-4" style="background: linear-gradient(135deg, #581a62 0%, #7b2284 100%);">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-user-plus font-size-22"></i>
                        <h5 class="modal-title text-white fw-bold mb-0">Enrol New Administrator</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark font-size-13">
                            Full Name <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bx bx-user font-size-16"></i>
                            </span>
                            <input type="text" 
                                   name="name" 
                                   class="form-control border-start-0 ps-0" 
                                   value="{{ old('name') }}" 
                                   placeholder="e.g. David Jolayemi" 
                                   required 
                                   autocomplete="off">
                        </div>
                        <small class="text-muted font-size-11">First and last name as recognized on corporate identification.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark font-size-13">
                            Email Address <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bx bx-envelope font-size-16"></i>
                            </span>
                            <input type="email" 
                                   name="email" 
                                   class="form-control border-start-0 ps-0" 
                                   value="{{ old('email') }}" 
                                   placeholder="admin@moshedibles.com" 
                                   required 
                                   autocomplete="off">
                        </div>
                        <small class="text-muted font-size-11">A high-entropy password setup invitation will be automatically dispatched here.</small>
                    </div>

                    <div class="alert alert-soft-info border-info font-size-12 d-flex align-items-center gap-2 mb-0">
                        <i class="bx bx-info-circle font-size-18 text-info flex-shrink-0"></i>
                        <div>
                            <strong>Automatic Security Provisioning:</strong> The new officer will receive an encrypted setup link to configure their private password. No plain passwords are sent.
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2 px-4 d-flex align-items-center justify-content-between">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Discard</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold" id="btnSubmitAddAdmin">
                        <i class="bx bx-check-circle me-1"></i>Enrol Administrator
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
    const formAddAdmin = document.getElementById('formAddAdmin');
    const btnSubmit = document.getElementById('btnSubmitAddAdmin');

    if (formAddAdmin && btnSubmit) {
        formAddAdmin.addEventListener('submit', function () {
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Creating Account...';
        });
    }
});
</script>
@endsection
