@extends('admin.layout.dashboard')

@section('content')

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
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">All Units</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUnit">
                    Add Unit
                </button>   
            </div>

            <div class="card-body">
                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Symbol</th>
                            <th>Type</th>
                            <th>Multiplier</th>
                            <th>Base Unit</th>
                            <th>Purchase</th>
                            <th>Recipe</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($units as $unit)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $unit->name }}</td>
                            <td>{{ $unit->symbol }}</td>
                            <td>{{ ucfirst($unit->unit_type) }}</td>
                            <td>{{ number_format($unit->base_multiplier, 4) }}</td>
                            <td>{{ $unit->base_unit }}</td>
                            <td>
                                <span class="badge bg-{{ $unit->use_for_purchase ? 'success' : 'secondary' }}">
                                    {{ $unit->use_for_purchase ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $unit->use_for_recipe ? 'success' : 'secondary' }}">
                                    {{ $unit->use_for_recipe ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $unit->is_active ? 'success' : 'danger' }}">
                                    {{ $unit->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-info m-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editUnit{{ $unit->id }}">
                                    <i class="mdi mdi-pencil"></i>
                                </button>

                                <button class="btn btn-danger m-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteUnit{{ $unit->id }}">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="editUnit{{ $unit->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <form method="POST" action="{{ url('/admin/updateUnit') }}">
                                    @csrf
                                    <input type="hidden" name="unit_id" value="{{ $unit->id }}">

                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Unit: {{ $unit->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body row g-3">
                                            <div class="col-md-6">
                                                <label>Name *</label>
                                                <input type="text" name="name" class="form-control" value="{{ $unit->name }}" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Symbol *</label>
                                                <input type="text" name="symbol" class="form-control" value="{{ $unit->symbol }}" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Unit Type *</label>
                                                <select name="unit_type" class="form-control" required>
                                                    <option value="mass" {{ $unit->unit_type=='mass'?'selected':'' }}>Mass (Base: g)</option>
                                                    <option value="volume" {{ $unit->unit_type=='volume'?'selected':'' }}>Volume (Base: ml)</option>
                                                    <option value="count" {{ $unit->unit_type=='count'?'selected':'' }}>Count (Base: pcs)</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="text-primary">Base Multiplier *</label>
                                                <input type="number" name="base_multiplier" step="0.0001" class="form-control" 
                                                       value="{{ $unit->base_multiplier }}" required
                                                       {{ in_array($unit->symbol, ['g', 'ml', 'pcs']) ? 'readonly' : '' }}>
                                                @if(in_array($unit->symbol, ['g', 'ml', 'pcs']))
                                                    <small class="text-danger">Core base units must remain 1.0</small>
                                                @else
                                                    <small class="text-muted">Conversion factor to reach base unit (g/ml)</small>
                                                @endif
                                            </div>

                                            <div class="col-md-6">
                                                <label>Base Unit Label</label>
                                                <input type="text" name="base_unit" class="form-control" value="{{ $unit->base_unit }}" placeholder="g, ml, or pcs">
                                            </div>

                                            <div class="col-md-2 mt-4">
                                                <div class="form-check mt-2">
                                                    <input type="checkbox" name="use_for_purchase" class="form-check-input" id="pur{{$unit->id}}" {{ $unit->use_for_purchase?'checked':'' }}>
                                                    <label class="form-check-label" for="pur{{$unit->id}}">Purchase</label>
                                                </div>
                                            </div>

                                            <div class="col-md-2 mt-4">
                                                <div class="form-check mt-2">
                                                    <input type="checkbox" name="use_for_recipe" class="form-check-input" id="rec{{$unit->id}}" {{ $unit->use_for_recipe?'checked':'' }}>
                                                    <label class="form-check-label" for="rec{{$unit->id}}">Recipe</label>
                                                </div>
                                            </div>

                                            <div class="col-md-2 mt-4">
                                                <div class="form-check mt-2">
                                                    <input type="checkbox" name="is_active" class="form-check-input" id="act{{$unit->id}}" {{ $unit->is_active?'checked':'' }}>
                                                    <label class="form-check-label" for="act{{$unit->id}}">Active</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-success">Save Changes</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="modal fade" id="deleteUnit{{ $unit->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <form method="POST" action="{{ url('/admin/deleteUnit') }}">
                                    @csrf
                                    <input type="hidden" name="unit_id" value="{{ $unit->id }}">

                                    <div class="modal-content">
                                        <div class="modal-body text-center p-5">
                                            <button type="button" class="btn-close float-end" data-bs-dismiss="modal"></button>
                                            <div class="mt-4">
                                                <i class="mdi mdi-alert-circle-outline text-danger display-4"></i>
                                                <h4 class="mt-4">Delete Unit?</h4>
                                                <p class="text-muted">Are you sure you want to delete <b>{{ $unit->name }}</b>?<br>This may affect existing recipes and stock records.</p>
                                                
                                                <div class="d-flex gap-2 justify-content-center mt-4">
                                                    <button type="button" class="btn btn-light w-sm" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-danger w-sm">Yes, Delete It!</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addUnit" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="{{ url('/admin/newUnit') }}">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Unit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label>Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Kilogram" required>
                    </div>

                    <div class="col-md-6">
                        <label>Symbol *</label>
                        <input type="text" name="symbol" class="form-control" placeholder="e.g. kg" required>
                    </div>

                    <div class="col-md-6">
                        <label>Unit Type *</label>
                        <select name="unit_type" class="form-control" required>
                            <option value="mass">Mass (Base: g)</option>
                            <option value="volume">Volume (Base: ml)</option>
                            <option value="count">Count (Base: pcs)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="text-primary">Base Multiplier *</label>
                        <input type="number" name="base_multiplier" step="0.0001" class="form-control" placeholder="e.g. 1000 for kg, 5 for tsp" required>
                        <small class="text-muted">How many base units (g/ml) fit into this unit?</small>
                    </div>

                    <div class="col-md-6">
                        <label>Base Unit Label</label>
                        <input type="text" name="base_unit" class="form-control" placeholder="e.g. g">
                    </div>

                    <div class="col-md-2 mt-4">
                        <div class="form-check mt-2">
                            <input type="checkbox" name="use_for_purchase" class="form-check-input" id="addPur" checked>
                            <label class="form-check-label" for="addPur">Purchase</label>
                        </div>
                    </div>

                    <div class="col-md-2 mt-4">
                        <div class="form-check mt-2">
                            <input type="checkbox" name="use_for_recipe" class="form-check-input" id="addRec" checked>
                            <label class="form-check-label" for="addRec">Recipe</label>
                        </div>
                    </div>

                    <div class="col-md-2 mt-4">
                        <div class="form-check mt-2">
                            <input type="checkbox" name="is_active" class="form-check-input" id="addAct" checked>
                            <label class="form-check-label" for="addAct">Active</label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Save Unit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection