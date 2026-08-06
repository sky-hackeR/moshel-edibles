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

    <link rel="stylesheet" href="{{ asset('assets/cdn.jsdelivr.net/gh/iconoir-icons/iconoir%40main/css/iconoir.css') }}">

    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/ib771jqvt5joab026vosdy4bkhoad3hty1tycnv696zoka2w/tinymce/7/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks | bold italic underline | align | numlist bullist | table | removeformat'
        });
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
                                style="height: 30px; width: auto; object-fit: contain; vertical-align: middle;">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset($pageGlobalData->setting?->logo) }}" alt="Logo" 
                                style="height: 40px; width: auto; max-width: 100%; object-fit: contain; vertical-align: middle;">
                        </span>
                    </a>

                    <a href="{{ url('/admin/home') }}" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ asset($pageGlobalData->setting?->favicon) }}" alt="Favicon" 
                                style="height: 30px; width: auto; object-fit: contain; vertical-align: middle;">
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
        <div data-simplebar class="h-100">
            <div id="sidebar-menu">
                <ul class="metismenu list-unstyled">

                    <li class="menu-title">Menu</li>
                    <li>
                        <a href="{{ url('/admin/home') }}"><i class="bx bx-home-circle"></i><span>Dashboard</span></a>
                    </li>

                    <li class="menu-title">System Configuration</li>
                    <li>
                        <a href="{{ url('/admin/siteSettings') }}"><i class="bx bx-cog"></i><span>System Settings</span></a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/unitManagement') }}"><i class="bx bx-transfer"></i><span>Unit Management</span></a>
                    </li>

                    <li class="menu-title">Inventory Management</li>
                    <li>
                        <a href="{{ url('/admin/ingredients') }}"><i class="bx bx-box"></i><span>Ingredients</span></a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/stockIn') }}"><i class="bx bx-download"></i><span>Stock In (Purchases)</span></a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/inventory') }}"><i class="bx bx-bar-chart-alt-2"></i><span>Inventory Overview</span></a>
                    </li>

                    <li class="menu-title">Products & Recipes</li>
                    <li>
                        <a href="{{ url('/admin/products') }}"><i class="bx bx-food-menu"></i><span>Products</span></a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/recipes') }}"><i class="bx bx-receipt"></i><span>Recipes</span></a>
                    </li>

                    <li class="menu-title">Production</li>
                    <li>
                        <a href="{{ url('/admin/production') }}"><i class="bx bx-wrench"></i><span>Record Production</span></a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/productionHistory') }}"><i class="bx bx-history"></i><span>Production History</span></a>
                    </li>

                    <li class="menu-title">Sales & POS</li>
                    <li>
                        <a href="{{ url('/admin/pos') }}"><i class="bx bx-cart"></i><span>Point of Sale (POS)</span></a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/salesHistory') }}"><i class="bx bx-list-check"></i><span>Sales History</span></a>
                    </li>

                    <li class="menu-title">Users</li>
                    <li>
                        <a href="{{ url('/admin/adminList') }}"><i class="bx bx-user"></i><span>Admins</span></a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/staffList') }}"><i class="bx bx-user-check"></i><span>Production Staff</span></a>
                    </li>

                    <li>
                        <a href="{{ url('/admin/logout') }}" 
                        onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                            <i class="bx bx-power-off"></i><span>Logout</span>
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

<!-- Datatable init js -->
<script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>    

<script src="{{ asset('assets/js/app.js') }}"></script>

</body>
</html>
