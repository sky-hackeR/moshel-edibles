@extends('mail.layout.mail')

@section('content')
    <div style="text-align: center; margin-bottom: 30px;">
        <div style="display: inline-block; background-color: #f0fdf4; padding: 15px; border-radius: 50%; margin-bottom: 15px;">
            <span style="font-size: 30px;">🏭</span>
        </div>
        <h2 style="color: #059669; font-size: 24px; margin: 0;">Production Finalized</h2>
        <p style="color: #718096;">A new production run has been completed and stock has been updated.</p>
    </div>

    <div style="background-color: #f8f9fa; border-radius: 12px; padding: 25px; margin-bottom: 30px; border: 1px solid #e2e8f0; text-align: center;">
        <p style="margin: 0; color: #718096; font-size: 12px; text-transform: uppercase; font-weight: 700;">Items Produced</p>
        <h1 style="margin: 5px 0; color: #2d3748; font-size: 28px; font-weight: 800;">
            {{ number_format($production->quantity) }} <span style="font-size: 16px; color: #718096;">{{ $production->product->sales_unit }}</span>
        </h1>
        <p style="margin: 0; color: #556ee6; font-weight: 600; font-size: 18px;">{{ $production->product->name }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th colspan="2">Cost Analysis</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #718096; width: 40%;">Total Batch Cost</td>
                <td style="font-weight: 600; color: #2d3748; text-align: right;">
                    ₦{{ number_format($production->total_cost, 2) }}
                </td>
            </tr>
            <tr style="background-color: #fcfcfd;">
                <td style="color: #718096;">Unit Production Cost</td>
                <td style="font-weight: 800; color: #059669; text-align: right; font-size: 16px;">
                    ₦{{ number_format($production->unit_cost, 2) }}
                </td>
            </tr>
            <tr>
                <td style="color: #718096;">Recorded By</td>
                <td style="color: #4a5568; text-align: right;">{{ Auth::user()->name ?? 'System' }}</td>
            </tr>
        </tbody>
    </table>

    @if($production->notes)
    <div style="background-color: #fffaf0; padding: 15px; border-radius: 8px; border: 1px dashed #f6ad55; margin-top: 20px;">
        <p style="margin: 0 0 5px 0; font-size: 11px; color: #9c4221; font-weight: 700; text-transform: uppercase;">Production Notes:</p>
        <p style="margin: 0; font-size: 13px; color: #7b341e; font-style: italic;">
            "{{ $production->notes }}"
        </p>
    </div>
    @endif

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ url('/admin/production-history') }}" class="button" style="background-color: #2d3748;">
            View Production Log
        </a>
    </div>
@endsection