@extends('staff.layout.dashboard')

@section('content')
    @include('shared.erp.sales.history', [
        'portal' => 'staff',
        'sales' => $sales,
    ])
@endsection
