@extends('mail.layout.mail')

@section('preheader', 'Verify your email address to activate your Moshel account')

@section('content')
    <h2>Verify your email address</h2>
    <p>Hello {{ $name }},</p>
    <p>Confirm your email address to activate your Moshel account and sign in.</p>

    <div style="margin: 28px 0; text-align: center;">
        <a href="{{ $verificationUrl }}" class="button">Verify email address</a>
    </div>

    <div class="alert-box">
        <p style="margin: 0; color: #64236f; font-weight: 700;">This link expires in 60 minutes.</p>
        <p style="margin: 6px 0 0;">If you did not create this account, you can ignore this email.</p>
    </div>
@endsection