@extends('mail.layout.mail')

@section('preheader', 'Daily financial performance report: Net Profit ₦' . number_format($stats['profit'], 2))

@section('content')
    <div style="text-align: center; margin-bottom: 24px;">
        <span class="badge-purple" style="display: inline-block; margin-bottom: 10px;">Executive Briefing</span>
        <h2 style="margin: 0 0 6px; font-size: 22px; color: #2a172e; font-weight: 700;">Daily Financial Summary</h2>
        <p style="margin: 0; color: #6c5a70; font-size: 14px;">Reporting period ending: <strong>{{ now()->format('d M, Y') }}</strong></p>
    </div>

    <!-- Hero Profit Card -->
    <div style="background: {{ $stats['profit'] >= 0 ? 'linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%)' : 'linear-gradient(135deg, #fff5f5 0%, #fef2f2 100%)' }}; border: 1px solid {{ $stats['profit'] >= 0 ? '#bbf7d0' : '#fecaca' }}; border-radius: 12px; padding: 24px; text-align: center; margin-bottom: 26px;">
        <p style="margin: 0 0 6px; color: {{ $stats['profit'] >= 0 ? '#166534' : '#991b1b' }}; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px;">Today's Net Profit</p>
        <h1 style="margin: 0 0 10px; color: {{ $stats['profit'] >= 0 ? '#15803d' : '#b91c1c' }}; font-size: 34px; font-weight: 800; letter-spacing: -0.5px;">
            ₦{{ number_format($stats['profit'], 2) }}
        </h1>
        <span class="{{ $stats['profit'] >= 0 ? 'badge-success' : 'badge-danger' }}" style="display: inline-block;">
            {{ $stats['profit'] >= 0 ? '↗ Positive Yield' : '↘ Loss Recorded' }}
        </span>
    </div>

    <!-- Breakdown Table -->
    <table class="data-table" style="margin-bottom: 22px;">
        <thead>
            <tr>
                <th colspan="2" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.6px;">Revenue & Cost Breakdown</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Total Gross Revenue</td>
                <td style="font-weight: 700; text-align: right; color: #2a172e; font-size: 15px;">
                    ₦{{ number_format($stats['revenue'], 2) }}
                </td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Total Production Cost</td>
                <td style="font-weight: 700; text-align: right; color: #b91c1c; font-size: 15px;">
                    (₦{{ number_format($stats['cost'], 2) }})
                </td>
            </tr>
            <tr style="background-color: #fbf6fc;">
                <td style="color: #581a62; font-weight: 700; font-size: 14px;">Final Margin</td>
                <td style="font-weight: 800; text-align: right; color: #581a62; font-size: 17px;">
                    ₦{{ number_format($stats['profit'], 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="alert-box" style="margin-bottom: 28px; text-align: center;">
        <p style="margin: 0; font-size: 12px; color: #581a62; line-height: 1.5;">
            💡 <strong>Insight:</strong> Figures are calculated from all completed production batches and finalized POS transactions recorded between 12:00 AM and 11:59 PM today.
        </p>
    </div>

    <div style="text-align: center;">
        <a href="{{ url('/admin/home') }}" class="button">
            View Analytics Dashboard
        </a>
    </div>
@endsection
