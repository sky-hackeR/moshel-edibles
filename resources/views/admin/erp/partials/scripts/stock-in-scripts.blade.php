{{-- =====================================================
     DYNAMIC ROW ADDITION & COST CALCULATION JAVASCRIPT
     ===================================================== --}}
<script>
let rowCount = 1;

function addRow() {
    const tbody = document.getElementById('itemsBody');
    const row = document.createElement('tr');
    row.className = 'table-line-item-row';
    row.id = `row-${rowCount}`;

    row.innerHTML = `
        <td>
            <select name="items[${rowCount}][ingredient_id]" class="form-select font-size-13" required>
                <option value="">-- Choose Raw Material --</option>
                @foreach($ingredients as $ingredient)
                    <option value="{{ $ingredient->id }}">{{ $ingredient->name }} (Base: {{ $ingredient->baseUnit->symbol ?? 'unit' }})</option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" step="0.001" min="0.001" name="items[${rowCount}][quantity]" class="form-control font-size-13 item-qty-input" required placeholder="0.000">
        </td>
        <td>
            <select name="items[${rowCount}][unit_id]" class="form-select font-size-13" required>
                <option value="">-- Select Unit --</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->symbol }} × {{ (float)$unit->base_multiplier }})</option>
                @endforeach
            </select>
        </td>
        <td>
            <div class="input-group">
                <span class="input-group-text bg-light font-size-12">₦</span>
                <input type="number" step="0.01" min="0" name="items[${rowCount}][total_price]" class="form-control font-size-13 item-cost-input" required placeholder="0.00" oninput="calculatePurchaseGrandTotal()">
            </div>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeRow(this)" title="Remove item">
                <i class="mdi mdi-trash-can-outline"></i>
            </button>
        </td>
    `;

    tbody.appendChild(row);
    rowCount++;
    calculatePurchaseGrandTotal();
}

function removeRow(button) {
    const row = button.closest('tr');
    if (row) {
        row.remove();
        calculatePurchaseGrandTotal();
    }
}

function calculatePurchaseGrandTotal() {
    let total = 0;
    const costInputs = document.querySelectorAll('.item-cost-input');
    costInputs.forEach(input => {
        const val = parseFloat(input.value) || 0;
        total += val;
    });

    const display = document.getElementById('grandTotalDisplay');
    if (display) {
        display.textContent = '₦' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
}
</script>
