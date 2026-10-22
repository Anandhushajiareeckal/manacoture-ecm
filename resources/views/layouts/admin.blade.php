<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Dashboard') | {{ config('app.name', 'INSPINIA Commerce') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />

    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- Vendor css -->
    <link href="{{ asset('assets/css/vendors.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link id="app-style" href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        .content-page > .container-fluid {
            padding-top: 1.25rem;
            padding-bottom: 1.5rem;
        }
        .table-responsive {
            min-height: 250px;
        }
        .badge {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">
        <!-- Topbar Header -->
        @include('layouts.partials.topbar')

        <!-- Sidenav Menu -->
        @include('layouts.partials.sidebar')

        <!-- Page Content -->
        <div class="content-page">
            <div class="container-fluid">
                <!-- Flash messages -->
                @include('layouts.partials.alerts')

                @yield('content')
            </div>

            <!-- Footer -->
            @include('layouts.partials.footer')
        </div>
    </div>
    <!-- END wrapper -->

    <!-- Vendor js -->
    <script src="{{ asset('assets/js/vendors.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>
