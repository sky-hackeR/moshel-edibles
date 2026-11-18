@extends('staff.layout.auth')

@section('content')

<div class="mb-3">
    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill font-size-11 mb-2 d-inline-flex align-items-center gap-1">
        <i class="bx bx-shield-quarter"></i> Security Setup
    </span>
    <h3 class="fw-bold text-dark mb-1" style="letter-spacing: -0.02em;">Set Terminal Password</h3>
    <p class="text-muted font-size-13 mb-3">Create a strong, secure password to access your staff workstation and POS register.</p>
</div>

{{-- AUTHORIZED ACCOUNT BADGE --}}
<div class="mb-4 p-3 rounded-3 bg-light border d-flex align-items-center gap-2">
    <div class="avatar-xs rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
        <i class="bx bx-user-check font-size-18"></i>
    </div>
    <div>
        <small class="text-muted text-uppercase fw-bold font-size-10 d-block" style="letter-spacing: 0.5px;">Authorized Staff Account</small>
        <strong class="text-dark font-size-13">{{ $email ?? request()->get('email') ?? 'Staff Member' }}</strong>
    </div>
</div>

<form id="updatePasswordForm" method="POST" action="{{ url('/staff/password/reset') }}">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ $email ?? request()->get('email') }}">

    {{-- NEW PASSWORD --}}
    <div class="mb-3">
        <label for="password-input" class="form-label font-size-13 fw-semibold text-dark mb-1.5">New Password</label>
        <div class="input-group staff-input-group">
            <span class="input-group-text"><i class="bx bx-lock-alt font-size-18"></i></span>
            <input type="password"
                   name="password"
                   id="password-input"
                   class="form-control font-size-13 @error('password') is-invalid @enderror"
                   placeholder="Enter new password (min. 8 characters)"
                   required
                   autofocus>
            <button class="btn btn-link text-muted text-decoration-none px-3" type="button" id="password-addon" aria-label="Toggle password visibility">
                <i class="mdi mdi-eye-outline font-size-18" id="password-icon"></i>
            </button>
        </div>
        @error('password')
            <span class="text-danger font-size-12 mt-1 d-block"><strong>{{ $message }}</strong></span>
        @enderror
        <small class="text-muted font-size-11 d-block mt-1">Must be at least 8 characters with a mix of letters and numbers.</small>
    </div>

    {{-- CONFIRM NEW PASSWORD --}}
    <div class="mb-4">
        <label for="password-confirm" class="form-label font-size-13 fw-semibold text-dark mb-1.5">Confirm New Password</label>
        <div class="input-group staff-input-group">
            <span class="input-group-text"><i class="bx bx-check-shield font-size-18"></i></span>
            <input type="password"
                   name="password_confirmation"
                   id="password-confirm"
                   class="form-control font-size-13"
                   placeholder="Re-enter your new password"
                   required>
            <button class="btn btn-link text-muted text-decoration-none px-3" type="button" id="password-confirm-addon" aria-label="Toggle password visibility">
                <i class="mdi mdi-eye-outline font-size-18" id="password-confirm-icon"></i>
            </button>
        </div>
    </div>

    {{-- SUBMIT BUTTON --}}
    <div class="d-grid mb-4">
        <button id="submitBtn" class="btn btn-staff-primary w-100 py-2.5 font-size-14 d-flex align-items-center justify-content-center gap-2" type="submit">
            <span class="btn-text fw-semibold">Save Password &amp; Access Terminal</span>
            <i class="bx bx-right-arrow-alt font-size-18 btn-text"></i>
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
        </button>
    </div>

    {{-- BACK TO LOGIN --}}
    <div class="text-center pt-2 font-size-13">
        <a href="{{ url('/staff/login') }}" class="fw-semibold text-muted text-decoration-none">
            <i class="bx bx-arrow-back me-0.5"></i> Return to Staff Login
        </a>
    </div>
</form>

@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle new password visibility
        $('#password-addon').on('click', function() {
            const input = $('#password-input');
            const icon = $('#password-icon');
            
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('mdi-eye-outline').addClass('mdi-eye-off-outline');
            } else {
                input.attr('type', 'password');
                icon.removeClass('mdi-eye-off-outline').addClass('mdi-eye-outline');
            }
        });

        // Toggle confirm password visibility
        $('#password-confirm-addon').on('click', function() {
            const input = $('#password-confirm');
            const icon = $('#password-confirm-icon');
            
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('mdi-eye-outline').addClass('mdi-eye-off-outline');
            } else {
                input.attr('type', 'password');
                icon.removeClass('mdi-eye-off-outline').addClass('mdi-eye-outline');
            }
        });

        // Loading state
        $('#updatePasswordForm').on('submit', function() {
            const btn = $('#submitBtn');
            const text = btn.find('.btn-text');
            const spinner = btn.find('.spinner-border');

            btn.prop('disabled', true);
            text.addClass('d-none');
            spinner.removeClass('d-none');
        });
    });
</script>
@endpush

@endsection
