{{-- =====================================================
     ADD NEW RECIPE MODAL
     ===================================================== --}}
<div class="modal fade" id="addRecipe" tabindex="-1" aria-labelledby="addRecipeTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <form method="POST" action="{{ route('newRecipe') }}" style="width: 100%">
            @csrf
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                {{-- Header --}}
                <div class="modal-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="addRecipeTitle">
                            <i class="bx bx-plus-circle me-1 text-primary"></i> Create New Recipe Formulation
                        </h5>
                        <small class="text-muted">Link a commercial product to its production bill of materials.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold font-size-12 text-dark mb-1">Target Product <span class="text-danger">*</span></label>
                            <select name="product_id" class="form-select font-size-13" required>
                                <option value="">-- Choose unconfigured product --</option>
                                @foreach($products as $product)
                                    @if(!$product->recipe)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }} (Sales Unit: {{ $product->sales_unit }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <small class="text-muted font-size-11 mt-1 d-block">Each commercial product can have one master recipe formulation.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold font-size-12 text-dark mb-1">Recipe Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-tag"></i></span>
                                <input type="text" name="name" class="form-control border-start-0 font-size-13" placeholder="e.g. Standard Batch Formula" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold font-size-12 text-dark mb-1">Preparation / Batch Notes</label>
                            <textarea name="note" class="form-control font-size-13" rows="2" placeholder="Optional mixing instructions, oven settings, or production notes..."></textarea>
                        </div>
                    </div>

                    {{-- Composition Table --}}
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="fw-bold text-dark font-size-13 mb-0">Ingredients Composition (BOM) <span class="text-danger">*</span></h6>
                        <button type="button" class="btn btn-soft-primary btn-sm rounded-pill px-3" onclick="addRecipeRow('recipeItems')">
                            <i class="bx bx-plus me-1"></i> Add Another Ingredient
                        </button>
                    </div>

                    <div class="table-responsive rounded border mb-3">
                        <table class="table table-bordered table-hover align-middle mb-0 font-size-13">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width: 280px;">Ingredient / Raw Material <span class="text-danger">*</span></th>
                                    <th style="min-width: 140px;">Quantity <span class="text-danger">*</span></th>
                                    <th style="min-width: 160px;">Recipe Unit <span class="text-danger">*</span></th>
                                    <th class="text-center" style="width: 60px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="recipeItems">
                                <tr class="table-line-item-row">
                                    <td>
                                        <select name="items[0][ingredient_id]" class="form-select font-size-13" required>
                                            <option value="">-- Choose Raw Material --</option>
                                            @foreach($ingredients as $ingredient)
                                                <option value="{{ $ingredient->id }}">{{ $ingredient->name }} (Base: {{ $ingredient->baseUnit->symbol ?? 'unit' }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="0.001" min="0.001" name="items[0][quantity]" class="form-control font-size-13" required placeholder="0.000">
                                    </td>
                                    <td>
                                        <select name="items[0][unit_id]" class="form-select font-size-13" required>
                                            <option value="">-- Unit --</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->symbol }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" disabled title="First ingredient cannot be removed">
                                            <i class="mdi mdi-trash-can-outline"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="modal-footer bg-light border-top px-4 py-2.5 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 font-size-12" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 font-size-12 fw-medium shadow-sm">
                        <i class="bx bx-check me-1 font-size-14"></i> Save Recipe
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
