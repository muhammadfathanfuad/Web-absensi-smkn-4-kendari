<!DOCTYPE html>
<html @yield('html-attribute')>

<head>
    @include('layouts.partials/title-meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Memuat CSS utama terlebih dahulu --}}
    @include('layouts.partials/head-css')
    
    {{-- Baru memuat CSS khusus dari halaman lain --}}
    @yield('css')
</head>

<body @yield('body-attribuet')>

    @yield('content')

    @include('layouts.partials/vendor-scripts')
    
    {{-- Time Override Scripts --}}
    <script src="{{ asset('js/time-override.js') }}"></script>
    <script src="{{ asset('js/time-override-init.js') }}"></script>

    @yield('scripts')

</body>
</html>