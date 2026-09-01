@extends('admin.layout.dashboard')

@section('content')

    {{-- PAGE HEADER --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Production Control</h4>
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

                    <h4 class="card-title mb-4">New Production Batch</h4>

                    <form method="POST" action="{{ url('/admin/produce') }}">
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
                                                <small class="text-muted">The system will automatically deduct ingredients based on the recipe.</small>
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
                                        <i class="mdi mdi-clipboard-text-outline me-2"></i> Batch Metadata (Optional)
                                    </button>
                                </h2>

                                <div id="metaSection"
                                    class="accordion-collapse collapse"
                                    data-bs-parent="#productionAccordion">

                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label">Batch Notes</label>
                                                <textarea name="notes" class="form-control" rows="2" placeholder="e.g. Morning shift, used alternative flour brand..."></textarea>
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
                            <button type="reset" class="btn btn-light px-4 me-2">Clear</button>
                            <button class="btn btn-success px-5 fw-bold">
                                <i class="mdi mdi-play-circle-outline me-1"></i> Start Production
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        {{-- RIGHT PANEL: LIVE STATS --}}
        <div class="col-xl-4">
            <div class="card bg-primary text-white shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-white-50 fw-medium mb-2">Today's Output</p>
                            <h4 class="text-white mb-0">{{ $stats['today_production'] ?? 0 }} Batches</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-light text-white rounded-circle font-size-20">
                                <i class="mdi mdi-trending-up"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Inventory Protection</h5>
                    <div class="alert alert-soft-warning border-warning font-size-13 mb-0">
                        <i class="mdi mdi-information-outline me-1"></i> 
                        Recording production will automatically deduct ingredients from your stock in <strong>Grams (g)</strong> and <strong>Milliliters (ml)</strong> using the weighted average cost.
                    </div>
                    
                    <ul class="list-group list-group-flush mt-3 font-size-13">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Total Products Tracked <span class="badge bg-primary rounded-pill">{{ $products->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Active Recipes 
                            <span class="badge bg-success rounded-pill">
                                {{ $products->filter(fn($p) => $p->recipe !== null)->count() }}
                            </span>
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