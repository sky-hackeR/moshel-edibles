@extends('admin.layout.dashboard')

@section('content')

{{-- PAGE HEADER --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Recipe Management</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Recipes</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- MAIN CARD --}}
<div class="row">
    <div class="col-12">
        <div class="card border shadow-none">
            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title mb-0">All Recipes</h4>
                    <p class="text-muted mb-0 small">Manage ingredient compositions for your products.</p>
                </div>
                <button class="btn btn-primary btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addRecipe">
                    <i class="mdi mdi-plus me-1"></i> Add Recipe
                </button>
            </div>

            <div class="card-body">
                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100 align-middle">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">S/N</th>
                            <th>Product</th>
                            <th>Recipe Name</th>
                            <th>Ingredients</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($recipes as $recipe)
                            <tr>
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <h5 class="font-size-14 mb-1">{{ $recipe->product->name }}</h5>
                                </td>
                                <td>{{ $recipe->name }}</td>
                                <td>
                                    <span class="badge badge-soft-info font-size-12">
                                        <i class="mdi mdi-flask-outline me-1"></i> {{ $recipe->items->count() }} items
                                    </span>
                                </td>
                                <td>
                                    @if($recipe->is_active)
                                        <span class="badge badge-soft-success">Active</span>
                                    @else
                                        <span class="badge badge-soft-danger">Inactive</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="javascript:void(0);" class="btn btn-soft-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewRecipe{{ $recipe->id }}" title="View">
                                            <i class="mdi mdi-eye-outline font-size-16"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-soft-success btn-sm" data-bs-toggle="modal" data-bs-target="#editRecipe{{ $recipe->id }}" title="Edit">
                                            <i class="mdi mdi-pencil-outline font-size-16"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-soft-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteRecipe{{ $recipe->id }}" title="Delete">
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

@foreach($recipes as $recipe)
    {{-- DELETE MODAL (Internal for Logic) --}}
    <div class="modal fade" id="deleteRecipe{{ $recipe->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <form method="POST" action="{{ url('/admin/deleteRecipe') }}" style="width: 100%">
                @csrf
                <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">
                <div class="modal-content border-0">
                    <div class="modal-body text-center p-4">
                        <div class="text-danger mb-3">
                            <i class="mdi mdi-alert-circle-outline display-4"></i>
                        </div>
                        <h4>Delete Recipe?</h4>
                        <p class="text-muted mb-4">{{ $recipe->product->name }}</p>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light w-50" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger w-50">Confirm</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach



{{-- MODALS LOOP --}}
@foreach($recipes as $recipe)
    {{-- VIEW MODAL --}}
    <div class="modal fade" id="viewRecipe{{ $recipe->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-transparent border-bottom">
                    <h5 class="modal-title">Recipe Details</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="text-muted small text-uppercase fw-bold">Product</label>
                        <h5 class="mb-0">{{ $recipe->product->name }}</h5>
                    </div>
                    
                    @if($recipe->note)
                    <div class="mb-4">
                        <label class="text-muted small text-uppercase fw-bold">Notes</label>
                        <p class="mb-0 text-dark bg-light p-2 rounded">{!! $recipe->note !!}</p>
                    </div>
                    @endif

                    <label class="text-muted small text-uppercase fw-bold mb-2">Ingredients List</label>
                    <div class="table-responsive">
                        <table class="table table-sm table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Ingredient</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-center">Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recipe->items as $item)
                                    <tr>
                                        <td>{{ $item->ingredient->name }}</td>
                                        <td class="text-end fw-bold">{{ $item->quantity }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-soft-secondary">{{ $item->unit->symbol }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div class="modal fade" id="editRecipe{{ $recipe->id }}" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <form method="POST" action="{{ url('/admin/updateRecipe') }}">
                @csrf
                <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Recipe — {{ $recipe->product->name }}</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label class="form-label">Recipe Name *</label>
                                <input type="text" name="name" class="form-control" value="{{ $recipe->name }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label d-block">Status</label>
                                <div class="form-check form-switch form-switch-md mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" {{ $recipe->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label">Active</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Note</label>
                                <textarea name="note" class="form-control" rows="2">{{ $recipe->note }}</textarea>
                            </div>
                        </div>

                        <h6 class="mb-3">Composition</h6>
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Ingredient</th>
                                    <th style="width: 200px;">Qty</th>
                                    <th style="width: 150px;">Unit</th>
                                    <th width="50"></th>
                                </tr>
                            </thead>
                            <tbody id="editRecipeItems{{ $recipe->id }}">
                                @foreach($recipe->items as $index => $item)
                                <tr>
                                    <td>
                                        <select name="items[{{ $index }}][ingredient_id]" class="form-control" required>
                                            @foreach($ingredients as $ingredient)
                                                <option value="{{ $ingredient->id }}" {{ $ingredient->id == $item->ingredient_id ? 'selected' : '' }}>
                                                    {{ $ingredient->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="0.001" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" class="form-control" required>
                                    </td>
                                    <td>
                                        <select name="items[{{ $index }}][unit_id]" class="form-control" required>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}" {{ $unit->id == $item->unit_id ? 'selected' : '' }}>
                                                    {{ $unit->symbol }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-soft-danger btn-sm" onclick="this.closest('tr').remove()">
                                            <i class="mdi mdi-close"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-soft-primary btn-sm" onclick="addRecipeRow('editRecipeItems{{ $recipe->id }}')">
                            <i class="mdi mdi-plus me-1"></i> Add Ingredient
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Update Recipe</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

{{-- ADD RECIPE MODAL --}}
<div class="modal fade" id="addRecipe" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="{{ url('/admin/newRecipe') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Recipe</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Product *</label>
                            <select name="product_id" class="form-select" required>
                                <option value="">Choose product...</option>
                                @foreach($products as $product)
                                    @if(!$product->recipe)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Recipe Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Standard Formula" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Preparation Note</label>
                            <textarea name="note" class="form-control" placeholder="Optional notes..."></textarea>
                        </div>
                    </div>

                    <h6 class="mb-3">Ingredients</h6>
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Ingredient</th>
                                <th style="width: 200px;">Qty</th>
                                <th style="width: 150px;">Unit</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody id="recipeItems">
                            <tr>
                                <td>
                                    <select name="items[0][ingredient_id]" class="form-control" required>
                                        <option value="">Select Ingredient</option>
                                        @foreach($ingredients as $ingredient)
                                            <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" step="0.001" name="items[0][quantity]" class="form-control" required></td>
                                <td>
                                    <select name="items[0][unit_id]" class="form-control" required>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->symbol }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-soft-primary btn-sm" onclick="addRecipeRow('recipeItems')">
                        <i class="mdi mdi-plus me-1"></i> Add Ingredient
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Recipe</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function addRecipeRow(containerId) {
    const tbody = document.getElementById(containerId);
    const index = tbody.children.length;

    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <select name="items[${index}][ingredient_id]" class="form-control" required>
                <option value="">Select Ingredient</option>
                @foreach($ingredients as $ingredient)
                    <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                @endforeach
            </select>
        </td>
        <td><input type="number" step="0.001" name="items[${index}][quantity]" class="form-control" required></td>
        <td>
            <select name="items[${index}][unit_id]" class="form-control" required>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->symbol }}</option>
                @endforeach
            </select>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-soft-danger btn-sm" onclick="this.closest('tr').remove()">
                <i class="mdi mdi-close"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
}
</script>

@endsection