@extends('staff.layout.dashboard')

@section('content')

    {{-- PAGE HEADER --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Staff Production Portal</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Record Production</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <div class="card shadow-sm">
                <div class="card-body">

                    <h4 class="card-title mb-4">New Production Entry</h4>

                    <form method="POST" action="{{ url('/staff/produce') }}">
                        @csrf

                        <div class="accordion" id="productionAccordion">

                            {{-- PRODUCT & QUANTITY --}}
                            <div class="accordion-item border-0 mb-3 shadow-sm">
                                <h2 class="accordion-header">
                                    <button class="accordion-button fw-bold bg-light text-primary" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#productSection">
                                        <i class="mdi mdi-flask-outline me-2"></i> Production Details
                                    </button>
                                </h2>

                                <div id="productSection"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#productionAccordion">

                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <div class="col-md-7">
                                                <label class="form-label fw-bold">Select Product *</label>
                                                <select name="product_id" id="product_select" class="form-select select2" required>
                                                    <option value="">-- Search Product --</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" data-unit="{{ $product->sales_unit }}">
                                                            {{ $product->name }} (Available: {{ (float)$product->stock_on_hand }} {{ $product->sales_unit }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="text-muted">Stock will be updated automatically upon submission.</small>
                                            </div>

                                            <div class="col-md-5">
                                                <label class="form-label fw-bold">Quantity to Produce *</label>
                                                <div class="input-group">
                                                    <input type="number" name="quantity" step="any" min="0.01" class="form-control" placeholder="0" required>
                                                    <span class="input-group-text" id="unit_label">Units</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- BATCH METADATA --}}
                            <div class="accordion-item border-0 shadow-sm">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold bg-light text-dark" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#metaSection">
                                        <i class="mdi mdi-clipboard-text-outline me-2"></i> Production Notes
                                    </button>
                                </h2>

                                <div id="metaSection"
                                    class="accordion-collapse collapse"
                                    data-bs-parent="#productionAccordion">

                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label">Shift Notes / Observations</label>
                                                <textarea name="notes" class="form-control" rows="2" placeholder="e.g. Batch looks consistent, machine serviced..."></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Production Date</label>
                                                <input type="date" name="produced_at" class="form-control" value="{{ date('Y-m-d') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mt-4 pt-2 border-top text-end">
                            <button type="reset" class="btn btn-light px-4 me-2">Reset Form</button>
                            <button class="btn btn-success px-5 fw-bold">
                                <i class="mdi mdi-check-circle-outline me-1"></i> Submit Production
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        {{-- RIGHT PANEL: STAFF STATS --}}
        <div class="col-xl-4">
            <div class="card bg-info text-white shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-white-50 fw-medium mb-2">My Production Today</p>
                            <h4 class="text-white mb-0">{{ $stats['today_production'] ?? 0 }} Entries</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-light text-white rounded-circle font-size-20">
                                <i class="mdi mdi-account-check"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Staff Guidelines</h5>
                    <div class="alert alert-soft-info border-info font-size-13 mb-0">
                        <i class="mdi mdi-information-outline me-1"></i> 
                        Please ensure all measurements are accurate. Discrepancies in ingredients should be reported to the Admin immediately.
                    </div>
                    
                    <ul class="list-group list-group-flush mt-3 font-size-13">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Available Products <span class="badge bg-info rounded-pill">{{ $products->count() }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#product_select').on('change', function() {
                var selected = $(this).find(':selected');
                var unit = selected.data('unit') || 'Units';
                $('#unit_label').text(unit);
            });
        });
    </script>
@endsection