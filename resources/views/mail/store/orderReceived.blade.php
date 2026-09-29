@extends('mail.layout.mail')

@section('content')
<h2 style="color: #2d3748; font-size: 24px; margin: 0 0 16px;">New storefront order</h2>
<p style="color: #4a5568;">A customer payment has been confirmed. @if($sale->order_status === 'payment_review') This order requires an availability and payment review before fulfillment. @else Stock has been deducted for fulfillment. @endif</p>
<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
    <tr><td style="padding: 8px 0; font-weight: 700;">Reference</td><td style="padding: 8px 0; text-align: right;">{{ $sale->reference_no }}</td></tr>
    <tr><td style="padding: 8px 0; font-weight: 700;">Customer</td><td style="padding: 8px 0; text-align: right;">{{ optional($sale->customer)->name ?: 'Customer account unavailable' }} ({{ optional($sale->customer)->email ?: 'no email on record' }})</td></tr>
    <tr><td style="padding: 8px 0; font-weight: 700;">Total</td><td style="padding: 8px 0; text-align: right;">NGN {{ number_format($sale->payable_amount, 2) }}</td></tr>
    <tr><td style="padding: 8px 0; font-weight: 700;">Order status</td><td style="padding: 8px 0; text-align: right;">{{ ucfirst(str_replace('_', ' ', $sale->order_status)) }}</td></tr>
    @if($sale->paystack_amount !== null)
        <tr><td style="padding: 8px 0; font-weight: 700;">Paystack amount</td><td style="padding: 8px 0; text-align: right;">{{ $sale->paystack_currency }} {{ number_format($sale->paystack_amount / 100, 2) }}</td></tr>
    @endif
</table>
<h3 style="color: #2d3748; margin-top: 24px;">Items</h3>
@foreach($sale->items as $item)
<p style="color: #4a5568; margin: 6px 0;">{{ $item->quantity }} x {{ optional($item->product)->name ?: 'Unavailable product' }} - NGN {{ number_format($item->subtotal, 2) }}</p>
@endforeach
<p style="color: #4a5568; margin-top: 24px;"><strong>Delivery address:</strong><br>{{ $sale->delivery_address }}<br>{{ $sale->delivery_phone }}</p>
@endsection