<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.title-meta')
    @include('layouts.partials.head-css')
</head>
<body>

    <div class="content-page">
        <div class="content">
            
            <div class="container-fluid">
                @yield('content')
            </div>

        </div> @include('layouts.partials.footer')
        </div>

    {{-- KITA TIDAK MEMUAT VENDOR-SCRIPTS DI SINI UNTUK MENGHINDARI KONFLIK --}}

    {{-- Script khusus untuk halaman ini akan dimuat di sini --}}
    @yield('script')

</body>
</html>