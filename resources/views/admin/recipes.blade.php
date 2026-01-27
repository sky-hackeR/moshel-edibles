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
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">All Recipes</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRecipe">
                    Add Recipe
                </button>
            </div>

            <div class="card-body">
                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Product</th>
                            <th>Recipe Name</th>
                            <th>Ingredients</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($recipes as $recipe)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $recipe->product->name }}</td>
                                <td>{{ $recipe->name }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $recipe->items->count() }} items
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $recipe->is_active ? 'success' : 'danger' }}">
                                        {{ $recipe->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-info m-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewRecipe{{ $recipe->id }}">
                                        <i class="mdi mdi-eye"></i>
                                    </button>

                                    <button class="btn btn-success m-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editRecipe{{ $recipe->id }}">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>

                                    <button class="btn btn-danger m-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteRecipe{{ $recipe->id }}">
                                        <i class="mdi mdi-delete"></i>
                                    </button>

                                </td>
                            </tr>

                            {{-- DELETE MODAL --}}
                            <div class="modal fade" id="deleteRecipe{{ $recipe->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form method="POST" action="{{ url('/admin/deleteRecipe') }}">
                                        @csrf
                                        <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">

                                        <div class="modal-content">
                                            <div class="modal-body text-center p-5">
                                                <button type="button" class="btn-close float-end" data-bs-dismiss="modal"></button>
                                                <h4 class="mt-4">Delete Recipe?</h4>
                                                <p class="text-muted">
                                                    {{ $recipe->product->name }} — {{ $recipe->name }}
                                                </p>
                                                <button class="btn btn-danger w-100 mt-3">
                                                    Yes, Delete
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No recipe records found
                                </td>
                            </tr>

                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

@foreach($recipes as $recipe)
    {{-- VIEW MODAL --}}
    <div class="modal fade" id="viewRecipe{{ $recipe->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Recipe for {{ $recipe->product->name }}
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p class="text-muted">{!! $recipe->note ?? '—' !!}</p>

                    <table class="table table-sm table-bordered">
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
                                <td class="text-end">{{ $item->quantity }}</td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">
                                        {{ $item->unit->symbol }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>

@endforeach


@foreach($recipes as $recipe)
    <div class="modal fade" id="editRecipe{{ $recipe->id }}" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <form method="POST" action="{{ url('/admin/updateRecipe') }}">
                @csrf
                <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">

                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">
                            Edit Recipe — {{ $recipe->product->name }}
                        </h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        {{-- BASIC INFO --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label>Recipe Name *</label>
                                <input type="text"
                                    name="name"
                                    class="form-control"
                                    value="{{ $recipe->name }}"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label>Status</label><br>
                                <input type="checkbox"
                                    name="is_active"
                                    {{ $recipe->is_active ? 'checked' : '' }}>
                                Active
                            </div>

                            <div class="col-md-12">
                                <label>Note</label>
                                <textarea name="note"
                                        class="form-control">{{ $recipe->note }}</textarea>
                            </div>
                        </div>

                        {{-- ITEMS TABLE --}}
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Ingredient</th>
                                    <th>Qty</th>
                                    <th>Unit</th>
                                    <th width="50"></th>
                                </tr>
                            </thead>

                            <tbody id="editRecipeItems{{ $recipe->id }}">
                                @foreach($recipe->items as $index => $item)
                                <tr>
                                    <td>
                                        <select name="items[{{ $index }}][ingredient_id]"
                                                class="form-control"
                                                required>
                                            <option value="">Select Ingredient</option>
                                            @foreach($ingredients as $ingredient)
                                                <option value="{{ $ingredient->id }}"
                                                    {{ $ingredient->id == $item->ingredient_id ? 'selected' : '' }}>
                                                    {{ $ingredient->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <input type="number"
                                            step="0.001"
                                            name="items[{{ $index }}][quantity]"
                                            value="{{ $item->quantity }}"
                                            class="form-control"
                                            required>
                                    </td>

                                    <td>
                                        <select name="items[{{ $index }}][unit_id]"
                                                class="form-control"
                                                required>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}"
                                                    {{ $unit->id == $item->unit_id ? 'selected' : '' }}>
                                                    {{ $unit->symbol }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td class="text-center">
                                        <button type="button"
                                                class="btn btn-danger btn-sm"
                                                onclick="this.closest('tr').remove()">
                                            ✕
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <button type="button"
                                class="btn btn-outline-primary btn-sm"
                                onclick="addRecipeRow('editRecipeItems{{ $recipe->id }}')">
                            + Add Ingredient
                        </button>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success">Update Recipe</button>
                        <button class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endforeach


{{-- ADD RECIPE MODAL --}}
<div class="modal fade" id="addRecipe" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <form method="POST" action="{{ url('/admin/newRecipe') }}">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add New Recipe</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label>Product *</label>
                            <select name="product_id" class="form-control" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    @if(!$product->recipe)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Recipe Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="col-md-12">
                            <label>Note</label>
                            <textarea name="note" class="form-control"></textarea>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Ingredient</th>
                                <th>Qty</th>
                                <th>Unit</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody id="recipeItems">
                            <tr>
                                <td>
                                    <select name="items[0][ingredient_id]" class="form-control" required>
                                        <option value="">Select Ingredient</option>
                                        @foreach($ingredients as $ingredient)
                                            <option value="{{ $ingredient->id }}">
                                                {{ $ingredient->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="0.001" name="items[0][quantity]" class="form-control" required>
                                </td>
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

                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addRecipeRow('recipeItems')">
                        + Add Ingredient
                    </button>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Save Recipe</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- <script>
let recipeRow = 1;

function addRecipeRow() {
    const tbody = document.getElementById('recipeItems');

    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <select name="items[${recipeRow}][ingredient_id]" class="form-control" required>
                <option value="">Select Ingredient</option>
                @foreach($ingredients as $ingredient)
                    <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" step="0.001" name="items[${recipeRow}][quantity]" class="form-control" required>
        </td>
        <td>
            <select name="items[${recipeRow}][unit_id]" class="form-control" required>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->symbol }}</option>
                @endforeach
            </select>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm"
                onclick="this.closest('tr').remove()">✕</button>
        </td>
    `;
    tbody.appendChild(row);
    recipeRow++;
}
</script> --}}

<script>
let recipeRow = 1;

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

        <td>
            <input type="number"
                   step="0.001"
                   name="items[${index}][quantity]"
                   class="form-control"
                   required>
        </td>

        <td>
            <select name="items[${index}][unit_id]" class="form-control" required>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->symbol }}</option>
                @endforeach
            </select>
        </td>

        <td class="text-center">
            <button type="button"
                    class="btn btn-danger btn-sm"
                    onclick="this.closest('tr').remove()">
                ✕
            </button>
        </td>
    `;

    tbody.appendChild(row);
}
</script>


@endsection
