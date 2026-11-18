@extends('mail.layout.mail')

@section('preheader', '[AUDIT ALERT] Transaction voided: #' . $saleReference . ' — ₦' . number_format($amount, 2))

@section('content')
    <div style="text-align: center; margin-bottom: 24px;">
        <span class="badge-danger" style="display: inline-block; margin-bottom: 10px;">Security & Audit Alert</span>
        <h2 style="margin: 0 0 6px; font-size: 22px; color: #b91c1c; font-weight: 700;">Transaction Voided</h2>
        <p style="margin: 0; color: #6c5a70; font-size: 14px;">A previously finalized transaction has been voided from the active ledger.</p>
    </div>

    <div class="alert-box-danger" style="margin-bottom: 24px;">
        <p style="margin: 0 0 4px; color: #b91c1c; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">High-Priority Cash Audit Required</p>
        <p style="margin: 0; font-size: 13px; color: #991b1b; line-height: 1.5;">
            This action reversed recorded revenue and restored the product quantities to inventory. Please verify cash-on-hand against the terminal register.
        </p>
    </div>

    <table class="data-table" style="margin-bottom: 24px;">
        <thead>
            <tr>
                <th colspan="2" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.6px;">Void Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #6c5a70; width: 40%; font-weight: 500;">Reference Number</td>
                <td style="font-weight: 700; color: #2a172e; font-family: 'Courier New', monospace;">{{ $saleReference }}</td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Voided By</td>
                <td style="font-weight: 600; color: #2a172e;">{{ $user->name }}</td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Amount Reversed</td>
                <td style="font-weight: 800; color: #b91c1c; font-size: 17px;">
                    ₦{{ number_format($amount, 2) }}
                </td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Recorded Timestamp</td>
                <td style="font-size: 13px; color: #2a172e;">{{ now()->format('d M, Y | h:i A') }}</td>
            </tr>
        </tbody>
    </table>

    <div style="background-color: #fff5f5; border: 1px solid #fecaca; border-radius: 8px; padding: 16px; margin-bottom: 28px;">
        <p style="margin: 0 0 6px; font-size: 11px; color: #b91c1c; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px;">Reason for Void:</p>
        <p style="margin: 0; font-size: 13px; color: #2a172e; line-height: 1.5; font-style: italic;">
            "{{ $reason ?? 'No explanation provided by staff.' }}"
        </p>
    </div>

    <div style="text-align: center;">
        <a href="{{ url('/admin/salesHistory') }}" class="button" style="background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%); box-shadow: 0 4px 12px rgba(185, 28, 28, 0.25);">
            Investigate Transaction
        </a>
    </div>
@endsection
