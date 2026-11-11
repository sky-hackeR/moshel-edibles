@extends('admin.layout.dashboard')

@section('content')
<style>
    /* 1. Layout Fix: Prevent double scrollbars and fit to frame */
    .pos-wrapper {
        display: flex;
        height: calc(100vh - 150px); 
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #eee;
    }

    /* 2. Product Side (Fluid) */
    .product-section {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: #f8f9fb;
    }

    /* 3. Cart Side (Fixed Width but proportional) */
    .cart-section {
        width: 380px;
        border-left: 1px solid #eee;
        display: flex;
        flex-direction: column;
        background: #fff;
    }

    /* 4. Product Cards: Clean & Pro */
    .product-card {
        background: #fff;
        border: 1px solid #eaeaea;
        border-radius: 10px;
        transition: all 0.2s;
        cursor: pointer;
    }
    .product-card:hover {
        border-color: #556ee6;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    /* 5. Cart Items Styling */
    .cart-item-row {
        display: flex;
        align-items: center;
        padding: 12px;
        border-bottom: 1px solid #f1f1f1;
    }
    .qty-box {
        display: flex;
        align-items: center;
        background: #f3f3f9;
        border-radius: 6px;
        padding: 2px;
    }
    .qty-btn {
        width: 24px;
        height: 24px;
        border: none;
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        font-weight: bold;
        line-height: 1;
    }

    .custom-scroll::-webkit-scrollbar { width: 5px; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #e2e2e2; border-radius: 10px; }
</style>
{{-- PAGE HEADER --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Sales & POS</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">POS</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="pos-wrapper shadow-sm">
    {{-- LEFT: PRODUCTS --}}
    <div class="product-section custom-scroll">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold m-0 text-dark">Inventory</h5>
            <input type="text" id="productSearch" class="form-control form-control-sm w-25 border-0 shadow-sm" placeholder="Search products...">
        </div>

        <div class="row g-3">
            @foreach($products as $product)
            <div class="col-md-4 col-lg-3 product-item" data-name="{{ strtolower($product->name) }}">
                <div class="card h-100 product-card border-0 mb-0" 
                     onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->selling_price }}, {{ $product->stock_on_hand }})">
                    <div class="card-body p-3">
                        <div class="text-primary mb-2">
                             <span class="badge bg-soft-primary text-primary">₦{{ number_format($product->selling_price, 0) }}</span>
                        </div>
                        <h6 class="fw-bold text-dark text-truncate mb-1">{{ $product->name }}</h6>
                        <small class="text-muted">In Stock: {{ $product->stock_on_hand }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- RIGHT: CART --}}
    <div class="cart-section">
        <div class="p-3 border-bottom">
            <h6 class="fw-bold m-0">Current Order</h6>
        </div>

        <div class="flex-grow-1 custom-scroll" id="cartContainer" style="overflow-y: auto;">
            <div id="emptyMsg" class="text-center mt-5 text-muted">
                <p>No items added yet</p>
            </div>
            <div id="cartList"></div>
        </div>

        <div class="p-3 bg-light border-top">
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted small">Subtotal</span>
                <span class="fw-bold" id="subtotalLabel">₦0</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted small">Discount (₦)</span>
                <input type="number" id="discountInput" class="form-control form-control-sm w-25 text-end border-0" value="0" oninput="calculateTotals()">
            </div>
            <hr>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="h6 fw-bold m-0">Total</span>
                <span class="h5 fw-bold text-primary m-0" id="totalLabel">₦0</span>
            </div>
            <button class="btn btn-primary w-100 py-2 fw-bold" id="checkoutBtn" onclick="openCheckout()" disabled>
                CHECKOUT
            </button>
        </div>
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="checkoutModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow">
            <div class="modal-body p-4">
                <h6 class="text-center text-muted fw-bold mb-3">PAYMENT METHOD</h6>
                <div class="d-grid gap-2 mb-3">
                    <input type="radio" class="btn-check" name="payMethod" id="mCash" value="Cash" checked>
                    <label class="btn btn-outline-primary py-2" for="mCash">Cash</label>
                    
                    <input type="radio" class="btn-check" name="payMethod" id="mTransfer" value="Transfer">
                    <label class="btn btn-outline-primary py-2" for="mTransfer">Transfer</label>

                    <input type="radio" class="btn-check" name="payMethod" id="mCard" value="Card">
                    <label class="btn btn-outline-primary py-2" for="mCard">Card / POS</label>
                </div>

                <div class="mb-3">
                    <label class="small text-muted fw-bold">SALE NOTES</label>
                    <textarea id="saleNotes" class="form-control bg-light border-0" rows="2" placeholder="Optional notes..."></textarea>
                </div>

                <button class="btn btn-success w-100 py-2 fw-bold" id="confirmBtn" onclick="submitSale()">Confirm & Print</button>
            </div>
        </div>
    </div>
</div>

<script>
    let cart = [];

    // Search Fix
    document.getElementById('productSearch').addEventListener('input', function() {
        let q = this.value.toLowerCase();
        document.querySelectorAll('.product-item').forEach(el => {
            el.style.display = el.dataset.name.includes(q) ? 'block' : 'none';
        });
    });

    function addToCart(id, name, price, stock) {
        let existing = cart.find(i => i.id === id);
        
        if (existing) {
            if (existing.qty + 1 > stock) {
                Swal.fire({ icon: 'warning', title: 'Limit reached', text: 'Not enough stock', toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
                return;
            }
            existing.qty++;
        } else {
            if (stock < 1) {
                Swal.fire({ icon: 'error', title: 'Out of stock', toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
                return;
            }
            cart.push({ id, name, price, qty: 1 });
        }
        renderCart();
    }

    function updateQty(index, newQty) {
        if (newQty < 1) {
            cart.splice(index, 1);
        } else {
            cart[index].qty = newQty;
        }
        renderCart();
    }

    function renderCart() {
        const list = document.getElementById('cartList');
        const empty = document.getElementById('emptyMsg');
        const btn = document.getElementById('checkoutBtn');
        
        list.innerHTML = '';
        
        if (cart.length === 0) {
            empty.style.display = 'block';
            btn.disabled = true;
        } else {
            empty.style.display = 'none';
            btn.disabled = false;
            
            cart.forEach((item, index) => {
                list.innerHTML += `
                <div class="cart-item-row">
                    <div class="flex-grow-1">
                        <div class="fw-bold text-dark small">${item.name}</div>
                        <div class="text-muted small">₦${item.price.toLocaleString()}</div>
                    </div>
                    <div class="qty-box">
                        <button class="qty-btn text-danger" onclick="updateQty(${index}, ${item.qty - 1})">-</button>
                        <span class="mx-2 medium fw-bold">${item.qty}</span>
                        <button class="qty-btn text-success" onclick="updateQty(${index}, ${item.qty + 1})">+</button>
                    </div>
                </div>`;
            });
        }
        calculateTotals();
    }

    function calculateTotals() {
        let sub = cart.reduce((acc, item) => acc + (item.price * item.qty), 0);
        let disc = parseFloat(document.getElementById('discountInput').value) || 0;
        let total = Math.max(0, sub - disc);
        
        document.getElementById('subtotalLabel').innerText = '₦' + sub.toLocaleString();
        document.getElementById('totalLabel').innerText = '₦' + total.toLocaleString();
    }

    function openCheckout() {
        new bootstrap.Modal(document.getElementById('checkoutModal')).show();
    }

    async function submitSale() {
        const btn = document.getElementById('confirmBtn');
        btn.disabled = true;
        btn.innerText = 'Processing...';

        const sub = cart.reduce((a, i) => a + (i.price * i.qty), 0);
        const disc = parseFloat(document.getElementById('discountInput').value) || 0;
        const note = document.getElementById('saleNotes').value;

        try {
            const response = await fetch('{{ url("/admin/processSale") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({
                    payment_method: document.querySelector('input[name="payMethod"]:checked').value,
                    cart_items: cart.map(i => ({ product_id: i.id, quantity: i.qty, price: i.price })),
                    total_amount: sub,
                    discount_amount: disc,
                    payable_amount: sub - disc,
                    notes: note
                })
            });

            const res = await response.json();
            if (res.success) {
                Swal.fire({ icon: 'success', title: 'Sale Completed', text: res.message, timer: 1500, showConfirmButton: false })
                    .then(() => location.reload());
            } else {
                Swal.fire({ icon: 'error', title: 'Failed', text: res.message });
                btn.disabled = false;
                btn.innerText = 'Confirm & Print';
            }
        } catch (e) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'System Error' });
            btn.disabled = false;
            btn.innerText = 'Confirm & Print';
        }
    }
</script>
@endsection