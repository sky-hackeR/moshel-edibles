@extends('mail.layout.mail')

@section('content')
    <div style="text-align: center; margin-bottom: 30px;">
        <div style="display: inline-block; background-color: #ebf8ff; padding: 15px; border-radius: 50%; margin-bottom: 15px;">
            <span style="font-size: 30px;">👋</span>
        </div>
        <h2 style="color: #2b6cb0; font-size: 24px; margin: 0;">Welcome to the Team, {{ $staff->name }}!</h2>
        <p style="color: #718096;">Your staff account for the <strong>{{ config('app.name') }}</strong> management system is ready.</p>
    </div>

    <div class="alert-box" style="background-color: #f0f9ff; border-left: 4px solid #3182ce;">
        <p style="margin: 0; color: #2c5282; font-size: 14px; font-weight: 600;">
            🚀 Getting Started
        </p>
        <p style="margin: 5px 0 0; font-size: 13px; color: #2b6cb0;">
            Use the credentials below to log in to your dashboard. For your security, please update your password as soon as you sign in.
        </p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th colspan="2">Your Login Credentials</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #718096; width: 40%;">Portal Email</td>
                <td style="font-weight: 600; color: #2d3748;">{{ $staff->email }}</td>
            </tr>
            <tr>
                <td style="color: #718096;">Temp Password</td>
                <td style="font-family: 'Courier New', monospace; font-weight: 800; color: #556ee6; font-size: 18px;">
                    {{ $password }}
                </td>
            </tr>
            <tr>
                <td style="color: #718096;">Access Level</td>
                <td style="font-size: 13px; font-weight: 600; color: #4a5568;">Staff Operations</td>
            </tr>
        </tbody>
    </table>

    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 20px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #4a5568; line-height: 1.5;">
            You now have access to record sales, manage production batches, and monitor inventory levels based on your assigned permissions.
        </p>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ url('/staff/login') }}" class="button" style="background-color: #556ee6;">
            Open Dashboard
        </a>
    </div>
@endsection