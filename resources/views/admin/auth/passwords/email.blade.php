@extends('admin.layout.auth')

@section('title', 'Reset Admin Password')

@section('content')
    {{-- TOP BANNER --}}
    <div class="admin-auth-banner">
        <div class="row g-0 align-items-center">
            <div class="col-7">
                <div class="p-4">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill font-size-11 px-2.5 py-0.5 mb-2 d-inline-block">
                        <i class="mdi mdi-key-variant me-1"></i> Account Recovery
                    </span>
                    <h5 class="text-primary fw-bold mb-1">Reset Password</h5>
                    <p class="font-size-12 text-muted mb-0">
                        Restore access to {{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->site_name : 'Moshel' }}
                    </p>
                </div>
            </div>
            <div class="col-5 align-self-end text-end pe-3">
                <img src="{{ asset('assets/images/profile-img.png') }}" alt="Reset Password" class="img-fluid" style="max-height: 95px;">
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
                    <i class="mdi mdi-lock-reset text-primary font-size-28"></i>
                @endif
            </div>
        </div>
        
        <div class="pt-3">
            {{-- SESSION STATUS ALERT --}}
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show text-center mb-3 shadow-sm border-0 font-size-13" role="alert">
                    <i class="mdi mdi-check-all me-1"></i>
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- INSTRUCTION BANNER --}}
            <div class="alert alert-info border-0 bg-info-subtle text-info text-center mb-4 font-size-13 py-2 px-3 rounded-3" role="alert">
                <i class="mdi mdi-information-outline me-1"></i> Enter your registered email address and instructions will be sent to you.
            </div>

            <form class="form-horizontal" method="POST" action="{{ url('/admin/password/email') }}" id="adminResetEmailForm">
                @csrf
                
                {{-- EMAIL FIELD --}}
                <div class="mb-4">
                    <label for="email" class="form-label font-size-13 fw-semibold text-dark mb-1">
                        Email Address <span class="text-danger">*</span>
                    </label>
                    <div class="input-group admin-auth-input-group @error('email') border-danger @enderror">
                        <span class="input-group-text">
                            <i class="mdi mdi-email-send-outline text-primary font-size-18"></i>
                        </span>
                        <input type="email" name="email" class="form-control" id="email" 
                               placeholder="Enter your admin email" value="{{ old('email') }}" required autofocus autocomplete="email">
                    </div>
                    @error('email')
                        <span class="text-danger font-size-12 mt-1 d-block">
                            <i class="mdi mdi-alert-circle-outline me-1"></i><strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- SUBMIT BUTTON --}}
                <div class="d-grid mb-3">
                    <button class="btn btn-admin-primary fw-bold" type="submit" id="adminSendResetBtn">
                        <span id="resetBtnText">SEND RESET LINK <i class="mdi mdi-send ms-1 font-size-16 align-middle"></i></span>
                        <span id="resetBtnSpinner" class="d-none">
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            Dispatching Email...
                        </span>
                    </button>
                </div>

                {{-- RETURN TO LOGIN --}}
                <div class="pt-2 text-center">
                    <p class="mb-1 font-size-12 text-muted">Remember your password?</p>
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
        const form = document.getElementById('adminResetEmailForm');
        const submitBtn = document.getElementById('adminSendResetBtn');
        const btnText = document.getElementById('resetBtnText');
        const btnSpinner = document.getElementById('resetBtnSpinner');

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
