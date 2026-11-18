@extends('mail.layout.mail')

@section('preheader', '[SECURITY ALERT] New administrative account created for ' . $newAdmin->name)

@section('content')
    <div style="text-align: center; margin-bottom: 24px;">
        <span class="badge-purple" style="display: inline-block; margin-bottom: 10px;">Security Audit Alert</span>
        <h2 style="margin: 0 0 6px; font-size: 22px; color: #2a172e; font-weight: 700;">Admin Privileges Granted</h2>
        <p style="margin: 0; color: #6c5a70; font-size: 14px;">A new administrative user has been provisioned with elevated system permissions.</p>
    </div>

    <div class="alert-box-warning" style="margin-bottom: 24px;">
        <p style="margin: 0 0 4px; color: #b45309; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Authorization Notice</p>
        <p style="margin: 0; font-size: 13px; color: #92400e; line-height: 1.5;">
            An administrative account has full access to financial data, inventory changes, and system settings.
        </p>
    </div>

    <table class="data-table" style="margin-bottom: 24px;">
        <thead>
            <tr>
                <th colspan="2" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.6px;">Account Audit Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #6c5a70; width: 35%; font-weight: 500;">New Admin Name</td>
                <td style="font-weight: 700; color: #2a172e;">{{ $newAdmin->name }}</td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Email Address</td>
                <td style="font-weight: 600; color: #581a62;">{{ $newAdmin->email }}</td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Authorized By</td>
                <td style="font-weight: 600; color: #2a172e;">{{ $creator->name }}</td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Provisioned At</td>
                <td style="font-size: 13px; color: #6c5a70;">{{ now()->format('d M, Y | h:i A') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="alert-box" style="margin-bottom: 28px;">
        <p style="margin: 0; font-size: 13px; color: #581a62; line-height: 1.5;">
            <strong>Security Advisory:</strong> If you did not authorize this action, visit the 
            <a href="{{ url('/admin/adminList') }}" style="color: #ce278c; text-decoration: underline; font-weight: 600;">Admin Management</a> 
            portal immediately to revoke access.
        </p>
    </div>

    <div style="text-align: center;">
        <a href="{{ url('/admin/staffList') }}" class="button">
            Review Staff Directory
        </a>
    </div>
@endsection
