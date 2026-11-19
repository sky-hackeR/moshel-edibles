@php
    $admin = Auth::guard('admin')->user();
@endphp
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->site_name : "Food Production System" }} - Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->description : "Production & Inventory Management" }}" name="description" />
    <meta content="skyhackeR" name="author" />

    <link rel="shortcut icon" href="{{ !empty($pageGlobalData->setting) ? asset($pageGlobalData->setting->favicon) : '' }}">

    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />     
    <!-- Bootstrap & App CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" id="app-style"/>
    <link href="{{ asset('assets/css/moshel-erp-components.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="{{ asset('assets/cdn.jsdelivr.net/gh/iconoir-icons/iconoir%40main/css/iconoir.css') }}">

    <!-- Admin Workspace Switcher Styles -->
    <style>
        .admin-workspace-switcher {
            padding: 12px 14px 10px;
            background: #ffffff;
            border-bottom: 1px solid #f0e6f2;
        }
        .admin-workspace-pill-group {
            display: flex;
            background: #f7f3f8;
            border: 1px solid #ebddec;
            border-radius: 30px;
            padding: 3px;
            gap: 2px;
        }
        .admin-workspace-btn {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 6px 6px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 20px;
            border: none;
            background: transparent;
            color: #6c5a70;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
            outline: none;
        }
        .admin-workspace-btn i {
            font-size: 14px;
            transition: transform 0.2s;
        }
        .admin-workspace-btn:hover {
            color: #581a62;
            background: rgba(88, 26, 98, 0.08);
        }
        .admin-workspace-btn.active {
            background: linear-gradient(135deg, #581a62 0%, #7b2284 100%);
            color: #ffffff !important;
            box-shadow: 0 2px 6px rgba(88, 26, 98, 0.25);
        }
        .admin-workspace-btn.active i {
            color: #ffffff !important;
            transform: scale(1.1);
        }
        .workspace-badge-count {
            display: inline-block;
            font-size: 9px;
            background: #fbf6fc;
            color: #581a62;
            padding: 1px 5px;
            border-radius: 4px;
            border: 1px solid #ebddec;
            margin-left: 6px;
            font-weight: 600;
        }
        #adminToggleAllWorkspaces {
            transition: all 0.2s;
            font-weight: 600;
        }
        #adminToggleAllWorkspaces:hover {
            color: #581a62 !important;
        }
        /* Collapsed Sidebar Adaptation */
        body.vertical-collpsed .admin-workspace-switcher {
            padding: 10px 4px 6px;
        }
        body.vertical-collpsed .admin-workspace-pill-group {
            flex-direction: column;
            border-radius: 12px;
            padding: 2px;
        }
        body.vertical-collpsed .admin-workspace-btn {
            padding: 8px 0;
            justify-content: center;
        }
        body.vertical-collpsed .admin-workspace-btn span.tab-text {
            display: none !important;
        }
    </style>

    <!-- HugeRTE WYSIWYG Editor (Open-source drop-in replacement for TinyMCE) -->
    <script src="https://cdn.jsdelivr.net/npm/hugerte@1/hugerte.min.js"></script>
    <script>
        (function() {
            function initHugeRTE() {
                var editorLib = window.hugerte || window.tinymce;
                if (!editorLib) return;

                // Provide tinymce alias for backward compatibility
                if (!window.tinymce) {
                    window.tinymce = editorLib;
                }

                // Check if currently on the Store Management Product Edit view
                var isStoreProductEdit = document.querySelector('#storeProductUpdateForm') !== null
                    || window.location.pathname.indexOf('/store/products/') !== -1;

                var targetSelector;
                if (isStoreProductEdit) {
                    // On Store Management product edit: only attach to designated .hugerte-editor (leaving short_description & meta_description plain)
                    targetSelector = 'textarea.hugerte-editor';
                } else {
                    // On all other admin pages: attach to all textareas except explicit .no-editor
                    targetSelector = 'textarea:not(.no-editor):not([data-no-editor])';
                }

                var elements = document.querySelectorAll(targetSelector);
                if (elements.length > 0) {
                    elements.forEach(function (el) {
                        if (el.dataset.hugerteInitialized) return;
                        el.dataset.hugerteInitialized = 'true';

                        var rows = el.getAttribute('rows');
                        var calcHeight = rows && parseInt(rows, 10) <= 3 ? 220 : 340;

                        editorLib.init({
                            target: el,
                            height: calcHeight,
                            menubar: 'file edit view insert format tools table help',
                            plugins: [
                                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                                'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons'
                            ],
                            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | link image media table | emoticons charmap | removeformat | code fullscreen preview',
                            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; }',
                            branding: false,
                            promotion: false,
                            setup: function(editor) {
                                editor.on('change input paste keyup', function() {
                                    editor.save();
                                });
                            }
                        });
                    });

                    // Ensure contents are synchronized back to textareas before form submissions
                    document.querySelectorAll('form').forEach(function(form) {
                        if (form.dataset.hugerteBound) return;
                        form.dataset.hugerteBound = 'true';
                        form.addEventListener('submit', function() {
                            if (window.hugerte && window.hugerte.triggerSave) {
                                window.hugerte.triggerSave();
                            } else if (window.tinymce && window.tinymce.triggerSave) {
                                window.tinymce.triggerSave();
                            }
                        });
                    });
                }
            }

            // Prevent Bootstrap modal from blocking focus inside HugeRTE popups/dialogs
            document.addEventListener('focusin', function(e) {
                if (e.target && e.target.closest && e.target.closest('.tox-tinymce, .tox-tinymce-aux, .moxman-window, .tam-floorpane, .hugerte')) {
                    e.stopImmediatePropagation();
                }
            });

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initHugeRTE);
            } else {
                initHugeRTE();
            }
            window.addEventListener('load', initHugeRTE);

            // Re-initialize when Bootstrap modals are opened
            if (window.jQuery) {
                $(document).on('shown.bs.modal', function() {
                    initHugeRTE();
                });
            }
        })();
    </script>
</head>

<body data-sidebar="light">
@include('sweetalert::alert')

<div id="layout-wrapper">

    <!-- TOP BAR -->
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">

                <div class="navbar-brand-box">
                    <a href="{{ url('/admin/home') }}" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ asset($pageGlobalData->setting?->favicon) }}" alt="Favicon" 
                                style="height: 30px; width: 44px; object-fit: contain; vertical-align: middle;">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset($pageGlobalData->setting?->logo) }}" alt="Logo" 
                                style="height: 40px; width: auto; max-width: 100%; object-fit: contain; vertical-align: middle;">
                        </span>
                    </a>

                    <a href="{{ url('/admin/home') }}" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ asset($pageGlobalData->setting?->favicon) }}" alt="Favicon" 
                                style="height: 30px; width: 10px; object-fit: contain; vertical-align: middle;">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset($pageGlobalData->setting?->logo) }}" alt="Logo" 
                                style="height: 40px; width: auto; max-width: 100%; object-fit: contain; vertical-align: middle;">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                    <i class="fa fa-fw fa-bars"></i>
                </button>
            </div>

            <div class="d-flex">
                <div class="dropdown">
                    <button class="btn header-item" data-bs-toggle="dropdown">
                        <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/users/avatar-1.jpg') }}">
                        <span class="d-none d-xl-inline-block ms-1">{{ auth('admin')->user()?->name ?? 'Admin User' }}</span>
                        <i class="mdi mdi-chevron-down"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="{{ url('/admin/profile') }}"><i class="bx bx-user me-1"></i>Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger"
                        href="{{ url('/admin/logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form-dropdown').submit();">
                            <i class="bx bx-power-off me-1"></i>Logout
                        </a>

                        <form id="logout-form-dropdown" action="{{ url('/admin/logout') }}" method="POST" style="display:none;">
                            @csrf
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </header>

    <!-- SIDEBAR -->
    <div class="vertical-menu">
        <!-- Pinned Workspace Switcher (Admin Only) -->
        <div class="admin-workspace-switcher" id="adminSidebarSwitcher">
            <div class="admin-workspace-pill-group" role="tablist">
                <button type="button" class="admin-workspace-btn active" data-workspace="bakery" title="Bakery & Production Operations">
                    <i class="mdi mdi-cookie"></i>
                    <span class="tab-text">Bakery</span>
                </button>
                <button type="button" class="admin-workspace-btn" data-workspace="commerce" title="Sales, POS & Storefront Management">
                    <i class="bx bx-cart"></i>
                    <span class="tab-text">Sales</span>
                </button>
                <button type="button" class="admin-workspace-btn" data-workspace="system" title="System Settings, Governance & Team">
                    <i class="bx bx-shield-quarter"></i>
                    <span class="tab-text">Admin</span>
                </button>
            </div>
        </div>

        <div data-simplebar style="max-height: calc(100% - 60px);">
            <div id="sidebar-menu">
                <ul class="metismenu list-unstyled" id="side-menu">

                    <!-- ========================================== -->
                    <!-- 1. DASHBOARD / OVERVIEW (System) -->
                    <!-- ========================================== -->
                    <li class="menu-title" data-workspace="system">Menu</li>
                    <li data-workspace="system">
                        <a href="{{ url('/admin/home') }}"><i class="bx bx-home-circle"></i><span>Dashboard</span></a>
                    </li>

                    <!-- ========================================== -->
                    <!-- 2. SYSTEM CONFIGURATION (System) -->
                    <!-- ========================================== -->
                    <li class="menu-title" data-workspace="system">System Configuration</li>
                    <li data-workspace="system">
                        <a href="{{ url('/admin/siteSettings') }}"><i class="bx bx-cog"></i><span>System Settings</span></a>
                    </li>
                    <li data-workspace="system">
                        <a href="{{ url('/admin/unitManagement') }}"><i class="bx bx-transfer"></i><span>Unit Management</span></a>
                    </li>

                    <!-- ========================================== -->
                    <!-- 3. INVENTORY MANAGEMENT (Bakery) -->
                    <!-- ========================================== -->
                    <li class="menu-title" data-workspace="bakery">Inventory Management</li>
                    <li data-workspace="bakery">
                        <a href="{{ url('/admin/ingredients') }}"><i class="bx bx-box"></i><span>Ingredients</span></a>
                    </li>
                    <li data-workspace="bakery">
                        <a href="{{ url('/admin/openingStock') }}"><i class="bx bx-layer"></i><span>Opening Stock</span></a>
                    </li>
                    <li data-workspace="bakery">
                        <a href="{{ url('/admin/stockIn') }}"><i class="bx bx-download"></i><span>Stock In (Purchases)</span></a>
                    </li>
                    <li data-workspace="bakery">
                        <a href="{{ url('/admin/inventory') }}"><i class="bx bx-bar-chart-alt-2"></i><span>Inventory Overview</span></a>
                    </li>

                    <!-- ========================================== -->
                    <!-- 4. PRODUCTS & RECIPES (Bakery) -->
                    <!-- ========================================== -->
                    <li class="menu-title" data-workspace="bakery">Products & Recipes</li>
                    <li data-workspace="bakery">
                        <a href="{{ url('/admin/products') }}"><i class="bx bx-food-menu"></i><span>Products</span></a>
                    </li>
                    <li data-workspace="bakery">
                        <a href="{{ url('/admin/recipes') }}"><i class="bx bx-receipt"></i><span>Recipes</span></a>
                    </li>

                    <!-- ========================================== -->
                    <!-- 5. PRODUCTION (Bakery) -->
                    <!-- ========================================== -->
                    <li class="menu-title" data-workspace="bakery">Production</li>
                    <li data-workspace="bakery">
                        <a href="{{ url('/admin/production') }}"><i class="bx bx-wrench"></i><span>Record Production</span></a>
                    </li>
                    <li data-workspace="bakery">
                        <a href="{{ url('/admin/productionHistory') }}"><i class="bx bx-history"></i><span>Production History</span></a>
                    </li>

                    <!-- ========================================== -->
                    <!-- 6. SALES & POS (Commerce) -->
                    <!-- ========================================== -->
                    <li class="menu-title" data-workspace="commerce">Sales & POS</li>
                    <li data-workspace="commerce">
                        <a href="{{ url('/admin/pos') }}"><i class="bx bx-cart"></i><span>Point of Sale (POS)</span></a>
                    </li>
                    <li data-workspace="commerce">
                        <a href="{{ url('/admin/salesHistory') }}"><i class="bx bx-list-check"></i><span>Sales History</span></a>
                    </li>

                    <!-- ========================================== -->
                    <!-- 7. STOREFRONT (Commerce) -->
                    <!-- ========================================== -->
                    <li class="menu-title" data-workspace="commerce">Storefront</li>
                    <li data-workspace="commerce">
                        <a href="{{ url('/admin/store') }}">
                            <i class="bx bx-store"></i>
                            <span>Store Management</span>
                        </a>
                    </li>
                    <!-- ========================================== -->
                    <!-- 8. USERS (System) -->
                    <!-- ========================================== -->
                    <li class="menu-title" data-workspace="system">Users</li>
                    <li data-workspace="system">
                        <a href="{{ url('/admin/adminList') }}"><i class="bx bx-user"></i><span>Admins</span></a>
                    </li>
                    <li data-workspace="system">
                        <a href="{{ url('/admin/staffList') }}"><i class="bx bx-user-check"></i><span>Production Staff</span></a>
                    </li>

                    <!-- ========================================== -->
                    <!-- 9. CUSTOMERS (Commerce) -->
                    <!-- ========================================== -->
                    <li class="menu-title" data-workspace="commerce">Customers</li>
                    <li data-workspace="commerce">
                        <a href="{{ url('/admin/customers') }}"><i class="bx bxs-user-detail"></i><span>Customers</span></a>
                    </li>

                    <!-- ========================================== -->
                    <!-- 10. SECURITY & GOVERNANCE (System) -->
                    <!-- ========================================== -->
                    <li class="menu-title" data-workspace="system">Security & Governance</li>
                    <li data-workspace="system">
                        <a href="{{ route('admin.auditLogs.index') }}">
                            <i class="bx bx-shield-quarter"></i>
                            <span>Audit Log</span>
                        </a>
                    </li>

                    <!-- ========================================== -->
                    <!-- PERSISTENT FOOTER ACTIONS -->
                    <!-- ========================================== -->
                    <li data-workspace="all" class="border-top mt-3 pt-2">
                        <a href="javascript:void(0);" id="adminToggleAllWorkspaces" class="text-muted d-flex align-items-center justify-content-between" style="font-size: 11px;">
                            <span><i class="bx bx-grid-alt me-1 text-primary"></i><span id="adminToggleAllWorkspacesLabel">Show All Modules</span></span>
                            <i class="bx bx-chevron-down" id="adminToggleAllWorkspacesIcon"></i>
                        </a>
                    </li>

                    <li data-workspace="all">
                        <a href="{{ url('/admin/logout') }}" 
                        onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                            <i class="bx bx-power-off text-danger"></i><span class="text-danger font-weight-bold">Logout</span>
                        </a>

                        <form id="logout-form-sidebar" action="{{ url('/admin/logout') }}" method="POST" style="display:none;">
                            @csrf
                        </form>
                    </li>

                </ul>
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        © <script>document.write(new Date().getFullYear())</script>
                        {{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->site_name : "Food Production System" }}
                    </div>
                    <div class="col-sm-6 text-end">
                        Built by {{ env('APP_AUTHOR') }}
                    </div>
                </div>
            </div>
        </footer>
    </div>

</div>


<!-- JAVASCRIPT -->
<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
<!-- Required datatable js -->
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script>
    // Global DataTables configuration: prevent blocking browser alerts and enforce safe retrieval across all tables
    if (window.$ && $.fn && $.fn.dataTable) {
        $.fn.dataTable.ext.errMode = 'none';
        $.extend(true, $.fn.dataTable.defaults, {
            retrieve: true
        });
    }
</script>
<script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<!-- Buttons examples -->
<script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
<!-- Responsive examples -->
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

<!-- Datatable init js (cache busted) -->
<script src="{{ asset('assets/js/pages/datatables.init.js') }}?v={{ file_exists(public_path('assets/js/pages/datatables.init.js')) ? filemtime(public_path('assets/js/pages/datatables.init.js')) : '2.0.1' }}"></script>        

<script src="{{ asset('assets/js/app.js') }}"></script>

<!-- Admin Workspace Switcher Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const STORAGE_KEY = 'moshel_admin_active_workspace';
        const switcher = document.getElementById('adminSidebarSwitcher');
        if (!switcher) return;

        const buttons = switcher.querySelectorAll('.admin-workspace-btn');
        const menuItems = document.querySelectorAll('#sidebar-menu ul.metismenu > li[data-workspace]');
        const toggleAllBtn = document.getElementById('adminToggleAllWorkspaces');
        const toggleAllLabel = document.getElementById('adminToggleAllWorkspacesLabel');
        const toggleAllIcon = document.getElementById('adminToggleAllWorkspacesIcon');

        let isAllVisible = false;

        function setWorkspace(workspace, save = true) {
            isAllVisible = false;
            if (save) {
                try { localStorage.setItem(STORAGE_KEY, workspace); } catch (e) {}
            }

            buttons.forEach(btn => {
                const btnWs = btn.getAttribute('data-workspace');
                if (btnWs === workspace) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            menuItems.forEach(li => {
                const itemWs = li.getAttribute('data-workspace');
                if (itemWs === workspace || itemWs === 'all') {
                    li.style.display = '';
                } else {
                    li.style.display = 'none';
                }
            });

            if (toggleAllLabel) toggleAllLabel.textContent = 'Show All Modules';
            if (toggleAllIcon) toggleAllIcon.className = 'bx bx-chevron-down';

            // Refresh SimpleBar if present
            try {
                const simpleBarContainer = document.querySelector('.vertical-menu [data-simplebar]');
                if (simpleBarContainer && window.SimpleBar) {
                    const inst = window.SimpleBar.instances.get(simpleBarContainer);
                    if (inst) inst.recalculate();
                }
            } catch (e) {}
        }

        function showAllWorkspaces() {
            isAllVisible = true;
            buttons.forEach(btn => btn.classList.remove('active'));
            menuItems.forEach(li => {
                li.style.display = '';
            });

            if (toggleAllLabel) toggleAllLabel.textContent = 'Focus Active Module';
            if (toggleAllIcon) toggleAllIcon.className = 'bx bx-chevron-up';

            try {
                const simpleBarContainer = document.querySelector('.vertical-menu [data-simplebar]');
                if (simpleBarContainer && window.SimpleBar) {
                    const inst = window.SimpleBar.instances.get(simpleBarContainer);
                    if (inst) inst.recalculate();
                }
            } catch (e) {}
        }

        buttons.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const ws = this.getAttribute('data-workspace');
                setWorkspace(ws, true);
            });
        });

        if (toggleAllBtn) {
            toggleAllBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (isAllVisible) {
                    const fallback = detectActiveWorkspace() || 'bakery';
                    let targetWs = fallback;
                    try {
                        targetWs = localStorage.getItem(STORAGE_KEY) || fallback;
                    } catch (e) {}
                    setWorkspace(targetWs, true);
                } else {
                    showAllWorkspaces();
                }
            });
        }

        function detectActiveWorkspace() {
            // 1. Look for active menu link
            const activeLink = document.querySelector('#sidebar-menu a.active, #sidebar-menu li.mm-active a');
            if (activeLink) {
                const parentLi = activeLink.closest('li[data-workspace]');
                if (parentLi) {
                    const ws = parentLi.getAttribute('data-workspace');
                    if (ws && ws !== 'all') return ws;
                }
            }

            // 2. URL route pattern detection
            const path = window.location.pathname.toLowerCase();
            if (path.includes('/pos') || path.includes('/sales') || path.includes('/store') || path.includes('/customer')) {
                return 'commerce';
            }
            if (path.includes('/ingredient') || path.includes('/stock') || path.includes('/product') || path.includes('/recipe') || path.includes('/production')) {
                return 'bakery';
            }
            if (path.includes('/home') || path.includes('/sitesetting') || path.includes('/unit') || path.includes('/admin') || path.includes('/staff') || path.includes('/audit')) {
                return 'system';
            }
            return null;
        }

        // Initialize view:
        const detected = detectActiveWorkspace();
        let currentWorkspace = detected;

        if (!currentWorkspace) {
            try { currentWorkspace = localStorage.getItem(STORAGE_KEY); } catch (e) {}
        }
        if (!currentWorkspace) {
            currentWorkspace = 'bakery';
        }

        setWorkspace(currentWorkspace, false);
    });
</script>

@yield('script')
@yield('scripts')

</body>
</html>
