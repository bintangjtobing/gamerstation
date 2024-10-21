<!-- favicon -->
<link rel="shortcut icon" href="{{ get_fav($basic_settings, 'dark') }}" type="image/x-icon">
<link rel="stylesheet" href="{{ asset('public/frontend') }}/css/line-awesome.css">
<!-- bootstrap css link -->
<link rel="stylesheet" href="{{ asset('public/frontend') }}/css/bootstrap.css">
<!-- swipper css link -->
<link rel="stylesheet" href="{{ asset('public/frontend') }}/css/swiper.css">
<!-- animate css link -->
<link rel="stylesheet" href="{{ asset('public/frontend') }}/css/animate.css">
<!-- odometer css link -->
<link rel="stylesheet" href="{{ asset('public/frontend') }}/css/odometer.css">
<!-- nice-select css link -->
<link rel="stylesheet" href="{{ asset('public/frontend') }}/css/nice-select.css">
<!-- lightcase css link -->
<link rel="stylesheet" href="{{ asset('public/frontend') }}/css/lightcase.css">
<!-- style css link -->

{{-- Common style for all project  --}}
<link rel="stylesheet" href="{{ asset('public/backend/css/select2.css') }}">
<link rel="stylesheet" href="{{ asset('public/backend/library/popup/magnific-popup.css') }}">
<!-- Fileholder CSS CDN -->
<link rel="stylesheet" href="https://appdevs.cloud/cdn/fileholder/v1.0/css/fileholder-style.css" type="text/css">

<link rel="stylesheet" href="{{ asset('public/frontend') }}/css/style.css">
@php
    $base_color = $basic_settings->base_color;
@endphp
{{-- Dynamic Color From Admin --}}
<style>
    :root {
        --base_color: {{ $base_color }};
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #ffffff;
    }
</style>
