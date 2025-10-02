<!DOCTYPE html>
<html @yield('html-attribute')>

<head>
    @include('layouts.partials/title-meta')

    @include('layouts.partials/head-css')
</head>

<body @yield('body-attribuet')>

    @yield('content')

    @include('layouts.partials/vendor-scripts')

</body>

</html>