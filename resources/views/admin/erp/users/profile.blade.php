@extends('admin.layout.dashboard')

@section('content')
    @include('shared.erp.users.profile', [
        'portal' => 'admin',
        'user' => $admin,
    ])
@endsection
