@extends('mail.layout.mail')

@section('content')
    <div style="text-align: center; margin-bottom: 30px;">
        <p style="text-transform: uppercase; letter-spacing: 1px; font-size: 12px; font-weight: 700; color: #a0aec0; margin-bottom: 5px;">Performance Report</p>
        <h2 style="color: #2a3042; font-size: 24px; margin: 0;">Daily Financial Summary</h2>
        <p style="color: #718096;">For period ending: <strong>{{ now()->format('d M, Y') }}</strong></p>
    </div>

    <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 25px; text-align: center; margin-bottom: 30px;">
        <p style="margin: 0; color: #166534; font-size: 14px; font-weight: 600; text-transform: uppercase;">Net Profit</p>
        <h1 style="margin: 10px 0; color: #15803d; font-size: 36px; font-weight: 800;">
            ₦{{ number_format($stats['profit'], 2) }}
        </h1>
        <div style="display: inline-block; padding: 4px 12px; background: #dcfce7; color: #166534; border-radius: 20px; font-size: 12px; font-weight: 700;">
            {{ $stats['profit'] > 0 ? '↗ Positive Yield' : '↘ Loss Recorded' }}
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th colspan="2">Revenue & Cost Breakdown</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #718096;">Total Gross Revenue</td>
                <td style="font-weight: 600; text-align: right; color: #2d3748;">
                    ₦{{ number_format($stats['revenue'], 2) }}
                </td>
            </tr>
            <tr>
                <td style="color: #718096;">Total Production Cost</td>
                <td style="font-weight: 600; text-align: right; color: #e53e3e;">
                    (₦{{ number_format($stats['cost'], 2) }})
                </td>
            </tr>
            <tr style="background-color: #f8f9fa;">
                <td style="color: #2d3748; font-weight: 700;">Final Margin</td>
                <td style="font-weight: 800; text-align: right; color: #556ee6;">
                    ₦{{ number_format($stats['profit'], 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    <div style="background-color: #ebf8ff; padding: 15px; border-radius: 8px; margin-top: 20px;">
        <p style="margin: 0; font-size: 12px; color: #2b6cb0; text-align: center;">
            💡 <strong>Insight:</strong> These figures are calculated based on completed productions and POS sales recorded between 12:00 AM and 11:59 PM today.
        </p>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ url('/admin/home') }}" class="button">
            View Full Analytics
        </a>
    </div>
@endsection