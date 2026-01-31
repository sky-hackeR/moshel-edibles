@extends('mail.layout.mail')

@section('content')
    <div style="text-align: center; margin-bottom: 30px;">
        <div style="display: inline-block; background-color: #ecfdf5; padding: 15px; border-radius: 50%; margin-bottom: 15px;">
            <span style="font-size: 30px;">🌿</span>
        </div>
        <h2 style="color: #059669; font-size: 24px; margin: 0;">New Resource Registered</h2>
        <p style="color: #718096;">A new ingredient has been successfully added to the master list.</p>
    </div>

    <div class="alert-box" style="background-color: #f0fff4; border-left: 4px solid #34c38f;">
        <p style="margin: 0; color: #22543d; font-size: 14px; font-weight: 600;">
            📢 Attention Procurement Team
        </p>
        <p style="margin: 5px 0 0; font-size: 13px; color: #276749;">
            This item has been initialized with <strong>zero stock</strong>. It will not be available for production until a "Stock In" record is created.
        </p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th colspan="2">Ingredient Specifications</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #718096; width: 40%;">Resource Name</td>
                <td style="font-weight: 600; color: #2d3748;">{{ $ingredient->name }}</td>
            </tr>
            <tr>
                <td style="color: #718096;">Measurement Unit</td>
                <td style="font-weight: 600; color: #2d3748;">
                    {{ $ingredient->baseUnit->name }} ({{ $ingredient->baseUnit->symbol }})
                </td>
            </tr>
            <tr>
                <td style="color: #718096;">Registry Status</td>
                <td>
                    <span style="padding: 2px 8px; border-radius: 12px; font-size: 12px; font-weight: 700; text-transform: uppercase; background-color: {{ $ingredient->is_active ? '#d1fae5' : '#fee2e2' }}; color: {{ $ingredient->is_active ? '#065f46' : '#991b1b' }};">
                        {{ $ingredient->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
            </tr>
            <tr>
                <td style="color: #718096;">Added By</td>
                <td style="font-size: 14px; color: #4a5568;">{{ $creator->name }}</td>
            </tr>
        </tbody>
    </table>

    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 20px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #4a5568;">
            To record a purchase or update current stock levels, please click the button below to visit the management portal.
        </p>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ url('/admin/ingredients') }}" class="button" style="background-color: #34c38f;">
            Manage Resource
        </a>
    </div>
@endsection