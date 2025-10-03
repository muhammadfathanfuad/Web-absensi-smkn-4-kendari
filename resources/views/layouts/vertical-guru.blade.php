<!DOCTYPE html>
<html lang="en" @yield('html-attribute')>

<head>
    @include('layouts.partials.title-meta')

    @include('layouts.partials.head-css')

    @vite(['resources/scss/style.scss', 'resources/js/app.js'])
    </head>

<body>

    <div class="app-wrapper">

        @include('layouts.partials.sidebar-guru')

        @include('layouts.partials.topbar')

        <div class="page-content">

            <div class="container-fluid">

                @yield('content')

            </div>

            @include('layouts.partials.footer')
        </div>

    </div>

    @include('layouts.partials.vendor-scripts')

    @stack('scripts') </body>

</html>