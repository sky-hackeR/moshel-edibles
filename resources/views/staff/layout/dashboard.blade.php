@php
    $staff = Auth::guard('staff')->user();
@endphp
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->site_name : "Food Production System" }} - Staff Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->description : "Production & Inventory Management" }}" name="description" />
    <meta content="skyhackeR" name="author" />

    <link rel="shortcut icon" href="{{ !empty($pageGlobalData->setting) ? asset($pageGlobalData->setting->favicon) : '' }}">

    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />    
    
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" id="app-style"/>

    <link rel="stylesheet" href="{{ asset('assets/cdn.jsdelivr.net/gh/iconoir-icons/iconoir%40main/css/iconoir.css') }}">
</head>

<body data-sidebar="light">
@include('sweetalert::alert')

<div id="layout-wrapper">

    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <div class="navbar-brand-box">
                    <a href="{{ url('/staff/home') }}" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ asset($pageGlobalData->setting?->favicon) }}" alt="Favicon" style="height: 30px; width: auto; object-fit: contain;">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset($pageGlobalData->setting?->logo) }}" alt="Logo" style="height: 40px; width: auto; max-width: 100%; object-fit: contain;">
                        </span>
                    </a>
                    <a href="{{ url('/staff/home') }}" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ asset($pageGlobalData->setting?->favicon) }}" alt="Favicon" style="height: 30px; width: auto; object-fit: contain;">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset($pageGlobalData->setting?->logo) }}" alt="Logo" style="height: 40px; width: auto; max-width: 100%; object-fit: contain;">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                    <i class="fa fa-fw fa-bars"></i>
                </button>
            </div>

            <div class="d-flex">
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="Header Avatar">
                        <span class="d-none d-xl-inline-block ms-1">{{ $staff->name }}</span>
                        <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#"><i class="bx bx-user font-size-16 align-middle me-1"></i> Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="{{ url('/staff/logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form-dropdown').submit();">
                            <i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> Logout
                        </a>
                        <form id="logout-form-dropdown" action="{{ url('/staff/logout') }}" method="POST" style="display:none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="vertical-menu">
        <div data-simplebar class="h-100">
            <div id="sidebar-menu">
                <ul class="metismenu list-unstyled">

                    <li class="menu-title">Menu</li>
                    <li>
                        <a href="{{ url('/staff/home') }}"><i class="bx bx-home-circle"></i><span>Dashboard</span></a>
                    </li>

                    <li class="menu-title">Production</li>
                    <li>
                        <a href="{{ url('/staff/production') }}"><i class="bx bx-wrench"></i><span>Record Production</span></a>
                    </li>

                    <li>
                        <a href="{{ url('/staff/productionHistory') }}"><i class="bx bx-history"></i><span>Production History</span></a>
                    </li>

                    <li class="menu-title">Inventory</li>
                    <li>
                        <a href="{{ url('/staff/inventory') }}">
                            <i class="bx bx-bar-chart-alt-2"></i>
                            <span>Current Stock</span>
                        </a>
                    </li>

                    <li class="menu-title">Sales & POS</li>
                    <li>
                        <a href="{{ url('/staff/pos') }}"><i class="bx bx-cart"></i><span>Point of Sale (POS)</span></a>
                    </li>
                    <li>
                        <a href="{{ url('/staff/salesHistory') }}"><i class="bx bx-list-check"></i><span>Sales History</span></a>
                    </li>

                    <li>
                        <a href="{{ url('/staff/logout') }}" 
                        onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                            <i class="bx bx-power-off"></i><span>Logout</span>
                        </a>

                        <form id="logout-form-sidebar" action="{{ url('/staff/logout') }}" method="POST" style="display:none;">
                            @csrf
                        </form>
                    </li>

                </ul>
            </div>
        </div>
    </div>

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
                        {{ $pageGlobalData->setting->site_name ?? 'Food Production System' }}
                    </div>
                    <div class="col-sm-6 text-end">
                        Built by {{ env('APP_AUTHOR') }}
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>

<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>

<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

<script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>    
<script src="{{ asset('assets/js/app.js') }}"></script>

</body>
</html>