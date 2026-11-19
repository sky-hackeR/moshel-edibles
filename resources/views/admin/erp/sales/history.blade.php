@extends('admin.layout.dashboard')

@section('content')
    @include('shared.erp.sales.history', [
        'portal' => 'admin',
        'sales' => $sales,
    ])
@endsection
