<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="">
    <title> CPDS-DK-BS | @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#eef1fb"/>
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="CPDS">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    <link rel="apple-touch-icon" href="{{asset('assets/images/logo-2.png')}}">
    <!-- shortcut icon-->
    <link rel="icon" href="{{asset('')}}assets/images/logo-2.png" type="image/x-icon">
    <link rel="shortcut icon" href="{{asset('')}}assets/images/logo-2png" type="image/x-icon">
    <!-- Fonts css-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <!-- Font awesome -->
    <link href="{{asset('')}}assets/css/vendor/font-awesome.css" rel="stylesheet">
    <!--Datatable-->
    <link href="{{asset('')}}assets/css/vendor/datatable/jquery.dataTables.css" rel="stylesheet">
    <link href="{{asset('')}}assets/css/vendor/datatable/custom-datatable.css" rel="stylesheet">
    <!-- themify icon-->
    <link href="{{asset('')}}assets/css/vendor/themify-icons.css" rel="stylesheet">
     <!-- select 2 css-->
     <link href="{{asset('')}}assets/css/vendor/select2/select2.min.css" rel="stylesheet">
    <!-- flag icon-->
    <link href="{{asset('')}}assets/css/vendor/flag-icon/flag-icon.css" rel="stylesheet">
    <!-- Scrollbar-->
    <link href="{{asset('')}}assets/css/vendor/simplebar.css" rel="stylesheet">
    <!-- select 2 css-->
    <link href="{{asset('')}}assets/css/vendor/sweetalert/sweetalert2.min.css" rel="stylesheet">
    <!-- Bootstrap css-->
    <link href="{{asset('')}}assets/css/vendor/bootstrap.css" rel="stylesheet">
    <!-- Custom css-->
    <link href="{{asset('')}}assets/css/style.css" id="customstyle" rel="stylesheet">
    <!-- Glassmorphism theme layer-->
    <link href="{{asset('')}}assets/css/theme-glass.css" rel="stylesheet">
</head>

<body>
    <!-- Loader Start-->
    <div class="codex-loader">
        <div class="linespinner"></div>
    </div>
    <!-- Loader End-->
    <!-- Header Start-->
    <header class="codex-header">
        <div class="header-contian d-flex justify-content-between align-items-center">
            <div class="header-left d-flex align-items-center">
                <div class="sidebar-action navicon-wrap"><i data-feather="menu"></i></div>
                @php
                    $globalSearchUrl = auth()->user()->can('view member')
                        ? route('members.index')
                        : (auth()->user()->can('view khedmot') ? route('khedmots.index') : null);
                @endphp
                @if($globalSearchUrl)
                <div class="search-bar">
                    <div class="form-group mb-0">
                        <div class="input-group">
                            <input class="form-control" id="globalSearch" type="text" value="" autocomplete="off"
                                data-search-url="{{ $globalSearchUrl }}"
                                placeholder="জাকের নাম / ফোন / কল্যাণ নাম্বার খুঁজুন....."><span
                                class="input-group-text" id="globalSearchBtn" role="button" style="cursor: pointer;"><i data-feather="search"></i></span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="header-right d-flex align-items-center justify-content-end">
                <ul class="nav-iconlist">
                    {{-- <li>
                        <div class="navicon-wrap action-dark"><i class="fa fa-moon-o icon-dark"></i><i
                                class="fa fa-sun-o icon-light" style="display:none;"></i></div>
                    </li> --}}

                    <li class="nav-profile">
                        <div class="media">
                            <div class="user-icon"><img class="img-fluid rounded-50"
                                    src="{{asset('')}}assets/images/avtar/3.jpg" alt="logo"></div>
                            <div class="media-body d-block">
                                <h6>{{Auth::user()->name}}</h6><span class="text-light">{{Auth::user()->getRoleNames()->first()}}</span>
                            </div>
                        </div>
                        <div class="hover-dropdown navprofile-drop">
                            <ul>
                                <li><a href="{{route('profile.edit')}}"><i class="ti-settings"></i>setting</a></li>
                                <li>
                                    <a href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i>log out</a>
                                    <form id="logout-form" action="{{route('logout')}}" method="post">
                                        @csrf

                                    </form>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <!-- Header End-->
    <!-- sidebar start-->

    @include('layouts.sidebar')
    <!-- sidebar end-->
    <div class="themebody-wrap">
        <!-- breadcrumb start-->

        @yield('breadcrumb')
        <!-- breadcrumb end-->

        <!-- theme body start-->
        @yield('content')
        <!-- theme body end-->
    </div>
    <!-- footer start-->
    <footer class="codex-footer">
        <p>Copyright 2024-2025 © CPDS-DK-BS, All rights reserved.</p>
    </footer>
    <!-- footer end-->
    <!-- mobile bottom tab bar start-->
    <nav class="mobile-tabbar">
        <a href="{{ route('dashboard') }}" class="tabbar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i data-feather="home"></i>
            <span>হোম</span>
        </a>
        @can('view member')
        <a href="{{ route('members.index') }}" class="tabbar-item {{ request()->routeIs('members.*') ? 'active' : '' }}">
            <i data-feather="users"></i>
            <span>জাকের</span>
        </a>
        @endcan
        @can('view khedmot')
        <a href="{{ route('khedmots.index') }}" class="tabbar-item {{ request()->routeIs('khedmots.*') ? 'active' : '' }}">
            <i data-feather="message-square"></i>
            <span>খেদমত</span>
        </a>
        @endcan
        @can('view fund-collection')
        <a href="{{ route('fund.receive.index') }}" class="tabbar-item {{ request()->routeIs('fund.*') ? 'active' : '' }}">
            <i data-feather="dollar-sign"></i>
            <span>লেনদেন</span>
        </a>
        @endcan
        <a href="javascript:void(0);" class="tabbar-item sidebar-action">
            <i data-feather="menu"></i>
            <span>মেনু</span>
        </a>
    </nav>
    <!-- mobile bottom tab bar end-->
    <!-- back to top start //-->
    <div class="scroll-top"><i class="fa fa-angle-double-up"></i></div>
    <!-- back to top end //-->
    <!-- main jquery-->
    <script src="{{asset('')}}assets/js/jquery-3.6.0.js"></script>
    <!-- Feather icons js-->
    <script src="{{asset('')}}assets/js/icons/feather-icon/feather.js"></script>
    <!-- Bootstrap js-->
    <script src="{{asset('')}}assets/js/bootstrap.bundle.min.js"></script>
    <!-- Scrollbar-->
    <script src="{{asset('')}}assets/js/vendors/simplebar.js"></script>
    <!-- Notify-->
    <script src="{{asset('')}}assets/js/vendors/notify/bootstrap-notify.js"></script>
    <script src="{{asset('')}}assets/js/vendors/notify/bootstrap-customnotify.js"></script>
    {{-- dataTable --}}
    <script src="{{asset('')}}assets/js/vendors/datatable/jquery.dataTables.min.js"></script>
    <!-- select 2 js-->
    <script src="{{asset('')}}assets/js/vendors/select2/select2.min.js"></script>
    <script src="{{asset('')}}assets/js/vendors/select2/custom-select2.js"> </script>

    <!-- apex chart-->
    <script src="{{asset('')}}assets/js/vendors/chart/apexcharts.js"></script>

    <!-- sweetalert js-->
    <script src="{{asset('')}}assets/js/vendors/sweetalert/sweetalert2.js"></script>
    <script src="{{asset('')}}assets/js/vendors/sweetalert/custom-sweetalert2.js"></script>

    <!-- Custom script-->
    <script src="{{asset('')}}assets/js/custom-script.js"></script>

    @stack('script')
    <script>
        $(document).ready(function() {
            new DataTable('#dataTable');
            $('.select2').select2();

            // Global header search: jump to the list page with the term pre-applied.
            function runGlobalSearch() {
                const $input = $('#globalSearch');
                const term = $.trim($input.val());
                if (!term) { return; }
                const base = $input.data('search-url');
                window.location.href = base + '?q=' + encodeURIComponent(term);
            }
            $('#globalSearch').on('keydown', function(e) {
                if (e.key === 'Enter') { e.preventDefault(); runGlobalSearch(); }
            });
            $('#globalSearchBtn').on('click', runGlobalSearch);
        });
    </script>
    @if(session('status'))
        <script>
            $(document).ready(function() {
                showNotification(
                    "{{ session('status.type') }}",
                    "{{ session('status.message') }}",
                    "{{ ucfirst(session('status.type')) }}"
                );
                new DataTable('#dataTable');
            });
        </script>
    @endif

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                // Clear old service worker cache and re-register
                navigator.serviceWorker.getRegistrations().then(registrations => {
                    registrations.forEach(registration => {
                        registration.update(); // Force update to new version
                    });
                });

                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('Service Worker registered');
                        // Force update on page load
                        registration.update();
                    })
                    .catch(err => console.error('Service Worker registration failed:', err));
            });

            // Clear old caches on page load
            if ('caches' in window) {
                caches.keys().then(names => {
                    names.forEach(name => {
                        if (name.includes('-v1')) {
                            caches.delete(name);
                            console.log('Cleared old cache:', name);
                        }
                    });
                });
            }
        }
    </script>
</body>

</html>
