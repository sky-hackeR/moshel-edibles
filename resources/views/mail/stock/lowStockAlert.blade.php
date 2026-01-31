@extends('mail.layout.mail')

@section('content')
    <div style="text-align: center; margin-bottom: 30px;">
        <div style="display: inline-block; background-color: #fffaf0; padding: 15px; border-radius: 50%; margin-bottom: 15px;">
            <span style="font-size: 30px;">🌾</span>
        </div>
        <h2 style="color: #dd6b20; font-size: 24px; margin: 0;">Ingredient Reorder Alert</h2>
        <p style="color: #718096;">Multiple raw materials have fallen below the minimum safety threshold.</p>
    </div>

    <div class="alert-box" style="background-color: #fffaf0; border-left: 4px solid #ed8936;">
        <p style="margin: 0; color: #9c4221; font-size: 14px; font-weight: 600;">
            ⚠️ Procurement Action Required
        </p>
        <p style="margin: 5px 0 0; font-size: 13px; color: #7b341e;">
            Restock is needed to prevent production downtime. Below are the items requiring immediate replenishment.
        </p>
    </div>

    <table class="data-table">
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
                // Logic to display the effective threshold used by the system
                $unit = strtolower($item->ingredient->baseUnit->name ?? '');
                $thresholds = [
                    'gram' => 1000, 
                    'ml'   => 1000, 
                    'pcs'  => 10
                ];
                
                $effectiveThreshold = $item->ingredient->reorder_level > 0 
                    ? $item->ingredient->reorder_level 
                    : ($thresholds[$unit] ?? 0);
                
                $unitSymbol = $item->ingredient->baseUnit->symbol ?? '';
            @endphp
            <tr>
                <td style="color: #2d3748; font-weight: 600;">
                    {{ $item->ingredient->name }}
                </td>
                <td style="text-align: center; font-weight: 800; color: #e53e3e;">
                    {{ number_format($item->quantity, 2) }} <small style="font-weight: 400; color: #a0aec0;">{{ $unitSymbol }}</small>
                </td>
                <td style="text-align: right; color: #718096; font-size: 13px;">
                    {{ number_format($effectiveThreshold, 2) }} <small>{{ $unitSymbol }}</small>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="background-color: #f7fafc; padding: 20px; border-radius: 8px; margin-top: 25px; text-align: center; border: 1px solid #edf2f7;">
        <p style="margin: 0; font-size: 13px; color: #4a5568; line-height: 1.5;">
            <strong>Pro Tip:</strong> Once you've secured the supplies, click the button below to record the "Stock In" and update the live inventory.
        </p>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ url('/admin/stock-in') }}" class="button" style="background-color: #dd6b20;">
            Record Purchase / Stock In
        </a>
    </div>
@endsection