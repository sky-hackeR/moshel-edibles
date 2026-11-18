@extends('mail.layout.mail')

@section('preheader', 'New raw material registered: ' . $ingredient->name)

@section('content')
    <div style="text-align: center; margin-bottom: 24px;">
        <span class="badge-success" style="display: inline-block; margin-bottom: 10px;">Master Registry Updated</span>
        <h2 style="margin: 0 0 6px; font-size: 22px; color: #2a172e; font-weight: 700;">New Ingredient Registered</h2>
        <p style="margin: 0; color: #6c5a70; font-size: 14px;">A new raw material has been added to the master inventory catalogue.</p>
    </div>

    <div class="alert-box" style="margin-bottom: 24px;">
        <p style="margin: 0 0 4px; color: #581a62; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Attention Procurement Team</p>
        <p style="margin: 0; font-size: 13px; color: #2a172e; line-height: 1.5;">
            This ingredient has been initialized with <strong>zero stock</strong>. It will become eligible for baking batches once a <strong>Stock In</strong> purchase is logged.
        </p>
    </div>

    <table class="data-table" style="margin-bottom: 24px;">
        <thead>
            <tr>
                <th colspan="2" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.6px;">Ingredient Specifications</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #6c5a70; width: 40%; font-weight: 500;">Ingredient Name</td>
                <td style="font-weight: 700; color: #2a172e; font-size: 15px;">{{ $ingredient->name }}</td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Measurement Unit</td>
                <td style="font-weight: 600; color: #581a62;">
                    {{ $ingredient->baseUnit->name ?? 'Standard' }} ({{ $ingredient->baseUnit->symbol ?? 'unit' }})
                </td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Registry Status</td>
                <td>
                    <span class="{{ $ingredient->is_active ? 'badge-success' : 'badge-danger' }}">
                        {{ $ingredient->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Added By</td>
                <td style="color: #2a172e; font-weight: 500;">{{ $creator->name }}</td>
            </tr>
        </tbody>
    </table>

    <div style="text-align: center;">
        <a href="{{ url('/admin/ingredients') }}" class="button">
            Manage Ingredients
        </a>
    </div>
@endsection
