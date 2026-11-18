@extends('admin.layout.auth')

@section('title', 'Set New Admin Password')

@section('content')
    {{-- TOP BANNER --}}
    <div class="admin-auth-banner">
        <div class="row g-0 align-items-center">
            <div class="col-7">
                <div class="p-4">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill font-size-11 px-2.5 py-0.5 mb-2 d-inline-block">
                        <i class="mdi mdi-shield-refresh me-1"></i> Security Credential
                    </span>
                    <h5 class="text-primary fw-bold mb-1">Set New Password</h5>
                    <p class="font-size-12 text-muted mb-0">
                        Secure your administrator account for {{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->site_name : 'Moshel' }}
                    </p>
                </div>
            </div>
            <div class="col-5 align-self-end text-end pe-3">
                <img src="{{ asset('assets/images/profile-img.png') }}" alt="Set Password" class="img-fluid" style="max-height: 95px;">
            </div>
        </div>
    </div>

    {{-- CARD BODY & FLOATING AVATAR --}}
    <div class="card-body pt-0 px-4 pb-4"> 
        <div class="text-center">
            <div class="admin-auth-avatar">
                @if(!empty($pageGlobalData->setting?->favicon))
                    <img src="{{ asset($pageGlobalData->setting->favicon) }}" alt="Logo" class="rounded-circle" height="38" width="38" style="object-fit: contain;">
                @else
                    <i class="mdi mdi-shield-check text-primary font-size-28"></i>
                @endif
            </div>
        </div>

        <div class="pt-3">
            <form class="form-horizontal" method="POST" action="{{ url('/admin/password/reset') }}" id="adminResetPasswordForm">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- EMAIL FIELD --}}
                <div class="mb-3">
                    <label for="email" class="form-label font-size-13 fw-semibold text-dark mb-1">
                        Confirm Admin Email <span class="text-danger">*</span>
                    </label>
                    <div class="input-group admin-auth-input-group @error('email') border-danger @enderror">
                        <span class="input-group-text">
                            <i class="mdi mdi-email-outline text-primary font-size-18"></i>
                        </span>
                        <input type="email" name="email" class="form-control" id="email" 
                               placeholder="admin@domain.com" value="{{ $email ?? old('email') }}" required autofocus autocomplete="email">
                    </div>
                    @error('email')
                        <span class="text-danger font-size-12 mt-1 d-block">
                            <i class="mdi mdi-alert-circle-outline me-1"></i><strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- NEW PASSWORD --}}
                <div class="mb-3">
                    <label for="admin_new_password" class="form-label font-size-13 fw-semibold text-dark mb-1">
                        New Password <span class="text-danger">*</span>
                    </label>
                    <div class="input-group admin-auth-input-group @error('password') border-danger @enderror">
                        <span class="input-group-text">
                            <i class="mdi mdi-lock-open-outline text-primary font-size-18"></i>
                        </span>
                        <input type="password" name="password" class="form-control" id="admin_new_password" 
                               placeholder="Minimum 8 characters" required autocomplete="new-password">
                        <button class="btn btn-light bg-transparent border-0 text-muted" type="button" id="toggleNewPasswordBtn" title="Toggle password visibility">
                            <i class="mdi mdi-eye-outline" id="toggleNewPasswordIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="text-danger font-size-12 mt-1 d-block">
                            <i class="mdi mdi-alert-circle-outline me-1"></i><strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- CONFIRM PASSWORD --}}
                <div class="mb-3">
                    <label for="admin_password_confirmation" class="form-label font-size-13 fw-semibold text-dark mb-1">
                        Confirm New Password <span class="text-danger">*</span>
                    </label>
                    <div class="input-group admin-auth-input-group @error('password_confirmation') border-danger @enderror">
                        <span class="input-group-text">
                            <i class="mdi mdi-lock-check-outline text-primary font-size-18"></i>
                        </span>
                        <input type="password" name="password_confirmation" class="form-control" id="admin_password_confirmation" 
                               placeholder="Repeat new password" required autocomplete="new-password">
                        <button class="btn btn-light bg-transparent border-0 text-muted" type="button" id="toggleConfirmPasswordBtn" title="Toggle password visibility">
                            <i class="mdi mdi-eye-outline" id="toggleConfirmPasswordIcon"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <span class="text-danger font-size-12 mt-1 d-block">
                            <i class="mdi mdi-alert-circle-outline me-1"></i><strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- SECURITY HINT --}}
                <div class="p-2 mb-3 bg-light rounded-3 font-size-12 text-muted">
                    <i class="mdi mdi-information-outline text-primary me-1"></i> Passwords must be at least 8 characters and contain letters and numbers.
                </div>
               
                {{-- SUBMIT BUTTON --}}
                <div class="d-grid mb-3">
                    <button class="btn btn-admin-primary fw-bold" type="submit" id="adminUpdatePasswordBtn">
                        <span id="updateBtnText">UPDATE PASSWORD <i class="mdi mdi-content-save-check-outline ms-1 font-size-16 align-middle"></i></span>
                        <span id="updateBtnSpinner" class="d-none">
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            Saving Password...
                        </span>
                    </button>
                </div>

                {{-- RETURN TO LOGIN --}}
                <div class="pt-2 text-center">
                    <a href="{{ url('/admin/login') }}" class="fw-semibold text-primary font-size-13 text-decoration-none">
                        <i class="mdi mdi-arrow-left me-1"></i> Back to Sign In
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle New Password
        const toggleNewBtn = document.getElementById('toggleNewPasswordBtn');
        const newPasswordInput = document.getElementById('admin_new_password');
        const toggleNewIcon = document.getElementById('toggleNewPasswordIcon');

        if (toggleNewBtn && newPasswordInput && toggleNewIcon) {
            toggleNewBtn.addEventListener('click', function () {
                const isPassword = newPasswordInput.getAttribute('type') === 'password';
                newPasswordInput.setAttribute('type', isPassword ? 'text' : 'password');
                toggleNewIcon.classList.toggle('mdi-eye-outline', !isPassword);
                toggleNewIcon.classList.toggle('mdi-eye-off-outline', isPassword);
            });
        }

        // Toggle Confirm Password
        const toggleConfirmBtn = document.getElementById('toggleConfirmPasswordBtn');
        const confirmPasswordInput = document.getElementById('admin_password_confirmation');
        const toggleConfirmIcon = document.getElementById('toggleConfirmPasswordIcon');

        if (toggleConfirmBtn && confirmPasswordInput && toggleConfirmIcon) {
            toggleConfirmBtn.addEventListener('click', function () {
                const isPassword = confirmPasswordInput.getAttribute('type') === 'password';
                confirmPasswordInput.setAttribute('type', isPassword ? 'text' : 'password');
                toggleConfirmIcon.classList.toggle('mdi-eye-outline', !isPassword);
                toggleConfirmIcon.classList.toggle('mdi-eye-off-outline', isPassword);
            });
        }

        // Submit button loading state
        const form = document.getElementById('adminResetPasswordForm');
        const submitBtn = document.getElementById('adminUpdatePasswordBtn');
        const btnText = document.getElementById('updateBtnText');
        const btnSpinner = document.getElementById('updateBtnSpinner');

        if (form && submitBtn) {
            form.addEventListener('submit', function () {
                submitBtn.disabled = true;
                btnText.classList.add('d-none');
                btnSpinner.classList.remove('d-none');
            });
        }
    });
</script>
@endpush
