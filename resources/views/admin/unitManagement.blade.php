@extends('admin.layout.dashboard')

@section('content')

<!-- Page Header -->
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

<!-- Units Table -->
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

                        <!-- EDIT UNIT MODAL -->
                        <div class="modal fade" id="editUnit{{ $unit->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <form method="POST" action="{{ url('/admin/updateUnit') }}">
                                    @csrf
                                    <input type="hidden" name="unit_id" value="{{ $unit->id }}">

                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Unit</h5>
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
                                                    <option value="mass" {{ $unit->unit_type=='mass'?'selected':'' }}>Mass</option>
                                                    <option value="volume" {{ $unit->unit_type=='volume'?'selected':'' }}>Volume</option>
                                                    <option value="count" {{ $unit->unit_type=='count'?'selected':'' }}>Count</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Base Unit</label>
                                                <input type="text" name="base_unit" class="form-control" value="{{ $unit->base_unit }}">
                                            </div>

                                            <div class="col-md-4">
                                                <label>
                                                    <input type="checkbox" name="use_for_purchase" {{ $unit->use_for_purchase?'checked':'' }}>
                                                    Purchase
                                                </label>
                                            </div>

                                            <div class="col-md-4">
                                                <label>
                                                    <input type="checkbox" name="use_for_recipe" {{ $unit->use_for_recipe?'checked':'' }}>
                                                    Recipe
                                                </label>
                                            </div>

                                            <div class="col-md-4">
                                                <label>
                                                    <input type="checkbox" name="is_active" {{ $unit->is_active?'checked':'' }}>
                                                    Active
                                                </label>
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

                        <!-- DELETE UNIT MODAL -->
                        <div class="modal fade" id="deleteUnit{{ $unit->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <form method="POST" action="{{ url('/admin/deleteUnit') }}">
                                    @csrf
                                    <input type="hidden" name="unit_id" value="{{ $unit->id }}">

                                    <div class="modal-content">
                                        <div class="modal-body text-center p-5">
                                            <button type="button" class="btn-close float-end" data-bs-dismiss="modal"></button>

                                            <h4 class="mt-4">Delete Unit?</h4>
                                            <p class="text-muted">{{ $unit->name }} ({{ $unit->symbol }})</p>

                                            <button class="btn btn-danger w-100 mt-3">
                                                Yes, Delete
                                            </button>
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

<!-- ADD UNIT MODAL -->
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
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Symbol *</label>
                        <input type="text" name="symbol" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Unit Type *</label>
                        <select name="unit_type" class="form-control" required>
                            <option value="mass">Mass</option>
                            <option value="volume">Volume</option>
                            <option value="count">Count</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Base Unit</label>
                        <input type="text" name="base_unit" class="form-control" placeholder="g / ml / piece">
                    </div>

                    <div class="col-md-4">
                        <label>
                            <input type="checkbox" name="use_for_purchase" checked>
                            Purchase
                        </label>
                    </div>

                    <div class="col-md-4">
                        <label>
                            <input type="checkbox" name="use_for_recipe" checked>
                            Recipe
                        </label>
                    </div>

                    <div class="col-md-4">
                        <label>
                            <input type="checkbox" name="is_active" checked>
                            Active
                        </label>
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
