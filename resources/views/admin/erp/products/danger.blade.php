@extends('admin.layout.dashboard')

@section('content')
    @include('shared.erp.inventory.danger', [
        'entityType' => 'product',
        'items' => $products,
        'stats' => $stats,
    ])
@endsection
