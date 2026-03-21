@extends('staff.layout.auth')

@section('content')
<div class="mb-4 mb-md-5">
    <a href="{{ url('/') }}" class="d-block card-logo">
        <img src="{{ !empty($pageGlobalData->setting->favicon) ? asset($pageGlobalData->setting->favicon) : asset('assets/images/logo-dark.png') }}" alt="Logo" height="30" class="card-logo-dark">
    </a>
</div>

<div class="my-auto">
    <div>
        <h5 class="text-primary fw-bold">Update Password</h5>
        <p class="text-muted">Set a new secure password for your terminal access.</p>
    </div>

    <div class="mt-4 mb-4 p-3 rounded-3" style="background: rgba(85, 110, 230, 0.05); border: 1px dashed rgba(85, 110, 230, 0.2);">
        <p class="text-muted mb-1" style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Authorized Account</p>
        <div class="d-flex align-items-center">
            <div class="avatar-xs me-2">
                <span class="avatar-title rounded-circle bg-primary bg-soft text-primary d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 14px;">
                    <i class="bx bx-user-check"></i>
                </span>
            </div>
            <h6 class="mb-0 fw-bold text-dark">
                {{ $email ?? request()->get('email') ?? 'Staff Member' }}
            </h6>
        </div>
    </div>

    <form id="updatePasswordForm" method="POST" action="{{ url('/staff/password/reset') }}">
        @csrf
    
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email ?? request()->get('email') }}">

        <div class="mt-4">
            <div class="mb-3">
                <label for="password" class="form-label fw-medium">New Password</label>
                <div class="input-group auth-pass-inputgroup border rounded-3 overflow-hidden">
                    <input type="password" name="password" class="form-control border-0 @error('password') is-invalid @enderror" 
                           id="password-input" placeholder="••••••••" required autofocus>
                    <button class="btn btn-link text-decoration-none text-muted" type="button" id="password-addon">
                        <i class="mdi mdi-eye-outline" id="password-icon"></i>
                    </button>
                </div>
                @error('password')
                    <span class="text-danger small"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password-confirm" class="form-label fw-medium">Confirm New Password</label>
                <input id="password-confirm" type="password" class="form-control rounded-3" 
                       name="password_confirmation" placeholder="••••••••" required>
            </div>

            <div class="d-grid">
                <button id="submitBtn" class="btn btn-primary fw-bold p-2 shadow-sm" type="submit">
                    <span class="btn-text">UPDATE PASSWORD</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </form>
</div>

<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
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

        $('#updatePasswordForm').on('submit', function() {
            const btn = $('#submitBtn');
            const text = btn.find('.btn-text');
            const spinner = btn.find('.spinner-border');

            btn.prop('disabled', true).css('opacity', '0.8');
            text.addClass('d-none');
            spinner.removeClass('d-none');
        });
    });
</script>
@endsection