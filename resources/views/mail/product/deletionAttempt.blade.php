@extends('mail.layout.mail')

@section('preheader', '[DATA INTEGRITY] Deletion blocked for product: ' . $product->name)

@section('content')
    <div style="text-align: center; margin-bottom: 24px;">
        <span class="badge-danger" style="display: inline-block; margin-bottom: 10px;">Integrity Guard Active</span>
        <h2 style="margin: 0 0 6px; font-size: 22px; color: #b91c1c; font-weight: 700;">Product Deletion Blocked</h2>
        <p style="margin: 0; color: #6c5a70; font-size: 14px;">An attempt to delete a registered product was intercepted to preserve historical sales records.</p>
    </div>

    <div class="alert-box-danger" style="margin-bottom: 24px;">
        <p style="margin: 0 0 4px; color: #b91c1c; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">System Protection Intervention</p>
        <p style="margin: 0; font-size: 13px; color: #991b1b; line-height: 1.5;">
            Products associated with completed transactions, batch productions, or active inventory cannot be hard-deleted.
        </p>
    </div>

    <table class="data-table" style="margin-bottom: 24px;">
        <tbody>
            <tr>
                <th scope="row" style="width: 35%;">Target Product</th>
                <td style="font-weight: 700; color: #2a172e; font-size: 15px;">{{ $product->name }}</td>
            </tr>
            <tr>
                <th scope="row">Attempted By</th>
                <td style="color: #2a172e;">
                    <strong>{{ $user->name }}</strong>
                    <span style="color: #6c5a70; font-size: 12px;">({{ $user->email }})</span>
                </td>
            </tr>
            <tr>
                <th scope="row">Interception Rule</th>
                <td style="color: #b91c1c; font-weight: 600;">{{ $reason }}</td>
            </tr>
            <tr>
                <th scope="row">Timestamp</th>
                <td style="color: #6c5a70; font-size: 13px;">{{ now()->format('d M, Y | h:i A') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="alert-box" style="margin-bottom: 28px; text-align: center;">
        <p style="margin: 0; font-size: 12px; color: #581a62; line-height: 1.5;">
            <strong>Advisory:</strong> If you wish to hide this product from active sales, set its status to <em>Inactive</em> rather than attempting to delete the record.
        </p>
    </div>

    <div style="text-align: center;">
        <a href="{{ url('/admin/products') }}" class="button">
            Manage Products
        </a>
    </div>
@endsection
