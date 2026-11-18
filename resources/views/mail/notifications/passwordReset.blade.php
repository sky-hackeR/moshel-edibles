@extends('mail.layout.mail')

@section('preheader', 'Secure password setup for your ' . $portalName . ' account')

@section('content')
    <div style="text-align: center; margin-bottom: 24px;">
        <span class="badge badge-purple" style="margin-bottom: 12px; font-size: 11px;">🔐 Account Security</span>
        <h1 style="margin: 8px 0 10px; color: #4a1253;">{{ isset($heading) ? $heading : 'Set Up Your Password' }}</h1>
        <p style="color: #6d5d70; font-size: 15px; margin: 0 auto; max-width: 480px;">
            Hello <strong>{{ $name }}</strong>,<br>
            {{ isset($intro) ? $intro : 'An account has been created for you on Moshel Edibles. Please use the secure authorization link below to choose your password.' }}
        </p>
    </div>

    <div style="margin: 32px 0; text-align: center;">
        <a href="{{ $resetUrl }}" class="button" target="_blank">
            {{ isset($buttonText) ? $buttonText : 'Choose Password &rarr;' }}
        </a>
    </div>

    <div class="alert-box">
        <p style="margin: 0; font-weight: 700; color: #581a62;">
            ⏳ Time-sensitive: This link expires in 60 minutes
        </p>
        <p style="margin: 6px 0 0; color: #736277; font-size: 13px;">
            If you did not request this link, you can safely ignore this message. Your credentials will remain unchanged.
        </p>
    </div>

    <p style="margin: 24px 0 0; font-size: 12px; color: #8e7c91; text-align: center; line-height: 1.5;">
        Having trouble with the button? Copy and paste this URL into your browser:<br>
        <a href="{{ $resetUrl }}" style="word-break: break-all; color: #b82285; font-size: 11px;">{{ $resetUrl }}</a>
    </p>
@endsection
