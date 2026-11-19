{{-- =====================================================
     DYNAMIC INTERACTIVE SIMULATORS JAVASCRIPT
     ===================================================== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ----------------------------------------------------
    // Product Opening Stock Live Simulator
    // ----------------------------------------------------
    const productSelect = document.getElementById('product_id');
    const productQtyInput = document.getElementById('product_quantity');
    const productUnitBadge = document.getElementById('product_unit_badge');
    const previewProductName = document.getElementById('preview_product_name');
    const previewCurrentStock = document.getElementById('preview_current_stock');
    const previewAddingStock = document.getElementById('preview_adding_stock');
    const previewProductPrice = document.getElementById('preview_product_price');
    const previewProductValuation = document.getElementById('preview_product_valuation');
    const previewProjectedStock = document.getElementById('preview_projected_stock');

    function updateProductSimulator() {
        if (!productSelect || !productSelect.value) {
            previewProductName.textContent = 'No product selected';
            previewCurrentStock.textContent = '—';
            previewAddingStock.textContent = '—';
            if (previewProductPrice) previewProductPrice.textContent = '—';
            if (previewProductValuation) previewProductValuation.textContent = '—';
            previewProjectedStock.textContent = '—';
            productUnitBadge.textContent = 'Units';
            return;
        }

        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const name = selectedOption.getAttribute('data-name') || 'Selected Product';
        const currentStock = parseFloat(selectedOption.getAttribute('data-stock') || 0);
        const unit = selectedOption.getAttribute('data-unit') || 'Units';
        const price = parseFloat(selectedOption.getAttribute('data-price') || 0);
        const addingQty = parseFloat(productQtyInput.value || 0);
        const projected = currentStock + (isNaN(addingQty) ? 0 : addingQty);
        const addedVal = (isNaN(addingQty) ? 0 : addingQty) * price;

        productUnitBadge.textContent = unit;
        previewProductName.textContent = name;
        previewCurrentStock.textContent = currentStock.toLocaleString() + ' ' + unit;
        previewAddingStock.textContent = (isNaN(addingQty) ? 0 : addingQty).toLocaleString() + ' ' + unit;
        if (previewProductPrice) previewProductPrice.textContent = '₦' + price.toLocaleString(undefined, {minimumFractionDigits: 2});
        if (previewProductValuation) previewProductValuation.textContent = '₦' + addedVal.toLocaleString(undefined, {minimumFractionDigits: 2});
        previewProjectedStock.textContent = projected.toLocaleString() + ' ' + unit;
    }

    if (productSelect) productSelect.addEventListener('change', updateProductSimulator);
    if (productQtyInput) productQtyInput.addEventListener('input', updateProductSimulator);

    // ----------------------------------------------------
    // Ingredient Opening Stock Live Costing Simulator
    // ----------------------------------------------------
    const ingredientSelect = document.getElementById('ingredient_id');
    const ingredientQtyInput = document.getElementById('ingredient_quantity');
    const ingredientValInput = document.getElementById('ingredient_total_value');
    const ingredientUnitBadge = document.getElementById('ingredient_unit_badge');
    const ingredientUnitHelp = document.getElementById('ingredient_unit_help');
    const previewIngName = document.getElementById('preview_ingredient_name');
    const previewIngQty = document.getElementById('preview_ing_quantity');
    const previewIngVal = document.getElementById('preview_ing_value');
    const previewCalculatedCost = document.getElementById('preview_calculated_unit_cost');

    function updateIngredientSimulator() {
        if (!ingredientSelect || !ingredientSelect.value) {
            previewIngName.textContent = 'No ingredient selected';
            previewIngQty.textContent = '—';
            previewIngVal.textContent = '—';
            previewCalculatedCost.textContent = '₦0.00 / unit';
            ingredientUnitBadge.textContent = 'Base Units';
            ingredientUnitHelp.textContent = 'Select an ingredient first to see its base unit.';
            return;
        }

        const selectedOption = ingredientSelect.options[ingredientSelect.selectedIndex];
        const name = selectedOption.getAttribute('data-name') || 'Ingredient';
        const unit = selectedOption.getAttribute('data-unit') || 'unit';
        const unitName = selectedOption.getAttribute('data-unitname') || 'Base Unit';
        const qty = parseFloat(ingredientQtyInput.value || 0);
        const totalVal = parseFloat(ingredientValInput.value || 0);
        const unitCost = qty > 0 && totalVal > 0 ? (totalVal / qty) : 0;

        ingredientUnitBadge.textContent = unit;
        ingredientUnitHelp.innerHTML = 'Enter the quantity in <strong>' + unit + '</strong> (' + unitName + '), the base measurement standard.';
        previewIngName.textContent = name;
        previewIngQty.textContent = (isNaN(qty) ? 0 : qty).toLocaleString() + ' ' + unit;
        previewIngVal.textContent = '₦' + (isNaN(totalVal) ? 0 : totalVal).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        previewCalculatedCost.textContent = '₦' + unitCost.toLocaleString(undefined, {minimumFractionDigits: 4, maximumFractionDigits: 4}) + ' / ' + unit;
    }

    if (ingredientSelect) ingredientSelect.addEventListener('change', updateIngredientSimulator);
    if (ingredientQtyInput) ingredientQtyInput.addEventListener('input', updateIngredientSimulator);
    if (ingredientValInput) ingredientValInput.addEventListener('input', updateIngredientSimulator);
});
</script>
