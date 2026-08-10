@extends('admin.layout.dashboard')

@section('content')

{{-- INFORMATION ALERT --}}
<div class="row">
    <div class="col-12">
        <div class="alert alert-info">
            <div class="d-flex align-items-start">
                <i class="bx bx-info-circle fs-4 me-2"></i>

                <div>
                    <strong>What is Opening Stock?</strong>

                    <p class="mb-0 mt-1">
                        Opening Stock is used to enter products and ingredients
                        that already existed before you started using this system.
                        These entries are recorded separately from normal purchases
                        and production for audit purposes.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- FORMS CARD WITH TABS --}}
<div class="row">
    <div class="col-12">

        <div class="card">

            <div class="card-header border-bottom-0 pb-0">

                <ul
                    class="nav nav-tabs nav-tabs-custom card-header-tabs"
                    id="openingStockTabs"
                    role="tablist"
                >

                    <li class="nav-item" role="presentation">

                        <button
                            class="nav-link active"
                            id="product-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#product-pane"
                            type="button"
                            role="tab"
                            aria-controls="product-pane"
                            aria-selected="true"
                        >
                            <i class="bx bx-package me-1"></i>
                            Add Product Stock
                        </button>

                    </li>


                    <li class="nav-item" role="presentation">

                        <button
                            class="nav-link"
                            id="ingredient-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#ingredient-pane"
                            type="button"
                            role="tab"
                            aria-controls="ingredient-pane"
                            aria-selected="false"
                        >
                            <i class="bx bx-bowl-rice me-1"></i>
                            Add Ingredient Stock
                        </button>

                    </li>

                </ul>

            </div>


            <div class="card-body pt-4">

                <div
                    class="tab-content"
                    id="openingStockTabContent"
                >

                    {{-- ================================================= --}}
                    {{-- PRODUCT OPENING STOCK --}}
                    {{-- ================================================= --}}

                    <div
                        class="tab-pane fade show active"
                        id="product-pane"
                        role="tabpanel"
                        aria-labelledby="product-tab"
                    >

                        <div class="alert alert-light border mb-4">

                            <div class="d-flex align-items-start">

                                <i class="bx bx-package fs-4 me-2 text-primary"></i>

                                <div>

                                    <strong>Existing Finished Products</strong>

                                    <p class="mb-0 mt-1 text-muted">
                                        Use this section for finished products that
                                        were already available before the system
                                        started recording production and sales.
                                    </p>

                                    <small class="text-muted d-block mt-2">
                                        <strong>Example:</strong>
                                        If you already have 50 Meat Pies physically
                                        available, enter <strong>50</strong> as the
                                        opening quantity.
                                    </small>

                                </div>

                            </div>

                        </div>


                        <form
                            action="{{ route('addProductOpeningStock') }}"
                            method="POST"
                        >

                            @csrf

                            <div class="row align-items-center">

                                <div class="col-md-4 mb-3">

                                    <label class="form-label">
                                        Product
                                    </label>

                                    <select
                                        name="product_id"
                                        class="form-select"
                                        required
                                    >

                                        <option value="">
                                            Select Product
                                        </option>

                                        @foreach($products as $product)

                                            <option value="{{ $product->id }}">

                                                {{ $product->name }}

                                                (Current:
                                                {{ $product->stock_on_hand }}
                                                {{ $product->sales_unit }})

                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                <div class="col-md-3 mb-3">

                                    <label class="form-label">
                                        Opening Quantity
                                    </label>

                                    <input
                                        type="number"
                                        name="quantity"
                                        class="form-control"
                                        min="0.001"
                                        step="0.001"
                                        placeholder="e.g. 50"
                                        required
                                    >

                                    <small class="text-muted">
                                        Enter the quantity physically available.
                                    </small>

                                </div>


                                <div class="col-md-5 mb-3">

                                    <label class="form-label">
                                        Reason
                                    </label>

                                    <input
                                        type="text"
                                        name="reason"
                                        class="form-control"
                                        value="Existing stock before system commencement"
                                    >

                                    <small class="text-muted">
                                        Explain where this opening balance came from
                                        if necessary.
                                    </small>

                                </div>

                            </div>


                            <div class="d-flex justify-content-end mt-2">

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    <i class="bx bx-plus"></i>
                                    Add Product Stock
                                </button>

                            </div>

                        </form>

                    </div>


                    {{-- ================================================= --}}
                    {{-- INGREDIENT OPENING STOCK --}}
                    {{-- ================================================= --}}

                    <div
                        class="tab-pane fade"
                        id="ingredient-pane"
                        role="tabpanel"
                        aria-labelledby="ingredient-tab"
                    >

                        <div class="alert alert-warning mb-4">

                            <div class="d-flex align-items-start">

                                <i class="bx bx-error-circle fs-4 me-2"></i>

                                <div>

                                    <strong>
                                        Important: Ingredient Quantity
                                    </strong>

                                    <p class="mb-1 mt-1">
                                        Ingredient inventory is stored using the
                                        ingredient's <strong>base unit</strong>.
                                        Enter the quantity in the base unit shown
                                        beside the ingredient.
                                    </p>

                                    <small>
                                        <strong>Example:</strong>
                                        If Flour uses
                                        <strong>grams (g)</strong> as its base unit
                                        and you physically have
                                        <strong>25 kg</strong>, enter
                                        <strong>25,000 g</strong> — not 25 kg.
                                    </small>

                                </div>

                            </div>

                        </div>


                        <form
                            action="{{ route('addIngredientOpeningStock') }}"
                            method="POST"
                        >

                            @csrf

                            <div class="row align-items-start">

                                <div class="col-md-4 mb-3">

                                    <label class="form-label">
                                        Ingredient
                                    </label>

                                    <select
                                        name="ingredient_id"
                                        id="ingredient_id"
                                        class="form-select"
                                        required
                                    >

                                        <option value="">
                                            Select Ingredient
                                        </option>

                                        @foreach($ingredients as $ingredient)

                                            <option
                                                value="{{ $ingredient->id }}"
                                                data-unit="{{ optional($ingredient->baseUnit)->symbol }}"
                                            >

                                                {{ $ingredient->name }}

                                                -
                                                {{ optional($ingredient->baseUnit)->symbol ?? 'Base Unit' }}

                                            </option>

                                        @endforeach

                                    </select>

                                    <small class="text-muted d-block mt-1">
                                        The unit shown beside the ingredient is
                                        the base unit used by the inventory system.
                                    </small>

                                </div>


                                <div class="col-md-3 mb-3">

                                    <label class="form-label">
                                        Opening Quantity
                                    </label>

                                    <input
                                        type="number"
                                        name="quantity"
                                        class="form-control"
                                        min="0.001"
                                        step="0.001"
                                        placeholder="e.g. 25000"
                                        required
                                    >

                                    <small
                                        id="unitHelp"
                                        class="text-muted d-block mt-1"
                                    >
                                        Select an ingredient first.
                                    </small>

                                </div>


                                <div class="col-md-2 mb-3">

                                    <label class="form-label">
                                        Total Stock Value
                                    </label>

                                    <input
                                        type="number"
                                        name="total_value"
                                        class="form-control"
                                        min="0"
                                        step="0.01"
                                        placeholder="e.g. 37500"
                                        required
                                    >

                                    <small class="text-muted d-block mt-1">
                                        Total value of the existing stock.
                                    </small>

                                </div>


                                <div class="col-md-3 mb-3">

                                    <label class="form-label">
                                        Reason
                                    </label>

                                    <input
                                        type="text"
                                        name="reason"
                                        class="form-control"
                                        value="Existing stock before system commencement"
                                    >

                                    <small class="text-muted d-block mt-1">
                                        Optional explanation for the opening balance.
                                    </small>

                                </div>

                            </div>


                            {{-- COST EXPLANATION --}}
                            <div class="alert alert-light border mt-2">

                                <div class="d-flex align-items-start">

                                    <i class="bx bx-calculator fs-4 me-2 text-success"></i>

                                    <div>

                                        <strong>
                                            How should Total Stock Value be entered?
                                        </strong>

                                        <p class="mb-1 mt-1 text-muted">
                                            Enter the estimated total value of all
                                            the existing stock you currently have.
                                            You do not need to calculate the
                                            cost per gram, millilitre, or piece.
                                            The system calculates that automatically.
                                        </p>

                                        <small class="text-muted d-block">
                                            <strong>Example:</strong>
                                            You have 25 kg of Flour and the stock
                                            is worth approximately ₦37,500.
                                        </small>

                                        <small class="text-muted d-block mt-1">
                                            Enter:
                                            <strong>25,000 g</strong>
                                            as the quantity and
                                            <strong>₦37,500</strong>
                                            as the total stock value.
                                        </small>

                                        <small class="text-muted d-block mt-1">
                                            The system automatically calculates
                                            the cost per gram and uses it for
                                            future production costing.
                                        </small>

                                    </div>

                                </div>

                            </div>


                            <div class="d-flex justify-content-end mt-3">

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    <i class="bx bx-plus"></i>
                                    Add Ingredient Stock
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>


{{-- ================================================= --}}
{{-- OPENING STOCK HISTORY --}}
{{-- ================================================= --}}

<div class="row">

    <div class="col-12">

        <div class="card">

            <div class="card-header">

                <h5 class="card-title mb-0">
                    Opening Stock History
                </h5>

            </div>


            <div class="card-body">

                <div class="table-responsive">

                    <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">

                        <thead>

                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Opening Cost / Unit</th>
                                <th>Reason</th>
                                <th>Added By</th>
                                <th>Actions</th>
                            </tr>

                        </thead>


                        <tbody>

                            @forelse($openingStocks as $openingStock)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>
                                        {{ $openingStock->created_at->format('d M Y, h:i A') }}
                                    </td>


                                    <td>

                                        @if($openingStock->item_type === 'product')

                                            <span class="badge bg-primary">
                                                Product
                                            </span>

                                        @else

                                            <span class="badge bg-success">
                                                Ingredient
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        @if($openingStock->item_type === 'product')

                                            {{ optional($openingStock->product)->name ?? 'Deleted Product' }}

                                        @else

                                            {{ optional($openingStock->ingredient)->name ?? 'Deleted Ingredient' }}

                                        @endif

                                    </td>


                                    <td>

                                        {{ number_format($openingStock->quantity, 4) }}

                                        @if($openingStock->item_type === 'product')

                                            {{ optional($openingStock->product)->sales_unit }}

                                        @else

                                            {{ optional(optional($openingStock->ingredient)->baseUnit)->symbol }}

                                        @endif

                                    </td>


                                    <td>

                                        @if($openingStock->item_type === 'ingredient')

                                            ₦{{ number_format($openingStock->average_cost, 4) }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>
                                        {{ $openingStock->reason ?? '—' }}
                                    </td>


                                    <td>
                                        {{ optional($openingStock->admin)->name ?? 'Unknown' }}
                                    </td>

                                    <td> 
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewOpeningStockModal{{ $openingStock->id }}" >
                                            <i class="bx bx-show"></i> View 
                                        </button> 
                                    </td>

                                </tr>
                                {{-- ================================================= --}}
                                {{-- VIEW OPENING STOCK MODAL --}}
                                {{-- ================================================= --}}
                                <div 
                                    class="modal fade" 
                                    id="viewOpeningStockModal{{ $openingStock->id }}" 
                                    tabindex="-1" 
                                    aria-labelledby="viewOpeningStockModalLabel{{ $openingStock->id }}" 
                                    aria-hidden="true"
                                >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            {{-- MODAL HEADER --}}
                                            <div class="modal-header">
                                                <div>
                                                    <h5 class="modal-title" id="viewOpeningStockModalLabel{{ $openingStock->id }}">
                                                        Opening Stock Details
                                                    </h5>
                                                    <small class="text-muted">Record #{{ $openingStock->id }}</small>
                                                </div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            {{-- MODAL BODY --}}
                                            <div class="modal-body">
                                                {{-- TYPE --}}
                                                <div class="text-center mb-4">
                                                    @if($openingStock->item_type === 'product')
                                                        <span class="badge bg-primary px-3 py-2">
                                                            <i class="bx bx-package me-1"></i> Product Opening Stock
                                                        </span>
                                                    @else
                                                        <span class="badge bg-success px-3 py-2">
                                                            <i class="bx bx-bowl-rice me-1"></i> Ingredient Opening Stock
                                                        </span>
                                                    @endif
                                                </div>

                                                {{-- ITEM --}}
                                                <div class="row mb-3">
                                                    <div class="col-5 text-muted">Item</div>
                                                    <div class="col-7 fw-semibold">
                                                        @if($openingStock->item_type === 'product')
                                                            {{ optional($openingStock->product)->name ?? 'Deleted Product' }}
                                                        @else
                                                            {{ optional($openingStock->ingredient)->name ?? 'Deleted Ingredient' }}
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- QUANTITY --}}
                                                <div class="row mb-3">
                                                    <div class="col-5 text-muted">Opening Quantity</div>
                                                    <div class="col-7 fw-semibold">
                                                        {{ number_format($openingStock->quantity, 4) }} 
                                                        @if($openingStock->item_type === 'product')
                                                            {{ optional($openingStock->product)->sales_unit }}
                                                        @else
                                                            {{ optional(optional($openingStock->ingredient)->baseUnit)->symbol }}
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- INGREDIENT COST DETAILS --}}
                                                @if($openingStock->item_type === 'ingredient')
                                                    <div class="row mb-3">
                                                        <div class="col-5 text-muted">Opening Cost / Unit</div>
                                                        <div class="col-7 fw-semibold text-success">
                                                            ₦{{ number_format($openingStock->average_cost, 6) }}
                                                        </div>
                                                    </div>

                                                    {{-- TOTAL VALUE --}}
                                                    <div class="row mb-3">
                                                        <div class="col-5 text-muted">Opening Stock Value</div>
                                                        <div class="col-7 fw-semibold">
                                                            ₦{{ number_format($openingStock->quantity * $openingStock->average_cost, 2) }}
                                                        </div>
                                                    </div>
                                                @endif

                                                {{-- REASON --}}
                                                <div class="row mb-3">
                                                    <div class="col-5 text-muted">Reason</div>
                                                    <div class="col-7">{{ $openingStock->reason ?? 'No reason provided.' }}</div>
                                                </div>

                                                {{-- ADDED BY --}}
                                                <div class="row mb-3">
                                                    <div class="col-5 text-muted">Added By</div>
                                                    <div class="col-7">
                                                        <i class="bx bx-user me-1"></i> {{ optional($openingStock->admin)->name ?? 'Unknown' }}
                                                    </div>
                                                </div>

                                                {{-- DATE --}}
                                                <div class="row mb-3">
                                                    <div class="col-5 text-muted">Date Added</div>
                                                    <div class="col-7">{{ $openingStock->created_at->format('d M Y, h:i A') }}</div>
                                                </div>

                                                {{-- UPDATED DATE --}}
                                                @if($openingStock->updated_at && $openingStock->updated_at->ne($openingStock->created_at))
                                                    <div class="row mb-0">
                                                        <div class="col-5 text-muted">Last Updated</div>
                                                        <div class="col-7">{{ $openingStock->updated_at->format('d M Y, h:i A') }}</div>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- MODAL FOOTER --}}
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bx bx-x"></i> Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @empty

                                <tr>

                                    <td
                                        colspan="8"
                                        class="text-center text-muted py-4"
                                    >

                                        <i class="bx bx-package fs-3 d-block mb-2"></i>

                                        No opening stock has been recorded yet.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ================================================= --}}
{{-- INGREDIENT UNIT GUIDANCE --}}
{{-- ================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const ingredientSelect =
        document.getElementById('ingredient_id');

    const unitHelp =
        document.getElementById('unitHelp');


    ingredientSelect.addEventListener('change', function () {

        const selected =
            this.options[this.selectedIndex];

        const unit =
            selected.getAttribute('data-unit');


        if (unit) {

            unitHelp.innerHTML =
                'Enter the quantity in <strong>' +
                unit +
                '</strong>, the ingredient\'s base unit.';

        } else {

            unitHelp.textContent =
                'Select an ingredient first.';

        }

    });

});

</script>

@endsection