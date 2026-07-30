@extends('admin.layout.dashboard')

@section('content')

@include('admin.partials.page-header',[
    'title'=>'Utilities',
    'description'=>'Import, export and manage business data from a single location.',
    'breadcrumb'=>'Bulk Operations'
])

@include('admin.partials.bulk.cards.statistics')

@include('admin.partials.bulk.tabs')

@include('admin.partials.bulk.modals.upload-modal')

@endsection