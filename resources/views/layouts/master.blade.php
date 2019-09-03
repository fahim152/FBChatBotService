<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    @include ('layouts.meta-content')
    <link href="{{ asset('css/core.css') }}" rel="stylesheet">
    @yield('style')
</head>
<body class=" page-header-fixed page-container-bg-solid page-footer-fixed lang-{{ config('app.locale') }}">
    @include ('layouts.header')
    <div class="clearfix"> </div>
    <div class="page-container page-content-inner page-container-bg-solid">
        @include ('layouts.side-bar')
        @yield('content')
    </div>
    @include ('layouts.footer')

    <script src="{{ asset('js/core.js') }}" type="text/javascript"></script>

    @yield('script')
</body>
</html>
