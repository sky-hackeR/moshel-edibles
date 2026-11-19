@php
    $modalTitle = $modalTitle ?? 'Audited Sales Receipt';
    $detailsEndpoint = $detailsEndpoint ?? url('admin/sales/details');
    $customerName = $customerName ?? null;
    $receiptHeading = $receiptHeading ?? 'OFFICIAL SALES RECEIPT';
@endphp

{{-- SHARED THERMAL RECEIPT MODAL --}}
<div class="modal fade" id="saleDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom px-4 py-3 bg-light">
                <div class="d-flex align-items-center gap-2">
                    <span class="avatar-xs">
                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle"><i class="bx bx-receipt font-size-18"></i></span>
                    </span>
                    <h5 class="modal-title fw-bold text-dark mb-0">{{ $modalTitle }}</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4 thermal-receipt-wrapper">
                <div id="saleDetailsContent">
                    {{-- DYNAMIC THERMAL RECEIPT CONTAINER --}}
                </div>
            </div>

            <div class="modal-footer border-top px-4 py-3 bg-white d-flex justify-content-between">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success px-4 fw-bold" onclick="printReceipt()">
                    <i class="bx bx-printer me-1"></i> Print 80mm Receipt
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    async function viewSaleDetails(saleReference) {
        const modal = new bootstrap.Modal(document.getElementById('saleDetailsModal'));
        const content = document.getElementById('saleDetailsContent');
        
        content.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="text-muted small mt-2">Loading receipt details...</p></div>';
        modal.show();

        try {
            const endpoint = "{{ $detailsEndpoint }}/" + encodeURIComponent(saleReference);
            const response = await fetch(endpoint);
            const data = await response.json();

            if (data.success) {
                const sale = data.sale;
                let itemsHtml = '';
                sale.items.forEach(item => {
                    const lineTotal = parseFloat(item.quantity) * parseFloat(item.unit_price);
                    itemsHtml += `
                        <tr>
                            <td style="padding: 4px 0;">
                                <div style="font-weight: 700;">${item.product_name}</div>
                                <div style="font-size: 11px; color: #555;">${item.quantity} × ₦${parseFloat(item.unit_price).toLocaleString(undefined, {minimumFractionDigits: 2})}</div>
                            </td>
                            <td style="text-align: right; padding: 4px 0; font-weight: 700;">₦${lineTotal.toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                        </tr>`;
                });

                const subtotal = parseFloat(sale.total_amount || 0);
                const discount = parseFloat(sale.discount_amount || 0);
                const shipping = parseFloat(sale.shipping_fee || 0);
                const payable = parseFloat(sale.payable_amount || 0);
                const customerDisplay = @json($customerName) || sale.customer_name || 'Walk-in Customer';

                content.innerHTML = `
                    <div class="thermal-paper" id="thermalPrintContent">
                        <div style="text-align: center; margin-bottom: 8px;">
                            <h5 style="font-weight: 900; margin-bottom: 2px; text-transform: uppercase;">{{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->site_name : "MOSHEL FOOD PRODUCTION" }}</h5>
                            <div style="font-size: 11px; color: #444; line-height: 1.3;">
                                {{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->address : "Sales & Retail Operations" }}<br>
                                Tel: {{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->phone : "+234 800 000 0000" }}
                            </div>
                        </div>

                        <div class="receipt-dashed-line"></div>

                        <div style="text-align: center;">
                            <div style="font-weight: 800; font-size: 13px; letter-spacing: 1px;">{{ $receiptHeading }}</div>
                            <div class="receipt-barcode"></div>
                            <div style="font-size: 12px; font-weight: bold; letter-spacing: 0.5px;">${sale.reference_no}</div>
                        </div>

                        <div class="receipt-dashed-line"></div>

                        <div style="font-size: 11px; line-height: 1.5;">
                            <div style="display: flex; justify-content: space-between;">
                                <span>DATE & TIME:</span>
                                <strong>${sale.created_at}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>CUSTOMER:</span>
                                <strong>${customerDisplay}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>PAYMENT METHOD:</span>
                                <strong>${sale.payment_method || 'Cash'}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>SETTLEMENT:</span>
                                <strong>${(sale.payment_status || 'PAID').toUpperCase()}</strong>
                            </div>
                        </div>

                        <div class="receipt-dashed-line"></div>

                        <table style="width: 100%; border-collapse: collapse; font-size: 11.5px;">
                            <thead>
                                <tr style="border-bottom: 1px dashed #000;">
                                    <th style="text-align: left; padding-bottom: 4px;">ITEM</th>
                                    <th style="text-align: right; padding-bottom: 4px;">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${itemsHtml}
                            </tbody>
                        </table>

                        <div class="receipt-dashed-line"></div>

                        <div style="font-size: 12px; line-height: 1.5;">
                            <div style="display: flex; justify-content: space-between;">
                                <span>SUBTOTAL:</span>
                                <span>₦${subtotal.toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                            </div>
                            ${discount > 0 ? `
                            <div style="display: flex; justify-content: space-between; color: #b91c1c;">
                                <span>DISCOUNT APPLIED:</span>
                                <span>-₦${discount.toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                            </div>` : ''}
                            ${shipping > 0 ? `
                            <div style="display: flex; justify-content: space-between;">
                                <span>SHIPPING FEE:</span>
                                <span>₦${shipping.toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                            </div>` : ''}
                            <div class="receipt-double-line"></div>
                            <div style="display: flex; justify-content: space-between; font-size: 15px; font-weight: 900;">
                                <span>TOTAL PAID:</span>
                                <span>₦${payable.toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                            </div>
                        </div>

                        <div class="receipt-dashed-line"></div>
                        <div style="text-align: center; font-size: 11px; color: #333; line-height: 1.4;">
                            <p style="margin-bottom: 2px; font-weight: 700;">Thank you for your patronage!</p>
                            <p style="margin-bottom: 0;">Freshly baked & produced with excellence.</p>
                        </div>
                    </div>`;
            } else {
                content.innerHTML = `<div class="alert alert-danger m-3">${data.message || 'Transaction details not found.'}</div>`;
            }
        } catch (error) {
            content.innerHTML = `<div class="alert alert-danger m-3">Network error fetching transaction data.</div>`;
        }
    }

    function printReceipt() {
        const printWindow = window.open('', '_blank', 'width=420,height=650');
        const content = document.getElementById('thermalPrintContent').outerHTML;
        
        printWindow.document.write(`
            <!doctype html>
            <html>
                <head>
                    <title>Thermal Receipt</title>
                    <style>
                        body {
                            margin: 0;
                            padding: 10px;
                            background: #ffffff;
                            color: #000000;
                            font-family: 'Courier New', Courier, monospace;
                            width: 80mm;
                            margin: 0 auto;
                        }
                        .thermal-paper {
                            width: 100%;
                            box-shadow: none;
                            border: none;
                            padding: 0;
                        }
                        table { width: 100%; border-collapse: collapse; }
                        @media print {
                            @page {
                                size: 80mm auto;
                                margin: 0;
                            }
                            body {
                                width: 80mm;
                                padding: 6px;
                            }
                        }
                        .receipt-dashed-line {
                            border-top: 1px dashed #000;
                            margin: 8px 0;
                        }
                        .receipt-double-line {
                            border-top: 3px double #000;
                            margin: 8px 0;
                        }
                        .receipt-barcode {
                            height: 30px;
                            background: repeating-linear-gradient(to right, #000, #000 2px, #fff 2px, #fff 4px, #000 4px, #000 5px, #fff 5px, #fff 8px);
                            margin: 6px auto;
                            width: 70%;
                        }
                    </style>
                </head>
                <body onload="window.print(); window.close();">
                    ${content}
                </body>
            </html>
        `);
        printWindow.document.close();
    }
</script>
