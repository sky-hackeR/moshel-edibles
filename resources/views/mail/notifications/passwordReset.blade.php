@extends('mail.layout.mail')

@section('preheader', 'Secure password setup for your ' . $portalName . ' account')

@section('content')
    <h2>{{ isset($heading) ? $heading : 'Set up your password' }}</h2>
    <p>Hello {{ $name }},</p>
    <p>{{ isset($intro) ? $intro : 'An account has been created for you. Use the secure link below to choose your password.' }}</p>

    <div style="margin: 28px 0; text-align: center;">
        <a href="{{ $resetUrl }}" class="button">{{ isset($buttonText) ? $buttonText : 'Set Password' }}</a>
    </div>

    <div class="alert-box">
        <p style="margin: 0; color: #64236f; font-weight: 700;">This link expires in 60 minutes.</p>
        <p style="margin: 6px 0 0;">If you did not expect this message, you can ignore it. Your password will not change unless the link is used.</p>
    </div>
@endsection