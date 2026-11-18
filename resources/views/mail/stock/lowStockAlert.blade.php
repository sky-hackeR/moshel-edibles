@extends('mail.layout.mail')

@section('preheader', 'Procurement alert: raw materials have fallen below safety thresholds')

@section('content')
    <div style="text-align: center; margin-bottom: 24px;">
        <span class="badge-warning" style="display: inline-block; margin-bottom: 10px;">Procurement Alert</span>
        <h2 style="margin: 0 0 6px; font-size: 22px; color: #2a172e; font-weight: 700;">Ingredient Reorder Notice</h2>
        <p style="margin: 0; color: #6c5a70; font-size: 14px;">Raw material inventory levels have fallen below the configured minimum safety buffer.</p>
    </div>

    <div class="alert-box-warning" style="margin-bottom: 24px;">
        <p style="margin: 0 0 4px; color: #b45309; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Restock Required</p>
        <p style="margin: 0; font-size: 13px; color: #92400e; line-height: 1.5;">
            Immediate replenishment is advised to avoid interruptions in daily bakery production runs.
        </p>
    </div>

    <table class="data-table" style="margin-bottom: 24px;">
        <thead>
            <tr>
                <th>Ingredient</th>
                <th style="text-align: center;">On Hand</th>
                <th style="text-align: right;">Min. Threshold</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lowStockItems as $item)
            @php
                $effectiveThreshold = $item->ingredient->reorder_level ?? $item->ingredient->safety_stock_level ?? 0;
                $unitSymbol = $item->ingredient->baseUnit->symbol ?? '';
            @endphp
            <tr>
                <td style="color: #2a172e; font-weight: 600;">
                    {{ $item->ingredient->name ?? 'Unnamed Ingredient' }}
                </td>
                <td style="text-align: center; font-weight: 800; color: #b91c1c;">
                    {{ number_format($item->quantity, 2) }} <small style="font-weight: 500; color: #6c5a70;">{{ $unitSymbol }}</small>
                </td>
                <td style="text-align: right; color: #6c5a70; font-size: 13px;">
                    {{ number_format($effectiveThreshold, 2) }} <small>{{ $unitSymbol }}</small>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="alert-box" style="margin-bottom: 28px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #581a62; line-height: 1.5;">
            <strong>Next Step:</strong> Once materials arrive, record a <strong>Stock In</strong> to automatically refresh available quantities.
        </p>
    </div>

    <div style="text-align: center;">
        <a href="{{ url('/admin/stock-in') }}" class="button">
            Record Stock In
        </a>
    </div>
@endsection
