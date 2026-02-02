@extends('mail.layout.mail')

@section('content')
    <div style="text-align: center; margin-bottom: 30px;">
        <div style="display: inline-block; background-color: #fff5f5; padding: 15px; border-radius: 50%; margin-bottom: 15px;">
            <span style="font-size: 30px;">🛡️</span>
        </div>
        <h2 style="color: #c53030; font-size: 24px; margin: 0;">Security Audit Notification</h2>
        <p style="color: #718096;">A new administrative user has been granted access to the system.</p>
    </div>

    <div style="background-color: #fff5f5; border-left: 4px solid #c53030; padding: 15px 20px; margin-bottom: 25px; border-radius: 4px;">
        <p style="margin: 0; color: #c53030; font-size: 14px; font-weight: 600;">
            Action: Administrative Account Creation
        </p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th colspan="2">Audit Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #718096; width: 35%;">New Admin Name</td>
                <td style="font-weight: 600; color: #2d3748;">{{ $newAdmin->name }}</td>
            </tr>
            <tr>
                <td style="color: #718096;">Email Address</td>
                <td style="font-weight: 600; color: #2d3748;">{{ $newAdmin->email }}</td>
            </tr>
            <tr>
                <td style="color: #718096;">Authorized By</td>
                <td style="font-weight: 600; color: #2d3748;">{{ $creator->name }}</td>
            </tr>
            <tr>
                <td style="color: #718096;">Timestamp</td>
                <td style="font-weight: 600; color: #2d3748;">{{ now()->format('d M, Y | h:i A') }}</td>
            </tr>
        </tbody>
    </table>

    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 30px;">
        <p style="margin: 0; font-size: 13px; color: #4a5568; line-height: 1.5;">
            <strong>Pro Tip:</strong> If this action was unauthorized, log in to the 
            <a href="{{ url('/admin/adminList') }}" style="color: #556ee6; text-decoration: none;">Security Settings</a> 
            immediately to deactivate the account and reset your own credentials.
        </p>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ url('/admin/staffList') }}" class="button" style="background-color: #2d3748;">
            Review Staff List
        </a>
    </div>
@endsection