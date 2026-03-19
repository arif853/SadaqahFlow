<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#000000"/>
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="CPDS">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/logo-2.png') }}">

    <!-- shortcut icon -->
    <link rel="icon" href="{{ asset('assets/images/logo-2.png') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Font awesome -->
    <link href="{{ asset('assets/css/vendor/font-awesome.css') }}" rel="stylesheet">
    <!-- themify icon -->
    <link href="{{ asset('assets/css/vendor/themify-icons.css') }}" rel="stylesheet">
    <!-- Scrollbar -->
    <link href="{{ asset('assets/css/vendor/simplebar.css') }}" rel="stylesheet">
    <!-- Bootstrap css -->
    <link href="{{ asset('assets/css/vendor/bootstrap.css') }}" rel="stylesheet">
    <!-- Custom css -->
    <link href="{{ asset('assets/css/style.css') }}" id="customstyle" rel="stylesheet">

    @inertiaHead
    @viteReactRefresh
    @vite('resources/js/app.jsx')
</head>
<body>
    @inertia

    <!-- Core JS (jQuery + Bootstrap + simplebar needed for template) -->
    <script src="{{ asset('assets/js/jquery-3.6.0.js') }}"></script>
    <script src="{{ asset('assets/js/icons/feather-icon/feather.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/simplebar.js') }}"></script>
    <script src="{{ asset('assets/js/custom-script.js') }}"></script>

    <!-- Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.getRegistrations().then(registrations => {
                    registrations.forEach(registration => {
                        registration.update();
                    });
                });
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('Service Worker registered');
                        registration.update();
                    })
                    .catch(err => console.error('Service Worker registration failed:', err));
            });
            if ('caches' in window) {
                caches.keys().then(names => {
                    names.forEach(name => {
                        if (name.includes('-v1')) {
                            caches.delete(name);
                        }
                    });
                });
            }
        }
    </script>
</body>
</html>
