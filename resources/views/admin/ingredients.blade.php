@extends('admin.layout.dashboard')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Ingredient Management</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Ingredients</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">All Ingredients</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addIngredient">
                    Add Ingredient
                </button>
            </div>

            <div class="card-body">
                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Base Unit</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($ingredients as $ingredient)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $ingredient->name }}</td>
                            <td>{{ $ingredient->baseUnit->name }} ({{ $ingredient->baseUnit->symbol }})</td>
                            <td>
                                <span class="badge bg-{{ $ingredient->is_active ? 'success' : 'danger' }}">
                                    {{ $ingredient->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-info m-1" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editIngredient{{ $ingredient->id }}">
                                    <i class="mdi mdi-pencil"></i>
                                </button>

                                <button class="btn btn-danger m-1" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteIngredient{{ $ingredient->id }}">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editIngredient{{ $ingredient->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <form method="POST" action="{{ url('/admin/updateIngredient') }}">
                                    @csrf
                                    <input type="hidden" name="ingredient_id" value="{{ $ingredient->id }}">

                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Ingredient</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body row g-3">
                                            <div class="col-md-6">
                                                <label>Name *</label>
                                                <input type="text" name="name" class="form-control" value="{{ $ingredient->name }}" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Base Unit *</label>
                                                <select name="base_unit_id" class="form-control" required>
                                                    @foreach($units as $unit)
                                                        <option value="{{ $unit->id }}" {{ $ingredient->base_unit_id == $unit->id ? 'selected' : '' }}>
                                                            {{ $unit->name }} ({{ $unit->symbol }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-check-label">
                                                    <input type="checkbox" name="is_active" {{ $ingredient->is_active ? 'checked' : '' }}>
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

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteIngredient{{ $ingredient->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <form method="POST" action="{{ url('/admin/deleteIngredient') }}">
                                    @csrf
                                    <input type="hidden" name="ingredient_id" value="{{ $ingredient->id }}">

                                    <div class="modal-content">
                                        <div class="modal-body text-center p-5">
                                            <button type="button" class="btn-close float-end" data-bs-dismiss="modal"></button>
                                            <h4 class="mt-4">Delete Ingredient?</h4>
                                            <p class="text-muted">{{ $ingredient->name }}</p>
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

<!-- Add Ingredient Modal -->
<div class="modal fade" id="addIngredient" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="{{ url('/admin/newIngredient') }}" style="width: 100%;">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Ingredient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label>Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Flour" required>
                    </div>

                    <div class="col-md-6">
                        <label>Base Unit *</label>
                        <select name="base_unit_id" class="form-control" required>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->symbol }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label>
                            <input type="checkbox" name="is_active" checked>
                            Active
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Save Ingredient</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>


@endsection