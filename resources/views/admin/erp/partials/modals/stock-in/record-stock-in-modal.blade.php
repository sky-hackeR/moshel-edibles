{{-- =====================================================
     CREATE NEW STOCK PURCHASE MODAL PARTIAL
     ===================================================== --}}
<div class="modal fade" id="addStock" tabindex="-1" aria-labelledby="addStockTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <form method="POST" action="{{ route('newStockIn') }}" style="width: 100%" id="newStockInForm">
            @csrf
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">

                {{-- Modal Header --}}
                <div class="modal-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="addStockTitle">
                            <i class="bx bx-plus-circle me-1 text-primary"></i> Record Stock Purchase
                        </h5>
                        <small class="text-muted">Enter vendor invoice details and restock raw materials into inventory with automatic weighted costing.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    {{-- Invoice Metadata --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold font-size-12 text-dark mb-1">Purchase Date <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-calendar"></i></span>
                                <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}" class="form-control border-start-0 font-size-13" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold font-size-12 text-dark mb-1">Supplier / Vendor Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-store"></i></span>
                                <input type="text" name="supplier" class="form-control border-start-0 font-size-13" placeholder="e.g. Flour Mills Ltd, Golden Penny">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold font-size-12 text-dark mb-1">Purchase Note / Invoice Reference</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-file"></i></span>
                                <input type="text" name="note" class="form-control border-start-0 font-size-13" placeholder="e.g. Invoice #4892, Paid via Transfer">
                            </div>
                        </div>
                    </div>

                    {{-- Dynamic Line Items Table --}}
                    <div class="table-responsive rounded border mb-3">
                        <table class="table table-bordered table-hover align-middle mb-0 font-size-13" id="purchaseItemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width: 280px;">Ingredient / Raw Material <span class="text-danger">*</span></th>
                                    <th style="min-width: 140px;">Quantity <span class="text-danger">*</span></th>
                                    <th style="min-width: 180px;">Purchase Unit <span class="text-danger">*</span></th>
                                    <th style="min-width: 160px;">Total Line Cost (₦) <span class="text-danger">*</span></th>
                                    <th class="text-center" style="width: 60px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <tr class="table-line-item-row" id="row-0">
                                    <td>
                                        <select name="items[0][ingredient_id]" class="form-select font-size-13" required>
                                            <option value="">-- Choose Raw Material --</option>
                                            @foreach($ingredients as $ingredient)
                                                <option value="{{ $ingredient->id }}">{{ $ingredient->name }} (Base: {{ $ingredient->baseUnit->symbol ?? 'unit' }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="0.001" min="0.001" name="items[0][quantity]" class="form-control font-size-13 item-qty-input" required placeholder="0.000">
                                    </td>
                                    <td>
                                        <select name="items[0][unit_id]" class="form-select font-size-13" required>
                                            <option value="">-- Select Unit --</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->symbol }} × {{ (float)$unit->base_multiplier }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light font-size-12">₦</span>
                                            <input type="number" step="0.01" min="0" name="items[0][total_price]" class="form-control font-size-13 item-cost-input" required placeholder="0.00" oninput="calculatePurchaseGrandTotal()">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" disabled title="First line item cannot be deleted">
                                            <i class="mdi mdi-trash-can-outline"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Actions & Grand Total Counter --}}
                    <div class="d-flex flex-wrap align-items-center justify-content-between p-3 bg-light rounded-3 border">
                        <div>
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" onclick="addRow()">
                                <i class="bx bx-plus me-1"></i> Add Another Line Item
                            </button>
                        </div>
                        <div class="text-end">
                            <span class="text-muted font-size-12 me-2">Estimated Total Spend:</span>
                            <span class="fw-bold text-success font-size-16" id="grandTotalDisplay">₦0.00</span>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="modal-footer bg-light border-top px-4 py-3 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 font-size-12" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 font-size-12 fw-medium shadow-sm">
                        <i class="bx bx-check-double me-1 font-size-14"></i> Save Stock Purchase
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
