@extends('mail.layout.mail')

@section('content')
    <div style="text-align: center; margin-bottom: 30px;">
        <div style="display: inline-block; background-color: #ebf8ff; padding: 15px; border-radius: 50%; margin-bottom: 15px;">
            <span style="font-size: 30px;">🚚</span>
        </div>
        <h2 style="color: #2b6cb0; font-size: 24px; margin: 0;">Inventory Replenished</h2>
        <p style="color: #718096;">A new stock purchase has been successfully received and recorded.</p>
    </div>

    <div style="background-color: #f8f9fa; border-radius: 12px; padding: 25px; margin-bottom: 30px; border: 1px solid #edf2f7;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td style="padding-bottom: 10px; color: #718096; font-size: 13px; text-transform: uppercase;">Reference ID</td>
                <td style="padding-bottom: 10px; text-align: right; font-weight: 700; color: #2d3748;">{{ $stockIn->reference }}</td>
            </tr>
            <tr>
                <td style="padding-bottom: 10px; color: #718096; font-size: 13px; text-transform: uppercase;">Supplier</td>
                <td style="padding-bottom: 10px; text-align: right; font-weight: 600; color: #2d3748;">{{ $stockIn->supplier ?? 'Direct Purchase' }}</td>
            </tr>
            <tr>
                <td style="padding-bottom: 10px; color: #718096; font-size: 13px; text-transform: uppercase;">Recorded By</td>
                <td style="padding-bottom: 10px; text-align: right; color: #4a5568;">{{ $user->name }}</td>
            </tr>
            <tr>
                <td style="padding-top: 15px; border-top: 1px solid #e2e8f0; font-weight: 700; font-size: 16px; color: #2d3748;">Total Expenditure</td>
                <td style="padding-top: 15px; border-top: 1px solid #e2e8f0; text-align: right; font-weight: 800; font-size: 20px; color: #3498db;">
                    ₦{{ number_format($totalSpent, 2) }}
                </td>
            </tr>
        </table>
    </div>

    <h3 style="font-size: 14px; text-transform: uppercase; color: #a0aec0; letter-spacing: 1px; margin-bottom: 15px;">Supplies Received</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Ingredient</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stockIn->items as $item)
            <tr>
                <td style="font-size: 14px; color: #2d3748; font-weight: 600;">{{ $item->ingredient->name }}</td>
                <td style="text-align: center; color: #4a5568;">
                    {{ number_format($item->quantity, 1) }} <small>{{ $item->unit->symbol }}</small>
                </td>
                <td style="text-align: right; font-weight: 600; color: #2d3748;">
                    ₦{{ number_format($item->total_price, 2) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="background-color: #f7fafc; padding: 15px; border-radius: 8px; margin-top: 25px; text-align: center;">
        <p style="margin: 0; font-size: 12px; color: #718096;">
            Purchase recorded on: <strong>{{ \Carbon\Carbon::parse($stockIn->purchase_date)->format('d M, Y') }}</strong>
        </p>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ url('/admin/inventory') }}" class="button" style="background-color: #3498db;">
            View Current Stock
        </a>
    </div>
@endsection