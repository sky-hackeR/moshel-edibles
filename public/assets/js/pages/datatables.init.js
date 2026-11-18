/**
 * Moshel DataTables Global Engine
 * Configures all application tables with idempotent initialization,
 * zero blocking alerts, and safe instance retrieval.
 */
(function ($) {
    'use strict';

    if (!$ || !$.fn || !$.fn.dataTable) {
        return;
    }

    // 1. Permanently silence all blocking browser alert dialogs (tn/3, tn/7, etc.)
    // Log to console instead of interrupting user experience with alert popups
    $.fn.dataTable.ext.errMode = 'none';

    // 2. Set global defaults across ALL tables in the application
    $.extend(true, $.fn.dataTable.defaults, {
        retrieve: true // Always return existing DataTable instance if already initialized
    });

    // 3. Safe idempotent initialization
    function initAllDataTables() {
        // Plain datatable
        if ($('#datatable').length) {
            try {
                if (!$.fn.DataTable.isDataTable('#datatable')) {
                    $('#datatable').DataTable({
                        retrieve: true
                    });
                }
            } catch (err) {
                console.warn('DataTable #datatable handled:', err);
            }
        }

        // Datatable with export buttons
        if ($('#datatable-buttons').length) {
            try {
                if (!$.fn.DataTable.isDataTable('#datatable-buttons')) {
                    var btnTable = $('#datatable-buttons').DataTable({
                        lengthChange: false,
                        retrieve: true,
                        buttons: ['copy', 'excel', 'pdf', 'colvis']
                    });
                    if (btnTable && btnTable.buttons) {
                        var $wrapperTarget = $('#datatable-buttons_wrapper .col-md-6:eq(0)');
                        if ($wrapperTarget.length) {
                            btnTable.buttons().container().appendTo($wrapperTarget);
                        }
                    }
                }
            } catch (err) {
                console.warn('DataTable #datatable-buttons handled:', err);
            }
        }

        // Style select dropdown
        $('.dataTables_length select').addClass('form-select form-select-sm');
    }

    // Initialize on DOM ready or immediately if already loaded
    if (document.readyState === 'loading') {
        $(document).ready(initAllDataTables);
    } else {
        initAllDataTables();
    }
})(window.jQuery);
