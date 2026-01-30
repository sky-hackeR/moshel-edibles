@extends('staff.layout.auth')

@section('content')
<div class="mb-4 mb-md-5">
    <a href="{{ url('/') }}" class="d-block card-logo">
        <img src="{{ !empty($pageGlobalData->setting) ? asset($pageGlobalData->setting->favicon) : asset('assets/images/logo-dark.png') }}" alt="Logo" height="30" class="card-logo-dark">
    </a>
</div>

<div class="my-auto">
    <div>
        <h5 class="text-primary">Staff Login</h5>
        <p class="text-muted">Enter your credentials to access the terminal.</p>
    </div>

    <div class="mt-4">
        <form action="{{ url('/staff/login') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="staff@example.com" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="mb-3">
                <div class="float-end">
                    <a href="{{ url('/staff/password/reset') }}" class="text-muted">Forgot password?</a>
                </div>
                <label class="form-label">Password</label>
                <div class="input-group auth-pass-inputgroup">
                    <input type="password" name="password" class="form-control" placeholder="Enter password" aria-label="Password" aria-describedby="password-addon" required>
                    <button class="btn btn-light " type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                </div>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember-check">
                <label class="form-check-label" for="remember-check">
                    Keep me signed in
                </label>
            </div>

            <div class="mt-3 d-grid">
                <button class="btn btn-primary waves-effect waves-light fw-bold" type="submit">LOG IN</button>
            </div>
        </form>

        <div class="mt-5 text-center">
            <p>Need assistance? <a href="mailto:support@yourdomain.com" class="fw-medium text-primary"> Contact IT Support </a> </p>
        </div>
    </div>
</div>
@endsection