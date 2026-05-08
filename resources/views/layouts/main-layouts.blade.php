<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Belva') }}</title>

    <link rel="shortcut icon" href="{{ asset('mazer/assets/compiled/svg/favicon.svg') }}" type="image/x-icon" />
    <link rel="icon" type="image/x-icon" sizes="16x16"
        href="https://d2kchovjbwl1tk.cloudfront.net/favicon/favicon_web_1632967746769_resized16-jpg.webp">

    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/compiled/css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/compiled/css/app-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/compiled/css/iconly.css') }}" />

    <!-- Toastify -->
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/extensions/toastify-js/src/toastify.css') }}">

    <!-- Sweetalert2 -->
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/extensions/sweetalert2/sweetalert2.min.css') }}">

    <!-- DataTable -->
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/extensions/simple-datatables/style.css') }}">

    <!-- Flatpickr -->
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/extensions/flatpickr/flatpickr.css') }}">

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/extensions/@icon/dripicons/dripicons.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/compiled/css/ui-icons-dripicons.css') }}">

    <!-- Toastify -->
    <script src="{{ asset('mazer/dist/assets/extensions/toastify-js/src/toastify.js') }}"></script>
    <script src="{{ asset('mazer/dist/assets/static/js/pages/toastify.js') }}"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* Chrome, Safari, Edge, Opera */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        .dataTable-selector.form-select,
        .dataTable-input,
        .dataTable-info,
        .dataTable-pagination-list {
            border-radius: 10px;
            font-size: 12px;
        }

        .select2 {
            border-radius: 10px !important;
            font-size: 12px;
        }

        .select2-search__field {
            border-radius: 10px;
            font-size: 13px;
        }

        .select2-container--default .select2-selection--single.is-invalid {
            border: 1px solid #dc3545 !important;
            border-radius: 0.2rem !important;
        }

        .rounded-cs {
            border-radius: 10px !important;
            font-size: 13px;
        }

        #page-loader {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.9);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .spinner {
            width: 48px;
            height: 48px;
            border: 5px solid #ddd;
            border-top: 5px solid #435ebe;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <script src="{{ asset('mazer/dist/assets/static/js/initTheme.js') }}"></script>
    <div id="page-loader">
        <div class="spinner"></div>
    </div>

    <div id="app">
        <div id="sidebar">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>BLV</div>
                        <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20"
                                height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                                <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                        opacity=".3"></path>
                                    <g transform="translate(-210 -1)">
                                        <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                        <circle cx="220.5" cy="11.5" r="4"></circle>
                                        <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2">
                                        </path>
                                    </g>
                                </g>
                            </svg>
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input  me-0" type="checkbox" id="toggle-dark"
                                    style="cursor: pointer">
                                <label class="form-check-label"></label>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                aria-hidden="true" role="img" class="iconify iconify--mdi" width="20"
                                height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                                </path>
                            </svg>
                        </div>
                        <div class="sidebar-toggler x">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i
                                    class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    @php
                        $role = \Illuminate\Support\Facades\DB::table('roles')
                            ->where('role_id', auth()->user()->role_id)
                            ->value('role_name');

                        $canInbound = in_array($role, ['Superadmin', 'Tim Produksi', 'Staff Gudang']);
                        $canOutbound = in_array($role, ['Superadmin', 'Tim Penjualan', 'Staff Gudang']);
                        $canOrders = in_array($role, ['Superadmin', 'Tim Penjualan']);
                        $canRfid = in_array($role, ['Superadmin', 'Tim Produksi']);
                        $canMaster = $role === 'Superadmin';
                    @endphp

                    <ul class="menu" style="margin-top:0px !important;">

                        <li class="sidebar-title" style="margin-top:0px !important;">Menu</li>

                        <li class="sidebar-item">
                            <a href="/" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        @if ($canInbound)
                            <li class="sidebar-item">
                                <a href="{{ route('inbound.index') }}" class='sidebar-link'>
                                    <i class="fa-solid fa-arrows-down-to-line"></i>
                                    <span>Inbound</span>
                                </a>
                            </li>
                        @endif

                        @if ($canOutbound)
                            <li class="sidebar-item">
                                <a href="{{ route('outbound.index') }}" class='sidebar-link'>
                                    <i class="fa-solid fa-arrows-up-to-line"></i>
                                    <span>Outbound</span>
                                </a>
                            </li>
                        @endif

                        @if ($canOrders)
                            <li class="sidebar-item">
                                <a href="{{ route('sales-orders.index') }}" class='sidebar-link'>
                                    <i class="fa-solid fa-coins"></i>
                                    <span>Orders</span>
                                </a>
                            </li>
                        @endif

                        {{-- Inventory: semua role boleh lihat --}}
                        <li class="sidebar-item has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="fa-solid fa-box-open"></i>
                                <span>Inventory</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item">
                                    <a href="{{ route('product.index') }}" class="submenu-link">View Inventory</a>
                                </li>
                                <li class="submenu-item">
                                    <a href="{{ route('product.history') }}" class="submenu-link">History
                                        Inventory</a>
                                </li>
                            </ul>
                        </li>

                        @if ($canRfid)
                            <li class="sidebar-item">
                                <a href="{{ route('rfid-tags.index') }}" class='sidebar-link'>
                                    <i class="fa-solid fa-tags"></i>
                                    <span>RFID</span>
                                </a>
                            </li>
                        @endif

                        @if ($canMaster)
                            <li class="sidebar-item">
                                <a href="{{ route('unit.index') }}" class='sidebar-link'>
                                    <i class="fa-solid fa-boxes-stacked"></i>
                                    <span>Unit</span>
                                </a>
                            </li>

                            <li class="sidebar-item has-sub">
                                <a href="#" class="sidebar-link">
                                    <i class="bi bi-person-badge"></i>
                                    <span>Role</span>
                                </a>
                                <ul class="submenu">
                                    <li class="submenu-item">
                                        <a href="{{ route('role.create') }}" class="submenu-link">Add Role</a>
                                    </li>
                                    <li class="submenu-item">
                                        <a href="{{ route('role.index') }}" class="submenu-link">View Role</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="sidebar-item has-sub">
                                <a href="#" class="sidebar-link">
                                    <i class="bi bi-person-circle"></i>
                                    <span>Account</span>
                                </a>
                                <ul class="submenu">
                                    <li class="submenu-item">
                                        <a href="{{ route('account.create') }}" class="submenu-link">Add Account</a>
                                    </li>
                                    <li class="submenu-item">
                                        <a href="{{ route('account.index') }}" class="submenu-link">View Account</a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>

        <div id="main" class="layout-navbar navbar-fixed">
            <header>
                <nav class="navbar navbar-expand navbar-light navbar-top">
                    <div class="container-fluid">
                        <a href="#" class="burger-btn d-block">
                            <i class="bi bi-justify fs-3"></i>
                        </a>

                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mb-lg-0">
                                {{-- <li class="nav-item dropdown me-3">
                                    <a class="nav-link active dropdown-toggle text-gray-600" href="#"
                                        data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                        <i class="bi bi-bell bi-sub fs-4"></i>
                                        <span class="badge badge-notification bg-danger">7</span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown"
                                        aria-labelledby="dropdownMenuButton">
                                        <li class="dropdown-header">
                                            <h6>Notifications</h6>
                                        </li>
                                        <li class="dropdown-item notification-item">
                                            <a class="d-flex align-items-center" href="#">
                                                <div class="notification-icon bg-primary">
                                                    <i class="bi bi-cart-check"></i>
                                                </div>
                                                <div class="notification-text ms-4">
                                                    <p class="notification-title font-bold">
                                                        Successfully check
                                                        out
                                                    </p>
                                                    <p class="notification-subtitle font-thin text-sm">
                                                        Order ID #256
                                                    </p>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="dropdown-item notification-item">
                                            <a class="d-flex align-items-center" href="#">
                                                <div class="notification-icon bg-success">
                                                    <i class="bi bi-file-earmark-check"></i>
                                                </div>
                                                <div class="notification-text ms-4">
                                                    <p class="notification-title font-bold">
                                                        Homework submitted
                                                    </p>
                                                    <p class="notification-subtitle font-thin text-sm">
                                                        Algebra math
                                                        homework
                                                    </p>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <p class="text-center py-2 mb-0">
                                                <a href="#">See all notification</a>
                                            </p>
                                        </li>
                                    </ul>
                                </li> --}}
                            </ul>
                            <div class="dropdown">
                                <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-menu d-flex">
                                        <div class="user-name text-end me-3">
                                            <h6 class="mb-0 text-gray-600">
                                                {{ Auth::user()->name }}
                                            </h6>
                                            <p class="mb-0 text-sm text-gray-600">
                                                {{ session('role_name') }}
                                            </p>
                                        </div>
                                        <div class="user-img d-flex align-items-center">
                                            <div class="avatar avatar-md">
                                                <img src="{{ asset('mazer/dist/assets/compiled/jpg/1.jpg') }}" />
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu
                                                    dropdown-menu-end"
                                    aria-labelledby="dropdownMenuButton" style="min-width: 11rem">
                                    <li>
                                        <h6 class="dropdown-header">
                                            Hello, {{ Auth::user()->name }}
                                        </h6>
                                    </li>
                                    {{-- <li>
                                        <a class="dropdown-item" href="#"><i
                                                class="icon-mid bi bi-person me-2"></i>
                                            My Profile</a>
                                    </li> --}}
                                    <li>
                                        <hr class="dropdown-divider" />
                                    </li>
                                    <li>
                                        {{-- <a class="dropdown-item" href="{{ route('logout') }}"><i
                                                class="icon-mid bi bi-box-arrow-left me-2"></i>
                                            Logout</a> --}}

                                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-box-arrow-left me-2"></i> Logout
                                            </button>
                                        </form>


                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
            @if (session('toast_success'))
                <script>
                    Toastify({
                        text: @json(session('toast_success')),
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#4fbe87",
                    }).showToast();
                </script>
            @elseif(session('toast_error'))
                <script>
                    Toastify({
                        text: @json(session('toast_error')),
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#ff5f6d",
                    }).showToast();
                </script>
            @endif

            <div id="main-content">

                @yield('content')

                {{-- <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2023 &copy; Mazer</p>
                    </div>
                    <div class="float-end">
                        <p>
                            Crafted with
                            <span class="text-danger"><i class="bi bi-heart-fill icon-mid"></i></span>
                            by <a href="https://saugi.me">Saugi</a>
                        </p>
                    </div>
                </div>
            </footer> --}}
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="assets/static/js/components/dark.js"></script>
    <script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>

    <script src="assets/js/app.js" type="module"></script>

    <script src="{{ asset('mazer/dist/assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('mazer/dist/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

    <script src="{{ asset('mazer/dist/assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('mazer/dist/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

    <!-- Perfect Scrollbar -->
    <script src="{{ asset('mazer/dist/assets/extensions/perfect-scrollbar/perfect-scrollbar.common.js') }}"></script>

    <!-- DataTable -->
    <script src="{{ asset('mazer/dist/assets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script src="{{ asset('mazer/dist/assets/static/js/pages/simple-datatables.js') }}"></script>

    <!-- Flatpickr -->
    <script src="{{ asset('mazer/dist/assets/extensions/flatpickr/flatpickr.js') }}"></script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- Chart.Js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Sweetalert2 -->
    <script src="{{ asset('mazer/dist/assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('mazer/dist/assets/static/js/pages/sweetalert2.js') }}"></script>
    {{-- <script src="{{ asset('mazer/assets/extensions/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('mazer/assets/static/js/pages/ui-chartjs.js') }}"></script> --}}

    <script type="text/javascript">
        $(function() {
            $('.date').flatpickr({
                dateFormat: 'Y-m-d',
                enableTime: false,
            });

            $('.time').flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: 'H:i',
                time_24hr: true,
            });

            $('.select2').select2({
                placeholder: "Pilih opsi",
                allowClear: true,
                width: '100%',
            });

            $(function() {
                const loader = $('#page-loader');

                window.addEventListener('beforeunload', function() {
                    loader.css('display', 'flex');
                });

                $(document).on('submit', 'form', function() {
                    if ($(this).data('use-loader')) {
                        loader.css('display', 'flex');
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
