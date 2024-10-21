<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('Admin | Login') }}</title>
    <!-- favicon -->
    <link rel="shortcut icon" href="{{ get_fav($basic_settings) }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@200;300;400;500;600;700;800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- fontawesome css link -->
    <link rel="stylesheet" href="{{ asset('public/backend/css/fontawesome-all.css') }}">
    <!-- bootstrap css link -->
    <link rel="stylesheet" href="{{ asset('public/backend/css/bootstrap.css') }}">
    <!-- line-awesome-icon css -->
    <link rel="stylesheet" href="{{ asset('public/backend/css/line-awesome.css') }}">
    <!-- animate.css -->
    <link rel="stylesheet" href="{{ asset('public/backend/css/animate.css') }}">
    <!-- nice select css -->
    <link rel="stylesheet" href="{{ asset('public/backend/css/nice-select.css') }}">
    <!-- select2 css -->
    <link rel="stylesheet" href="{{ asset('public/backend/css/select2.css') }}">
    <!-- rte css -->
    <link rel="stylesheet" href="{{ asset('public/backend/css/rte_theme_default.css') }}">
    <!-- main style css link -->
    <link rel="stylesheet" href="{{ asset('public/backend/css/style.css') }}">

    @stack('css')
</head>

<body>

    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Admin
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <div class="page-wrapper">
        <div class="account-area">
            @yield('section')
        </div>
        <ul class="bg-bubbles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Admin
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->

    <!-- jquery -->
    <script src="{{ asset('public/backend/js/jquery-3.6.0.js') }}"></script>
    <!-- bootstrap js -->
    <script src="{{ asset('public/backend/js/bootstrap.bundle.js') }}"></script>
    <!-- smooth scroll js -->
    <script src="{{ asset('public/backend/js/smoothscroll.js') }}"></script>
    <!-- nice select js -->
    <script src="{{ asset('public/backend/js/jquery.nice-select.js') }}"></script>
    <!-- select2 js -->
    <script src="{{ asset('public/backend/js/select2.js') }}"></script>
    <!-- rte js -->
    <script src="{{ asset('public/backend/js/rte.js') }}"></script>
    <!-- rte plugins js -->
    <script src='{{ asset('public/backend/js/all_plugins.js') }}'></script>
    <!-- main -->
    <script src="{{ asset('public/backend/js/main.js') }}"></script>

    @include('admin.partials.notify')

    @stack('script')

</body>

</html>
