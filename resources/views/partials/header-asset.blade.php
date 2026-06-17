<!-- favicon -->
<link rel="shortcut icon" href="{{ get_fav($basic_settings) }}" type="image/x-icon">

<link rel="stylesheet" href="{{ asset('public/frontend/') }}/css/fontawesome-all.css">
<!-- bootstrap css link -->
<link rel="stylesheet" href="{{ asset('public/frontend/') }}/css/bootstrap.css">
<!-- swipper css link -->
<link rel="stylesheet" href="{{ asset('public/frontend/') }}/css/swiper.css">
<!-- lightcase css links -->
<link rel="stylesheet" href="{{ asset('public/frontend/') }}/css/lightcase.css">
 <!-- AOS css link -->
 <link rel="stylesheet" href="{{ asset('public/frontend/') }}/css/aos.css">
<!-- odometer css link -->
<link rel="stylesheet" href="{{ asset('public/frontend/') }}/css/odometer.css">
<!-- animate.css -->
<link rel="stylesheet" href="{{ asset('public/frontend/') }}/css/animate.css">
<!-- Magnific popup -->
<link rel="stylesheet" href="{{ asset('public/backend/library/popup/magnific-popup.css') }}">
<!-- line-awesome-icon css -->
<link rel="stylesheet" href="{{ asset('public/frontend/') }}/css/line-awesome.css">
<!-- nice-select -->
<link rel="stylesheet" href="{{ asset('public/frontend/') }}/css/nice-select.css">
 <!-- select2 css -->
 <link rel="stylesheet" href="{{ asset('public/frontend/') }}/css/select2.css">
<!-- main style css link -->
<link rel="stylesheet" href="{{ asset('public/frontend/') }}/css/style.css">

@php
    $base_color = $basic_settings->base_color;
@endphp
<style>
    :root {
        --primary-color: {{ $base_color }};
    }
</style>
