@extends('mail.layout.mail')

@section('content')
    <h2 style="color: #c53030;">Product Deletion Blocked</h2>
    <p>A product deletion attempt was blocked to protect production data.</p>

    <table class="data-table">
        <tbody>
            <tr>
                <th>Product</th>
                <td>{{ $product->name }}</td>
            </tr>
            <tr>
                <th>Attempted By</th>
                <td>{{ $user->name }} ({{ $user->email }})</td>
            </tr>
            <tr>
                <th>Reason</th>
                <td>{{ $reason }}</td>
            </tr>
        </tbody>
    </table>
@endsection