@extends('mail.layout.mail')

@section('preheader', 'Verify your email address to activate your Moshel account')

@section('content')
    <div style="text-align: center; margin-bottom: 24px;">
        <span class="badge badge-purple" style="margin-bottom: 12px; font-size: 11px;">✨ Welcome to Moshel</span>
        <h1 style="margin: 8px 0 10px; color: #4a1253;">Verify Your Email Address</h1>
        <p style="color: #6d5d70; font-size: 15px; margin: 0 auto; max-width: 460px;">
            Hello <strong>{{ $name }}</strong>, thank you for joining Moshel Edibles. Please confirm your email address to activate your account and explore our menu.
        </p>
    </div>

    <div style="margin: 32px 0; text-align: center;">
        <a href="{{ $verificationUrl }}" class="button" target="_blank">
            Verify Email Address &rarr;
        </a>
    </div>

    <div class="alert-box">
        <p style="margin: 0; font-weight: 700; color: #581a62;">
            🔒 Security Notice: Link expires in 60 minutes
        </p>
        <p style="margin: 6px 0 0; color: #736277; font-size: 13px;">
            If you did not create an account on Moshel Edibles, no further action is required and you can safely disregard this message.
        </p>
    </div>

    <p style="margin: 24px 0 0; font-size: 12px; color: #8e7c91; text-align: center; line-height: 1.5;">
        Button not working? Copy and paste this URL into your browser:<br>
        <a href="{{ $verificationUrl }}" style="word-break: break-all; color: #b82285; font-size: 11px;">{{ $verificationUrl }}</a>
    </p>
@endsection
