@extends('mail.layout.mail')

@section('preheader', 'New custom order inquiry from ' . $payload['clientName'])

@section('content')
    <div style="text-align: center; margin-bottom: 28px;">
        <span class="badge-pink" style="display: inline-block; margin-bottom: 12px;">Customer Inquiry</span>
        <h2 style="margin: 0 0 6px; font-size: 22px; color: #2a172e; font-weight: 700;">Custom Order Request</h2>
        <p style="margin: 0; color: #6c5a70; font-size: 14px;">A new customer inquiry has been submitted and is awaiting your review.</p>
    </div>

    <table class="data-table" style="margin-bottom: 24px;">
        <tbody>
            <tr>
                <th scope="row" style="width: 35%;">Customer Name</th>
                <td style="font-weight: 600; color: #2a172e;">{{ $payload['clientName'] }}</td>
            </tr>
            <tr>
                <th scope="row">Email Address</th>
                <td><a href="mailto:{{ $payload['clientEmail'] }}" style="color: #ce278c; text-decoration: none; font-weight: 600;">{{ $payload['clientEmail'] }}</a></td>
            </tr>
            <tr>
                <th scope="row">Phone Number</th>
                <td style="color: #2a172e; font-weight: 500;">{{ $payload['clientPhone'] }}</td>
            </tr>
            <tr>
                <th scope="row">Requested Date</th>
                <td style="color: #581a62; font-weight: 700;">{{ $payload['deliveryDate'] }}</td>
            </tr>
            <tr>
                <th scope="row">Target Products</th>
                <td style="color: #2a172e;">{{ $payload['items'] }}</td>
            </tr>
        </tbody>
    </table>

    <div class="alert-box" style="margin-bottom: 28px;">
        <p style="margin: 0 0 8px; color: #581a62; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Customer Notes & Specifications</p>
        <p style="margin: 0; color: #2a172e; font-size: 14px; line-height: 1.6; white-space: pre-wrap;">{{ $payload['specifications'] }}</p>
    </div>

    <div style="text-align: center;">
        <a href="mailto:{{ $payload['clientEmail'] }}?subject={{ urlencode('Re: Custom Order Inquiry - Moshel Edibles') }}" class="button">
            Reply to Customer
        </a>
    </div>
@endsection
