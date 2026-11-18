@extends('admin.layout.dashboard')

@section('content')
<style>
    /* ==========================================================================
       MOSHEL EXECUTIVE PROFILE & SECURITY STYLES
       ========================================================================== */
    .profile-hero-card {
        border-radius: 14px;
        overflow: hidden;
        border: none;
    }
    .profile-cover-gradient {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
        height: 110px;
        position: relative;
    }
    .profile-avatar-wrapper {
        position: relative;
        margin-top: -50px;
        margin-bottom: 12px;
        padding-left: 20px;
    }
    .profile-avatar-circle {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        border: 4px solid #ffffff;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 32px;
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        color: #ffffff;
    }
    .security-badge-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px;
    }
    .password-toggle-btn {
        cursor: pointer;
        background: transparent;
        border: none;
        color: #64748b;
    }
    .password-toggle-btn:hover {
        color: #1e293b;
    }
    .custom-accordion .accordion-item {
        border-radius: 12px !important;
        overflow: hidden;
    }
    .custom-accordion .accordion-button {
        box-shadow: none !important;
    }
    .custom-accordion .accordion-button:not(.collapsed) {
        background-color: #f8fafc;
        color: #1e293b;
    }
</style>

@php
    $adminInitials = collect(explode(' ', trim($admin->name)))
        ->filter()
        ->map(fn($part) => strtoupper(substr($part, 0, 1)))
        ->take(2)
        ->implode('');
    if (empty($adminInitials)) {
        $adminInitials = 'A';
    }
@endphp

{{-- =====================================================
     BREADCRUMB & PAGE HEADER
     ===================================================== --}}
<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between pb-2">
            <div>
                <h4 class="mb-sm-0 font-size-18 fw-bold">Admin Profile & Security</h4>
                <ol class="breadcrumb m-0 mt-1">
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 font-size-12 fw-medium rounded-pill">
                    <i class="bx bx-radio-circle-marked me-1"></i> Active Officer Session
                </span>
            </div>
        </div>
    </div>
</div>

{{-- =====================================================
     SESSION ALERTS
     ===================================================== --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 10px;">
        <i class="bx bx-check-circle font-size-18 text-success"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 10px;">
        <div class="d-flex align-items-start gap-2">
            <i class="bx bx-error-circle font-size-18 text-danger mt-0.5"></i>
            <div>
                <strong class="d-block mb-1">Please correct the following errors:</strong>
                <ul class="mb-0 ps-3 font-size-13">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    {{-- =====================================================
         LEFT COLUMN: EXECUTIVE IDENTITY & ACCOUNT OVERVIEW
         ===================================================== --}}
    <div class="col-xl-4 col-lg-5">
        {{-- Profile Hero Card --}}
        <div class="card shadow-sm profile-hero-card mb-4">
            <div class="profile-cover-gradient">
                <div class="position-absolute top-0 end-0 p-3">
                    <span class="badge bg-white bg-opacity-25 text-white font-size-11 px-2.5 py-1 rounded-pill backdrop-blur">
                        <i class="bx bx-shield-quarter me-1"></i> Administrator
                    </span>
                </div>
            </div>

            <div class="profile-avatar-wrapper">
                <div class="profile-avatar-circle">
                    {{ $adminInitials }}
                </div>
            </div>

            <div class="card-body pt-0 px-4 pb-4">
                <h4 class="fw-bold text-dark mb-0.5">{{ $admin->name }}</h4>
                <p class="text-muted font-size-13 mb-3">{{ $admin->email }}</p>

                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-primary-subtle text-primary font-size-11 px-2.5 py-1 rounded-pill">
                        <i class="bx bx-key me-1"></i> Super Admin
                    </span>
                    <span class="badge bg-light text-dark border font-size-11 px-2.5 py-1 rounded-pill">
                        <i class="bx bx-calendar me-1 text-muted"></i> Joined {{ $admin->created_at ? $admin->created_at->format('M Y') : 'N/A' }}
                    </span>
                </div>

                <hr class="my-3 text-muted opacity-25">

                <div class="security-badge-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted font-size-11 text-uppercase fw-bold">Security Standing</span>
                        <span class="badge bg-success-subtle text-success font-size-10 px-2 py-0.5 rounded-pill">Secured</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bx bx-lock-alt text-success font-size-16"></i>
                        <span class="font-size-12 text-dark fw-medium">Password Protection Active</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-check-shield text-primary font-size-16"></i>
                        <span class="font-size-12 text-dark fw-medium">Full ERP Access Privileges</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Account Details Card --}}
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3 px-3">
                <h6 class="card-title mb-0 fw-bold text-dark font-size-14">
                    <i class="bx bx-id-card me-1 text-primary"></i> Account Summary
                </h6>
            </div>
            <div class="card-body p-3">
                <div class="d-flex justify-content-between py-2 border-bottom font-size-13">
                    <span class="text-muted">Officer ID</span>
                    <strong class="text-dark">#ADM-{{ str_pad($admin->id, 4, '0', STR_PAD_LEFT) }}</strong>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom font-size-13">
                    <span class="text-muted">Display Name</span>
                    <strong class="text-dark">{{ $admin->name }}</strong>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom font-size-13">
                    <span class="text-muted">Primary Email</span>
                    <span class="text-dark fw-semibold text-truncate ms-2" style="max-width: 180px;" title="{{ $admin->email }}">{{ $admin->email }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 font-size-13">
                    <span class="text-muted">Last Updated</span>
                    <span class="text-dark">{{ $admin->updated_at ? $admin->updated_at->format('d M, Y') : 'Recent' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- =====================================================
         RIGHT COLUMN: ACCORDION DETAIL CARDS
         ===================================================== --}}
    <div class="col-xl-8 col-lg-7">
        <div class="accordion custom-accordion" id="adminProfileAccordion">
            {{-- ACCORDION ITEM 1: PERSONAL INFORMATION --}}
            <div class="accordion-item border-0 shadow-sm mb-4">
                <h2 class="accordion-header" id="headingPersonalInfo">
                    <button class="accordion-button bg-white py-3 px-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePersonalInfo" aria-expanded="true" aria-controls="collapsePersonalInfo">
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar-xs flex-shrink-0">
                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                    <i class="bx bx-user-circle font-size-18"></i>
                                </span>
                            </span>
                            <div>
                                <h5 class="mb-0 fw-bold text-dark font-size-15">Personal Information</h5>
                                <small class="text-muted">Update your officer name and contact email address.</small>
                            </div>
                        </div>
                    </button>
                </h2>
                <div id="collapsePersonalInfo" class="accordion-collapse collapse show" aria-labelledby="headingPersonalInfo" data-bs-parent="#adminProfileAccordion">
                    <div class="accordion-body p-4 bg-white border-top">
                        <form action="{{ url('/admin/updateProfile') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark font-size-12 mb-1">Full Legal Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-user"></i></span>
                                        <input type="text" name="name" class="form-control border-start-0 @error('name') is-invalid @enderror" value="{{ old('name', $admin->name) }}" required placeholder="e.g. John Doe">
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark font-size-12 mb-1">Primary Email Address <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-envelope"></i></span>
                                        <input type="email" name="email" class="form-control border-start-0 @error('email') is-invalid @enderror" value="{{ old('email', $admin->email) }}" required placeholder="admin@moshel.com">
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4 pt-2 border-top">
                                <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 font-size-13 fw-semibold shadow-sm">
                                    <i class="bx bx-check-circle me-1 font-size-14"></i> Save Profile Details
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ACCORDION ITEM 2: PASSWORD & SECURITY --}}
            <div class="accordion-item border-0 shadow-sm mb-4">
                <h2 class="accordion-header" id="headingPasswordSecurity">
                    <button class="accordion-button bg-white py-3 px-4 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePasswordSecurity" aria-expanded="false" aria-controls="collapsePasswordSecurity">
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar-xs flex-shrink-0">
                                <span class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                    <i class="bx bx-lock-alt font-size-18"></i>
                                </span>
                            </span>
                            <div>
                                <h5 class="mb-0 fw-bold text-dark font-size-15">Password & Security</h5>
                                <small class="text-muted">Ensure your account uses a strong password with at least 8 characters.</small>
                            </div>
                        </div>
                    </button>
                </h2>
                <div id="collapsePasswordSecurity" class="accordion-collapse collapse" aria-labelledby="headingPasswordSecurity" data-bs-parent="#adminProfileAccordion">
                    <div class="accordion-body p-4 bg-white border-top">
                        <form action="{{ url('/admin/updatePassword') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark font-size-12 mb-1">Current Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-key"></i></span>
                                        <input type="password" name="current_password" id="admin_current_password" class="form-control border-start-0 border-end-0 @error('current_password') is-invalid @enderror" required placeholder="Enter your current password">
                                        <span class="input-group-text bg-light border-start-0">
                                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('admin_current_password', this)">
                                                <i class="bx bx-show"></i>
                                            </button>
                                        </span>
                                    </div>
                                    @error('current_password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark font-size-12 mb-1">New Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-lock-open-alt"></i></span>
                                        <input type="password" name="new_password" id="admin_new_password" class="form-control border-start-0 border-end-0 @error('new_password') is-invalid @enderror" required placeholder="Minimum 8 characters">
                                        <span class="input-group-text bg-light border-start-0">
                                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('admin_new_password', this)">
                                                <i class="bx bx-show"></i>
                                            </button>
                                        </span>
                                    </div>
                                    @error('new_password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark font-size-12 mb-1">Confirm New Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-check-double"></i></span>
                                        <input type="password" name="new_password_confirmation" id="admin_confirm_password" class="form-control border-start-0 border-end-0" required placeholder="Re-enter new password">
                                        <span class="input-group-text bg-light border-start-0">
                                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('admin_confirm_password', this)">
                                                <i class="bx bx-show"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-light border font-size-12 mt-3 mb-0 p-3 rounded-3">
                                <div class="d-flex align-items-center gap-2 text-muted">
                                    <i class="bx bx-info-circle text-primary font-size-16"></i>
                                    <span>Password must be at least <strong>8 characters</strong>. Avoid reusing old passwords across other accounts.</span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4 pt-2 border-top">
                                <button type="submit" class="btn btn-warning rounded-pill px-4 py-2 font-size-13 fw-semibold shadow-sm text-dark">
                                    <i class="bx bx-shield-alt-2 me-1 font-size-14"></i> Update Security Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (!input) return;

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bx-show');
        icon.classList.add('bx-hide');
    } else {
        input.type = 'password';
        icon.classList.remove('bx-hide');
        icon.classList.add('bx-show');
    }
}
</script>
@endsection
