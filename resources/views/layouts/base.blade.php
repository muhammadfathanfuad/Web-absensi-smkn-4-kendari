<!DOCTYPE html>
<html @yield('html-attribute')>

<head>
    @include('layouts.partials/title-meta')

    {{-- Memuat CSS utama terlebih dahulu --}}
    @include('layouts.partials/head-css')
    
    {{-- Baru memuat CSS khusus dari halaman lain --}}
    @yield('css')
</head>

<body @yield('body-attribuet')>

    @yield('content')

    @include('layouts.partials/vendor-scripts')

    @yield('scripts')

</body>
</html>