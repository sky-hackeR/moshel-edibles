@extends('admin.layout.dashboard')

@section('content')
    @include('shared.erp.inventory.danger', [
        'entityType' => 'ingredient',
        'items' => $ingredients,
        'stats' => $stats,
    ])
@endsection
