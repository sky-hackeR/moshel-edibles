<script>
(function() {
    // Expose all units cleanly to JavaScript
    var ALL_UNITS = @json($units);

    function initUnitManagementEngine() {
        if (typeof $ === 'undefined') {
            setTimeout(initUnitManagementEngine, 50);
            return;
        }

        $(document).ready(function() {
            // 1. DATA TABLE INITIALIZATION / FILTERING
            function getUnitTable() {
                if (window.$ && $.fn.DataTable && $('#datatable-buttons').length) {
                    return $('#datatable-buttons').DataTable();
                }
                return null;
            }

            $('.unit-filter-btn').on('click', function() {
                $('.unit-filter-btn').removeClass('active');
                $(this).addClass('active');

                var filter = $(this).data('filter');
                var unitTable = getUnitTable();
                if (unitTable) {
                    if (filter === 'all') {
                        unitTable.column(2).search('').draw();
                    } else {
                        unitTable.column(2).search(filter).draw();
                    }
                }
            });

            // 2. DRAGGABLE CALCULATOR WIDGET CONTROLS
            var $calc = $('#floatingCalculatorWidget');
            var isMinimized = false;

            function openCalculator(unitId) {
                $calc.fadeIn(180);
                if (isMinimized) {
                    $('#calcBodyContent').show();
                    $('#calcMinimizeBtn').find('i').removeClass('bx-plus').addClass('bx-minus');
                    isMinimized = false;
                }
                if (unitId) {
                    $('#calcFromUnit').val(unitId).trigger('change');
                }
            }

            function toggleCalculator() {
                if ($calc.is(':visible')) {
                    $calc.fadeOut(150);
                } else {
                    openCalculator();
                }
            }

            $('#btnToggleFloatingCalculator').on('click', function(e) {
                e.preventDefault();
                toggleCalculator();
            });

            $('#calcCloseBtn').on('click', function(e) {
                e.preventDefault();
                $calc.fadeOut(150);
            });

            $('#calcMinimizeBtn').on('click', function(e) {
                e.preventDefault();
                isMinimized = !isMinimized;
                $('#calcBodyContent').slideToggle(150);
                $(this).find('i').toggleClass('bx-minus bx-plus');
            });

            // Shortcut button from table rows
            $('.btn-load-floating-calc').on('click', function(e) {
                e.preventDefault();
                var unitId = $(this).data('id');
                openCalculator(unitId);
            });

            // 3. DRAG FUNCTIONALITY (MOUSE & TOUCH)
            (function setupDragging() {
                var calcElem = document.getElementById('floatingCalculatorWidget');
                var handleElem = calcElem ? calcElem.querySelector('.calc-drag-handle') : null;
                if (!calcElem || !handleElem) return;

                var isDragging = false;
                var startX = 0, startY = 0;
                var initialLeft = 0, initialTop = 0;

                function dragStart(e) {
                    // Don't drag if clicking buttons
                    if (e.target.closest('button') || e.target.closest('a')) return;

                    isDragging = true;
                    handleElem.style.cursor = 'grabbing';

                    var pageX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
                    var pageY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;

                    startX = pageX;
                    startY = pageY;

                    var rect = calcElem.getBoundingClientRect();
                    initialLeft = rect.left;
                    initialTop = rect.top;

                    // Switch from right-anchored to absolute left/top
                    calcElem.style.right = 'auto';
                    calcElem.style.bottom = 'auto';
                    calcElem.style.left = initialLeft + 'px';
                    calcElem.style.top = initialTop + 'px';

                    document.addEventListener('mousemove', dragMove);
                    document.addEventListener('mouseup', dragEnd);
                    document.addEventListener('touchmove', dragMove, { passive: false });
                    document.addEventListener('touchend', dragEnd);
                }

                function dragMove(e) {
                    if (!isDragging) return;
                    if (e.cancelable) e.preventDefault();

                    var pageX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
                    var pageY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;

                    var dx = pageX - startX;
                    var dy = pageY - startY;

                    var newLeft = initialLeft + dx;
                    var newTop = initialTop + dy;

                    var maxLeft = window.innerWidth - calcElem.offsetWidth - 12;
                    var maxTop = window.innerHeight - calcElem.offsetHeight - 12;

                    newLeft = Math.max(12, Math.min(newLeft, maxLeft));
                    newTop = Math.max(12, Math.min(newTop, maxTop));

                    calcElem.style.left = newLeft + 'px';
                    calcElem.style.top = newTop + 'px';
                }

                function dragEnd() {
                    if (!isDragging) return;
                    isDragging = false;
                    handleElem.style.cursor = 'grab';

                    document.removeEventListener('mousemove', dragMove);
                    document.removeEventListener('mouseup', dragEnd);
                    document.removeEventListener('touchmove', dragMove);
                    document.removeEventListener('touchend', dragEnd);
                }

                handleElem.addEventListener('mousedown', dragStart);
                handleElem.addEventListener('touchstart', dragStart, { passive: false });
            })();

            // 4. CONVERSION CALCULATOR MATHEMATICAL LOGIC
            var dimensionPresets = {
                mass: [
                    { label: '1 kg', val: 1 },
                    { label: '5 kg', val: 5 },
                    { label: '10 kg', val: 10 },
                    { label: '25 kg Bag', val: 25 },
                    { label: '50 kg Bag', val: 50 },
                    { label: '500 g', val: 0.5 }
                ],
                volume: [
                    { label: '1 tsp', val: 1 },
                    { label: '1 tbsp', val: 1 },
                    { label: '1 Cup', val: 1 },
                    { label: '1 Litre', val: 1 },
                    { label: '5 Litres', val: 5 },
                    { label: '25L Keg', val: 25 }
                ],
                count: [
                    { label: '1 pc', val: 1 },
                    { label: 'Half Doz (6)', val: 6 },
                    { label: '1 Dozen', val: 12 },
                    { label: 'Carton 24', val: 24 },
                    { label: 'Egg Crate 30', val: 30 }
                ]
            };

            function syncTargetUnitsDropdown() {
                var fromId = $('#calcFromUnit').val();
                var fromUnit = ALL_UNITS.find(function(u) { return u.id == fromId; });
                if (!fromUnit) return;

                var prevToId = $('#calcToUnit').val();
                var compatible = ALL_UNITS.filter(function(u) { return u.unit_type === fromUnit.unit_type; });

                var html = '';
                compatible.forEach(function(u) {
                    var isSelected = false;
                    if (prevToId && u.id == prevToId) {
                        isSelected = true;
                    } else if (!prevToId && u.symbol === fromUnit.base_unit) {
                        isSelected = true;
                    }
                    html += '<option value="' + u.id + '" ' + (isSelected ? 'selected' : '') + '>' + u.name + ' (' + u.symbol + ')</option>';
                });
                $('#calcToUnit').html(html);

                // If previously selected wasn't compatible, pick the base unit
                if (!$('#calcToUnit').val()) {
                    var baseU = compatible.find(function(u) { return u.symbol === fromUnit.base_unit; });
                    if (baseU) $('#calcToUnit').val(baseU.id);
                }

                // Update quick presets strip
                updateCalculatorPresets(fromUnit.unit_type);

                recalculateConversion();
            }

            function updateCalculatorPresets(dimType) {
                var list = dimensionPresets[dimType] || [];
                var html = '';
                list.forEach(function(p) {
                    html += '<button type="button" class="btn btn-sm calc-preset-btn btn-calc-preset" data-val="' + p.val + '">' + p.label + '</button>';
                });
                $('#calcQuickPresets').html(html);

                var dimLabels = { mass: 'Mass Presets', volume: 'Liquid / Volume Presets', count: 'Discrete / Count Presets' };
                $('#calcPresetDimNote').text(dimLabels[dimType] || 'Presets');
            }

            function recalculateConversion() {
                var qty = parseFloat($('#calcInputQty').val());
                if (isNaN(qty) || qty <= 0) qty = 0;

                var fromId = $('#calcFromUnit').val();
                var toId = $('#calcToUnit').val();

                var fromUnit = ALL_UNITS.find(function(u) { return u.id == fromId; });
                var toUnit = ALL_UNITS.find(function(u) { return u.id == toId; });

                if (!fromUnit || !toUnit) return;

                // 1. Convert source into base units
                var inBase = qty * parseFloat(fromUnit.base_multiplier);

                // 2. Convert base into target unit
                var targetMult = parseFloat(toUnit.base_multiplier) || 1;
                var result = inBase / targetMult;

                // 3. Render outputs in clear black and muted format
                var formattedResult = result.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 4
                });

                $('#calcOutputValue').text(formattedResult);
                $('#calcOutputUnit').text(toUnit.name + ' (' + toUnit.symbol + ')');

                // Base standard row
                var baseStandardLabel = fromUnit.base_unit === 'g' ? 'Grams (g)' : (fromUnit.base_unit === 'ml' ? 'Millilitres (ml)' : 'Pieces (pcs)');
                var formattedBase = inBase.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 4
                }) + ' ' + fromUnit.base_unit;
                $('#calcBaseEquivalentVal').text(formattedBase);

                // Step tag
                $('#calcFormulaTag').text(qty + ' ' + fromUnit.symbol + ' → ' + toUnit.symbol);
            }

            // Events for calculator
            $('#calcInputQty').on('input keyup change', recalculateConversion);
            $('#calcFromUnit').on('change', syncTargetUnitsDropdown);
            $('#calcToUnit').on('change', recalculateConversion);

            // Stepper buttons
            $('.btn-qty-step').on('click', function() {
                var step = parseFloat($(this).data('step')) || 1;
                var cur = parseFloat($('#calcInputQty').val()) || 0;
                var next = Math.max(0.0001, cur + step);
                $('#calcInputQty').val(next).trigger('change');
            });

            // AC Clear button
            $('#calcClearBtn').on('click', function() {
                $('#calcInputQty').val(1).trigger('change');
            });

            // Swap units button
            $('#calcSwapBtn').on('click', function() {
                var fromId = $('#calcFromUnit').val();
                var toId = $('#calcToUnit').val();
                if (fromId && toId && fromId !== toId) {
                    $('#calcFromUnit').val(toId);
                    syncTargetUnitsDropdown();
                    $('#calcToUnit').val(fromId);
                    recalculateConversion();
                }
            });

            // Quick preset click
            $(document).on('click', '.btn-calc-preset', function() {
                var val = $(this).data('val');
                $('#calcInputQty').val(val).trigger('change');
            });

            // Initial calculator sync
            syncTargetUnitsDropdown();

            // 5. ADD UNIT MODAL PRESETS & LIVE PREVIEW
            var addPresets = {
                mass: [
                    { label: '1 kg', val: 1000 },
                    { label: '5 kg Bag', val: 5000 },
                    { label: '10 kg Bag', val: 10000 },
                    { label: '25 kg Bag', val: 25000 },
                    { label: '50 kg Bag', val: 50000 }
                ],
                volume: [
                    { label: '1 tsp (5ml)', val: 5 },
                    { label: '1 tbsp (15ml)', val: 15 },
                    { label: '1 Cup (250ml)', val: 250 },
                    { label: '1 Litre', val: 1000 },
                    { label: '5L Gallon', val: 5000 },
                    { label: '25L Keg', val: 25000 }
                ],
                count: [
                    { label: 'Half Dozen (6)', val: 6 },
                    { label: 'Dozen (12)', val: 12 },
                    { label: 'Carton 24', val: 24 },
                    { label: 'Egg Crate (30)', val: 30 },
                    { label: 'Gross (144)', val: 144 }
                ]
            };

            function updateAddModalPresets() {
                var type = $('#addUnitType').val();
                var baseMapping = { mass: 'g', volume: 'ml', count: 'pcs' };
                var base = baseMapping[type] || 'g';

                $('#addBaseUnitPreview').text(base);
                $('#addEqBase').text(base);

                var list = addPresets[type] || [];
                var html = '';
                list.forEach(function(p) {
                    html += '<button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 rounded-pill font-size-11 btn-preset-val" data-val="' + p.val + '">' + p.label + '</button>';
                });
                $('#addPresetsContainer').html(html);

                updateAddEquationPreview();
            }

            function updateAddEquationPreview() {
                var sym = $('#addUnitSymbol').val().trim() || 'unit';
                var mult = parseFloat($('#addBaseMultiplier').val()) || 0;
                var type = $('#addUnitType').val();
                var baseMapping = { mass: 'g', volume: 'ml', count: 'pcs' };

                $('#addEqSym').text(sym);
                $('#addEqMult').text(mult.toLocaleString());
                $('#addEqBase').text(baseMapping[type] || 'g');
            }

            $('#addUnitType').on('change', updateAddModalPresets);
            $('#addUnitSymbol').on('input', updateAddEquationPreview);
            $('#addBaseMultiplier').on('input', updateAddEquationPreview);

            $(document).on('click', '.btn-preset-val', function() {
                var val = $(this).data('val');
                $('#addBaseMultiplier').val(val).trigger('input');
            });

            updateAddModalPresets();
        });
    }

    initUnitManagementEngine();
})();
</script>
