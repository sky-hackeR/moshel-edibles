@extends('mail.layout.mail')

@section('preheader', 'New custom order inquiry from ' . $payload['clientName'])

@section('content')
    <h2>Custom order inquiry</h2>
    <p>A new customer inquiry has been submitted for review.</p>

    <table class="data-table">
        <tbody>
            <tr>
                <th scope="row">Customer</th>
                <td>{{ $payload['clientName'] }}</td>
            </tr>
            <tr>
                <th scope="row">Email</th>
                <td><a href="mailto:{{ $payload['clientEmail'] }}">{{ $payload['clientEmail'] }}</a></td>
            </tr>
            <tr>
                <th scope="row">Phone</th>
                <td>{{ $payload['clientPhone'] }}</td>
            </tr>
            <tr>
                <th scope="row">Requested date</th>
                <td>{{ $payload['deliveryDate'] }}</td>
            </tr>
            <tr>
                <th scope="row">Products</th>
                <td>{{ $payload['items'] }}</td>
            </tr>
        </tbody>
    </table>

    <div class="alert-box">
        <p style="margin: 0 0 8px; color: #64236f; font-weight: 700;">Specifications and notes</p>
        <p style="margin: 0; white-space: pre-wrap;">{{ $payload['specifications'] }}</p>
    </div>
@endsection