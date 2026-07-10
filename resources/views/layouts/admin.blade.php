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

                    @if(auth()->user()->isAdminLevel())
                    <li class="nav-notification" style="position: relative;">
                        <div class="navicon-wrap" id="notifBell" style="position: relative; cursor: pointer;">
                            <i data-feather="bell"></i>
                            <span id="notifBadge" style="position:absolute;top:-6px;right:-6px;display:none;background:#dc3545;color:#fff;border-radius:50%;font-size:10px;line-height:1;padding:3px 5px;min-width:16px;text-align:center;">0</span>
                        </div>
                        <div id="notifDrop" style="display:none;position:absolute;right:0;top:120%;width:320px;max-width:90vw;background:#fff;box-shadow:0 6px 24px rgba(0,0,0,.15);border-radius:8px;z-index:1050;overflow:hidden;">
                            <div style="padding:10px 12px;border-bottom:1px solid rgba(0,0,0,.08);display:flex;justify-content:space-between;align-items:center;">
                                <strong style="color:#333;">নোটিফিকেশন</strong>
                                <a href="javascript:void(0);" id="notifMarkRead" style="font-size:12px;">সব পড়া হয়েছে</a>
                            </div>
                            <ul id="notifList" style="max-height:340px;overflow-y:auto;margin:0;padding:0;list-style:none;">
                                <li style="padding:14px;text-align:center;color:#888;">লোড হচ্ছে...</li>
                            </ul>
                        </div>
                    </li>
                    @endif

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
                                @if(auth()->user()->isAdminLevel())
                                <li><a href="{{route('maintenance.cache-clear')}}"><i class="fa fa-refresh"></i>ক্যাশ ক্লিয়ার</a></li>
                                <li><a href="{{route('maintenance.storage-link')}}"><i class="fa fa-link"></i>স্টোরেজ লিংক</a></li>
                                @endif
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

    @if(auth()->check() && auth()->user()->isAdminLevel())
    <script>
        // ===== Admin notifications: in-app bell + Web Push subscription =====
        (function () {
            const VAPID_PUBLIC = @json(config('webpush.vapid.public_key'));
            const CSRF = @json(csrf_token());
            const URLS = {
                list: @json(route('notifications.index')),
                read: @json(route('notifications.read')),
                subscribe: @json(route('push.subscribe')),
            };

            function urlBase64ToUint8Array(base64String) {
                const padding = '='.repeat((4 - base64String.length % 4) % 4);
                const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
                const raw = atob(base64);
                const arr = new Uint8Array(raw.length);
                for (let i = 0; i < raw.length; i++) arr[i] = raw.charCodeAt(i);
                return arr;
            }
            function bufToB64Url(buf) {
                return btoa(String.fromCharCode.apply(null, new Uint8Array(buf)))
                    .replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
            }

            function render(data) {
                const badge = document.getElementById('notifBadge');
                const list = document.getElementById('notifList');
                if (badge) {
                    if (data.unread > 0) { badge.textContent = data.unread > 99 ? '99+' : data.unread; badge.style.display = 'inline-block'; }
                    else { badge.style.display = 'none'; }
                }
                if (!list) return;
                if (!data.notifications || !data.notifications.length) {
                    list.innerHTML = '<li style="padding:14px;text-align:center;color:#888;">কোন নোটিফিকেশন নেই</li>';
                    return;
                }
                list.innerHTML = data.notifications.map(function (n) {
                    const bg = n.read ? 'transparent' : 'rgba(13,110,253,.06)';
                    return '<li style="padding:10px 12px;border-bottom:1px solid rgba(0,0,0,.06);background:' + bg + ';">' +
                        '<a href="' + n.url + '" style="display:block;color:inherit;text-decoration:none;">' +
                        '<div style="font-weight:600;font-size:13px;color:#333;">' + (n.title || '') + '</div>' +
                        '<div style="font-size:12px;color:#555;">' + (n.message || '') + '</div>' +
                        '<div style="font-size:11px;color:#999;margin-top:2px;">' + (n.time || '') + '</div>' +
                        '</a></li>';
                }).join('');
            }

            function load() {
                fetch(URLS.list, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' })
                    .then(function (r) { return r.json(); }).then(render).catch(function () {});
            }

            const bell = document.getElementById('notifBell');
            const drop = document.getElementById('notifDrop');
            if (bell && drop) {
                bell.addEventListener('click', function (e) {
                    e.stopPropagation();
                    drop.style.display = (drop.style.display === 'none' || !drop.style.display) ? 'block' : 'none';
                    ensurePush();
                });
                document.addEventListener('click', function (e) {
                    if (!drop.contains(e.target) && !bell.contains(e.target)) drop.style.display = 'none';
                });
            }
            const markBtn = document.getElementById('notifMarkRead');
            if (markBtn) {
                markBtn.addEventListener('click', function () {
                    fetch(URLS.read, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }, credentials: 'same-origin' })
                        .then(function () { load(); });
                });
            }

            function ensurePush() {
                if (!('serviceWorker' in navigator) || !('PushManager' in window) || !VAPID_PUBLIC) return;
                if (typeof Notification === 'undefined' || Notification.permission === 'denied') return;
                const subscribe = function () {
                    return navigator.serviceWorker.ready.then(function (reg) {
                        return reg.pushManager.getSubscription().then(function (existing) {
                            return existing || reg.pushManager.subscribe({
                                userVisibleOnly: true,
                                applicationServerKey: urlBase64ToUint8Array(VAPID_PUBLIC)
                            });
                        });
                    }).then(function (sub) {
                        return fetch(URLS.subscribe, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                            credentials: 'same-origin',
                            body: JSON.stringify({
                                endpoint: sub.endpoint,
                                keys: { p256dh: bufToB64Url(sub.getKey('p256dh')), auth: bufToB64Url(sub.getKey('auth')) }
                            })
                        });
                    }).catch(function (err) { console.warn('Push subscribe failed', err); });
                };
                if (Notification.permission === 'granted') subscribe();
                else Notification.requestPermission().then(function (p) { if (p === 'granted') subscribe(); });
            }

            load();
            setInterval(load, 30000);
            if (typeof Notification !== 'undefined' && Notification.permission === 'granted') ensurePush();
        })();
    </script>
    @endif
</body>

</html>
