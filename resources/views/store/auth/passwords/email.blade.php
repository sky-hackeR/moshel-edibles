@extends('store.layouts.app')
@section('title', 'Reset Password')
@section('content')
<div class="page-header bg-section"><div class="container"><div class="page-header-box"><h1>Reset password</h1><nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('store.welcome') }}">Home</a></li><li class="breadcrumb-item active">Password reset</li></ol></nav></div></div></div>
<section class="store-account-section bg-section"><div class="container"><div class="row justify-content-center"><div class="col-lg-6"><div class="store-form-panel"><div class="section-title"><h2>Get back into your account</h2><p>Enter your email and we will send you a secure reset link.</p></div>@if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
<form method="POST" action="{{ route('customer.password.email') }}">@csrf<div class="store-form-field"><label for="email">Email address</label><input id="email" type="email" name="email" value="{{ old('email') }}" required>@error('email')<small>{{ $message }}</small>@enderror</div><button type="submit" class="btn-default">Send reset link</button></form>
</div></div></div></div></section>
@endsection
