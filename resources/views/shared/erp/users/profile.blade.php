@php
    $portal = $portal ?? ($role ?? 'admin');
    $isAdmin = ($portal === 'admin');

    $user = $user ?? ($isAdmin ? ($admin ?? auth('admin')->user()) : ($staff ?? auth('staff')->user()));

    $initials = collect(explode(' ', trim($user->name ?? '')))
        ->filter()
        ->map(fn($part) => strtoupper(substr($part, 0, 1)))
        ->take(2)
        ->implode('');
    if (empty($initials)) {
        $initials = $isAdmin ? 'A' : 'S';
    }

    $coverGradient = $isAdmin
        ? 'linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%)'
        : 'linear-gradient(135deg, #064e3b 0%, #065f46 50%, #059669 100%)';

    $avatarGradient = $isAdmin
        ? 'linear-gradient(135deg, #4f46e5 0%, #6366f1 100%)'
        : 'linear-gradient(135deg, #059669 0%, #10b981 100%)';

    $pageTitle = $isAdmin ? 'Admin Profile & Security' : 'Staff Profile & Security';
    $sessionBadge = $isAdmin ? 'Active Officer Session' : 'Active Staff Session';
    $roleBadgeText = $isAdmin ? 'Administrator' : 'Staff Member';
    $roleBadgeIcon = $isAdmin ? 'bx bx-shield-quarter' : 'bx bx-user-check';

    $subRoleBadgeClass = $isAdmin ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success';
    $subRoleBadgeIcon = $isAdmin ? 'bx bx-key' : 'bx bx-store';
    $subRoleBadgeText = $isAdmin ? 'Super Admin' : 'Floor Operations';

    $standingLabel = $isAdmin ? 'Security Standing' : 'Station Access';
    $standingBadge = $isAdmin ? 'Secured' : 'Active';
    $privilegeIcon = $isAdmin ? 'bx bx-check-shield text-primary' : 'bx bx-store-alt text-primary';
    $privilegeText = $isAdmin ? 'Full ERP Access Privileges' : 'POS Terminal & Production';

    $idLabel = $isAdmin ? 'Officer ID' : 'Staff Badge';
    $idPrefix = $isAdmin ? '#ADM-' : '#STF-';
    $emailLabel = $isAdmin ? 'Primary Email' : 'Work Email';
    $emailFormLabel = $isAdmin ? 'Primary Email Address' : 'Work Email Address';
    $emailPlaceholder = $isAdmin ? 'admin@moshel.com' : 'staff@moshel.com';
    $namePlaceholder = $isAdmin ? 'e.g. John Doe' : 'e.g. Jane Doe';
    $infoSubtext = $isAdmin ? 'Update your officer name and contact email address.' : 'Update your staff name and contact email address.';

    $updateProfileUrl = $updateProfileUrl ?? url('/' . $portal . '/updateProfile');
    $updatePasswordUrl = $updatePasswordUrl ?? url('/' . $portal . '/updatePassword');

    $prefix = $portal;
    $accordionId = $isAdmin ? 'adminProfileAccordion' : 'staffProfileAccordion';
    $personalHeadingId = $isAdmin ? 'headingPersonalInfo' : 'headingStaffPersonalInfo';
    $personalCollapseId = $isAdmin ? 'collapsePersonalInfo' : 'collapseStaffPersonalInfo';
    $passwordHeadingId = $isAdmin ? 'headingPasswordSecurity' : 'headingStaffPasswordSecurity';
    $passwordCollapseId = $isAdmin ? 'collapsePasswordSecurity' : 'collapseStaffPasswordSecurity';

    $currentPassId = $prefix . '_current_password';
    $newPassId = $prefix . '_new_password';
    $confirmPassId = $prefix . '_confirm_password';

    $passwordNote = $isAdmin
        ? 'Password must be at least <strong>8 characters</strong>. Avoid reusing old passwords across other accounts.'
        : 'Password must be at least <strong>8 characters</strong>. Never share your password with other staff members.';
@endphp

<style>
    /* ==========================================================================
       MOSHEL EXECUTIVE PROFILE & SECURITY STYLES (SHARED)
       ========================================================================== */
    .profile-hero-card {
        border-radius: 14px;
        overflow: hidden;
        border: none;
    }
    .profile-cover-gradient {
        background: {{ $coverGradient }};
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
        background: {{ $avatarGradient }};
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

{{-- =====================================================
     BREADCRUMB & PAGE HEADER
     ===================================================== --}}
<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between pb-2">
            <div>
                <h4 class="mb-sm-0 font-size-18 fw-bold">{!! $pageTitle !!}</h4>
                <ol class="breadcrumb m-0 mt-1">
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 font-size-12 fw-medium rounded-pill">
                    <i class="bx bx-radio-circle-marked me-1"></i> {{ $sessionBadge }}
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
         LEFT COLUMN: IDENTITY & ACCOUNT OVERVIEW
         ===================================================== --}}
    <div class="col-xl-4 col-lg-5">
        {{-- Profile Hero Card --}}
        <div class="card shadow-sm profile-hero-card mb-4">
            <div class="profile-cover-gradient">
                <div class="position-absolute top-0 end-0 p-3">
                    <span class="badge bg-white bg-opacity-25 text-white font-size-11 px-2.5 py-1 rounded-pill backdrop-blur">
                        <i class="{{ $roleBadgeIcon }} me-1"></i> {{ $roleBadgeText }}
                    </span>
                </div>
            </div>

            <div class="profile-avatar-wrapper">
                <div class="profile-avatar-circle">
                    {{ $initials }}
                </div>
            </div>

            <div class="card-body pt-0 px-4 pb-4">
                <h4 class="fw-bold text-dark mb-0.5">{{ $user->name ?? '' }}</h4>
                <p class="text-muted font-size-13 mb-3">{{ $user->email ?? '' }}</p>

                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge {{ $subRoleBadgeClass }} font-size-11 px-2.5 py-1 rounded-pill">
                        <i class="{{ $subRoleBadgeIcon }} me-1"></i> {{ $subRoleBadgeText }}
                    </span>
                    <span class="badge bg-light text-dark border font-size-11 px-2.5 py-1 rounded-pill">
                        <i class="bx bx-calendar me-1 text-muted"></i> Joined {{ (!empty($user) && $user->created_at) ? $user->created_at->format('M Y') : 'N/A' }}
                    </span>
                </div>

                <hr class="my-3 text-muted opacity-25">

                <div class="security-badge-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted font-size-11 text-uppercase fw-bold">{{ $standingLabel }}</span>
                        <span class="badge bg-success-subtle text-success font-size-10 px-2 py-0.5 rounded-pill">{{ $standingBadge }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bx bx-lock-alt text-success font-size-16"></i>
                        <span class="font-size-12 text-dark fw-medium">Password Protection Active</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="{{ $privilegeIcon }} font-size-16"></i>
                        <span class="font-size-12 text-dark fw-medium">{!! $privilegeText !!}</span>
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
                    <span class="text-muted">{{ $idLabel }}</span>
                    <strong class="text-dark">{{ $idPrefix }}{{ (!empty($user) && $user->id) ? str_pad($user->id, 4, '0', STR_PAD_LEFT) : '0000' }}</strong>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom font-size-13">
                    <span class="text-muted">Display Name</span>
                    <strong class="text-dark">{{ $user->name ?? '' }}</strong>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom font-size-13">
                    <span class="text-muted">{{ $emailLabel }}</span>
                    <span class="text-dark fw-semibold text-truncate ms-2" style="max-width: 180px;" title="{{ $user->email ?? '' }}">{{ $user->email ?? '' }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 font-size-13">
                    <span class="text-muted">Last Updated</span>
                    <span class="text-dark">{{ (!empty($user) && $user->updated_at) ? $user->updated_at->format('d M, Y') : 'Recent' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- =====================================================
         RIGHT COLUMN: ACCORDION DETAIL CARDS
         ===================================================== --}}
    <div class="col-xl-8 col-lg-7">
        <div class="accordion custom-accordion" id="{{ $accordionId }}">
            {{-- ACCORDION ITEM 1: PERSONAL INFORMATION --}}
            <div class="accordion-item border-0 shadow-sm mb-4">
                <h2 class="accordion-header" id="{{ $personalHeadingId }}">
                    <button class="accordion-button bg-white py-3 px-4" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $personalCollapseId }}" aria-expanded="true" aria-controls="{{ $personalCollapseId }}">
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar-xs flex-shrink-0">
                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                    <i class="bx bx-user-circle font-size-18"></i>
                                </span>
                            </span>
                            <div>
                                <h5 class="mb-0 fw-bold text-dark font-size-15">Personal Information</h5>
                                <small class="text-muted">{{ $infoSubtext }}</small>
                            </div>
                        </div>
                    </button>
                </h2>
                <div id="{{ $personalCollapseId }}" class="accordion-collapse collapse show" aria-labelledby="{{ $personalHeadingId }}" data-bs-parent="#{{ $accordionId }}">
                    <div class="accordion-body p-4 bg-white border-top">
                        <form action="{{ $updateProfileUrl }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark font-size-12 mb-1">Full Legal Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-user"></i></span>
                                        <input type="text" name="name" class="form-control border-start-0 @error('name') is-invalid @enderror" value="{{ old('name', $user->name ?? '') }}" required placeholder="{{ $namePlaceholder }}">
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark font-size-12 mb-1">{{ $emailFormLabel }} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-envelope"></i></span>
                                        <input type="email" name="email" class="form-control border-start-0 @error('email') is-invalid @enderror" value="{{ old('email', $user->email ?? '') }}" required placeholder="{{ $emailPlaceholder }}">
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
                <h2 class="accordion-header" id="{{ $passwordHeadingId }}">
                    <button class="accordion-button bg-white py-3 px-4 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $passwordCollapseId }}" aria-expanded="false" aria-controls="{{ $passwordCollapseId }}">
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
                <div id="{{ $passwordCollapseId }}" class="accordion-collapse collapse" aria-labelledby="{{ $passwordHeadingId }}" data-bs-parent="#{{ $accordionId }}">
                    <div class="accordion-body p-4 bg-white border-top">
                        <form action="{{ $updatePasswordUrl }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark font-size-12 mb-1">Current Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-key"></i></span>
                                        <input type="password" name="current_password" id="{{ $currentPassId }}" class="form-control border-start-0 border-end-0 @error('current_password') is-invalid @enderror" required placeholder="Enter your current password">
                                        <span class="input-group-text bg-light border-start-0">
                                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('{{ $currentPassId }}', this)">
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
                                        <input type="password" name="new_password" id="{{ $newPassId }}" class="form-control border-start-0 border-end-0 @error('new_password') is-invalid @enderror" required placeholder="Minimum 8 characters">
                                        <span class="input-group-text bg-light border-start-0">
                                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('{{ $newPassId }}', this)">
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
                                        <input type="password" name="new_password_confirmation" id="{{ $confirmPassId }}" class="form-control border-start-0 border-end-0" required placeholder="Re-enter new password">
                                        <span class="input-group-text bg-light border-start-0">
                                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('{{ $confirmPassId }}', this)">
                                                <i class="bx bx-show"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-light border font-size-12 mt-3 mb-0 p-3 rounded-3">
                                <div class="d-flex align-items-center gap-2 text-muted">
                                    <i class="bx bx-info-circle text-primary font-size-16"></i>
                                    <span>{!! $passwordNote !!}</span>
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
