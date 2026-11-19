@extends('staff.layout.dashboard')

@section('content')
    @include('shared.erp.production.index', [
        'portal' => 'staff',
        'products' => $products,
        'stats' => $stats,
        'productionMatrix' => $productionMatrix,
    ])
@endsection
