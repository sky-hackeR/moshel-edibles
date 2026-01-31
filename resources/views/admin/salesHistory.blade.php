@extends('admin.layout.dashboard')

@section('content')

{{-- PAGE HEADER --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Sales & POS</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Sales History</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Sales History</h4>
                
                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Reference</th>
                            <th>Staff</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                        <tr>
                            <td>{{ $sale->created_at->format('d M, Y H:i') }}</td>
                            <td><span class="fw-bold text-primary">{{ $sale->reference_no }}</span></td>
                            {{-- This now displays the correct Admin or Staff name --}}
                            <td>{{ $sale->seller_name }}</td>
                            <td>₦{{ number_format($sale->payable_amount, 2) }}</td>
                            <td>
                                @php
                                    $color = ['Cash' => 'success', 'Transfer' => 'info', 'Card' => 'warning'][$sale->payment_method] ?? 'secondary';
                                @endphp
                                <span class="badge bg-soft-{{ $color }} text-{{ $color }}">{{ $sale->payment_method }}</span>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm" onclick="viewSaleDetails({{ $sale->id }})">
                                    <i class="mdi mdi-eye me-1"></i> View
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="saleDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Transaction Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="saleDetailsContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="printReceipt()">
                    <i class="mdi mdi-printer me-1"></i> Print Receipt
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    async function viewSaleDetails(saleId) {
        const modal = new bootstrap.Modal(document.getElementById('saleDetailsModal'));
        const content = document.getElementById('saleDetailsContent');
        
        content.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';
        modal.show();

        try {
            // Updated fetch URL to use absolute path
            const response = await fetch("{{ url('admin/sales/details') }}/" + saleId);
            const data = await response.json();

            if (data.success) {
                let itemsHtml = '';
                data.sale.items.forEach(item => {
                    itemsHtml += `
                        <tr>
                            <td class="ps-0">
                                <h6 class="mb-0">${item.product_name}</h6>
                                <small class="text-muted">${item.quantity} x ₦${parseFloat(item.unit_price).toLocaleString()}</small>
                            </td>
                            <td class="text-end pe-0">₦${(item.quantity * item.unit_price).toLocaleString()}</td>
                        </tr>`;
                });

                content.innerHTML = `
                    <div class="p-4">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold mb-0">RECEIPT</h4>
                            <small class="text-muted">${data.sale.reference_no}</small>
                        </div>
                        <div class="d-flex justify-content-between mb-3 small">
                            <span><strong>Date:</strong> ${data.sale.created_at}</span>
                            <span><strong>Staff:</strong> ${data.sale.staff_name}</span>
                        </div>
                        <table class="table table-sm table-borderless">
                            <thead class="border-bottom small text-uppercase">
                                <tr><th>Item</th><th class="text-end">Total</th></tr>
                            </thead>
                            <tbody>${itemsHtml}</tbody>
                        </table>
                        <div class="border-top pt-3 mt-2">
                            <div class="d-flex justify-content-between small"><span>Subtotal:</span><span>₦${parseFloat(data.sale.total_amount).toLocaleString()}</span></div>
                            <div class="d-flex justify-content-between small"><span>Discount:</span><span class="text-danger">-₦${parseFloat(data.sale.discount_amount).toLocaleString()}</span></div>
                            <div class="d-flex justify-content-between fw-bold h5 mt-2"><span>Total:</span><span class="text-primary">₦${parseFloat(data.sale.payable_amount).toLocaleString()}</span></div>
                        </div>
                        <div class="mt-3 p-2 bg-light rounded text-center small">
                            Payment Method: <strong>${data.sale.payment_method}</strong>
                        </div>
                    </div>`;
            } else {
                content.innerHTML = `<div class="alert alert-danger m-3">${data.message}</div>`;
            }
        } catch (error) {
            content.innerHTML = `<div class="alert alert-danger m-3">Error fetching transaction data.</div>`;
        }
    }

    function printReceipt() {
        const content = document.getElementById('saleDetailsContent').innerHTML;
        const printWindow = window.open('', '', 'height=600,width=400');
        printWindow.document.write('<html><head><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"></head><body onload="window.print(); window.close();">');
        printWindow.document.write(content);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
    }
</script>
@endsection