@extends('staff.layout.auth')

@section('content')

<div class="mb-3">
    <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1 rounded-pill font-size-11 mb-2.5 d-inline-flex align-items-center gap-1.5">
        <i class="bx bx-user-plus"></i> Staff Onboarding
    </span>
    <h3 class="fw-bold text-dark mb-1" style="letter-spacing: -0.02em;">Create Staff Account</h3>
    <p class="text-muted font-size-13 mb-4">Register a new terminal account for bakery operations and cashier access.</p>
</div>

<form id="registerForm" method="POST" action="{{ url('/staff/register') }}">
    @csrf

    {{-- FULL NAME --}}
    <div class="mb-3">
        <label for="name" class="form-label font-size-13 fw-semibold text-dark mb-1.5">Full Name</label>
        <div class="input-group staff-input-group">
            <span class="input-group-text"><i class="bx bx-user font-size-18"></i></span>
            <input id="name"
                   type="text"
                   class="form-control font-size-13 @error('name') is-invalid @enderror"
                   name="name"
                   value="{{ old('name') }}"
                   placeholder="e.g. Jane Doe"
                   required
                   autofocus>
        </div>
        @error('name')
            <span class="text-danger font-size-12 mt-1 d-block"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    {{-- EMAIL ADDRESS --}}
    <div class="mb-3">
        <label for="email" class="form-label font-size-13 fw-semibold text-dark mb-1.5">Staff Email Address</label>
        <div class="input-group staff-input-group">
            <span class="input-group-text"><i class="bx bx-envelope font-size-18"></i></span>
            <input id="email"
                   type="email"
                   class="form-control font-size-13 @error('email') is-invalid @enderror"
                   name="email"
                   value="{{ old('email') }}"
                   placeholder="staff@moshel.test"
                   required>
        </div>
        @error('email')
            <span class="text-danger font-size-12 mt-1 d-block"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    {{-- PASSWORD --}}
    <div class="mb-3">
        <label for="password-input" class="form-label font-size-13 fw-semibold text-dark mb-1.5">Password</label>
        <div class="input-group staff-input-group">
            <span class="input-group-text"><i class="bx bx-lock-alt font-size-18"></i></span>
            <input id="password-input"
                   type="password"
                   class="form-control font-size-13 @error('password') is-invalid @enderror"
                   name="password"
                   placeholder="Create password (min. 8 characters)"
                   required>
            <button class="btn btn-link text-muted text-decoration-none px-3" type="button" id="password-addon" aria-label="Toggle password visibility">
                <i class="mdi mdi-eye-outline font-size-18" id="password-icon"></i>
            </button>
        </div>
        @error('password')
            <span class="text-danger font-size-12 mt-1 d-block"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    {{-- CONFIRM PASSWORD --}}
    <div class="mb-4">
        <label for="password-confirm" class="form-label font-size-13 fw-semibold text-dark mb-1.5">Confirm Password</label>
        <div class="input-group staff-input-group">
            <span class="input-group-text"><i class="bx bx-check-shield font-size-18"></i></span>
            <input id="password-confirm"
                   type="password"
                   class="form-control font-size-13"
                   name="password_confirmation"
                   placeholder="Repeat your password"
                   required>
            <button class="btn btn-link text-muted text-decoration-none px-3" type="button" id="password-confirm-addon" aria-label="Toggle password visibility">
                <i class="mdi mdi-eye-outline font-size-18" id="password-confirm-icon"></i>
            </button>
        </div>
    </div>

    {{-- SUBMIT BUTTON --}}
    <div class="d-grid mb-4">
        <button id="submitBtn" class="btn btn-staff-primary w-100 py-2.5 font-size-14 d-flex align-items-center justify-content-center gap-2" type="submit">
            <span class="btn-text fw-semibold">Complete Staff Registration</span>
            <i class="bx bx-right-arrow-alt font-size-18 btn-text"></i>
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
        </button>
    </div>

    {{-- FOOTER SIGN IN LINK --}}
    <div class="text-center pt-2 font-size-13">
        <span class="text-muted">Already have a staff account?</span>
        <a href="{{ url('/staff/login') }}" class="fw-semibold text-primary text-decoration-none ms-1">
            Sign In Here <i class="bx bx-right-arrow-alt"></i>
        </a>
    </div>
</form>

@push('scripts')
<script>
    $(document).ready(function() {
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

        $('#registerForm').on('submit', function() {
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
