@extends('mail.layout.mail')

@section('preheader')
[SECURITY ALERT] Inventory depletion event recorded for {{ $productName }} by {{ $user->name ?? 'Administrator' }}.
@endsection

@section('content')
    <div style="text-align: center; margin-bottom: 24px;">
        <span class="badge-danger" style="display: inline-block; margin-bottom: 10px;">Stock Danger Zone Event</span>
        <h2 style="margin: 0 0 6px; font-size: 22px; color: #b91c1c; font-weight: 700;">Inventory Depletion Alert</h2>
        <p style="margin: 0; color: #6c5a70; font-size: 14px;">An administrative stock clearing action was performed in the Stock Danger Zone.</p>
    </div>

    <div class="alert-box-danger" style="margin-bottom: 24px;">
        <p style="margin: 0 0 4px; color: #b91c1c; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Physical Quantity Set to Zero</p>
        <p style="margin: 0; font-size: 13px; color: #991b1b; line-height: 1.5;">
            Available quantity on hand for the product(s) below has been immediately set to <strong>0.00</strong>.
        </p>
    </div>

    <table class="data-table" style="margin-bottom: 24px;">
        <tbody>
            <tr>
                <th scope="row" style="width: 35%;">Operation Scope</th>
                <td style="font-weight: 700;">
                    @if($isBulk)
                        <span class="badge-danger">Full Catalog Reset ({{ $affectedProductsCount }} Products)</span>
                    @else
                        <span class="badge-purple">Single Product Stock Clear</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th scope="row">Target Product</th>
                <td style="font-weight: 700; color: #2a172e; font-size: 15px;">{{ $productName }}</td>
            </tr>
            <tr>
                <th scope="row">Previous Stock</th>
                <td style="color: #6c5a70; font-weight: 600;">
                    {{ number_format($previousStock, 2) }} {{ $salesUnit }}
                </td>
            </tr>
            <tr>
                <th scope="row">New Stock on Hand</th>
                <td style="font-weight: 800; color: #b91c1c;">
                    0.00 {{ $salesUnit }} <span style="font-size: 11px; font-weight: 600; text-transform: uppercase;">(Depleted)</span>
                </td>
            </tr>
            <tr>
                <th scope="row">Depletion Mode</th>
                <td>
                    @if($accountingType === 'write_off')
                        <span class="badge-warning" style="display: inline-block; margin-bottom: 6px;">
                            Recognized Spoilage / Inventory Write-Off
                        </span>
                        @if($product && $product->selling_price)
                            <div style="font-size: 12px; color: #b91c1c; font-weight: 600;">
                                Retail valuation: ₦{{ number_format($previousStock * $product->selling_price, 2) }} (Flagged for loss records)
                            </div>
                        @endif
                    @else
                        <span class="badge-purple" style="display: inline-block; margin-bottom: 6px;">
                            Operational Stock Adjustment Only
                        </span>
                        <div style="font-size: 12px; color: #6c5a70; line-height: 1.4;">
                            Physical count reset. Does <strong>not</strong> affect sales figures, paid revenue, or projected income.
                        </div>
                    @endif
                </td>
            </tr>
            <tr>
                <th scope="row">Reason Provided</th>
                <td style="color: #2a172e; font-weight: 600;">
                    {{ $reason }}
                </td>
            </tr>
            @if(!empty($notes))
            <tr>
                <th scope="row">Admin Notes</th>
                <td style="color: #6c5a70; font-style: italic;">
                    {{ $notes }}
                </td>
            </tr>
            @endif
            <tr>
                <th scope="row">Executed By</th>
                <td style="color: #2a172e;">
                    <strong>{{ $user->name ?? 'Administrator' }}</strong> 
                    <span style="color: #6c5a70; font-size: 12px;">({{ $user->email ?? 'N/A' }})</span>
                </td>
            </tr>
            <tr>
                <th scope="row">IP Address</th>
                <td style="color: #6c5a70; font-family: 'Courier New', monospace; font-size: 13px;">
                    {{ $ipAddress }}
                </td>
            </tr>
            <tr>
                <th scope="row">Timestamp</th>
                <td style="color: #6c5a70; font-size: 13px;">
                    {{ $timestamp }}
                </td>
            </tr>
        </tbody>
    </table>

    <div style="text-align: center;">
        <a href="{{ url('/admin/products/stock-danger-zone') }}" class="button">
            Open Stock Danger Zone
        </a>
    </div>
@endsection
