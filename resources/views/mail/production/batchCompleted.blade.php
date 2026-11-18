@extends('mail.layout.mail')

@section('preheader', 'Production batch completed: ' . number_format($production->quantity) . ' ' . $production->product->sales_unit . ' of ' . $production->product->name)

@section('content')
    <div style="text-align: center; margin-bottom: 24px;">
        <span class="badge-success" style="display: inline-block; margin-bottom: 10px;">Production Finalized</span>
        <h2 style="margin: 0 0 6px; font-size: 22px; color: #2a172e; font-weight: 700;">Batch Successfully Completed</h2>
        <p style="margin: 0; color: #6c5a70; font-size: 14px;">Raw materials have been deducted and finished stock added to ready inventory.</p>
    </div>

    <!-- Yield Banner Card -->
    <div style="background-color: #fbf6fc; border-radius: 12px; padding: 22px; margin-bottom: 24px; border: 1px solid #ebddec; text-align: center;">
        <p style="margin: 0 0 4px; color: #6c5a70; font-size: 11px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.8px;">Total Units Produced</p>
        <h1 style="margin: 0 0 4px; color: #581a62; font-size: 32px; font-weight: 800; letter-spacing: -0.5px;">
            {{ number_format($production->quantity) }} <span style="font-size: 16px; font-weight: 600; color: #ce278c;">{{ $production->product->sales_unit }}</span>
        </h1>
        <p style="margin: 0; color: #2a172e; font-weight: 700; font-size: 16px;">{{ $production->product->name }}</p>
    </div>

    <table class="data-table" style="margin-bottom: 24px;">
        <thead>
            <tr>
                <th colspan="2" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.6px;">Cost & Yield Analysis</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #6c5a70; width: 45%; font-weight: 500;">Total Batch Cost</td>
                <td style="font-weight: 700; color: #2a172e; text-align: right; font-size: 15px;">
                    ₦{{ number_format($production->total_cost, 2) }}
                </td>
            </tr>
            <tr style="background-color: #fbf6fc;">
                <td style="color: #581a62; font-weight: 600;">Unit Production Cost</td>
                <td style="font-weight: 800; color: #047857; text-align: right; font-size: 16px;">
                    ₦{{ number_format($production->unit_cost, 2) }}
                </td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Recorded By</td>
                <td style="color: #2a172e; text-align: right; font-weight: 500;">{{ Auth::user()->name ?? 'System Automated' }}</td>
            </tr>
        </tbody>
    </table>

    @if($production->notes)
    <div class="alert-box" style="margin-bottom: 26px;">
        <p style="margin: 0 0 4px; font-size: 11px; color: #581a62; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px;">Baker / Production Notes:</p>
        <p style="margin: 0; font-size: 13px; color: #2a172e; font-style: italic; line-height: 1.5;">
            "{{ $production->notes }}"
        </p>
    </div>
    @endif

    <div style="text-align: center;">
        <a href="{{ url('/admin/production-history') }}" class="button">
            View Production Log
        </a>
    </div>
@endsection
