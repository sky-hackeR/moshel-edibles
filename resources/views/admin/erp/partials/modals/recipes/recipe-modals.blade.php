{{-- =====================================================
     VIEW, EDIT & DELETE RECIPE MODALS (LOOPED)
     ===================================================== --}}
@foreach($recipes as $recipe)
    @php
        $estCost = $recipe->items->sum(function($item) {
            return ((float)$item->base_quantity) * ((float)($item->ingredient->inventory->average_cost ?? 0));
        });
        $sellingPrice = (float)($recipe->product->selling_price ?? 0);
    @endphp

    {{-- 1. VIEW RECIPE MODAL --}}
    <div class="modal fade" id="viewRecipe{{ $recipe->id }}" tabindex="-1" aria-labelledby="viewRecipeTitle{{ $recipe->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                {{-- Modal Header --}}
                <div class="modal-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="viewRecipeTitle{{ $recipe->id }}">
                            <i class="bx bx-book-open me-1 text-primary"></i> Recipe Formulation Details
                        </h5>
                        <small class="text-muted">Formula: {{ $recipe->name }}</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    {{-- Hero Summary Card --}}
                    <div class="p-3 bg-light rounded-3 border mb-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="recipe-icon-avatar me-3" style="width: 48px; height: 48px; font-size: 18px;">
                                {{ strtoupper(substr($recipe->product->name ?? 'P', 0, 1)) }}
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0 font-size-16">{{ $recipe->product->name ?? 'Product' }}</h5>
                                <span class="text-muted font-size-12">Formula Name: <strong>{{ $recipe->name }}</strong></span>
                            </div>
                        </div>
                        <div>
                            @if($recipe->is_active)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 font-size-11">
                                    <i class="mdi mdi-check-circle-outline me-1"></i> Active Formula
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 font-size-11">
                                    <i class="mdi mdi-block-helper me-1"></i> Inactive
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Metrics Grid --}}
                    <div class="row g-2 mb-4">
                        <div class="col-sm-6 col-md-3">
                            <div class="p-2.5 bg-light rounded-3 border h-100 text-center">
                                <small class="text-muted font-size-11 text-uppercase fw-semibold d-block">Retail Price</small>
                                <span class="fw-bold text-dark font-size-14">
                                    ₦{{ number_format($sellingPrice, 2) }}
                                </span>
                                <small class="text-muted font-size-10 d-block">/ {{ $recipe->product->sales_unit ?? 'unit' }}</small>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="p-2.5 bg-light rounded-3 border h-100 text-center">
                                <small class="text-muted font-size-11 text-uppercase fw-semibold d-block">Est. Material Cost</small>
                                <span class="fw-bold text-dark font-size-14">
                                    ₦{{ number_format($estCost, 2) }}
                                </span>
                                <small class="text-muted font-size-10 d-block">Based on avg costing</small>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="p-2.5 bg-success-subtle rounded-3 border border-success-subtle h-100 text-center">
                                <small class="text-success font-size-11 text-uppercase fw-bold d-block">Est. Gross Margin</small>
                                @if($sellingPrice > $estCost && $estCost > 0)
                                    @php $profit = $sellingPrice - $estCost; $margin = round(($profit / $sellingPrice) * 100, 1); @endphp
                                    <span class="fw-bold text-success font-size-14">
                                        ₦{{ number_format($profit, 2) }}
                                    </span>
                                    <small class="text-success font-size-10 d-block fw-semibold">{{ $margin }}% margin</small>
                                @else
                                    <span class="fw-bold text-muted font-size-13">—</span>
                                    <small class="text-muted font-size-10 d-block">Awaiting costs</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="p-2.5 bg-light rounded-3 border h-100 text-center">
                                <small class="text-muted font-size-11 text-uppercase fw-semibold d-block">BOM Components</small>
                                <span class="fw-bold text-primary font-size-14">
                                    {{ $recipe->items->count() }} {{ Str::plural('Item', $recipe->items->count()) }}
                                </span>
                                <small class="text-muted font-size-10 d-block">Raw material inputs</small>
                            </div>
                        </div>
                    </div>

                    {{-- Preparation Notes if available --}}
                    @if(!empty($recipe->note))
                        <div class="alert alert-light border mb-4 p-2.5 font-size-12">
                            <i class="bx bx-info-circle me-1 text-primary"></i><strong>Preparation Notes:</strong> {!! nl2br(e($recipe->note)) !!}
                        </div>
                    @endif

                    {{-- Detailed BOM Table --}}
                    <label class="text-muted small text-uppercase fw-bold mb-2">Ingredients Composition (Bill of Materials)</label>
                    <div class="table-responsive rounded border mb-0">
                        <table class="table table-hover align-middle font-size-12 mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width: 180px;">Ingredient</th>
                                    <th class="text-center" style="min-width: 110px;">Recipe Quantity</th>
                                    <th class="text-center" style="min-width: 120px;">Stored Base Quantity</th>
                                    <th class="text-end" style="min-width: 110px;">Avg Unit Cost</th>
                                    <th class="text-end" style="min-width: 120px;">Est. Line Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recipe->items as $item)
                                    @php
                                        $avgCost = (float)($item->ingredient->inventory->average_cost ?? 0);
                                        $lineCost = ((float)$item->base_quantity) * $avgCost;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->ingredient->name ?? 'Raw Material' }}</div>
                                            <small class="text-muted font-monospace font-size-11">
                                                Base: {{ $item->ingredient->baseUnit->symbol ?? 'unit' }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-bold text-dark">{{ (float)$item->quantity }}</span>
                                            <span class="badge bg-light text-dark border ms-1">{{ $item->unit->symbol ?? '' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-primary fw-semibold">{{ number_format($item->base_quantity, 2) }}</span>
                                            <span class="text-muted font-size-11">{{ $item->unit->base_unit ?? $item->ingredient->baseUnit->symbol ?? '' }}</span>
                                        </td>
                                        <td class="text-end text-muted font-size-12">
                                            ₦{{ number_format($avgCost, 2) }}
                                        </td>
                                        <td class="text-end fw-bold text-dark font-size-13">
                                            ₦{{ number_format($lineCost, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="4" class="text-end fw-bold font-size-12">Total Est. Material Cost:</th>
                                    <th class="text-end fw-bold text-dark font-size-14">
                                        ₦{{ number_format($estCost, 2) }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="modal-footer bg-light border-top px-4 py-2.5 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 font-size-12" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary rounded-pill px-4 font-size-12 fw-medium shadow-sm" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#editRecipe{{ $recipe->id }}">
                        <i class="mdi mdi-pencil me-1"></i> Edit Formulation
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. EDIT RECIPE MODAL --}}
    <div class="modal fade" id="editRecipe{{ $recipe->id }}" tabindex="-1" aria-labelledby="editRecipeTitle{{ $recipe->id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <form method="POST" action="{{ route('updateRecipe') }}" style="width: 100%">
                @csrf
                <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                    {{-- Modal Header --}}
                    <div class="modal-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0" id="editRecipeTitle{{ $recipe->id }}">
                                <i class="bx bx-edit me-1 text-primary"></i> Edit Recipe: {{ $recipe->product->name ?? 'Product' }}
                            </h5>
                            <small class="text-muted">Modify formulation name, batch notes, or raw material proportions.</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold font-size-12 text-dark mb-1">Recipe Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-tag"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0 font-size-13" value="{{ $recipe->name }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold font-size-12 text-dark mb-1">Status</label>
                                <div class="p-2.5 bg-light rounded-3 border">
                                    <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-between p-0">
                                        <label class="form-check-label fw-bold text-dark font-size-13 mb-0" for="editActive{{ $recipe->id }}">Active Formula</label>
                                        <input class="form-check-input ms-3 float-none" type="checkbox" name="is_active" id="editActive{{ $recipe->id }}" {{ $recipe->is_active ? 'checked' : '' }} style="cursor: pointer; width: 36px; height: 20px;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold font-size-12 text-dark mb-1">Preparation / Batch Notes</label>
                                <textarea name="note" class="form-control font-size-13" rows="2" placeholder="Optional mixing instructions, bake times, or notes...">{{ $recipe->note }}</textarea>
                            </div>
                        </div>

                        {{-- Composition Table --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold text-dark font-size-13 mb-0">Ingredients Composition (BOM)</h6>
                            <button type="button" class="btn btn-soft-primary btn-sm rounded-pill px-3" onclick="addRecipeRow('editRecipeItems{{ $recipe->id }}')">
                                <i class="bx bx-plus me-1"></i> Add Ingredient
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
                                <tbody id="editRecipeItems{{ $recipe->id }}">
                                    @foreach($recipe->items as $index => $item)
                                    <tr class="table-line-item-row">
                                        <td>
                                            <select name="items[{{ $index }}][ingredient_id]" class="form-select font-size-13" required>
                                                @foreach($ingredients as $ingredient)
                                                    <option value="{{ $ingredient->id }}" {{ $ingredient->id == $item->ingredient_id ? 'selected' : '' }}>
                                                        {{ $ingredient->name }} (Base: {{ $ingredient->baseUnit->symbol ?? 'unit' }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" step="0.001" min="0.001" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" class="form-control font-size-13" required>
                                        </td>
                                        <td>
                                            <select name="items[{{ $index }}][unit_id]" class="form-select font-size-13" required>
                                                @foreach($units as $unit)
                                                    <option value="{{ $unit->id }}" {{ $unit->id == $item->unit_id ? 'selected' : '' }}>
                                                        {{ $unit->name }} ({{ $unit->symbol }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()" title="Remove item">
                                                <i class="mdi mdi-trash-can-outline"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="modal-footer bg-light border-top px-4 py-2.5 d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary rounded-pill px-4 font-size-12" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 font-size-12 fw-medium shadow-sm">
                            <i class="bx bx-check-double me-1 font-size-14"></i> Save Recipe Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- 3. DELETE RECIPE MODAL --}}
    <div class="modal fade" id="deleteRecipe{{ $recipe->id }}" tabindex="-1" aria-labelledby="deleteRecipeTitle{{ $recipe->id }}" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <form method="POST" action="{{ route('deleteRecipe') }}" style="width: 100%">
                @csrf
                <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                    <div class="modal-body text-center p-4">
                        <div class="avatar-md mx-auto mb-3">
                            <span class="avatar-title rounded-circle bg-danger-subtle text-danger font-size-28">
                                <i class="bx bx-trash"></i>
                            </span>
                        </div>
                        <h5 class="fw-bold text-dark font-size-16 mb-2">Delete Recipe Formulation?</h5>
                        <p class="text-muted font-size-13 mb-3">
                            You are about to remove the formulation for <strong>{{ $recipe->product->name ?? 'Product' }}</strong>. An automated notification will alert all administrators.
                        </p>
                        <div class="d-flex justify-content-center gap-2 mt-4">
                            <button type="button" class="btn btn-secondary rounded-pill px-4 font-size-12" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger rounded-pill px-4 font-size-12 fw-medium shadow-sm">
                                <i class="bx bx-trash me-1"></i> Confirm Deletion
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach
