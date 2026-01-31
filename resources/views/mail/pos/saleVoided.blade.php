@extends('mail.layout.mail')

@section('content')
    <div style="text-align: center; margin-bottom: 30px;">
        <div style="display: inline-block; background-color: #fff5f5; padding: 15px; border-radius: 50%; margin-bottom: 15px; border: 2px solid #feb2b2;">
            <span style="font-size: 30px;">⚠️</span>
        </div>
        <h2 style="color: #c53030; font-size: 24px; margin: 0;">Transaction Void Alert</h2>
        <p style="color: #718096;">A finalized sale has been removed from the records.</p>
    </div>

    <div class="alert-box" style="background-color: #fff5f5; border-left: 4px solid #c53030;">
        <p style="margin: 0; color: #9b2c2c; font-size: 14px; font-weight: 600;">
            🚨 High-Priority Audit Required
        </p>
        <p style="margin: 5px 0 0; font-size: 13px; color: #c53030;">
            This action has reversed the revenue and restored items to inventory. Please verify against physical cash-on-hand.
        </p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th colspan="2">Void Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #718096; width: 40%;">Reference Number</td>
                <td style="font-weight: 700; color: #2d3748;">{{ $saleReference }}</td>
            </tr>
            <tr>
                <td style="color: #718096;">Voided By</td>
                <td style="font-weight: 600; color: #2d3748;">{{ $user->name }}</td>
            </tr>
            <tr>
                <td style="color: #718096;">Amount Reversed</td>
                <td style="font-weight: 700; color: #c53030; font-size: 18px;">
                    ₦{{ number_format($amount, 2) }}
                </td>
            </tr>
            <tr>
                <td style="color: #718096;">Timestamp</td>
                <td style="font-size: 13px; color: #4a5568;">{{ now()->format('d M, Y | h:i A') }}</td>
            </tr>
        </tbody>
    </table>

    <div style="background-color: #fef2f2; padding: 20px; border-radius: 8px; margin-top: 20px; border: 1px solid #fee2e2;">
        <p style="margin: 0 0 5px 0; font-size: 12px; color: #9b2c2c; font-weight: 700; text-transform: uppercase;">Reason for Void:</p>
        <p style="margin: 0; font-size: 14px; color: #4a5568; line-height: 1.5; font-style: italic;">
            "{{ $reason ?? 'No explanation provided by staff.' }}"
        </p>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ url('/admin/salesHistory') }}" class="button" style="background-color: #c53030;">
            Investigate Transaction
        </a>
    </div>
@endsection