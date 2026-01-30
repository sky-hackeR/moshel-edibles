@extends('admin.layout.dashboard')

@section('content')

{{-- PAGE HEADER --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Unit Management</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Units</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border shadow-none">
            <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="card-title mb-0">All Units</h4>
                    <p class="text-muted mb-0 small">Base units (g, ml, pcs) are locked system references.</p>
                </div>
                <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#addUnit">
                    <i class="mdi mdi-plus me-1"></i> Add Unit
                </button>
            </div>

            <div class="card-body">
                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100 align-middle">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">S/N</th>
                            <th>Name & Symbol</th>
                            <th>Type</th>
                            <th>Multiplier</th>
                            <th>Base Ref</th>
                            <th class="text-center" style="width: 140px;">Usage Scope</th>
                            <th>Status</th>
                            <th width="100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($units as $unit)
                        @php $isBase = in_array($unit->symbol, ['g', 'ml', 'pcs']); @endphp
                        <tr>
                            <td class="text-center text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <h5 class="font-size-14 mb-1 {{ $isBase ? 'text-primary' : 'text-dark' }}">
                                    {{ $unit->name }} @if($isBase) <i class="mdi mdi-shield-check-outline" title="System Base"></i> @endif
                                </h5>
                                <span class="text-muted small">Symbol: <b>{{ $unit->symbol }}</b></span>
                            </td>
                            <td><span class="badge badge-soft-secondary text-capitalize">{{ $unit->unit_type }}</span></td>
                            <td class="fw-medium">{{ number_format($unit->base_multiplier, 4) }}</td>
                            <td><code class="text-primary">{{ $unit->base_unit }}</code></td>
                            
                            <td class="text-center">
                                <div class="d-flex flex-column gap-1">
                                    @if($unit->use_for_purchase) <span class="badge badge-soft-info py-1">PURCHASE</span> @endif
                                    @if($unit->use_for_recipe) <span class="badge badge-soft-primary py-1">RECIPE</span> @endif
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-{{ $unit->is_active ? 'success' : 'danger' }}">
                                    {{ $unit->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-soft-success btn-sm" data-bs-toggle="modal" data-bs-target="#editUnit{{ $unit->id }}">
                                        <i class="mdi mdi-pencil-outline font-size-16"></i>
                                    </button>
                                    @if(!$isBase)
                                    <button class="btn btn-soft-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteUnit{{ $unit->id }}">
                                        <i class="mdi mdi-delete-outline font-size-16"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@foreach($units as $unit)
    @php $isBase = in_array($unit->symbol, ['g', 'ml', 'pcs']); @endphp
    {{-- EDIT UNIT MODAL --}}
    <div class="modal fade" id="editUnit{{ $unit->id }}" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" action="{{ url('/admin/updateUnit') }}">
                @csrf
                <input type="hidden" name="unit_id" value="{{ $unit->id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Unit: {{ $unit->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 row g-3">
                        @if($isBase)
                            <div class="col-12">
                                <div class="alert alert-info py-2">
                                    <i class="mdi mdi-information-outline me-2"></i> This is a <b>System Base Unit</b>. Only the display name can be changed.
                                </div>
                            </div>
                        @endif

                        <div class="col-md-6">
                            <label class="form-label">Display Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ $unit->name }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Symbol *</label>
                            <input type="text" name="symbol" class="form-control" value="{{ $unit->symbol }}" required {{ $isBase ? 'readonly' : '' }}>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Unit Type *</label>
                            <select name="unit_type" class="form-select" required {{ $isBase ? 'disabled' : '' }}>
                                <option value="mass" {{ $unit->unit_type == 'mass' ? 'selected' : '' }}>Mass (Calculates in Grams)</option>
                                <option value="volume" {{ $unit->unit_type == 'volume' ? 'selected' : '' }}>Volume (Calculates in Milliliters)</option>
                                <option value="count" {{ $unit->unit_type == 'count' ? 'selected' : '' }}>Count (Calculates in Pieces)</option>
                            </select>
                            @if($isBase) <input type="hidden" name="unit_type" value="{{ $unit->unit_type }}"> @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-primary">Base Multiplier *</label>
                            <input type="number" name="base_multiplier" step="0.0001" class="form-control" 
                                   value="{{ $unit->base_multiplier }}" required {{ $isBase ? 'readonly' : '' }}>
                            <small class="text-muted">How many <b>{{ $isBase ? $unit->symbol : $unit->base_unit }}</b> are in 1 {{ $unit->symbol }}?</small>
                        </div>

                        <div class="col-md-12 border-top pt-3 mt-3">
                            <div class="d-flex gap-4">
                                <div class="form-check form-switch">
                                    <input type="checkbox" name="use_for_purchase" class="form-check-input" id="pur{{$unit->id}}" {{ $unit->use_for_purchase?'checked':'' }} {{ $isBase ? 'disabled' : '' }}>
                                    <label class="form-check-label">Purchase Use</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="checkbox" name="use_for_recipe" class="form-check-input" id="rec{{$unit->id}}" {{ $unit->use_for_recipe?'checked':'' }} {{ $isBase ? 'disabled' : '' }}>
                                    <label class="form-check-label">Recipe Use</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="checkbox" name="is_active" class="form-check-input" id="act{{$unit->id}}" {{ $unit->is_active?'checked':'' }} {{ $isBase ? 'disabled' : '' }}>
                                    <label class="form-check-label">Active Status</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary px-4">Update Unit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

@foreach($units as $unit)
    {{-- DELETE UNIT MODAL --}}
    <div class="modal fade" id="deleteUnit{{ $unit->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ url('/admin/deleteUnit') }}" style="width: 100%">
                @csrf
                <input type="hidden" name="unit_id" value="{{ $unit->id }}">
                <div class="modal-content border-danger">
                    <div class="modal-body text-center p-5">
                        <i class="mdi mdi-alert-circle-outline text-danger display-4"></i>
                        <h4 class="mt-4">Delete Unit?</h4>
                        <p class="text-muted">Are you sure you want to delete <b>{{ $unit->name }}</b>?<br>This may affect existing recipes and stock records.</p>
                        <div class="d-flex gap-2 justify-content-center mt-4">
                            <button type="button" class="btn btn-light w-50" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger w-50">Yes, Delete It!</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

@endsection