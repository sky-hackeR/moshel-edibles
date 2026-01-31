@extends('mail.layout.mail')

@section('content')
    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="color: #2a3042; font-size: 24px;">Account Created</h2>
        <p>Hello, <strong>{{ $admin->name }}</strong>. You have been granted Administrative access to the {{ config('app.name') }} dashboard.</p>
    </div>

    <div class="alert-box">
        <p style="margin: 0; color: #856404; font-size: 14px; font-weight: 600;">
            <span style="margin-right: 10px;">🔐</span> Security Action Required
        </p>
        <p style="margin: 5px 0 0; font-size: 13px;">Please use the temporary credentials below to access your account. You will be required to update your password upon first login.</p>
    </div>

    <table class="data-table">
        <tr>
            <td style="color: #718096; width: 30%;">Access Level</td>
            <td style="font-weight: 600;">Administrator</td>
        </tr>
        <tr>
            <td style="color: #718096;">Email Address</td>
            <td style="font-weight: 600;">{{ $admin->email }}</td>
        </tr>
        <tr>
            <td style="color: #718096;">Temp. Password</td>
            <td style="font-family: monospace; font-size: 16px; color: #e83e8c; font-weight: 700;">{{ $password }}</td>
        </tr>
    </table>

    <div style="text-align: center; margin-top: 40px; margin-bottom: 20px;">
        <a href="{{ url('/admin/login') }}" class="button">
            Secure Sign In
        </a>
    </div>

    <hr style="border: none; border-top: 1px solid #edf2f7; margin: 30px 0;">

    <p style="font-size: 13px; color: #a0aec0; text-align: center;">
        If you did not expect this invitation, please ignore this email or contact the system owner.
    </p>
@endsection