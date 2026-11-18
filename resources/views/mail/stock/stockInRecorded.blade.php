@extends('mail.layout.mail')

@section('preheader', 'Stock replenishment recorded: ' . $stockIn->reference . ' — ₦' . number_format($totalSpent, 2))

@section('content')
    <div style="text-align: center; margin-bottom: 24px;">
        <span class="badge-success" style="display: inline-block; margin-bottom: 10px;">Stock Purchase Received</span>
        <h2 style="margin: 0 0 6px; font-size: 22px; color: #2a172e; font-weight: 700;">Inventory Replenished</h2>
        <p style="margin: 0; color: #6c5a70; font-size: 14px;">A new stock delivery has been verified and added to raw materials inventory.</p>
    </div>

    <!-- Purchase Summary Card -->
    <div style="background-color: #fbf6fc; border-radius: 12px; padding: 20px 22px; margin-bottom: 24px; border: 1px solid #ebddec;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td style="padding-bottom: 10px; color: #6c5a70; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Reference ID</td>
                <td style="padding-bottom: 10px; text-align: right; font-weight: 700; color: #2a172e; font-family: 'Courier New', monospace;">{{ $stockIn->reference }}</td>
            </tr>
            <tr>
                <td style="padding-bottom: 10px; color: #6c5a70; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Supplier</td>
                <td style="padding-bottom: 10px; text-align: right; font-weight: 600; color: #2a172e;">{{ $stockIn->supplier ?? 'Direct Purchase / Local Market' }}</td>
            </tr>
            <tr>
                <td style="padding-bottom: 10px; color: #6c5a70; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Recorded By</td>
                <td style="padding-bottom: 10px; text-align: right; color: #2a172e; font-weight: 500;">{{ $user->name }}</td>
            </tr>
            <tr>
                <td style="padding-top: 14px; border-top: 1px dashed #ebddec; font-weight: 700; font-size: 16px; color: #2a172e;">Total Purchase Cost</td>
                <td style="padding-top: 14px; border-top: 1px dashed #ebddec; text-align: right; font-weight: 800; font-size: 22px; color: #581a62;">
                    ₦{{ number_format($totalSpent, 2) }}
                </td>
            </tr>
        </table>
    </div>

    <h3 style="font-size: 12px; text-transform: uppercase; color: #6c5a70; letter-spacing: 0.8px; margin: 0 0 12px; font-weight: 700;">Supplies Received</h3>
    <table class="data-table" style="margin-bottom: 24px;">
        <thead>
            <tr>
                <th>Ingredient</th>
                <th style="text-align: center;">Quantity</th>
                <th style="text-align: right;">Line Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stockIn->items as $item)
            <tr>
                <td style="font-size: 14px; color: #2a172e; font-weight: 600;">{{ $item->ingredient->name }}</td>
                <td style="text-align: center; color: #6c5a70;">
                    {{ number_format($item->quantity, 1) }} <small>{{ $item->unit->symbol }}</small>
                </td>
                <td style="text-align: right; font-weight: 700; color: #2a172e;">
                    ₦{{ number_format($item->total_price, 2) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="alert-box" style="margin-bottom: 28px; text-align: center;">
        <p style="margin: 0; font-size: 12px; color: #581a62;">
            Purchase recorded on: <strong>{{ \Carbon\Carbon::parse($stockIn->purchase_date)->format('d M, Y') }}</strong>
        </p>
    </div>

    <div style="text-align: center;">
        <a href="{{ url('/admin/inventory') }}" class="button">
            View Current Stock
        </a>
    </div>
@endsection
