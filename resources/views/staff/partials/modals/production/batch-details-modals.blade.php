{{-- REDESIGNED DETAIL MODALS --}}
@foreach($history as $record)
    @php
        $modalSalesUnit = $record->product->sales_unit ?? 'Units';
    @endphp
    <div class="modal fade" id="viewBatch{{ $record->id }}" tabindex="-1" aria-labelledby="batchTitle{{ $record->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 14px;">

                {{-- MODAL HEADER --}}
                <div class="modal-header bg-light border-bottom py-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="avatar-title bg-primary text-white rounded-circle p-2 font-size-18 d-inline-flex" style="width: 36px; height: 36px;">
                            <i class="mdi mdi-cookie"></i>
                        </span>
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0" id="batchTitle{{ $record->id }}">
                                {{ $record->product->name ?? 'Production Batch' }}
                            </h5>
                            <small class="text-muted font-size-12">Batch #{{ $record->id }} &bull; Produced on {{ $record->created_at->format('M d, Y | h:i A') }}</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- MODAL BODY --}}
                <div class="modal-body p-4">
                    <div class="p-3 bg-light rounded border mb-4 d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block text-uppercase font-size-11 fw-bold">Total Batch Yield</small>
                            <h4 class="fw-bold text-primary mb-0 font-size-18">{{ number_format($record->quantity) }} {{ $modalSalesUnit }}</h4>
                        </div>
                        <span class="badge bg-soft-success text-success font-size-12 px-3 py-2 fw-bold">
                            <i class="bx bx-check-circle me-1"></i> Recorded Successfully
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold text-dark mb-0 font-size-14">
                            <i class="bx bx-layer text-primary me-1"></i>Raw Materials Consumed
                        </h6>
                        <span class="badge bg-light text-secondary font-size-11">{{ $record->items->count() }} Ingredients</span>
                    </div>

                    <div class="table-responsive rounded border mb-3">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr class="font-size-11 text-uppercase text-muted">
                                    <th>Raw Material</th>
                                    <th class="text-end">Quantity Deducted</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($record->items as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark font-size-13">{{ $item->ingredient->name ?? 'Ingredient' }}</div>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-soft-secondary text-dark font-size-12 px-2 py-1">
                                                {{ number_format($item->quantity_used, 2) }} <small class="text-muted">g/ml</small>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($record->notes)
                        <div class="alert alert-light border rounded p-3 mb-0">
                            <div class="d-flex align-items-center gap-1 text-primary fw-bold font-size-12 mb-1">
                                <i class="bx bx-comment-detail"></i> Shift Notes:
                            </div>
                            <p class="text-dark font-size-13 mb-0 fst-italic">"{{ $record->notes }}"</p>
                        </div>
                    @endif
                </div>

                {{-- MODAL FOOTER --}}
                <div class="modal-footer bg-light border-top py-3 d-flex justify-content-between align-items-center">
                    <div class="text-start">
                        <small class="text-muted d-block text-uppercase" style="font-size: 10px;">Recorded By</small>
                        <span class="fw-bold text-dark font-size-13">{{ Auth::guard('staff')->user()->name ?? 'Staff' }}</span>
                    </div>
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>
@endforeach
