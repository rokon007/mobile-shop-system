<!doctype html>
<html lang="en" class="light-theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/favicon-32x32.png') }}" type="image/png" />
    @yield('css')
    <!-- Styles -->
    @include('layouts.styles')

    @livewireStyles
</head>

<body>
    <!--start wrapper-->
    <div class="wrapper">
        <!--start top header-->
        @include('layouts.header')
        <!--end top header-->

        <!--start sidebar -->
        @include('layouts.sidebar')
        <!--end sidebar -->

        <!--start content-->
        <main class="page-content">
            @yield('content')
        </main>
        <!--end page main-->

        <!--start overlay-->
        <div class="overlay nav-toggle-icon"></div>
        <!--end overlay-->

        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->

        <!--start switcher-->
        <div class="switcher-body">
            <!-- Switcher content from original template -->
        </div>
        <!--end switcher-->
    </div>
    <!--end wrapper-->

    <!-- Scripts -->
    @include('layouts.scripts')
    @livewireScripts
    @yield('js')
    @stack('scripts')
</body>
</html>
