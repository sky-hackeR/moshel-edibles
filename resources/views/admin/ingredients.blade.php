@extends('admin.layout.dashboard')

@section('content')

{{-- PAGE HEADER --}}
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

{{-- CONTENT --}}
<div class="row">
    <div class="col-12">
        <div class="card border shadow-none">
            <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="card-title mb-0">All Ingredients</h4>
                    <p class="text-muted mb-0 small">Manage your raw materials and their base units.</p>
                </div>
                <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#addIngredient">
                    <i class="mdi mdi-plus me-1"></i> Add Ingredient
                </button>
            </div>

            <div class="card-body">
                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100 align-middle">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">S/N</th>
                            <th>Ingredient Name</th>
                            <th>Base Unit</th>
                            <th>Status</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($ingredients as $ingredient)
                            <tr>
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <h5 class="font-size-14 mb-1">
                                        <a href="javascript: void(0);" class="text-dark">{{ $ingredient->name }}</a>
                                    </h5>
                                </td>
                                <td>
                                    <span class="badge badge-soft-primary">
                                        {{ $ingredient->baseUnit->name }} ({{ $ingredient->baseUnit->symbol }})
                                    </span>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" {{ $ingredient->is_active ? 'checked' : '' }} disabled>
                                        <label class="small {{ $ingredient->is_active ? 'text-success' : 'text-danger' }}">
                                            {{ $ingredient->is_active ? 'Active' : 'Disabled' }}
                                        </label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="javascript:void(0);" 
                                        class="btn btn-soft-success btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editIngredient{{ $ingredient->id }}" 
                                        title="Edit Ingredient">
                                            <i class="mdi mdi-pencil-outline font-size-16"></i>
                                        </a>

                                        <a href="javascript:void(0);" 
                                        class="btn btn-soft-danger btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteIngredient{{ $ingredient->id }}" 
                                        title="Delete Ingredient">
                                            <i class="mdi mdi-delete-outline font-size-16"></i>
                                        </a>
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

@foreach($ingredients as $ingredient)
    {{-- EDIT MODAL --}}
    <div class="modal fade" id="editIngredient{{ $ingredient->id }}" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" action="{{ url('admin/updateIngredient') }}" style="width: 100%">
                @csrf
                <input type="hidden" name="ingredient_id" value="{{ $ingredient->id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit: {{ $ingredient->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Ingredient Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $ingredient->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Base Unit</label>
                            <select name="base_unit_id" class="form-control select2" required>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ $ingredient->base_unit_id == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }} ({{ $unit->symbol }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_active" id="editActive{{ $ingredient->id }}" {{ $ingredient->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="editActive{{ $ingredient->id }}">Mark as Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary">Update Ingredient</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- DELETE MODAL --}}
    <div class="modal fade" id="deleteIngredient{{ $ingredient->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ url('admin/deleteIngredient') }}" style="width: 100%">
                @csrf
                <input type="hidden" name="ingredient_id" value="{{ $ingredient->id }}">
                <div class="modal-content border-danger">
                    <div class="modal-body text-center p-4">
                        <i class="mdi mdi-alert-circle-outline text-danger display-4"></i>
                        <h4 class="mt-3">Are you sure?</h4>
                        <p class="text-muted">You are about to delete <b>{{ $ingredient->name }}</b>.</p>
                        <div class="d-flex gap-2 mt-4">
                            <button type="button" class="btn btn-light w-50" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-danger w-50">Delete</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

{{-- ADD INGREDIENT MODAL --}}
<div class="modal fade" id="addIngredient" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="{{ url('admin/newIngredient') }}" style="width: 100%">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Ingredient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Ingredient Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Flour" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Base Unit</label>
                        <select name="base_unit_id" class="form-control" required>
                            <option value="" selected disabled>Select Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->symbol }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="is_active" id="newActive" checked>
                            <label class="form-check-label" for="newActive">Set as Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Discard</button>
                    <button class="btn btn-primary px-4">Create Ingredient</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection